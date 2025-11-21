<?php
// Configurar manejo de errores para producción
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);

require_once 'conexion.php';
require_once 'enviar_correo.php';

// =====================================================
// MANEJAR PETICIONES JSON PARA EVALUACION.PHP
// =====================================================
$input_json = file_get_contents('php://input');
$json_data = json_decode($input_json, true);

// Si es una petición JSON con action
if ($json_data && isset($json_data['action'])) {
    header('Content-Type: application/json; charset=utf-8');
    
    switch ($json_data['action']) {
        case 'completar_evaluacion':
            try {
                $id_registro = intval($json_data['id_registro'] ?? 0);
                $puntajes = $json_data['puntajes'] ?? [];
                
                if ($id_registro <= 0) {
                    echo json_encode(['success' => false, 'mensaje' => 'ID de registro inválido']);
                    exit;
                }
                
                $conexion = conectarDB();
                if (!$conexion) {
                    echo json_encode(['success' => false, 'mensaje' => 'Error de conexión']);
                    exit;
                }
                
                $conexion->beginTransaction();
                
                // Calcular totales por área
                $total_conducta = 0;
                $total_productividad = 0;
                $total_competencias = 0;
                $total_otros = 0;
                
                // Conducta Laboral (c1-c5)
                for ($i = 1; $i <= 5; $i++) {
                    $total_conducta += intval($puntajes['c' . $i] ?? 0);
                }
                
                // Productividad (p1-p6)
                for ($i = 1; $i <= 6; $i++) {
                    $total_productividad += intval($puntajes['p' . $i] ?? 0);
                }
                
                // Competencias Específicas (ce1-ce10)
                for ($i = 1; $i <= 10; $i++) {
                    $total_competencias += intval($puntajes['ce' . $i] ?? 0);
                }
                
                // Otros Factores (o1-o2)
                for ($i = 1; $i <= 2; $i++) {
                    $total_otros += intval($puntajes['o' . $i] ?? 0);
                }
                
                // Calcular porcentajes
                $porc_conducta = ($total_conducta / 50) * 100;
                $porc_productividad = ($total_productividad / 60) * 100;
                $porc_competencias = ($total_competencias / 70) * 100;
                $porc_otros = ($total_otros / 20) * 100;
                
                // Verificar si ya existe registro en matriz
                $sqlCheck = "SELECT id_matriz FROM matriz WHERE id_info_reg = ?";
                $stmtCheck = $conexion->prepare($sqlCheck);
                $stmtCheck->execute([$id_registro]);
                $existe = $stmtCheck->fetch(PDO::FETCH_ASSOC);
                
                if ($existe) {
                    // Actualizar registro existente
                    $sqlUpdate = "UPDATE matriz SET 
                        porc_cond_lab = ?,
                        porc_prod = ?,
                        porc_comp = ?,
                        porc_otros = ?
                        WHERE id_info_reg = ?";
                    $stmtUpdate = $conexion->prepare($sqlUpdate);
                    $stmtUpdate->execute([
                        round($porc_conducta, 2),
                        round($porc_productividad, 2),
                        round($porc_competencias, 2),
                        round($porc_otros, 2),
                        $id_registro
                    ]);
                } else {
                    // Insertar registro nuevo
                    $sqlInsert = "INSERT INTO matriz (id_info_reg, porc_cond_lab, porc_prod, porc_comp, porc_otros) 
                        VALUES (?, ?, ?, ?, ?)";
                    $stmtInsert = $conexion->prepare($sqlInsert);
                    $stmtInsert->execute([
                        $id_registro,
                        round($porc_conducta, 2),
                        round($porc_productividad, 2),
                        round($porc_competencias, 2),
                        round($porc_otros, 2)
                    ]);
                }
                
                // Actualizar timestamp en info_registro
                $sqlUpdateInfo = "UPDATE info_registro SET fe_actu = NOW() WHERE id_info_reg = ?";
                $stmtUpdateInfo = $conexion->prepare($sqlUpdateInfo);
                $stmtUpdateInfo->execute([$id_registro]);
                
                $puntaje_total = $total_conducta + $total_productividad + $total_competencias + $total_otros;
                $porcentaje_total = ($puntaje_total / 200) * 100;
                
                $conexion->commit();
                
                echo json_encode([
                    'success' => true,
                    'mensaje' => 'Evaluación completada exitosamente',
                    'puntaje_total' => $puntaje_total,
                    'porcentaje_total' => round($porcentaje_total, 2)
                ]);
                
            } catch (Exception $e) {
                if (isset($conexion) && $conexion->inTransaction()) {
                    $conexion->rollBack();
                }
                echo json_encode(['success' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
            }
            exit;
            
        default:
            echo json_encode(['success' => false, 'mensaje' => 'Acción no reconocida']);
            exit;
    }
}

// Si es una petición POST form-data con action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json; charset=utf-8');
    
    switch ($_POST['action']) {
        case 'obtener_evaluaciones_pendientes':
            try {
                $conexion = conectarDB();
                if (!$conexion) {
                    echo json_encode(['success' => false, 'mensaje' => 'Error de conexión']);
                    exit;
                }
                
                $sql = "SELECT 
                    ir.id_info_reg as id_registro,
                    DATE_FORMAT(ir.fe_crea, '%Y-%m-%d') as fecha,
                    CONCAT(c.nom_col, ' ', c.apell_col) as nombre_completo,
                    car.nom_car as cargo
                    FROM info_registro ir
                    INNER JOIN colaborador c ON ir.id_info_reg = c.id_info_reg
                    INNER JOIN cargos car ON c.id_cargo = car.id_cargo
                    LEFT JOIN matriz m ON ir.id_info_reg = m.id_info_reg
                    WHERE (m.porc_cond_lab IS NULL OR m.porc_cond_lab = 0)
                    AND ir.activo = 1
                    ORDER BY ir.fe_crea DESC";
                
                $stmt = $conexion->prepare($sql);
                $stmt->execute();
                $evaluaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo json_encode([
                    'success' => true,
                    'evaluaciones' => $evaluaciones
                ]);
                
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
            }
            exit;
            
        case 'obtener_detalle_evaluacion':
            try {
                $conexion = conectarDB();
                if (!$conexion) {
                    echo json_encode(['success' => false, 'mensaje' => 'Error de conexión']);
                    exit;
                }
                
                $id_registro = intval($_POST['id_registro'] ?? 0);
                
                if ($id_registro <= 0) {
                    echo json_encode(['success' => false, 'mensaje' => 'ID inválido']);
                    exit;
                }
                
                // Obtener datos del colaborador
                $sqlCol = "SELECT 
                    c.nom_col, c.apell_col, c.email_col as email,
                    DATE_FORMAT(c.fech_ing_col, '%d/%m/%Y') as fecha_ingreso_format,
                    car.nom_car as cargo,
                    dep.nom_dep as departamento
                    FROM colaborador c
                    INNER JOIN cargos car ON c.id_cargo = car.id_cargo
                    INNER JOIN departamentos dep ON c.id_departamento = dep.id_dep
                    WHERE c.id_info_reg = ?";
                
                $stmtCol = $conexion->prepare($sqlCol);
                $stmtCol->execute([$id_registro]);
                $colaborador = $stmtCol->fetch(PDO::FETCH_ASSOC);
                
                // Obtener datos del evaluador
                $sqlEval = "SELECT 
                    e.nom_eva, e.apell_eva, e.email_eva as email,
                    car.nom_car as cargo,
                    dep.nom_dep as departamento
                    FROM evaluador e
                    INNER JOIN cargos car ON e.id_cargo = car.id_cargo
                    INNER JOIN departamentos dep ON e.id_departamento = dep.id_dep
                    WHERE e.id_info_reg = ?";
                
                $stmtEval = $conexion->prepare($sqlEval);
                $stmtEval->execute([$id_registro]);
                $evaluador = $stmtEval->fetch(PDO::FETCH_ASSOC);
                
                if (!$colaborador || !$evaluador) {
                    echo json_encode(['success' => false, 'mensaje' => 'No se encontraron datos completos']);
                    exit;
                }
                
                echo json_encode([
                    'success' => true,
                    'colaborador' => $colaborador,
                    'evaluador' => $evaluador
                ]);
                
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
            }
            exit;
            
        default:
            // Continuar con el procesamiento normal de POST
            break;
    }
}

// FUNCIONES AUXILIARES PARA OBTENER IDs DE CATÁLOGOS

function obtenerIdCargo($valor_cargo) {
    $conexion = conectarDB();
    if ($conexion === null) return null;
    
    // Si es un número, verificar si es un ID válido
    if (is_numeric($valor_cargo)) {
        $sql = "SELECT id_cargo FROM cargos WHERE id_cargo = ? AND activo = 1 LIMIT 1";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([intval($valor_cargo)]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($resultado) {
            return $resultado['id_cargo'];
        }
    }
    
    // Si no es numérico o no se encontró por ID, buscar por nombre
    $sql = "SELECT id_cargo FROM cargos WHERE nom_car = ? AND activo = 1 LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$valor_cargo]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $resultado ? $resultado['id_cargo'] : null;
}

function obtenerIdDepartamento($valor_departamento) {
    $conexion = conectarDB();
    if ($conexion === null) return null;
    
    // Si es un número, verificar si es un ID válido
    if (is_numeric($valor_departamento)) {
        $sql = "SELECT id_dep FROM departamentos WHERE id_dep = ? AND activo = 1 LIMIT 1";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([intval($valor_departamento)]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($resultado) {
            return $resultado['id_dep'];
        }
    }
    
    // Si no es numérico o no se encontró por ID, buscar por nombre
    $sql = "SELECT id_dep FROM departamentos WHERE nom_dep = ? AND activo = 1 LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$valor_departamento]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $resultado ? $resultado['id_dep'] : null;
}

function guardarColaborador($nombres, $apellidos, $cargo, $departamento, $fecha_ingreso, $email, $eval_nombres, $eval_apellidos, $eval_cargo, $eval_departamento, $eval_email, $periodo_prueba, $tipo_evaluacion) {
    $conexion = conectarDB();
    
    if ($conexion === null) {
        return array('exito' => false, 'mensaje' => 'Error de conexión a la base de datos');
    }

    try {
        // Obtener IDs de catálogos
        $id_cargo_colaborador = obtenerIdCargo($cargo);
        $id_departamento_colaborador = obtenerIdDepartamento($departamento);
        $id_cargo_evaluador = obtenerIdCargo($eval_cargo);
        $id_departamento_evaluador = obtenerIdDepartamento($eval_departamento);
        
        // Validar que existan los catálogos
        if (!$id_cargo_colaborador) {
            return array('exito' => false, 'mensaje' => "El cargo '$cargo' no existe en el catálogo");
        }
        if (!$id_departamento_colaborador) {
            return array('exito' => false, 'mensaje' => "El departamento '$departamento' no existe en el catálogo");
        }
        if (!$id_cargo_evaluador) {
            return array('exito' => false, 'mensaje' => "El cargo del evaluador '$eval_cargo' no existe en el catálogo");
        }
        if (!$id_departamento_evaluador) {
            return array('exito' => false, 'mensaje' => "El departamento del evaluador '$eval_departamento' no existe en el catálogo");
        }

        // Iniciar transacción
        $conexion->beginTransaction();
        
        // 1. Crear UN SOLO info_registro para la evaluación completa
        $sql_info = "INSERT INTO info_registro (activo, periodo_prueba, tipo_evaluacion) VALUES (1, :periodo_prueba, :tipo_evaluacion)";
        $stmt_info = $conexion->prepare($sql_info);
        $stmt_info->bindParam(":periodo_prueba", $periodo_prueba);
        $stmt_info->bindParam(":tipo_evaluacion", $tipo_evaluacion);
        $stmt_info->execute();
        $id_info_evaluacion = $conexion->lastInsertId();
        
        // 2. Crear evaluador (referenciando el mismo info_registro)
        $sql_evaluador = "INSERT INTO evaluador (nom_eva, apell_eva, id_cargo, id_departamento, email_eva, id_info_reg) 
                         VALUES (:eval_nombres, :eval_apellidos, :id_cargo, :id_departamento, :eval_email, :id_info_reg)";
        $stmt_evaluador = $conexion->prepare($sql_evaluador);
        $stmt_evaluador->bindParam(":eval_nombres", $eval_nombres);
        $stmt_evaluador->bindParam(":eval_apellidos", $eval_apellidos);
        $stmt_evaluador->bindParam(":id_cargo", $id_cargo_evaluador);
        $stmt_evaluador->bindParam(":id_departamento", $id_departamento_evaluador);
        $stmt_evaluador->bindParam(":eval_email", $eval_email);
        $stmt_evaluador->bindParam(":id_info_reg", $id_info_evaluacion);
        $stmt_evaluador->execute();
        $id_evaluador = $conexion->lastInsertId();
        
        // 3. Crear colaborador (referenciando el mismo info_registro)
        $sql_colaborador = "INSERT INTO colaborador (nom_col, apell_col, id_cargo, id_departamento, fech_ing_col, email_col, id_info_reg)
                           VALUES (:nombres, :apellidos, :id_cargo, :id_departamento, :fecha_ingreso, :email, :id_info_reg)";
        $stmt_colaborador = $conexion->prepare($sql_colaborador);
        $stmt_colaborador->bindParam(":nombres", $nombres);
        $stmt_colaborador->bindParam(":apellidos", $apellidos);
        $stmt_colaborador->bindParam(":id_cargo", $id_cargo_colaborador);
        $stmt_colaborador->bindParam(":id_departamento", $id_departamento_colaborador);
        $stmt_colaborador->bindParam(":fecha_ingreso", $fecha_ingreso);
        $stmt_colaborador->bindParam(":email", $email);
        $stmt_colaborador->bindParam(":id_info_reg", $id_info_evaluacion);
        $stmt_colaborador->execute();
        $id_colaborador = $conexion->lastInsertId();
        
        // Confirmar transacción
        $conexion->commit();
        
        // ===== ENVÍO AUTOMÁTICO DE CORREO AL JEFE INMEDIATO =====
        $correoEnviado = false;
        if (!empty($eval_email)) {
            try {
                $datosColaborador = [
                    'nom_col' => $nombres,
                    'apell_col' => $apellidos,
                    'fech_ing_col' => $fecha_ingreso,
                    'email_col' => $email
                ];
                
                $datosEvaluador = [
                    'nom_eva' => $eval_nombres,
                    'apell_eva' => $eval_apellidos,
                    'email_eva' => $eval_email
                ];
                
                $correoEnviado = enviarNotificacionJefe($datosColaborador, $datosEvaluador);
                
            } catch (Exception $e) {
                error_log("Error enviando correo de notificación: " . $e->getMessage());
            }
        }
        
        $mensaje = '✅ Evaluación registrada exitosamente';
        if ($correoEnviado) {
            $mensaje .= ' - Notificación enviada al jefe inmediato';
        } elseif (!empty($eval_email)) {
            $mensaje .= ' - Error enviando notificación por correo';
        }
        
        return array(
            'exito' => true, 
            'mensaje' => $mensaje,
            'id_evaluacion' => $id_info_evaluacion,
            'id_colaborador' => $id_colaborador,
            'id_evaluador' => $id_evaluador,
            'correo_enviado' => $correoEnviado
        );
        
    } catch (PDOException $e) {
        // Revertir transacción en caso de error
        $conexion->rollback();
        return array('exito' => false, 'mensaje' => '❌ Error al guardar: ' . $e->getMessage());
    }
}

// FUNCIÓN PARA TABLA MATRIZ SIMPLIFICADA

// Procesar datos del formulario si se envía por POST (solo para index.php)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action']) && !$json_data) {
    
    // Validar campos requeridos - Colaborador
    $nombres = trim($_POST['nombres'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $cargo = trim($_POST['cargo'] ?? '');
    $departamento = trim($_POST['departamento'] ?? '');
    $fecha_ingreso = $_POST['fecha_ingreso'] ?? null;
    $email = trim($_POST['email'] ?? '');
    
    
    // Validar campos requeridos - Evaluador
    $eval_nombres = trim($_POST['evaluador_nombres'] ?? '');
    $eval_apellidos = trim($_POST['evaluador_apellidos'] ?? '');
    $eval_cargo = trim($_POST['eval_cargo'] ?? '');
    $eval_departamento = trim($_POST['eval_departamento'] ?? '');
    $eval_email = trim($_POST['evaluador_email'] ?? '');
    
    // Nuevos campos
    $periodo_prueba = trim($_POST['periodo_prueba'] ?? 'no');
    $tipo_evaluacion = trim($_POST['tipo_evaluacion'] ?? '');
    
    $errores = array();
    
    // Validaciones del Colaborador
    if (empty($nombres)) {
        $errores[] = 'Los nombres del colaborador son requeridos';
    }
    
    if (empty($apellidos)) {
        $errores[] = 'Los apellidos del colaborador son requeridos';
    }
    
    if (empty($cargo)) {
        $errores[] = 'El cargo del colaborador es requerido';
    }
    
    if (empty($departamento)) {
        $errores[] = 'El departamento del colaborador es requerido';
    }
    
    // Validaciones del Evaluador
    if (empty($eval_nombres)) {
        $errores[] = 'Los nombres del evaluador son requeridos';
    }
    
    if (empty($eval_apellidos)) {
        $errores[] = 'Los apellidos del evaluador son requeridos';
    }
    
    if (empty($eval_cargo)) {
        $errores[] = 'El cargo del evaluador es requerido';
    }
    
    if (empty($eval_departamento)) {
        $errores[] = 'El departamento del evaluador es requerido';
    }
    
    // Validación de tipo_evaluacion
    if (empty($tipo_evaluacion)) {
        $errores[] = 'El tipo de evaluación es requerido';
    }
    
    if (!in_array($tipo_evaluacion, ['semestral', 'anual'])) {
        $errores[] = 'El tipo de evaluación debe ser "semestral" o "anual"';
    }
    
    if (!in_array($periodo_prueba, ['si', 'no'])) {
        $errores[] = 'El campo período de prueba debe ser "si" o "no"';
    }
    
    // Validaciones de email
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El email del colaborador no tiene un formato válido';
    }
    
    if (!empty($eval_email) && !filter_var($eval_email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El email del evaluador no tiene un formato válido';
    }
    
    // Si la fecha está vacía, convertir a NULL
    if (empty($fecha_ingreso)) {
        $fecha_ingreso = null;
    }
    
    // Verificar si es evaluación completa o solo registro de colaborador
    $es_evaluacion_completa = isset($_POST['porc_conducta_laboral']) || 
                             isset($_POST['porc_productividad']) || 
                             isset($_POST['porc_competencias']) || 
                             isset($_POST['porc_otros_factores']);
    
    // Si no hay errores, procesar según el tipo
    if (empty($errores)) {
        
        if ($es_evaluacion_completa) {
            // ===== GUARDAR EVALUACIÓN COMPLETA CON TABLA MATRIZ =====
            $resultado = procesarEvaluacionNuevaTabla($_POST);
            
        } else {
            // ===== GUARDAR SOLO COLABORADOR =====
            $resultado = guardarColaborador($nombres, $apellidos, $cargo, $departamento, $fecha_ingreso, $email, 
                                          $eval_nombres, $eval_apellidos, $eval_cargo, $eval_departamento, $eval_email,
                                          $periodo_prueba, $tipo_evaluacion);
        }
        
        // Respuesta JSON para AJAX
        if (!headers_sent()) {
            header('Content-Type: application/json');
        }
        echo json_encode($resultado);
        exit;
        
    } else {
        // Devolver errores
        if (!headers_sent()) {
            header('Content-Type: application/json');
        }
        echo json_encode(array(
            'exito' => false, 
            'mensaje' => 'Errores de validación: ' . implode(', ', $errores)
        ));
        exit;
    }
}

// PARA TABLA SIMPLIFICADA
function procesarEvaluacionNuevaTabla($data) {
    $conexion = conectarDB();
    
    if ($conexion === null) {
        return array('exito' => false, 'mensaje' => 'Error de conexión a la base de datos');
    }

    try {
        $conexion->beginTransaction();
        
        // 1. Crear registro base
        $sql_info = "INSERT INTO info_registro (periodo_prueba, tipo_evaluacion) VALUES (?, ?)";
        
        $stmt_info = $conexion->prepare($sql_info);
        $stmt_info->execute([
            $data['periodo_prueba'] ?? 'si',
            $data['tipo_evaluacion'] ?? 'semestral'
        ]);
        
        $id_info_reg = $conexion->lastInsertId();
        
        // Obtener IDs de catálogos
        $id_cargo_colaborador = obtenerIdCargo($data['cargo'] ?? '');
        $id_departamento_colaborador = obtenerIdDepartamento($data['departamento'] ?? '');
        $id_cargo_evaluador = obtenerIdCargo($data['eval_cargo'] ?? '');
        $id_departamento_evaluador = obtenerIdDepartamento($data['eval_departamento'] ?? '');
        
        // Validar que existan los catálogos
        if (!$id_cargo_colaborador) {
            $conexion->rollback();
            return array('exito' => false, 'mensaje' => "El cargo '{$data['cargo']}' no existe en el catálogo");
        }
        if (!$id_departamento_colaborador) {
            $conexion->rollback();
            return array('exito' => false, 'mensaje' => "El departamento '{$data['departamento']}' no existe en el catálogo");
        }
        if (!$id_cargo_evaluador) {
            $conexion->rollback();
            return array('exito' => false, 'mensaje' => "El cargo del evaluador '{$data['eval_cargo']}' no existe en el catálogo");
        }
        if (!$id_departamento_evaluador) {
            $conexion->rollback();
            return array('exito' => false, 'mensaje' => "El departamento del evaluador '{$data['eval_departamento']}' no existe en el catálogo");
        }

        // 2. Guardar información del evaluador
        $sql_evaluador = "INSERT INTO evaluador (
            nom_eva, apell_eva, id_cargo, id_departamento, email_eva, id_info_reg
        ) VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt_evaluador = $conexion->prepare($sql_evaluador);
        $stmt_evaluador->execute([
            $data['evaluador_nombres'] ?? '',
            $data['evaluador_apellidos'] ?? '',
            $id_cargo_evaluador,
            $id_departamento_evaluador,
            $data['evaluador_email'] ?? '',
            $id_info_reg
        ]);
        
        // 3. Guardar información del colaborador
        $sql_colaborador = "INSERT INTO colaborador (
            nom_col, apell_col, id_cargo, id_departamento, fech_ing_col, email_col, id_info_reg
        ) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt_colaborador = $conexion->prepare($sql_colaborador);
        $stmt_colaborador->execute([
            $data['nombres'] ?? '',
            $data['apellidos'] ?? '',
            $id_cargo_colaborador,
            $id_departamento_colaborador,
            $data['fecha_ingreso'] ?? null,
            $data['email'] ?? '',
            $id_info_reg
        ]);
        
        // 4. Guardar evaluación en tabla matriz (solo porcentajes)
        $sql_eval = "INSERT INTO matriz (
            id_info_reg,
            porc_cond_lab, porc_prod, porc_comp, porc_otros
        ) VALUES (?, ?, ?, ?, ?)";
        
        $stmt_eval = $conexion->prepare($sql_eval);
        $stmt_eval->execute([
            $id_info_reg,
            $data['porc_conducta_laboral'] ?? 0,    // va a porc_cond_lab
            $data['porc_productividad'] ?? 0,       // va a porc_prod  
            $data['porc_competencias'] ?? 0,        // va a porc_comp
            $data['porc_otros_factores'] ?? 0       // va a porc_otros
        ]);
        
        $conexion->commit();
        
        // ===== ENVÍO AUTOMÁTICO DE CORREO PARA EVALUACIÓN COMPLETA =====
        $correoEnviado = false;
        $emailEvaluador = $data['evaluador_email'] ?? '';
        
        if (!empty($emailEvaluador)) {
            try {
                $datosColaborador = [
                    'nom_col' => $data['nombres'] ?? '',
                    'apell_col' => $data['apellidos'] ?? '',
                    'fech_ing_col' => $data['fecha_ingreso'] ?? null,
                    'email_col' => $data['email'] ?? ''
                ];
                
                $datosEvaluador = [
                    'nom_eva' => $data['evaluador_nombres'] ?? '',
                    'apell_eva' => $data['evaluador_apellidos'] ?? '',
                    'email_eva' => $emailEvaluador
                ];
                
                $correoEnviado = enviarNotificacionJefe($datosColaborador, $datosEvaluador);
                
            } catch (Exception $e) {
                error_log("Error enviando correo de notificación en evaluación completa: " . $e->getMessage());
            }
        }
        
        $mensaje = '✅ Evaluación completa guardada exitosamente';
        if ($correoEnviado) {
            $mensaje .= ' - Notificación enviada al jefe inmediato';
        } elseif (!empty($emailEvaluador)) {
            $mensaje .= ' - Error enviando notificación por correo';
        }
        
        return array(
            'exito' => true,
            'mensaje' => $mensaje,
            'id_info_reg' => $id_info_reg,
            'id_matriz' => $conexion->lastInsertId(),
            'correo_enviado' => $correoEnviado
        );
        
    } catch (PDOException $e) {
        $conexion->rollback();
        error_log("Error en evaluación completa: " . $e->getMessage());
        return array(
            'exito' => false,
            'mensaje' => '❌ Error al guardar la evaluación: ' . $e->getMessage()
        );
    }
}

?>
