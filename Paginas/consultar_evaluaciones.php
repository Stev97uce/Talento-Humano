<?php
header('Content-Type: application/json');
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    try {
        $conexion = conectarDB();
        
        if ($conexion === null) {
            echo json_encode(['exito' => false, 'mensaje' => 'Error de conexión']);
            exit;
        }
        
        // Verificar que la vista existe
        $check_view = $conexion->prepare("SHOW TABLES LIKE 'vista_evaluaciones_matriz'");
        $check_view->execute();
        if (!$check_view->fetch()) {
            echo json_encode([
                'exito' => false, 
                'mensaje' => 'La vista de evaluaciones no existe. Ejecute el script de actualización de base de datos.'
            ]);
            exit;
        }
        
        // Construir la consulta con filtros usando la vista correcta
        $sql = "SELECT 
                    vem.*
                FROM vista_evaluaciones_matriz vem
                WHERE vem.activo = 1";
        
        $params = [];
        
        // Aplicar filtros
        if (!empty($input['colaborador'])) {
            $sql .= " AND (vem.colaborador_nombres LIKE ? OR vem.colaborador_apellidos LIKE ? OR vem.colaborador_completo LIKE ?)";
            $filtroColaborador = '%' . $input['colaborador'] . '%';
            $params[] = $filtroColaborador;
            $params[] = $filtroColaborador;
            $params[] = $filtroColaborador;
        }
        
        if (!empty($input['departamento'])) {
            $sql .= " AND vem.colaborador_departamento = ?";
            $params[] = $input['departamento'];
        }
        
        if (!empty($input['tipo'])) {
            $sql .= " AND vem.tipo_evaluacion = ?";
            $params[] = $input['tipo'];
        }
        
        if (!empty($input['periodo'])) {
            $sql .= " AND vem.periodo_prueba = ?";
            $params[] = $input['periodo'];
        }
        
        // Verificar tipo de operación solicitada
        if (isset($input['detalle']) && $input['detalle']) {
            // Obtener detalle específico
            obtenerDetalleEvaluacion($conexion, $input['detalle']);
        } elseif (isset($input['eliminar']) && $input['eliminar']) {
            // Eliminar evaluación
            eliminarEvaluacion($conexion, $input['eliminar']);
        } else {
            // Obtener lista con filtros
            $sql .= " ORDER BY vem.fecha_creacion_evaluacion DESC LIMIT 100";
            
            $stmt = $conexion->prepare($sql);
            $stmt->execute($params);
            $evaluaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'exito' => true,
                'evaluaciones' => $evaluaciones,
                'total' => count($evaluaciones)
            ]);
        }
        
    } catch (PDOException $e) {
        error_log("Error en consulta de evaluaciones: " . $e->getMessage());
        echo json_encode([
            'exito' => false,
            'mensaje' => 'Error al consultar evaluaciones: ' . $e->getMessage()
        ]);
    }
    
} else {
    echo json_encode(['exito' => false, 'mensaje' => 'Método no permitido']);
}

function obtenerDetalleEvaluacion($conexion, $id) {
    try {
        $sql = "SELECT 
                    vem.*
                FROM vista_evaluaciones_matriz vem
                WHERE vem.id_evaluacion = ? AND vem.activo = 1
                LIMIT 1";
        
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$id]);
        $evaluacion = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($evaluacion) {
            echo json_encode([
                'exito' => true,
                'evaluacion' => $evaluacion
            ]);
        } else {
            echo json_encode([
                'exito' => false,
                'mensaje' => 'Evaluación no encontrada'
            ]);
        }
        
    } catch (PDOException $e) {
        error_log("Error al obtener detalle de evaluación: " . $e->getMessage());
        echo json_encode([
            'exito' => false,
            'mensaje' => 'Error al obtener detalles: ' . $e->getMessage()
        ]);
    }
}

function eliminarEvaluacion($conexion, $id) {
    try {
        // Iniciar transacción para consistencia de datos
        $conexion->beginTransaction();
        
        // Verificar que la evaluación existe
        $checkSql = "SELECT id_info_reg FROM info_registro WHERE id_info_reg = ? AND activo = 1";
        $checkStmt = $conexion->prepare($checkSql);
        $checkStmt->execute([$id]);
        
        if (!$checkStmt->fetch()) {
            $conexion->rollBack();
            echo json_encode([
                'exito' => false,
                'mensaje' => 'La evaluación no existe o ya fue eliminada'
            ]);
            return;
        }
        
        // ===== ELIMINACIÓN FÍSICA COMPLETA =====
        
        // 1. Eliminar de tabla matriz (si existe)
        $deleteMatrizSql = "DELETE FROM matriz WHERE id_info_reg = ?";
        $deleteMatrizStmt = $conexion->prepare($deleteMatrizSql);
        $deleteMatrizStmt->execute([$id]);
        
        // 2. Eliminar de tabla colaborador
        $deleteColaboradorSql = "DELETE FROM colaborador WHERE id_info_reg = ?";
        $deleteColaboradorStmt = $conexion->prepare($deleteColaboradorSql);
        $deleteColaboradorStmt->execute([$id]);
        
        // 3. Eliminar de tabla evaluador
        $deleteEvaluadorSql = "DELETE FROM evaluador WHERE id_info_reg = ?";
        $deleteEvaluadorStmt = $conexion->prepare($deleteEvaluadorSql);
        $deleteEvaluadorStmt->execute([$id]);
        
        // 4. Finalmente, eliminar el registro principal
        $deleteInfoSql = "DELETE FROM info_registro WHERE id_info_reg = ?";
        $deleteInfoStmt = $conexion->prepare($deleteInfoSql);
        $deleteInfoStmt->execute([$id]);
        
        // Verificar que se eliminó el registro principal
        if ($deleteInfoStmt->rowCount() > 0) {
            $conexion->commit();
            
            // Log de la eliminación
            error_log("Evaluación eliminada completamente - ID: {$id} - Usuario IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'N/A'));
            
            echo json_encode([
                'exito' => true,
                'mensaje' => 'Evaluación eliminada completamente de la base de datos'
            ]);
        } else {
            $conexion->rollBack();
            echo json_encode([
                'exito' => false,
                'mensaje' => 'No se pudo eliminar la evaluación del registro principal'
            ]);
        }
        
    } catch (PDOException $e) {
        $conexion->rollBack();
        error_log("Error al eliminar evaluación: " . $e->getMessage());
        echo json_encode([
            'exito' => false,
            'mensaje' => 'Error al eliminar evaluación: ' . $e->getMessage()
        ]);
    }
}
?>