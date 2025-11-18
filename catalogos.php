<?php
require_once 'conexion.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Manejar tanto form data como JSON
    $input_json = json_decode(file_get_contents('php://input'), true);
    
    if ($input_json) {
        // Petición JSON
        $action = $input_json['accion'] ?? '';
        $data = $input_json;
    } else {
        // Petición form data
        $action = $_POST['action'] ?? '';
        $data = $_POST;
    }
    
    switch ($action) {
        case 'obtener_departamentos':
            try {
                $conexion = conectarDB();
                if (!$conexion) {
                    echo json_encode(['success' => false, 'mensaje' => 'Error de conexión']);
                    exit;
                }
                
                $sql = "SELECT id_dep as id_departamento, nom_dep, descri_dep FROM departamentos WHERE activo = 1 ORDER BY nom_dep ASC";
                $stmt = $conexion->prepare($sql);
                $stmt->execute();
                $departamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo json_encode([
                    'success' => true,
                    'departamentos' => $departamentos
                ]);
                
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
            }
            break;
            
        case 'obtener_cargos':
            try {
                $conexion = conectarDB();
                if (!$conexion) {
                    echo json_encode(['success' => false, 'mensaje' => 'Error de conexión']);
                    exit;
                }
                
                $sql = "SELECT id_cargo, nom_car, descri_car FROM cargos WHERE activo = 1 ORDER BY nom_car ASC";
                $stmt = $conexion->prepare($sql);
                $stmt->execute();
                $cargos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo json_encode([
                    'success' => true,
                    'cargos' => $cargos
                ]);
                
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
            }
            break;
            
        case 'obtener_competencias_cargo':
            try {
                require_once 'competencias_definicion.php';
                
                $nombre_cargo = isset($data['nombre_cargo']) ? trim($data['nombre_cargo']) : '';
                
                if (empty($nombre_cargo)) {
                    echo json_encode(['success' => false, 'mensaje' => 'Nombre de cargo requerido']);
                    exit;
                }
                
                $resultado = obtenerCompetenciasPorCargo($nombre_cargo);
                
                echo json_encode([
                    'success' => true,
                    'competencias' => $resultado['competencias'],
                    'es_especifico' => $resultado['es_especifico'],
                    'cargo' => $resultado['cargo']
                ]);
                
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
            }
            break;
            
        case 'agregar_departamento':
            try {
                $conexion = conectarDB();
                if (!$conexion) {
                    echo json_encode(['exito' => false, 'mensaje' => 'Error de conexión']);
                    exit;
                }
                
                $nombre = trim($data['nombre'] ?? '');
                $descripcion = trim($data['descripcion'] ?? '');
                
                if (empty($nombre)) {
                    echo json_encode(['exito' => false, 'mensaje' => 'El nombre del departamento es requerido']);
                    exit;
                }
                
                $sql = "INSERT INTO departamentos (nom_dep, descri_dep) VALUES (?, ?)";
                $stmt = $conexion->prepare($sql);
                $stmt->execute([$nombre, $descripcion]);
                
                echo json_encode([
                    'exito' => true,
                    'mensaje' => 'Departamento agregado exitosamente',
                    'nombre' => $nombre
                ]);
                
            } catch (Exception $e) {
                if ($e->getCode() == '23000') {
                    echo json_encode(['exito' => false, 'mensaje' => 'Ya existe un departamento con ese nombre']);
                } else {
                    echo json_encode(['exito' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
                }
            }
            break;
            
        case 'agregar_cargo':
            try {
                $conexion = conectarDB();
                if (!$conexion) {
                    echo json_encode(['exito' => false, 'mensaje' => 'Error de conexión']);
                    exit;
                }
                
                $nombre = trim($data['nombre'] ?? '');
                $descripcion = trim($data['descripcion'] ?? '');
                
                if (empty($nombre)) {
                    echo json_encode(['exito' => false, 'mensaje' => 'El nombre del cargo es requerido']);
                    exit;
                }
                
                $sql = "INSERT INTO cargos (nom_car, descri_car) VALUES (?, ?)";
                $stmt = $conexion->prepare($sql);
                $stmt->execute([$nombre, $descripcion]);
                
                echo json_encode([
                    'exito' => true,
                    'mensaje' => 'Cargo agregado exitosamente',
                    'nombre' => $nombre
                ]);
                
            } catch (Exception $e) {
                if ($e->getCode() == '23000') {
                    echo json_encode(['exito' => false, 'mensaje' => 'Ya existe un cargo con ese nombre']);
                } else {
                    echo json_encode(['exito' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
                }
            }
            break;
            
        case 'editar_departamento':
            try {
                $conexion = conectarDB();
                if (!$conexion) {
                    echo json_encode(['exito' => false, 'mensaje' => 'Error de conexión']);
                    exit;
                }
                
                $id = intval($data['id'] ?? 0);
                $nombre = trim($data['nombre'] ?? '');
                $descripcion = trim($data['descripcion'] ?? '');
                
                if ($id <= 0) {
                    echo json_encode(['exito' => false, 'mensaje' => 'ID de departamento inválido']);
                    exit;
                }
                
                if (empty($nombre)) {
                    echo json_encode(['exito' => false, 'mensaje' => 'El nombre del departamento es requerido']);
                    exit;
                }
                
                $sql = "UPDATE departamentos SET nom_dep = ?, descri_dep = ? WHERE id_dep = ?";
                $stmt = $conexion->prepare($sql);
                $stmt->execute([$nombre, $descripcion, $id]);
                
                if ($stmt->rowCount() > 0) {
                    echo json_encode([
                        'exito' => true,
                        'mensaje' => 'Departamento actualizado exitosamente',
                        'nombre' => $nombre
                    ]);
                } else {
                    echo json_encode(['exito' => false, 'mensaje' => 'No se pudo actualizar el departamento']);
                }
                
            } catch (Exception $e) {
                if ($e->getCode() == '23000') {
                    echo json_encode(['exito' => false, 'mensaje' => 'Ya existe un departamento con ese nombre']);
                } else {
                    echo json_encode(['exito' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
                }
            }
            break;
            
        case 'eliminar_departamento':
            try {
                $conexion = conectarDB();
                if (!$conexion) {
                    echo json_encode(['exito' => false, 'mensaje' => 'Error de conexión']);
                    exit;
                }
                
                $id = intval($data['id'] ?? 0);
                
                if ($id <= 0) {
                    echo json_encode(['exito' => false, 'mensaje' => 'ID de departamento inválido']);
                    exit;
                }
                
                // Verificar si está en uso
                $sql_check = "SELECT COUNT(*) as count FROM (
                    SELECT id_departamento FROM colaborador WHERE id_departamento = ?
                    UNION ALL
                    SELECT id_departamento FROM evaluador WHERE id_departamento = ?
                ) as dept_usage";
                $stmt_check = $conexion->prepare($sql_check);
                $stmt_check->execute([$id, $id]);
                $usage = $stmt_check->fetch(PDO::FETCH_ASSOC);
                
                if ($usage['count'] > 0) {
                    echo json_encode(['exito' => false, 'mensaje' => 'No se puede eliminar el departamento porque está siendo utilizado']);
                    exit;
                }
                
                $sql = "DELETE FROM departamentos WHERE id_dep = ?";
                $stmt = $conexion->prepare($sql);
                $stmt->execute([$id]);
                
                if ($stmt->rowCount() > 0) {
                    echo json_encode(['exito' => true, 'mensaje' => 'Departamento eliminado exitosamente']);
                } else {
                    echo json_encode(['exito' => false, 'mensaje' => 'No se encontró el departamento a eliminar']);
                }
                
            } catch (Exception $e) {
                echo json_encode(['exito' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
            }
            break;
            
        case 'editar_cargo':
            try {
                $conexion = conectarDB();
                if (!$conexion) {
                    echo json_encode(['exito' => false, 'mensaje' => 'Error de conexión']);
                    exit;
                }
                
                $id = intval($data['id'] ?? 0);
                $nombre = trim($data['nombre'] ?? '');
                $descripcion = trim($data['descripcion'] ?? '');
                
                if ($id <= 0) {
                    echo json_encode(['exito' => false, 'mensaje' => 'ID de cargo inválido']);
                    exit;
                }
                
                if (empty($nombre)) {
                    echo json_encode(['exito' => false, 'mensaje' => 'El nombre del cargo es requerido']);
                    exit;
                }
                
                $sql = "UPDATE cargos SET nom_car = ?, descri_car = ? WHERE id_cargo = ?";
                $stmt = $conexion->prepare($sql);
                $stmt->execute([$nombre, $descripcion, $id]);
                
                if ($stmt->rowCount() > 0) {
                    echo json_encode([
                        'exito' => true,
                        'mensaje' => 'Cargo actualizado exitosamente',
                        'nombre' => $nombre
                    ]);
                } else {
                    echo json_encode(['exito' => false, 'mensaje' => 'No se pudo actualizar el cargo']);
                }
                
            } catch (Exception $e) {
                if ($e->getCode() == '23000') {
                    echo json_encode(['exito' => false, 'mensaje' => 'Ya existe un cargo con ese nombre']);
                } else {
                    echo json_encode(['exito' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
                }
            }
            break;
            
        case 'eliminar_cargo':
            try {
                $conexion = conectarDB();
                if (!$conexion) {
                    echo json_encode(['exito' => false, 'mensaje' => 'Error de conexión']);
                    exit;
                }
                
                $id = intval($data['id'] ?? 0);
                
                if ($id <= 0) {
                    echo json_encode(['exito' => false, 'mensaje' => 'ID de cargo inválido']);
                    exit;
                }
                
                // Verificar si está en uso
                $sql_check = "SELECT COUNT(*) as count FROM (
                    SELECT id_cargo FROM colaborador WHERE id_cargo = ?
                    UNION ALL
                    SELECT id_cargo FROM evaluador WHERE id_cargo = ?
                ) as cargo_usage";
                $stmt_check = $conexion->prepare($sql_check);
                $stmt_check->execute([$id, $id]);
                $usage = $stmt_check->fetch(PDO::FETCH_ASSOC);
                
                if ($usage['count'] > 0) {
                    echo json_encode(['exito' => false, 'mensaje' => 'No se puede eliminar el cargo porque está siendo utilizado']);
                    exit;
                }
                
                $sql = "DELETE FROM cargos WHERE id_cargo = ?";
                $stmt = $conexion->prepare($sql);
                $stmt->execute([$id]);
                
                if ($stmt->rowCount() > 0) {
                    echo json_encode(['exito' => true, 'mensaje' => 'Cargo eliminado exitosamente']);
                } else {
                    echo json_encode(['exito' => false, 'mensaje' => 'No se encontró el cargo a eliminar']);
                }
                
            } catch (Exception $e) {
                echo json_encode(['exito' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'mensaje' => 'Acción no válida: ' . $action]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $tipo = $_GET['tipo'] ?? '';
    
    switch ($tipo) {
        case 'departamentos':
            try {
                $conexion = conectarDB();
                if (!$conexion) {
                    echo json_encode(['exito' => false, 'mensaje' => 'Error de conexión']);
                    exit;
                }
                
                $sql = "SELECT id_dep, nom_dep, descri_dep FROM departamentos WHERE activo = 1 ORDER BY nom_dep ASC";
                $stmt = $conexion->prepare($sql);
                $stmt->execute();
                $departamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo json_encode([
                    'exito' => true,
                    'departamentos' => $departamentos
                ]);
                
            } catch (Exception $e) {
                echo json_encode(['exito' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
            }
            break;
            
        case 'cargos':
            try {
                $conexion = conectarDB();
                if (!$conexion) {
                    echo json_encode(['exito' => false, 'mensaje' => 'Error de conexión']);
                    exit;
                }
                
                $sql = "SELECT id_cargo, nom_car, descri_car FROM cargos WHERE activo = 1 ORDER BY nom_car ASC";
                $stmt = $conexion->prepare($sql);
                $stmt->execute();
                $cargos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo json_encode([
                    'exito' => true,
                    'cargos' => $cargos
                ]);
                
            } catch (Exception $e) {
                echo json_encode(['exito' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
            }
            break;
            
        default:
            echo json_encode(['exito' => false, 'mensaje' => 'Tipo no válido']);
    }
} else {
    echo json_encode(['success' => false, 'mensaje' => 'Método no permitido']);
}
?>