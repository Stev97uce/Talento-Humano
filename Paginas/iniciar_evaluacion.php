<?php
/**
 * Iniciar Nueva Evaluación - Solo Talento Humano
 * Backend integrado en el mismo archivo
 */

// Funciones auxiliares para obtener IDs
function obtenerIdCargo($valor_cargo) {
    $conexion = conectarDB();
    if ($conexion === null) return null;
    
    if (is_numeric($valor_cargo)) {
        $sql = "SELECT id_cargo FROM cargos WHERE id_cargo = ? LIMIT 1";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([intval($valor_cargo)]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($resultado) return $resultado['id_cargo'];
    }
    
    $sql = "SELECT id_cargo FROM cargos WHERE nom_car = ? LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$valor_cargo]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    return $resultado ? $resultado['id_cargo'] : null;
}

function obtenerIdDepartamento($valor_departamento) {
    $conexion = conectarDB();
    if ($conexion === null) return null;
    
    if (is_numeric($valor_departamento)) {
        $sql = "SELECT id_dep FROM departamentos WHERE id_dep = ? LIMIT 1";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([intval($valor_departamento)]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($resultado) return $resultado['id_dep'];
    }
    
    $sql = "SELECT id_dep FROM departamentos WHERE nom_dep = ? LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$valor_departamento]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    return $resultado ? $resultado['id_dep'] : null;
}

// Procesar formulario si viene por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'iniciar_evaluacion') {
    ob_start();
    header('Content-Type: application/json; charset=utf-8');
    error_reporting(0);
    ini_set('display_errors', 0);
    ob_clean();
    
    require_once '../conexion.php';
    require_once '../enviar_correo.php';
    
    try {
        $datos = $_POST;
        
        // Validar campos requeridos
        if (empty($datos['nombres']) || empty($datos['apellidos']) || empty($datos['cargo']) || empty($datos['departamento'])) {
            throw new Exception('Faltan datos del colaborador');
        }
        
        if (empty($datos['evaluador_nombres']) || empty($datos['evaluador_apellidos']) || 
            empty($datos['eval_cargo']) || empty($datos['eval_departamento']) || empty($datos['evaluador_email'])) {
            throw new Exception('Faltan datos del evaluador');
        }
        
        if (!filter_var($datos['evaluador_email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Email del evaluador inválido');
        }
        
        // Obtener IDs de catálogos
        $id_cargo_colaborador = obtenerIdCargo($datos['cargo']);
        $id_departamento_colaborador = obtenerIdDepartamento($datos['departamento']);
        $id_cargo_evaluador = obtenerIdCargo($datos['eval_cargo']);
        $id_departamento_evaluador = obtenerIdDepartamento($datos['eval_departamento']);
        
        // Validar que existan
        if (!$id_cargo_colaborador) {
            throw new Exception("El cargo '{$datos['cargo']}' no existe");
        }
        if (!$id_departamento_colaborador) {
            throw new Exception("El departamento '{$datos['departamento']}' no existe");
        }
        if (!$id_cargo_evaluador) {
            throw new Exception("El cargo del evaluador '{$datos['eval_cargo']}' no existe");
        }
        if (!$id_departamento_evaluador) {
            throw new Exception("El departamento del evaluador '{$datos['eval_departamento']}' no existe");
        }
        
        $conexion = conectarDB();
        if (!$conexion) {
            throw new Exception('Error de conexión a la base de datos');
        }
        
        $conexion->beginTransaction();
        
        // 1. Crear info_registro primero
        $sql_info = "INSERT INTO info_registro (activo, periodo_prueba, tipo_evaluacion) VALUES (1, ?, ?)";
        $stmt_info = $conexion->prepare($sql_info);
        $stmt_info->execute([
            $datos['periodo_prueba'], 
            $datos['tipo_evaluacion']
        ]);
        $id_info_registro = $conexion->lastInsertId();
        
        // 2. Crear evaluador
        $sql_evaluador = "INSERT INTO evaluador (nom_eva, apell_eva, id_cargo, id_departamento, email_eva, id_info_reg) 
                         VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_evaluador = $conexion->prepare($sql_evaluador);
        $stmt_evaluador->execute([
            $datos['evaluador_nombres'], 
            $datos['evaluador_apellidos'],
            $id_cargo_evaluador, 
            $id_departamento_evaluador, 
            $datos['evaluador_email'],
            $id_info_registro
        ]);
        
        // 3. Crear colaborador
        $sql_colaborador = "INSERT INTO colaborador (nom_col, apell_col, id_cargo, id_departamento, fech_ing_col, email_col, id_info_reg)
                           VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_colaborador = $conexion->prepare($sql_colaborador);
        $stmt_colaborador->execute([
            $datos['nombres'], 
            $datos['apellidos'],
            $id_cargo_colaborador, 
            $id_departamento_colaborador,
            $datos['fecha_ingreso'] ?? null, 
            $datos['email'] ?? null,
            $id_info_registro
        ]);
        
        $conexion->commit();
        
        // Enviar correo de notificación
        $correoEnviado = false;
        if (!empty($datos['evaluador_email'])) {
            try {
                $datosCol = [
                    'nom_col' => $datos['nombres'], 
                    'apell_col' => $datos['apellidos'],
                    'fech_ing_col' => $datos['fecha_ingreso'] ?? null, 
                    'email_col' => $datos['email'] ?? null
                ];
                
                $datosEva = [
                    'nom_eva' => $datos['evaluador_nombres'], 
                    'apell_eva' => $datos['evaluador_apellidos'],
                    'email_eva' => $datos['evaluador_email']
                ];
                
                $correoEnviado = enviarNotificacionJefe($datosCol, $datosEva);
            } catch (Exception $e) {
                // Error en correo no debe impedir el registro
                error_log("Error enviando correo: " . $e->getMessage());
            }
        }
        
        $mensaje = '✓ Evaluación iniciada exitosamente';
        if ($correoEnviado) {
            $mensaje .= '. Notificación enviada al evaluador.';
        } elseif (!empty($datos['evaluador_email'])) {
            $mensaje .= '. Advertencia: No se pudo enviar el correo de notificación.';
        }
        
        echo json_encode([
            'exito' => true,
            'mensaje' => $mensaje,
            'correo_enviado' => $correoEnviado,
            'id_registro' => $id_info_registro
        ]);
        exit;
        
    } catch (Exception $e) {
        if (isset($conexion) && $conexion->inTransaction()) $conexion->rollBack();
        echo json_encode(['exito' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
        exit;
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Iniciar Evaluación - KMSOLUTIONS S.A.</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../Estilos/styles.css">
</head>
<body>
  
  <div id="loadingOverlay" class="loading-overlay d-none">
    <div class="loading-content">
      <div class="spinner-border text-danger" role="status"><span class="visually-hidden">Cargando...</span></div>
      <p class="mt-3">Procesando solicitud...</p>
    </div>
  </div>

  <div id="alertContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 1060;"></div>

  <!-- Header (igual al index.php) -->
  <header class="bg-dark text-white py-3 shadow">
    <div class="container-fluid">
      <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
          <i class="bi bi-clipboard-check-fill text-danger fs-3 me-3"></i>
          <div>
            <h4 class="mb-0 text-danger fw-bold">KMSOLUTIONS S.A.</h4>
            <small class="text-muted">Sistema de Evaluación de Desempeño</small>
          </div>
        </div>
        <div class="text-end">
          <nav class="mb-2">
            <a href="iniciar_evaluacion.php" class="btn btn-outline-light btn-sm me-2 active">
              <i class="bi bi-plus-circle me-1"></i>Iniciar Evaluación
            </a>
            <a href="consultas.php" class="btn btn-outline-light btn-sm">
              <i class="bi bi-search me-1"></i>Consultar Evaluaciones
            </a>
          </nav>
          <div class="small">Fecha: <span id="currentDate"></span></div>
          <div class="small text-muted">Usuario: <span class="badge bg-info">Talento Humano</span></div>
        </div>
      </div>
    </div>
  </header>

  <div class="container-fluid py-4" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: calc(100vh - 140px);">
    <div class="row justify-content-center">
      <div class="col-12 col-xl-10">
        
        <form id="iniciarEvaluacionForm" class="needs-validation" novalidate>
          
          <div class="card mb-4 shadow-lg border-0">
            <div class="card-header bg-danger text-white">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <div class="fw-bold fs-5">KMSOLUTIONS S.A.</div>
                  <div class="small">SISTEMA DE GESTIÓN INTEGRADO</div>
                </div>
                <div class="text-end">
                  <div class="small">Cód.: GTH-PA-P001-F056</div>
                  <div class="small">Versión: 1.0</div>
                </div>
              </div>
            </div>
            <div class="card-body bg-light">
              <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0 text-danger fw-bold">
                  <i class="bi bi-clipboard-data me-2"></i>INICIAR NUEVA EVALUACIÓN
                </h4>
                <div class="text-end">
                  <label class="form-label fw-bold mb-1">FECHA: *</label>
                  <input type="date" class="form-control form-control-sm" name="fecha" required />
                </div>
              </div>
              <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                Complete la información y se enviará notificación al evaluador por correo.
              </div>
            </div>
          </div>

          <!-- SECCIONES COPIADAS EXACTAS DEL INDEX.PHP -->
          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <div class="card border-danger h-100">
                <div class="card-header bg-light border-danger">
                  <h6 class="mb-0 text-danger fw-bold">
                    <i class="bi bi-person-fill me-2"></i>COLABORADOR (a)
                  </h6>
                </div>
                <div class="card-body">
                  <div class="row g-3">
                    <div class="col-6">
                      <label class="form-label fw-semibold">Nombres: *</label>
                      <input class="form-control" name="nombres" required />
                      <div class="invalid-feedback">Este campo es requerido.</div>
                    </div>
                    <div class="col-6">
                      <label class="form-label fw-semibold">Apellidos: *</label>
                      <input class="form-control" name="apellidos" required />
                      <div class="invalid-feedback">Este campo es requerido.</div>
                    </div>
                    <div class="col-6">
                      <label class="form-label fw-semibold">Cargo Actual: *</label>
                      <div class="input-group">
                        <select class="form-select" name="cargo" id="selectCargo" required>
                          <option value="">Cargando cargos...</option>
                        </select>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoCargoColaborador">
                          <i class="bi bi-plus-circle"></i>
                        </button>
                      </div>
                      <div class="invalid-feedback">Este campo es requerido.</div>
                    </div>
                    <div class="col-6">
                      <label class="form-label fw-semibold">Departamento: *</label>
                      <div class="input-group">
                        <select class="form-select" name="departamento" id="selectDepartamento" required>
                          <option value="">Cargando departamentos...</option>
                        </select>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoDepartamentoColaborador">
                          <i class="bi bi-plus-circle"></i>
                        </button>
                      </div>
                      <div class="invalid-feedback">Este campo es requerido.</div>
                    </div>
                    <div class="col-6">
                      <label class="form-label fw-semibold">Fecha ingreso:</label>
                      <input type="date" class="form-control" name="fecha_ingreso" />
                    </div>
                    <div class="col-6">
                      <label class="form-label fw-semibold">Email (notificaciones):</label>
                      <input type="email" class="form-control" name="email" placeholder="ejemplo@kmsolutions.com" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="card border-dark h-100">
                <div class="card-header bg-dark text-white">
                  <h6 class="mb-0 fw-bold">
                    <i class="bi bi-person-badge-fill me-2"></i>EVALUADOR (a)
                  </h6>
                </div>
                <div class="card-body">
                  <div class="row g-3">
                    <div class="col-6">
                      <label class="form-label fw-semibold">Nombres: *</label>
                      <input class="form-control" name="evaluador_nombres" required />
                      <div class="invalid-feedback">Este campo es requerido.</div>
                    </div>
                    <div class="col-6">
                      <label class="form-label fw-semibold">Apellidos: *</label>
                      <input class="form-control" name="evaluador_apellidos" required />
                      <div class="invalid-feedback">Este campo es requerido.</div>
                    </div>
                    <div class="col-6">
                      <label class="form-label fw-semibold">Cargo Actual: *</label>
                      <div class="input-group">
                        <select class="form-select" name="eval_cargo" id="selectCargoEvaluador" required>
                          <option value="">Cargando cargos...</option>
                        </select>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoCargoEvaluador">
                          <i class="bi bi-plus-circle"></i>
                        </button>
                      </div>
                      <div class="invalid-feedback">Este campo es requerido.</div>
                    </div>
                    <div class="col-6">
                      <label class="form-label fw-semibold">Departamento: *</label>
                      <div class="input-group">
                        <select class="form-select" name="eval_departamento" id="selectDepartamentoEvaluador" required>
                          <option value="">Cargando departamentos...</option>
                        </select>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoDepartamentoEvaluador">
                          <i class="bi bi-plus-circle"></i>
                        </button>
                      </div>
                      <div class="invalid-feedback">Este campo es requerido.</div>
                    </div>
                    <div class="col-12">
                      <label class="form-label fw-semibold">Email (notificaciones): *</label>
                      <input type="email" class="form-control" name="evaluador_email" placeholder="ejemplo@kmsolutions.com" required />
                      <div class="invalid-feedback">Por favor ingrese un email válido.</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- PERÍODO (COPIADO EXACTO DEL INDEX.PHP) -->
          <div class="card mb-4 shadow">
            <div class="card-header bg-info text-white">
              <h6 class="mb-0 fw-bold">
                <i class="bi bi-calendar-range me-2"></i>INFORMACIÓN DEL PERÍODO
              </h6>
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-3">
                  <label class="form-label fw-semibold">PERIODO EVALUADO - Desde:</label>
                  <input type="date" class="form-control" name="periodo_desde" />
                </div>
                <div class="col-md-3">
                  <label class="form-label fw-semibold">Hasta:</label>
                  <input type="date" class="form-control" name="periodo_hasta" />
                </div>
                <div class="col-md-3">
                  <label class="form-label fw-semibold">Período de prueba: *</label>
                  <select class="form-select" name="periodo_prueba" required>
                    <option value="no" selected>No</option>
                    <option value="si">Sí</option>
                  </select>
                  <div class="invalid-feedback">Este campo es requerido.</div>
                </div>
                <div class="col-md-3">
                  <label class="form-label fw-semibold">Tipo de evaluación: *</label>
                  <div class="mt-2">
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="tipo_evaluacion" id="semestral" value="semestral" required>
                      <label class="form-check-label" for="semestral">Semestral</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="tipo_evaluacion" id="anual" value="anual" required>
                      <label class="form-check-label" for="anual">Anual</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Botones -->
          <div class="card shadow-lg">
            <div class="card-body">
              <div class="alert alert-warning mb-3">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Importante:</strong> Se enviará notificación por correo al evaluador.
              </div>
              <div class="d-flex justify-content-center gap-3 flex-wrap">
                <button type="button" class="btn btn-outline-secondary" onclick="limpiarFormulario()">
                  <i class="bi bi-arrow-clockwise me-2"></i>Limpiar Formulario
                </button>
                <button type="submit" class="btn btn-danger btn-lg">
                  <i class="bi bi-send me-2"></i>Enviar Solicitud al Evaluador
                </button>
              </div>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>

  <!-- Footer (igual al index.php) -->
  <footer class="bg-dark text-white text-center py-3 mt-5">
    <div class="container">
      <small>&copy; 2025 KMSOLUTIONS S.A. - Sistema de Evaluación de Desempeño v1.0 | GTH-PA-P001-F056</small>
    </div>
  </footer>

  <!-- MODALES COPIADOS EXACTOS DEL INDEX.PHP -->
  
  <!-- Modal Gestión Departamentos para Colaborador -->
  <div class="modal fade" id="modalNuevoDepartamentoColaborador" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">
            <i class="bi bi-building-gear me-2"></i>
            Gestión de Departamentos (Colaborador)
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <!-- Formulario para agregar/editar -->
            <div class="col-md-5">
              <h6 class="text-primary mb-3">
                <i class="bi bi-plus-circle me-1"></i>
                <span id="tituloFormDeptColab">Agregar Departamento</span>
              </h6>
              <form id="formNuevoDepartamentoColaborador">
                <input type="hidden" id="editDeptIdColab" name="departamento_id">
                <div class="mb-3">
                  <label class="form-label fw-semibold">Nombre del Departamento: *</label>
                  <input type="text" class="form-control" id="nombreDeptColab" name="nombre_departamento" required maxlength="100">
                  <div class="form-text">Máximo 100 caracteres</div>
                </div>
                <div class="mb-3">
                  <label class="form-label fw-semibold">Descripción:</label>
                  <textarea class="form-control" id="descripcionDeptColab" name="descripcion_departamento" rows="3" placeholder="Descripción opcional del departamento..."></textarea>
                </div>
                <div class="d-grid gap-2">
                  <button type="button" class="btn btn-primary" onclick="guardarDepartamentoColaborador()">
                    <i class="bi bi-check-circle me-1"></i>
                    <span id="btnTextDeptColab">Guardar</span>
                  </button>
                  <button type="button" class="btn btn-outline-secondary" onclick="cancelarEdicionDeptColab()" style="display:none;" id="btnCancelarDeptColab">
                    <i class="bi bi-x-circle me-1"></i>Cancelar Edición
                  </button>
                </div>
              </form>
            </div>

            <!-- Lista de departamentos existentes -->
            <div class="col-md-7">
              <h6 class="text-secondary mb-3">
                <i class="bi bi-list-ul me-1"></i>
                Departamentos Existentes
              </h6>
              <div id="listaDepartamentosColab" class="border rounded p-2" style="max-height: 300px; overflow-y: auto;">
                <div class="text-center text-muted p-3">
                  <i class="bi bi-hourglass-split"></i>
                  Cargando departamentos...
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Gestión Cargos para Colaborador -->
  <div class="modal fade" id="modalNuevoCargoColaborador" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title">
            <i class="bi bi-person-gear me-2"></i>
            Gestión de Cargos (Colaborador)
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <!-- Formulario para agregar/editar -->
            <div class="col-md-5">
              <h6 class="text-success mb-3">
                <i class="bi bi-plus-circle me-1"></i>
                <span id="tituloFormCargoColab">Agregar Cargo</span>
              </h6>
              <form id="formNuevoCargoColaborador">
                <input type="hidden" id="editCargoIdColab" name="cargo_id">
                <div class="mb-3">
                  <label class="form-label fw-semibold">Nombre del Cargo: *</label>
                  <input type="text" class="form-control" id="nombreCargoColab" name="nombre_cargo" required maxlength="100">
                  <div class="form-text">Máximo 100 caracteres</div>
                </div>
                <div class="mb-3">
                  <label class="form-label fw-semibold">Descripción:</label>
                  <textarea class="form-control" id="descripcionCargoColab" name="descripcion_cargo" rows="3" placeholder="Descripción opcional del cargo..."></textarea>
                </div>
                <div class="d-grid gap-2">
                  <button type="button" class="btn btn-success" onclick="guardarCargoColaborador()">
                    <i class="bi bi-check-circle me-1"></i>
                    <span id="btnTextCargoColab">Guardar</span>
                  </button>
                  <button type="button" class="btn btn-outline-secondary" onclick="cancelarEdicionCargoColab()" style="display:none;" id="btnCancelarCargoColab">
                    <i class="bi bi-x-circle me-1"></i>Cancelar Edición
                  </button>
                </div>
              </form>
            </div>

            <!-- Lista de cargos existentes -->
            <div class="col-md-7">
              <h6 class="text-secondary mb-3">
                <i class="bi bi-list-ul me-1"></i>
                Cargos Existentes
              </h6>
              <div id="listaCargosColab" class="border rounded p-2" style="max-height: 300px; overflow-y: auto;">
                <div class="text-center text-muted p-3">
                  <i class="bi bi-hourglass-split"></i>
                  Cargando cargos...
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Gestión Departamentos para Evaluador -->
  <div class="modal fade" id="modalNuevoDepartamentoEvaluador" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">
            <i class="bi bi-building-gear me-2"></i>
            Gestión de Departamentos (Evaluador)
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <!-- Formulario para agregar/editar -->
            <div class="col-md-5">
              <h6 class="text-primary mb-3">
                <i class="bi bi-plus-circle me-1"></i>
                <span id="tituloFormDeptEval">Agregar Departamento</span>
              </h6>
              <form id="formNuevoDepartamentoEvaluador">
                <input type="hidden" id="editDeptIdEval" name="departamento_id">
                <div class="mb-3">
                  <label class="form-label fw-semibold">Nombre del Departamento: *</label>
                  <input type="text" class="form-control" id="nombreDeptEval" name="nombre_departamento" required maxlength="100">
                  <div class="form-text">Máximo 100 caracteres</div>
                </div>
                <div class="mb-3">
                  <label class="form-label fw-semibold">Descripción:</label>
                  <textarea class="form-control" id="descripcionDeptEval" name="descripcion_departamento" rows="3" placeholder="Descripción opcional del departamento..."></textarea>
                </div>
                <div class="d-grid gap-2">
                  <button type="button" class="btn btn-primary" onclick="guardarDepartamentoEvaluador()">
                    <i class="bi bi-check-circle me-1"></i>
                    <span id="btnTextDeptEval">Guardar</span>
                  </button>
                  <button type="button" class="btn btn-outline-secondary" onclick="cancelarEdicionDeptEval()" style="display:none;" id="btnCancelarDeptEval">
                    <i class="bi bi-x-circle me-1"></i>Cancelar Edición
                  </button>
                </div>
              </form>
            </div>

            <!-- Lista de departamentos existentes -->
            <div class="col-md-7">
              <h6 class="text-secondary mb-3">
                <i class="bi bi-list-ul me-1"></i>
                Departamentos Existentes
              </h6>
              <div id="listaDepartamentosEval" class="border rounded p-2" style="max-height: 300px; overflow-y: auto;">
                <div class="text-center text-muted p-3">
                  <i class="bi bi-hourglass-split"></i>
                  Cargando departamentos...
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Gestión Cargos para Evaluador -->
  <div class="modal fade" id="modalNuevoCargoEvaluador" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title">
            <i class="bi bi-person-gear me-2"></i>
            Gestión de Cargos (Evaluador)
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <!-- Formulario para agregar/editar -->
            <div class="col-md-5">
              <h6 class="text-success mb-3">
                <i class="bi bi-plus-circle me-1"></i>
                <span id="tituloFormCargoEval">Agregar Cargo</span>
              </h6>
              <form id="formNuevoCargoEvaluador">
                <input type="hidden" id="editCargoIdEval" name="cargo_id">
                <div class="mb-3">
                  <label class="form-label fw-semibold">Nombre del Cargo: *</label>
                  <input type="text" class="form-control" id="nombreCargoEval" name="nombre_cargo" required maxlength="100">
                  <div class="form-text">Máximo 100 caracteres</div>
                </div>
                <div class="mb-3">
                  <label class="form-label fw-semibold">Descripción:</label>
                  <textarea class="form-control" id="descripcionCargoEval" name="descripcion_cargo" rows="3" placeholder="Descripción opcional del cargo..."></textarea>
                </div>
                <div class="d-grid gap-2">
                  <button type="button" class="btn btn-success" onclick="guardarCargoEvaluador()">
                    <i class="bi bi-check-circle me-1"></i>
                    <span id="btnTextCargoEval">Guardar</span>
                  </button>
                  <button type="button" class="btn btn-outline-secondary" onclick="cancelarEdicionCargoEval()" style="display:none;" id="btnCancelarCargoEval">
                    <i class="bi bi-x-circle me-1"></i>Cancelar Edición
                  </button>
                </div>
              </form>
            </div>

            <!-- Lista de cargos existentes -->
            <div class="col-md-7">
              <h6 class="text-secondary mb-3">
                <i class="bi bi-list-ul me-1"></i>
                Cargos Existentes
              </h6>
              <div id="listaCargosEval" class="border rounded p-2" style="max-height: 300px; overflow-y: auto;">
                <div class="text-center text-muted p-3">
                  <i class="bi bi-hourglass-split"></i>
                  Cargando cargos...
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts del index.php incluidos aquí -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../Estilos/app.js"></script>
  <script>
    // Variables y funciones globales
    let mostrarAlerta = function(mensaje, tipo = 'info') {
      const container = document.getElementById('alertContainer');
      const alertId = 'alert-' + Date.now();
      const iconos = {success: 'check-circle', danger: 'exclamation-circle', warning: 'exclamation-triangle', info: 'info-circle'};
      container.insertAdjacentHTML('afterbegin', `
        <div id="${alertId}" class="alert alert-${tipo} alert-dismissible fade show" role="alert">
          <i class="bi bi-${iconos[tipo]} me-2"></i>${mensaje}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      `);
      if (tipo === 'success') setTimeout(() => document.getElementById(alertId)?.remove(), 5000);
    };

    function limpiarFormulario() {
      if (confirm('¿Limpiar formulario?')) {
        const form = document.getElementById('iniciarEvaluacionForm');
        form.reset();
        form.classList.remove('was-validated');
        document.querySelector('input[name="fecha"]').value = new Date().toISOString().split('T')[0];
      }
    }

    document.addEventListener('DOMContentLoaded', function() {
      // Fecha actual
      const currentDate = document.getElementById('currentDate');
      if (currentDate) {
        currentDate.textContent = new Date().toLocaleDateString('es-ES', {year: 'numeric', month: 'long', day: 'numeric'});
      }
      
      const fechaInput = document.querySelector('input[name="fecha"]');
      if (fechaInput && !fechaInput.value) {
        fechaInput.value = new Date().toISOString().split('T')[0];
      }
      
      // Cargar catálogos - igual que index.php
      setTimeout(() => {
        console.log('Iniciando carga de catálogos después de timeout...');
        cargarDepartamentos();
        cargarCargos();
      }, 500);
      
      // Event listeners para cargar listas cuando se abren los modales - COPIADO DE INDEX.PHP
      const modalDeptColab = document.getElementById('modalNuevoDepartamentoColaborador');
      if (modalDeptColab) {
        modalDeptColab.addEventListener('shown.bs.modal', function() {
          console.log('Modal departamentos colaborador abierto, cargando lista...');
          cargarListaDepartamentosColab();
        });
      }
      
      const modalCargoColab = document.getElementById('modalNuevoCargoColaborador');
      if (modalCargoColab) {
        modalCargoColab.addEventListener('shown.bs.modal', function() {
          console.log('Modal cargos colaborador abierto, cargando lista...');
          cargarListaCargosColab();
        });
      }
      
      const modalDeptEval = document.getElementById('modalNuevoDepartamentoEvaluador');
      if (modalDeptEval) {
        modalDeptEval.addEventListener('shown.bs.modal', function() {
          console.log('Modal departamentos evaluador abierto, cargando lista...');
          cargarListaDepartamentosEval();
        });
      }
      
      const modalCargoEval = document.getElementById('modalNuevoCargoEvaluador');
      if (modalCargoEval) {
        modalCargoEval.addEventListener('shown.bs.modal', function() {
          console.log('Modal cargos evaluador abierto, cargando lista...');
          cargarListaCargosEval();
        });
      }
    });

    // Envío del formulario con backend integrado
    document.getElementById('iniciarEvaluacionForm').addEventListener('submit', async function(e) {
      e.preventDefault();
      
      if (!this.checkValidity()) {
        e.stopPropagation();
        this.classList.add('was-validated');
        mostrarAlerta('Complete todos los campos requeridos', 'danger');
        return;
      }
      
      document.getElementById('loadingOverlay').classList.remove('d-none');
      
      try {
        const formData = new FormData(this);
        formData.append('action', 'iniciar_evaluacion');
        
        console.log('Enviando formulario...');
        const response = await fetch('iniciar_evaluacion.php', {method: 'POST', body: formData});
        
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers.get('content-type'));
        
        // Obtener texto primero para debug
        const text = await response.text();
        console.log('Response text (primeros 500 chars):', text.substring(0, 500));
        
        let result;
        try {
          result = JSON.parse(text);
        } catch (parseError) {
          console.error('Error parsing JSON:', parseError);
          console.error('Full response:', text);
          throw new Error('El servidor no devolvió JSON válido. Revisa la consola para más detalles.');
        }
        
        if (result.exito) {
          mostrarAlerta(result.mensaje, 'success');
          setTimeout(() => {
            this.reset();
            this.classList.remove('was-validated');
            document.querySelector('input[name="fecha"]').value = new Date().toISOString().split('T')[0];
          }, 2000);
        } else {
          mostrarAlerta(result.mensaje, 'danger');
        }
      } catch (error) {
        mostrarAlerta('Error de conexión: ' + error.message, 'danger');
      } finally {
        document.getElementById('loadingOverlay').classList.add('d-none');
      }
    });

    // FUNCIONES COPIADAS EXACTAS DEL INDEX.PHP
    async function cargarDepartamentos() {
      console.log('=== CARGAR DEPARTAMENTOS INICIADO ===');
      
      const selectColaborador = document.getElementById('selectDepartamento');
      const selectEvaluador = document.getElementById('selectDepartamentoEvaluador');
      
      console.log('Elementos encontrados:', {
        selectColaborador: selectColaborador,
        selectEvaluador: selectEvaluador,
        colaboradorExiste: !!selectColaborador,
        evaluadorExiste: !!selectEvaluador
      });
      
      if (!selectColaborador) {
        console.error('ERROR: No se encontró selectDepartamento');
        return;
      }
      if (!selectEvaluador) {
        console.error('ERROR: No se encontró selectDepartamentoEvaluador'); 
        return;
      }
      
      try {
        console.log('Haciendo fetch a ../catalogos.php...');
        const response = await fetch('../catalogos.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'action=obtener_departamentos'
        });
        
        console.log('Response status:', response.status, response.statusText);
        console.log('Response ok:', response.ok);
        
        const text = await response.text();
        console.log('Raw response text:', text);
        
        const data = JSON.parse(text);
        console.log('Parsed JSON data:', data);
        
        if (data.success && data.departamentos && Array.isArray(data.departamentos)) {
          console.log('✓ Datos válidos recibidos, procesando', data.departamentos.length, 'departamentos');
          
          const optionsHTML = '<option value="">Seleccione un departamento</option>' +
            data.departamentos.map(dept => 
              `<option value="${dept.id_departamento}">${dept.nom_dep}</option>`
            ).join('');
          
          console.log('HTML generado:', optionsHTML.substring(0, 200) + '...');
          
          console.log('Actualizando selectColaborador...');
          selectColaborador.innerHTML = optionsHTML;
          console.log('selectColaborador actualizado, opciones:', selectColaborador.options.length);
          
          console.log('Actualizando selectEvaluador...');
          selectEvaluador.innerHTML = optionsHTML;
          console.log('selectEvaluador actualizado, opciones:', selectEvaluador.options.length);
          
          console.log('✓ Proceso completado');
        } else {
          console.error('✗ Datos inválidos:', data);
          const errorHTML = '<option value="">Error: ' + (data.mensaje || 'Datos inválidos') + '</option>';
          selectColaborador.innerHTML = errorHTML;
          selectEvaluador.innerHTML = errorHTML;
        }
      } catch (error) {
        console.error('Error fetch:', error);
        const errorHTML = '<option value="">Error de conexión: ' + error.message + '</option>';
        selectColaborador.innerHTML = errorHTML;
        selectEvaluador.innerHTML = errorHTML;
      }
    }
    
    async function cargarCargos() {
      console.log('=== Cargando cargos ===');
      
      const selectColaborador = document.getElementById('selectCargo');
      const selectEvaluador = document.getElementById('selectCargoEvaluador');
      
      console.log('Selectores encontrados:', {
        colaborador: !!selectColaborador,
        evaluador: !!selectEvaluador
      });
      
      try {
        console.log('Haciendo fetch de cargos a ../catalogos.php...');
        const response = await fetch('../catalogos.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'action=obtener_cargos'
        });
        
        console.log('Response status cargos:', response.status);
        const data = await response.json();
        console.log('Datos cargos recibidos:', data);
        
        if (data.success && data.cargos && Array.isArray(data.cargos)) {
          console.log('✓ Datos válidos de cargos recibidos, procesando', data.cargos.length, 'cargos');
          
          const optionsHTML = '<option value="">Seleccione un cargo</option>' +
            data.cargos.map(cargo => 
              `<option value="${cargo.id_cargo}">${cargo.nom_car}</option>`
            ).join('');
          
          selectColaborador.innerHTML = optionsHTML;
          selectEvaluador.innerHTML = optionsHTML;
          
          console.log('✓ Selectores de cargos actualizados exitosamente');
        } else {
          console.error('✗ Datos de cargos inválidos:', data);
          const errorHTML = '<option value="">Error: ' + (data.mensaje || 'Datos inválidos') + '</option>';
          selectColaborador.innerHTML = errorHTML;
          selectEvaluador.innerHTML = errorHTML;
        }
      } catch (error) {
        console.error('Error fetch cargos:', error);
        const errorHTML = '<option value="">Error de conexión: ' + error.message + '</option>';
        selectColaborador.innerHTML = errorHTML;
        selectEvaluador.innerHTML = errorHTML;
      }
    }
    
    // =====================================================
    // FUNCIONES PARA DEPARTAMENTOS DEL COLABORADOR
    // =====================================================
    
    async function guardarDepartamentoColaborador() {
      const form = document.getElementById('formNuevoDepartamentoColaborador');
      const formData = new FormData(form);
      
      const nombre = formData.get('nombre_departamento').trim();
      const descripcion = formData.get('descripcion_departamento').trim();
      const id = document.getElementById('editDeptIdColab').value;
      
      if (!nombre) {
        mostrarAlerta('El nombre del departamento es requerido', 'danger');
        return;
      }
      
      const esEdicion = id && id !== '';
      const accion = esEdicion ? 'editar_departamento' : 'agregar_departamento';
      const requestBody = esEdicion ? 
        { accion, id: parseInt(id), nombre, descripcion } :
        { accion, nombre, descripcion };
      
      try {
        const response = await fetch('../catalogos.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(requestBody)
        });
        
        const data = await response.json();
        
        if (data.exito) {
          form.reset();
          cancelarEdicionDeptColab();
          await cargarListaDepartamentosColab();
          await cargarDepartamentos();
          
          if (!esEdicion) {
            document.getElementById('selectDepartamento').value = data.nombre;
          }
          
          mostrarAlerta(data.mensaje, 'success');
        } else {
          mostrarAlerta(data.mensaje, 'danger');
        }
      } catch (error) {
        console.error('Error:', error);
        mostrarAlerta('Error al guardar departamento', 'danger');
      }
    }

    function cancelarEdicionDeptColab() {
      document.getElementById('editDeptIdColab').value = '';
      document.getElementById('tituloFormDeptColab').textContent = 'Agregar Departamento';
      document.getElementById('btnTextDeptColab').textContent = 'Guardar';
      document.getElementById('btnCancelarDeptColab').style.display = 'none';
      document.getElementById('formNuevoDepartamentoColaborador').reset();
    }

    function editarDepartamentoColab(id, nombre, descripcion) {
      document.getElementById('editDeptIdColab').value = id;
      document.getElementById('nombreDeptColab').value = nombre;
      document.getElementById('descripcionDeptColab').value = descripcion || '';
      document.getElementById('tituloFormDeptColab').textContent = 'Editar Departamento';
      document.getElementById('btnTextDeptColab').textContent = 'Actualizar';
      document.getElementById('btnCancelarDeptColab').style.display = 'block';
    }

    async function eliminarDepartamentoColab(id, nombre) {
      if (!confirm(`¿Está seguro de eliminar el departamento "${nombre}"?`)) {
        return;
      }
      
      try {
        const response = await fetch('../catalogos.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ accion: 'eliminar_departamento', id: parseInt(id) })
        });
        
        const data = await response.json();
        
        if (data.exito) {
          await cargarListaDepartamentosColab();
          await cargarDepartamentos();
          mostrarAlerta(data.mensaje, 'success');
        } else {
          mostrarAlerta(data.mensaje, 'danger');
        }
      } catch (error) {
        console.error('Error:', error);
        mostrarAlerta('Error al eliminar departamento', 'danger');
      }
    }

    async function cargarListaDepartamentosColab() {
      const container = document.getElementById('listaDepartamentosColab');
      
      try {
        const response = await fetch('../catalogos.php?tipo=departamentos');
        const data = await response.json();
        
        if (data.exito) {
          container.innerHTML = data.departamentos.map(dept => `
            <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
              <div>
                <div class="fw-semibold">${dept.nom_dep}</div>
                ${dept.descri_dep ? `<small class="text-muted">${dept.descri_dep}</small>` : ''}
              </div>
              <div class="btn-group btn-group-sm">
                <button class="btn btn-outline-primary btn-sm" onclick="editarDepartamentoColab(${dept.id_dep}, '${dept.nom_dep.replace(/'/g, "\\'")}', '${(dept.descri_dep || '').replace(/'/g, "\\'")}')">
                  <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-outline-danger btn-sm" onclick="eliminarDepartamentoColab(${dept.id_dep}, '${dept.nom_dep.replace(/'/g, "\\'")}')">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </div>
          `).join('');
        } else {
          container.innerHTML = '<div class="text-center text-muted p-3">Error al cargar departamentos</div>';
        }
      } catch (error) {
        container.innerHTML = '<div class="text-center text-muted p-3">Error de conexión</div>';
      }
    }

    // =====================================================
    // FUNCIONES PARA CARGOS DEL COLABORADOR
    // =====================================================
    
    async function guardarCargoColaborador() {
      const form = document.getElementById('formNuevoCargoColaborador');
      const formData = new FormData(form);
      
      const nombre = formData.get('nombre_cargo').trim();
      const descripcion = formData.get('descripcion_cargo').trim();
      const id = document.getElementById('editCargoIdColab').value;
      
      if (!nombre) {
        mostrarAlerta('El nombre del cargo es requerido', 'danger');
        return;
      }
      
      const esEdicion = id && id !== '';
      const accion = esEdicion ? 'editar_cargo' : 'agregar_cargo';
      const requestBody = esEdicion ? 
        { accion, id: parseInt(id), nombre, descripcion } :
        { accion, nombre, descripcion };
      
      try {
        const response = await fetch('../catalogos.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(requestBody)
        });
        
        const data = await response.json();
        
        if (data.exito) {
          form.reset();
          cancelarEdicionCargoColab();
          await cargarListaCargosColab();
          await cargarCargos();
          
          if (!esEdicion) {
            document.getElementById('selectCargo').value = data.nombre;
          }
          
          mostrarAlerta(data.mensaje, 'success');
        } else {
          mostrarAlerta(data.mensaje, 'danger');
        }
      } catch (error) {
        console.error('Error:', error);
        mostrarAlerta('Error al guardar cargo', 'danger');
      }
    }

    function cancelarEdicionCargoColab() {
      document.getElementById('editCargoIdColab').value = '';
      document.getElementById('tituloFormCargoColab').textContent = 'Agregar Cargo';
      document.getElementById('btnTextCargoColab').textContent = 'Guardar';
      document.getElementById('btnCancelarCargoColab').style.display = 'none';
      document.getElementById('formNuevoCargoColaborador').reset();
    }

    function editarCargoColab(id, nombre, descripcion) {
      document.getElementById('editCargoIdColab').value = id;
      document.getElementById('nombreCargoColab').value = nombre;
      document.getElementById('descripcionCargoColab').value = descripcion || '';
      document.getElementById('tituloFormCargoColab').textContent = 'Editar Cargo';
      document.getElementById('btnTextCargoColab').textContent = 'Actualizar';
      document.getElementById('btnCancelarCargoColab').style.display = 'block';
    }

    async function eliminarCargoColab(id, nombre) {
      if (!confirm(`¿Está seguro de eliminar el cargo "${nombre}"?`)) {
        return;
      }
      
      try {
        const response = await fetch('../catalogos.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ accion: 'eliminar_cargo', id: parseInt(id) })
        });
        
        const data = await response.json();
        
        if (data.exito) {
          await cargarListaCargosColab();
          await cargarCargos();
          mostrarAlerta(data.mensaje, 'success');
        } else {
          mostrarAlerta(data.mensaje, 'danger');
        }
      } catch (error) {
        console.error('Error:', error);
        mostrarAlerta('Error al eliminar cargo', 'danger');
      }
    }

    async function cargarListaCargosColab() {
      const container = document.getElementById('listaCargosColab');
      
      try {
        const response = await fetch('../catalogos.php?tipo=cargos');
        const data = await response.json();
        
        if (data.exito) {
          container.innerHTML = data.cargos.map(cargo => `
            <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
              <div>
                <div class="fw-semibold">${cargo.nom_car}</div>
                ${cargo.descri_car ? `<small class="text-muted">${cargo.descri_car}</small>` : ''}
              </div>
              <div class="btn-group btn-group-sm">
                <button class="btn btn-outline-primary btn-sm" onclick="editarCargoColab(${cargo.id_cargo}, '${cargo.nom_car.replace(/'/g, "\\'")}', '${(cargo.descri_car || '').replace(/'/g, "\\'")}')">
                  <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-outline-danger btn-sm" onclick="eliminarCargoColab(${cargo.id_cargo}, '${cargo.nom_car.replace(/'/g, "\\'")}')">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </div>
          `).join('');
        } else {
          container.innerHTML = '<div class="text-center text-muted p-3">Error al cargar cargos</div>';
        }
      } catch (error) {
        container.innerHTML = '<div class="text-center text-muted p-3">Error de conexión</div>';
      }
    }

    // =====================================================
    // FUNCIONES PARA DEPARTAMENTOS DEL EVALUADOR
    // =====================================================
    
    async function guardarDepartamentoEvaluador() {
      const form = document.getElementById('formNuevoDepartamentoEvaluador');
      const formData = new FormData(form);
      
      const nombre = formData.get('nombre_departamento').trim();
      const descripcion = formData.get('descripcion_departamento').trim();
      const id = document.getElementById('editDeptIdEval').value;
      
      if (!nombre) {
        mostrarAlerta('El nombre del departamento es requerido', 'danger');
        return;
      }
      
      const esEdicion = id && id !== '';
      const accion = esEdicion ? 'editar_departamento' : 'agregar_departamento';
      const requestBody = esEdicion ? 
        { accion, id: parseInt(id), nombre, descripcion } :
        { accion, nombre, descripcion };
      
      try {
        const response = await fetch('../catalogos.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(requestBody)
        });
        
        const data = await response.json();
        
        if (data.exito) {
          form.reset();
          cancelarEdicionDeptEval();
          await cargarListaDepartamentosEval();
          await cargarDepartamentos();
          
          if (!esEdicion) {
            document.getElementById('selectDepartamentoEvaluador').value = data.nombre;
          }
          
          mostrarAlerta(data.mensaje, 'success');
        } else {
          mostrarAlerta(data.mensaje, 'danger');
        }
      } catch (error) {
        console.error('Error:', error);
        mostrarAlerta('Error al guardar departamento', 'danger');
      }
    }

    function cancelarEdicionDeptEval() {
      document.getElementById('editDeptIdEval').value = '';
      document.getElementById('tituloFormDeptEval').textContent = 'Agregar Departamento';
      document.getElementById('btnTextDeptEval').textContent = 'Guardar';
      document.getElementById('btnCancelarDeptEval').style.display = 'none';
      document.getElementById('formNuevoDepartamentoEvaluador').reset();
    }

    function editarDepartamentoEval(id, nombre, descripcion) {
      document.getElementById('editDeptIdEval').value = id;
      document.getElementById('nombreDeptEval').value = nombre;
      document.getElementById('descripcionDeptEval').value = descripcion || '';
      document.getElementById('tituloFormDeptEval').textContent = 'Editar Departamento';
      document.getElementById('btnTextDeptEval').textContent = 'Actualizar';
      document.getElementById('btnCancelarDeptEval').style.display = 'block';
    }

    async function eliminarDepartamentoEval(id, nombre) {
      if (!confirm(`¿Está seguro de eliminar el departamento "${nombre}"?`)) {
        return;
      }
      
      try {
        const response = await fetch('../catalogos.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ accion: 'eliminar_departamento', id: parseInt(id) })
        });
        
        const data = await response.json();
        
        if (data.exito) {
          await cargarListaDepartamentosEval();
          await cargarDepartamentos();
          mostrarAlerta(data.mensaje, 'success');
        } else {
          mostrarAlerta(data.mensaje, 'danger');
        }
      } catch (error) {
        console.error('Error:', error);
        mostrarAlerta('Error al eliminar departamento', 'danger');
      }
    }

    async function cargarListaDepartamentosEval() {
      const container = document.getElementById('listaDepartamentosEval');
      
      try {
        const response = await fetch('../catalogos.php?tipo=departamentos');
        const data = await response.json();
        
        if (data.exito) {
          container.innerHTML = data.departamentos.map(dept => `
            <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
              <div>
                <div class="fw-semibold">${dept.nom_dep}</div>
                ${dept.descri_dep ? `<small class="text-muted">${dept.descri_dep}</small>` : ''}
              </div>
              <div class="btn-group btn-group-sm">
                <button class="btn btn-outline-primary btn-sm" onclick="editarDepartamentoEval(${dept.id_dep}, '${dept.nom_dep.replace(/'/g, "\\'")}', '${(dept.descri_dep || '').replace(/'/g, "\\'")}')">
                  <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-outline-danger btn-sm" onclick="eliminarDepartamentoEval(${dept.id_dep}, '${dept.nom_dep.replace(/'/g, "\\'")}')">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </div>
          `).join('');
        } else {
          container.innerHTML = '<div class="text-center text-muted p-3">Error al cargar departamentos</div>';
        }
      } catch (error) {
        container.innerHTML = '<div class="text-center text-muted p-3">Error de conexión</div>';
      }
    }

    // =====================================================
    // FUNCIONES PARA CARGOS DEL EVALUADOR
    // =====================================================
    
    async function guardarCargoEvaluador() {
      const form = document.getElementById('formNuevoCargoEvaluador');
      const formData = new FormData(form);
      
      const nombre = formData.get('nombre_cargo').trim();
      const descripcion = formData.get('descripcion_cargo').trim();
      const id = document.getElementById('editCargoIdEval').value;
      
      if (!nombre) {
        mostrarAlerta('El nombre del cargo es requerido', 'danger');
        return;
      }
      
      const esEdicion = id && id !== '';
      const accion = esEdicion ? 'editar_cargo' : 'agregar_cargo';
      const requestBody = esEdicion ? 
        { accion, id: parseInt(id), nombre, descripcion } :
        { accion, nombre, descripcion };
      
      try {
        const response = await fetch('../catalogos.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(requestBody)
        });
        
        const data = await response.json();
        
        if (data.exito) {
          form.reset();
          cancelarEdicionCargoEval();
          await cargarListaCargosEval();
          await cargarCargos();
          
          if (!esEdicion) {
            document.getElementById('selectCargoEvaluador').value = data.nombre;
          }
          
          mostrarAlerta(data.mensaje, 'success');
        } else {
          mostrarAlerta(data.mensaje, 'danger');
        }
      } catch (error) {
        console.error('Error:', error);
        mostrarAlerta('Error al guardar cargo', 'danger');
      }
    }

    function cancelarEdicionCargoEval() {
      document.getElementById('editCargoIdEval').value = '';
      document.getElementById('tituloFormCargoEval').textContent = 'Agregar Cargo';
      document.getElementById('btnTextCargoEval').textContent = 'Guardar';
      document.getElementById('btnCancelarCargoEval').style.display = 'none';
      document.getElementById('formNuevoCargoEvaluador').reset();
    }

    function editarCargoEval(id, nombre, descripcion) {
      document.getElementById('editCargoIdEval').value = id;
      document.getElementById('nombreCargoEval').value = nombre;
      document.getElementById('descripcionCargoEval').value = descripcion || '';
      document.getElementById('tituloFormCargoEval').textContent = 'Editar Cargo';
      document.getElementById('btnTextCargoEval').textContent = 'Actualizar';
      document.getElementById('btnCancelarCargoEval').style.display = 'block';
    }

    async function eliminarCargoEval(id, nombre) {
      if (!confirm(`¿Está seguro de eliminar el cargo "${nombre}"?`)) {
        return;
      }
      
      try {
        const response = await fetch('../catalogos.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ accion: 'eliminar_cargo', id: parseInt(id) })
        });
        
        const data = await response.json();
        
        if (data.exito) {
          await cargarListaCargosEval();
          await cargarCargos();
          mostrarAlerta(data.mensaje, 'success');
        } else {
          mostrarAlerta(data.mensaje, 'danger');
        }
      } catch (error) {
        console.error('Error:', error);
        mostrarAlerta('Error al eliminar cargo', 'danger');
      }
    }

    async function cargarListaCargosEval() {
      const container = document.getElementById('listaCargosEval');
      
      try {
        const response = await fetch('../catalogos.php?tipo=cargos');
        const data = await response.json();
        
        if (data.exito) {
          container.innerHTML = data.cargos.map(cargo => `
            <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
              <div>
                <div class="fw-semibold">${cargo.nom_car}</div>
                ${cargo.descri_car ? `<small class="text-muted">${cargo.descri_car}</small>` : ''}
              </div>
              <div class="btn-group btn-group-sm">
                <button class="btn btn-outline-primary btn-sm" onclick="editarCargoEval(${cargo.id_cargo}, '${cargo.nom_car.replace(/'/g, "\\'")}', '${(cargo.descri_car || '').replace(/'/g, "\\'")}')">
                  <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-outline-danger btn-sm" onclick="eliminarCargoEval(${cargo.id_cargo}, '${cargo.nom_car.replace(/'/g, "\\'")}')">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </div>
          `).join('');
        } else {
          container.innerHTML = '<div class="text-center text-muted p-3">Error al cargar cargos</div>';
        }
      } catch (error) {
        container.innerHTML = '<div class="text-center text-muted p-3">Error de conexión</div>';
      }
    }
  </script>
</body>
</html>
