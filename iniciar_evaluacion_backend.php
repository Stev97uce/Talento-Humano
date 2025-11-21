<?php
/**
 * Backend para iniciar nueva evaluación
 * Guarda la solicitud y envía notificación al evaluador
 */

header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ERROR | E_PARSE);

require_once 'conexion.php';
require_once 'enviar_correo.php';

try {
    // Validar que sea una petición POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }
    
    // Recoger datos del formulario
    $fecha = $_POST['fecha'] ?? '';
    $nombres = $_POST['nombres'] ?? '';
    $apellidos = $_POST['apellidos'] ?? '';
    $cedula = $_POST['cedula'] ?? null;
    $cargo = $_POST['cargo'] ?? '';
    $departamento = $_POST['departamento'] ?? '';
    $fecha_ingreso = $_POST['fecha_ingreso'] ?? null;
    $email = $_POST['email'] ?? null;
    
    $evaluador_nombres = $_POST['evaluador_nombres'] ?? '';
    $evaluador_apellidos = $_POST['evaluador_apellidos'] ?? '';
    $eval_cargo = $_POST['eval_cargo'] ?? '';
    $eval_departamento = $_POST['eval_departamento'] ?? '';
    $evaluador_email = $_POST['evaluador_email'] ?? '';
    
    $periodo_prueba = $_POST['periodo_prueba'] ?? '';
    $tipo_evaluacion = $_POST['tipo_evaluacion'] ?? '';
    $dias_notificar = $_POST['dias_notificar'] ?? null;
    
    // Validar campos requeridos
    if (empty($fecha) || empty($nombres) || empty($apellidos) || empty($cargo) || empty($departamento)) {
        throw new Exception('Faltan datos del colaborador');
    }
    
    if (empty($evaluador_nombres) || empty($evaluador_apellidos) || empty($eval_cargo) || empty($eval_departamento) || empty($evaluador_email)) {
        throw new Exception('Faltan datos del evaluador');
    }
    
    if (empty($periodo_prueba) || empty($tipo_evaluacion)) {
        throw new Exception('Faltan datos del período de evaluación');
    }
    
    // Validar email del evaluador
    if (!filter_var($evaluador_email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Email del evaluador inválido');
    }
    
    // Conectar a base de datos
    $conexion = obtenerConexion();
    
    // Iniciar transacción
    $conexion->beginTransaction();
    
    // 1. Insertar/actualizar colaborador
    $stmt = $conexion->prepare("
        INSERT INTO colaborador (nom_col, apell_col, ced_col, cargo_col, area_col, fech_ing_col, email_col)
        VALUES (:nombres, :apellidos, :cedula, :cargo, :departamento, :fecha_ingreso, :email)
        ON DUPLICATE KEY UPDATE
        nom_col = :nombres,
        apell_col = :apellidos,
        cargo_col = :cargo,
        area_col = :departamento,
        fech_ing_col = :fecha_ingreso,
        email_col = :email
    ");
    
    $stmt->execute([
        ':nombres' => $nombres,
        ':apellidos' => $apellidos,
        ':cedula' => $cedula,
        ':cargo' => $cargo,
        ':departamento' => $departamento,
        ':fecha_ingreso' => $fecha_ingreso,
        ':email' => $email
    ]);
    
    // Obtener ID del colaborador
    $id_colaborador = $conexion->lastInsertId();
    if ($id_colaborador == 0) {
        // Si fue UPDATE, buscar el ID
        $stmt = $conexion->prepare("SELECT id_colaborador FROM colaborador WHERE ced_col = :cedula ORDER BY id_colaborador DESC LIMIT 1");
        $stmt->execute([':cedula' => $cedula]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $id_colaborador = $result['id_colaborador'];
    }
    
    // 2. Insertar/actualizar evaluador
    $stmt = $conexion->prepare("
        INSERT INTO evaluador (nom_eva, apell_eva, cargo_eva, area_eva, email_eva)
        VALUES (:nombres, :apellidos, :cargo, :departamento, :email)
        ON DUPLICATE KEY UPDATE
        nom_eva = :nombres,
        apell_eva = :apellidos,
        cargo_eva = :cargo,
        area_eva = :departamento
    ");
    
    $stmt->execute([
        ':nombres' => $evaluador_nombres,
        ':apellidos' => $evaluador_apellidos,
        ':cargo' => $eval_cargo,
        ':departamento' => $eval_departamento,
        ':email' => $evaluador_email
    ]);
    
    // Obtener ID del evaluador
    $id_evaluador = $conexion->lastInsertId();
    if ($id_evaluador == 0) {
        $stmt = $conexion->prepare("SELECT id_evaluador FROM evaluador WHERE email_eva = :email ORDER BY id_evaluador DESC LIMIT 1");
        $stmt->execute([':email' => $evaluador_email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $id_evaluador = $result['id_evaluador'];
    }
    
    // 3. Crear registro de información básica
    $stmt = $conexion->prepare("
        INSERT INTO info_registro (
            fec_registro,
            id_colaborador,
            id_evaluador,
            periodo_prueba,
            tipo_evaluacion,
            dias_notificar,
            estado
        ) VALUES (
            :fecha,
            :id_colaborador,
            :id_evaluador,
            :periodo_prueba,
            :tipo_evaluacion,
            :dias_notificar,
            'pendiente'
        )
    ");
    
    $stmt->execute([
        ':fecha' => $fecha,
        ':id_colaborador' => $id_colaborador,
        ':id_evaluador' => $id_evaluador,
        ':periodo_prueba' => $periodo_prueba,
        ':tipo_evaluacion' => $tipo_evaluacion,
        ':dias_notificar' => $dias_notificar
    ]);
    
    $id_registro = $conexion->lastInsertId();
    
    // Confirmar transacción
    $conexion->commit();
    
    // 4. Enviar correo al evaluador
    $datosColaborador = [
        'nom_col' => $nombres,
        'apell_col' => $apellidos,
        'ced_col' => $cedula,
        'cargo_col' => $cargo,
        'area_col' => $departamento,
        'fech_ing_col' => $fecha_ingreso,
        'email_col' => $email
    ];
    
    $datosEvaluador = [
        'nom_eva' => $evaluador_nombres,
        'apell_eva' => $evaluador_apellidos,
        'cargo_eva' => $eval_cargo,
        'area_eva' => $eval_departamento,
        'email_eva' => $evaluador_email
    ];
    
    $correoEnviado = enviarNotificacionJefe($datosColaborador, $datosEvaluador);
    
    if ($correoEnviado) {
        echo json_encode([
            'exito' => true,
            'mensaje' => '✓ Solicitud de evaluación creada exitosamente. Se ha enviado notificación al evaluador.',
            'id_registro' => $id_registro,
            'correo_enviado' => true
        ]);
    } else {
        echo json_encode([
            'exito' => true,
            'mensaje' => '✓ Solicitud de evaluación creada. Advertencia: No se pudo enviar el correo al evaluador.',
            'id_registro' => $id_registro,
            'correo_enviado' => false
        ]);
    }
    
} catch (Exception $e) {
    // Revertir transacción si existe
    if (isset($conexion) && $conexion->inTransaction()) {
        $conexion->rollBack();
    }
    
    http_response_code(400);
    echo json_encode([
        'exito' => false,
        'mensaje' => 'Error: ' . $e->getMessage()
    ]);
}
?>
