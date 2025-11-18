<?php
// Configurar manejo de errores para producción
error_reporting(E_ERROR | E_PARSE); // Solo errores críticos
ini_set('display_errors', 0); // No mostrar errores en salida

require_once 'conexion.php';
require_once 'enviar_correo.php';

// =====================================================
// FUNCIONES AUXILIARES PARA OBTENER IDs DE CATÁLOGOS
// =====================================================

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

// =====================================================
// FUNCIÓN PARA TABLA MATRIZ SIMPLIFICADA
// =====================================================

// Procesar datos del formulario si se envía por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
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
// =====================================================
// PARA TABLA SIMPLIFICADA
// =====================================================
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
