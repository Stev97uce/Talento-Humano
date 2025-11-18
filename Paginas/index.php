<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Evaluación de Desempeño - KMSOLUTIONS S.A.</title>
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
  
  <!-- Custom CSS -->
  <link rel="stylesheet" href="../Estilos/styles.css">
</head>
<body>
  
  <!-- Loading Overlay -->
  <div id="loadingOverlay" class="loading-overlay d-none">
    <div class="loading-content">
      <div class="spinner-border text-danger" role="status">
        <span class="visually-hidden">Cargando...</span>
      </div>
      <p class="mt-3">Procesando evaluación...</p>
    </div>
  </div>

  <!-- Success/Error Messages -->
  <div id="alertContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 1060;"></div>

  <!-- Header -->
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
            <a href="index.php" class="btn btn-outline-light btn-sm me-2 active">
              <i class="bi bi-plus-circle me-1"></i>Nueva Evaluación
            </a>
            <a href="consultas.php" class="btn btn-outline-light btn-sm">
              <i class="bi bi-search me-1"></i>Consultar Evaluaciones
            </a>
          </nav>
          <div class="small">Fecha: <span id="currentDate"></span></div>
          <div class="small text-muted">Status: <span id="apiStatus" class="badge bg-secondary">Listo</span></div>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Container -->
  <div class="container-fluid py-4" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: calc(100vh - 140px);">
    
    <!-- Form Container -->
    <div class="row justify-content-center">
      <div class="col-12 col-xl-10">
        
        <form id="evaluationForm" method="POST" action="../guardar.php" class="needs-validation" novalidate>
          
          <!-- Header Card -->
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
                  <div class="small">Página 1 de 1</div>
                </div>
              </div>
            </div>
            
            <div class="card-body bg-light">
              <!-- Title and Date -->
              <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0 text-danger fw-bold">
                  <i class="bi bi-clipboard-data me-2"></i>
                  EVALUACIÓN DE DESEMPEÑO
                </h4>
                <div class="text-end">
                  <label class="form-label fw-bold mb-1">FECHA: *</label>
                  <input type="date" class="form-control form-control-sm" name="fecha" required />
                  <div class="invalid-feedback">Por favor seleccione una fecha.</div>
                </div>
              </div>
            </div>
          </div>

          
          <!-- Colaborador / Evaluador Section -->
          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <div class="card border-danger h-100">
                <div class="card-header bg-light border-danger">
                  <h6 class="mb-0 text-danger fw-bold">
                    <i class="bi bi-person-fill me-2"></i>
                    COLABORADOR (a)
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
                    <i class="bi bi-person-badge-fill me-2"></i>
                    EVALUADOR (a)
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
                      <label class="form-label fw-semibold">Email (notificaciones):</label>
                      <input type="email" class="form-control" name="evaluador_email" placeholder="ejemplo@kmsolutions.com" />
                      <div class="invalid-feedback">Por favor ingrese un email válido.</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Periodo Information -->
          <div class="card mb-4 shadow">
            <div class="card-header bg-info text-white">
              <h6 class="mb-0 fw-bold">
                <i class="bi bi-calendar-range me-2"></i>
                INFORMACIÓN DEL PERÍODO
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
                  <div class="invalid-feedback d-block" id="tipo_evaluacion_error" style="display: none !important;">
                    Por favor seleccione un tipo de evaluación.
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Instructions -->
          <div class="card mb-4 border-warning">
            <div class="card-header bg-warning">
              <h6 class="mb-0 fw-bold">
                <i class="bi bi-info-circle me-2"></i>
                INSTRUCCIONES
              </h6>
            </div>
            <div class="card-body">
              <p class="mb-2">Evalúe al empleado en el cargo que desempeña actualmente. El cuidado y objetividad con que efectúe la evaluación determinará la utilidad de esta para usted, el empleado y la empresa.</p>
              <div class="row">
                <div class="col-md-4">
                  <ol class="small mb-0">
                    <li>Lea detenidamente la descripción de cada área.</li>
                    <li>Determine el grado que refleje el desempeño.</li>
                    <li>Señale el puntaje en la casilla correspondiente.</li>
                  </ol>
                </div>
                <div class="col-md-8">
                  <div class="row text-center small">
                    <div class="col"><span class="badge bg-danger">D = 1-5</span> Deficiente</div>
                    <div class="col"><span class="badge bg-warning">R = 6-7</span> Regular</div>
                    <div class="col"><span class="badge bg-info">B = 8</span> Bueno</div>
                    <div class="col"><span class="badge bg-primary">MB = 9</span> Muy Bueno</div>
                    <div class="col"><span class="badge bg-success">E = 10</span> Excelente</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Evaluation Matrix -->
          <div class="card mb-4 shadow">
            <div class="card-header bg-dark text-white">
              <h6 class="mb-0 fw-bold">
                <i class="bi bi-table me-2"></i>
                MATRIZ DE EVALUACIÓN
              </h6>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover mb-0">
                  <thead class="table-dark">
                    <tr class="text-center small">
                      <th style="width:30%" class="text-start">ÁREAS DE EVALUACIÓN</th>
                      <th style="width:5%">1</th>
                      <th style="width:5%">2</th>
                      <th style="width:5%">3</th>
                      <th style="width:5%">4</th>
                      <th style="width:5%">5</th>
                      <th style="width:5%">6</th>
                      <th style="width:5%">7</th>
                      <th style="width:5%">8</th>
                      <th style="width:5%">9</th>
                      <th style="width:5%">10</th>
                      <th style="width:15%">Subtotal<br><small>(Puntos / %)</small></th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- Conducta Laboral Section -->
                    <tr class="table-danger">
                      <td colspan="12" class="fw-bold text-center">
                        <i class="bi bi-person-hearts me-2"></i>
                        CONDUCTA LABORAL
                      </td>
                    </tr>
                    <tr>
                      <td class="fw-semibold">
                        Rapidez
                        <br><small class="text-muted">Rapidez al ejecutar las tareas asignadas.</small>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c1" value="1" class="btn-check" id="c1_1">
                        <label class="btn btn-outline-primary btn-sm" for="c1_1">1</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c1" value="2" class="btn-check" id="c1_2">
                        <label class="btn btn-outline-primary btn-sm" for="c1_2">2</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c1" value="3" class="btn-check" id="c1_3">
                        <label class="btn btn-outline-primary btn-sm" for="c1_3">3</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c1" value="4" class="btn-check" id="c1_4">
                        <label class="btn btn-outline-primary btn-sm" for="c1_4">4</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c1" value="5" class="btn-check" id="c1_5">
                        <label class="btn btn-outline-primary btn-sm" for="c1_5">5</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c1" value="6" class="btn-check" id="c1_6">
                        <label class="btn btn-outline-secondary btn-sm" for="c1_6">6</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c1" value="7" class="btn-check" id="c1_7">
                        <label class="btn btn-outline-info btn-sm" for="c1_7">7</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c1" value="8" class="btn-check" id="c1_8">
                        <label class="btn btn-outline-success btn-sm" for="c1_8">8</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c1" value="9" class="btn-check" id="c1_9">
                        <label class="btn btn-outline-warning btn-sm" for="c1_9">9</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c1" value="10" class="btn-check" id="c1_10">
                        <label class="btn btn-outline-danger btn-sm" for="c1_10">10</label>
                      </td>
                      <td class="text-center">
                        <span class="badge score-badge small" name="level_c1"></span>
                      </td>
                    </tr>
                    <tr>
                      <td class="fw-semibold">
                        Asistencia y puntualidad
                        <br><small class="text-muted">Cumple con los horarios establecidos y tiene una asistencia regular.</small>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c2" value="1" class="btn-check" id="c2_1">
                        <label class="btn btn-outline-primary btn-sm" for="c2_1">1</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c2" value="2" class="btn-check" id="c2_2">
                        <label class="btn btn-outline-primary btn-sm" for="c2_2">2</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c2" value="3" class="btn-check" id="c2_3">
                        <label class="btn btn-outline-primary btn-sm" for="c2_3">3</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c2" value="4" class="btn-check" id="c2_4">
                        <label class="btn btn-outline-primary btn-sm" for="c2_4">4</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c2" value="5" class="btn-check" id="c2_5">
                        <label class="btn btn-outline-primary btn-sm" for="c2_5">5</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c2" value="6" class="btn-check" id="c2_6">
                        <label class="btn btn-outline-secondary btn-sm" for="c2_6">6</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c2" value="7" class="btn-check" id="c2_7">
                        <label class="btn btn-outline-info btn-sm" for="c2_7">7</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c2" value="8" class="btn-check" id="c2_8">
                        <label class="btn btn-outline-success btn-sm" for="c2_8">8</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c2" value="9" class="btn-check" id="c2_9">
                        <label class="btn btn-outline-warning btn-sm" for="c2_9">9</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c2" value="10" class="btn-check" id="c2_10">
                        <label class="btn btn-outline-danger btn-sm" for="c2_10">10</label>
                      </td>
                      <td class="text-center">
                        <span class="badge score-badge small" name="level_c2"></span>
                      </td>
                    </tr>
                    <tr>
                      <td class="fw-semibold">
                        Cumplimiento de normas
                        <br><small class="text-muted">Respeta y acata las normas, políticas y procedimientos de la organización.</small>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c3" value="1" class="btn-check" id="c3_1">
                        <label class="btn btn-outline-primary btn-sm" for="c3_1">1</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c3" value="2" class="btn-check" id="c3_2">
                        <label class="btn btn-outline-primary btn-sm" for="c3_2">2</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c3" value="3" class="btn-check" id="c3_3">
                        <label class="btn btn-outline-primary btn-sm" for="c3_3">3</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c3" value="4" class="btn-check" id="c3_4">
                        <label class="btn btn-outline-primary btn-sm" for="c3_4">4</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c3" value="5" class="btn-check" id="c3_5">
                        <label class="btn btn-outline-primary btn-sm" for="c3_5">5</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c3" value="6" class="btn-check" id="c3_6">
                        <label class="btn btn-outline-secondary btn-sm" for="c3_6">6</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c3" value="7" class="btn-check" id="c3_7">
                        <label class="btn btn-outline-info btn-sm" for="c3_7">7</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c3" value="8" class="btn-check" id="c3_8">
                        <label class="btn btn-outline-success btn-sm" for="c3_8">8</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c3" value="9" class="btn-check" id="c3_9">
                        <label class="btn btn-outline-warning btn-sm" for="c3_9">9</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c3" value="10" class="btn-check" id="c3_10">
                        <label class="btn btn-outline-danger btn-sm" for="c3_10">10</label>
                      </td>
                      <td class="text-center">
                        <span class="badge score-badge small" name="level_c3"></span>
                      </td>
                    </tr>
                    <tr>
                      <td class="fw-semibold">
                        Responsabilidad
                        <br><small class="text-muted">Asume las consecuencias de sus actos y decisiones.</small>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c4" value="1" class="btn-check" id="c4_1">
                        <label class="btn btn-outline-primary btn-sm" for="c4_1">1</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c4" value="2" class="btn-check" id="c4_2">
                        <label class="btn btn-outline-primary btn-sm" for="c4_2">2</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c4" value="3" class="btn-check" id="c4_3">
                        <label class="btn btn-outline-primary btn-sm" for="c4_3">3</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c4" value="4" class="btn-check" id="c4_4">
                        <label class="btn btn-outline-primary btn-sm" for="c4_4">4</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c4" value="5" class="btn-check" id="c4_5">
                        <label class="btn btn-outline-primary btn-sm" for="c4_5">5</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c4" value="6" class="btn-check" id="c4_6">
                        <label class="btn btn-outline-secondary btn-sm" for="c4_6">6</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c4" value="7" class="btn-check" id="c4_7">
                        <label class="btn btn-outline-info btn-sm" for="c4_7">7</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c4" value="8" class="btn-check" id="c4_8">
                        <label class="btn btn-outline-success btn-sm" for="c4_8">8</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c4" value="9" class="btn-check" id="c4_9">
                        <label class="btn btn-outline-warning btn-sm" for="c4_9">9</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c4" value="10" class="btn-check" id="c4_10">
                        <label class="btn btn-outline-danger btn-sm" for="c4_10">10</label>
                      </td>
                      <td class="text-center">
                        <span class="badge score-badge small" name="level_c4"></span>
                      </td>
                    </tr>
                    <tr>
                      <td class="fw-semibold">
                        Honestidad
                        <br><small class="text-muted">Es transparente y actúa con integridad en todas sus acciones.</small>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c5" value="1" class="btn-check" id="c5_1">
                        <label class="btn btn-outline-primary btn-sm" for="c5_1">1</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c5" value="2" class="btn-check" id="c5_2">
                        <label class="btn btn-outline-primary btn-sm" for="c5_2">2</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c5" value="3" class="btn-check" id="c5_3">
                        <label class="btn btn-outline-primary btn-sm" for="c5_3">3</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c5" value="4" class="btn-check" id="c5_4">
                        <label class="btn btn-outline-primary btn-sm" for="c5_4">4</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c5" value="5" class="btn-check" id="c5_5">
                        <label class="btn btn-outline-primary btn-sm" for="c5_5">5</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c5" value="6" class="btn-check" id="c5_6">
                        <label class="btn btn-outline-secondary btn-sm" for="c5_6">6</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c5" value="7" class="btn-check" id="c5_7">
                        <label class="btn btn-outline-info btn-sm" for="c5_7">7</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c5" value="8" class="btn-check" id="c5_8">
                        <label class="btn btn-outline-success btn-sm" for="c5_8">8</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c5" value="9" class="btn-check" id="c5_9">
                        <label class="btn btn-outline-warning btn-sm" for="c5_9">9</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="c5" value="10" class="btn-check" id="c5_10">
                        <label class="btn btn-outline-danger btn-sm" for="c5_10">10</label>
                      </td>
                      <td class="text-center">
                        <span class="badge score-badge small" name="level_c5"></span>
                      </td>
                    </tr>
                    <!-- Subtotal row for Conducta Laboral -->
                    <tr class="table-info fw-bold">
                      <td class="text-center">SUBTOTAL CONDUCTA LABORAL</td>
                      <td class="text-center" colspan="10">
                        <div class="d-flex justify-content-center align-items-center gap-3">
                          <div>
                            <span class="text-muted small">Puntos:</span>
                            <span class="badge bg-primary area-subtotal" id="subtotal_conducta">0</span>
                            <span class="text-muted small">/ 50</span>
                          </div>
                          <div>
                            <span class="text-muted small">Porcentaje:</span>
                            <span class="badge bg-success area-percent" id="percent_conducta">0%</span>
                          </div>
                        </div>
                      </td>
                      <td class="text-center">
                        <i class="bi bi-calculator text-muted"></i>
                      </td>
                    </tr>

                    <!-- Productividad Section -->
                    <tr class="table-primary">
                      <td colspan="12" class="fw-bold text-center">
                        <i class="bi bi-graph-up-arrow me-2"></i>
                        PRODUCTIVIDAD
                      </td>
                    </tr>
                    <tr>
                      <td class="fw-semibold">
                        Cumplimiento de metas
                        <br><small class="text-muted">Alcanza los objetivos y metas establecidas para su cargo.</small>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p1" value="1" class="btn-check" id="p1_1">
                        <label class="btn btn-outline-primary btn-sm" for="p1_1">1</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p1" value="2" class="btn-check" id="p1_2">
                        <label class="btn btn-outline-primary btn-sm" for="p1_2">2</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p1" value="3" class="btn-check" id="p1_3">
                        <label class="btn btn-outline-primary btn-sm" for="p1_3">3</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p1" value="4" class="btn-check" id="p1_4">
                        <label class="btn btn-outline-primary btn-sm" for="p1_4">4</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p1" value="5" class="btn-check" id="p1_5">
                        <label class="btn btn-outline-primary btn-sm" for="p1_5">5</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p1" value="6" class="btn-check" id="p1_6">
                        <label class="btn btn-outline-secondary btn-sm" for="p1_6">6</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p1" value="7" class="btn-check" id="p1_7">
                        <label class="btn btn-outline-info btn-sm" for="p1_7">7</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p1" value="8" class="btn-check" id="p1_8">
                        <label class="btn btn-outline-success btn-sm" for="p1_8">8</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p1" value="9" class="btn-check" id="p1_9">
                        <label class="btn btn-outline-warning btn-sm" for="p1_9">9</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p1" value="10" class="btn-check" id="p1_10">
                        <label class="btn btn-outline-danger btn-sm" for="p1_10">10</label>
                      </td>
                      <td class="text-center">
                        <span class="badge score-badge small" name="level_p1"></span>
                      </td>
                    </tr>
                    <tr>
                      <td class="fw-semibold">
                        Calidad del trabajo
                        <br><small class="text-muted">Entrega trabajos con el estándar de calidad requerido.</small>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p2" value="1" class="btn-check" id="p2_1">
                        <label class="btn btn-outline-primary btn-sm" for="p2_1">1</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p2" value="2" class="btn-check" id="p2_2">
                        <label class="btn btn-outline-primary btn-sm" for="p2_2">2</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p2" value="3" class="btn-check" id="p2_3">
                        <label class="btn btn-outline-primary btn-sm" for="p2_3">3</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p2" value="4" class="btn-check" id="p2_4">
                        <label class="btn btn-outline-primary btn-sm" for="p2_4">4</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p2" value="5" class="btn-check" id="p2_5">
                        <label class="btn btn-outline-primary btn-sm" for="p2_5">5</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p2" value="6" class="btn-check" id="p2_6">
                        <label class="btn btn-outline-secondary btn-sm" for="p2_6">6</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p2" value="7" class="btn-check" id="p2_7">
                        <label class="btn btn-outline-info btn-sm" for="p2_7">7</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p2" value="8" class="btn-check" id="p2_8">
                        <label class="btn btn-outline-success btn-sm" for="p2_8">8</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p2" value="9" class="btn-check" id="p2_9">
                        <label class="btn btn-outline-warning btn-sm" for="p2_9">9</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p2" value="10" class="btn-check" id="p2_10">
                        <label class="btn btn-outline-danger btn-sm" for="p2_10">10</label>
                      </td>
                      <td class="text-center">
                        <span class="badge score-badge small" name="level_p2"></span>
                      </td>
                    </tr>
                    <tr>
                      <td class="fw-semibold">
                        Iniciativa
                        <br><small class="text-muted">Propone mejoras y toma la iniciativa en situaciones relevantes.</small>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p3" value="1" class="btn-check" id="p3_1">
                        <label class="btn btn-outline-primary btn-sm" for="p3_1">1</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p3" value="2" class="btn-check" id="p3_2">
                        <label class="btn btn-outline-primary btn-sm" for="p3_2">2</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p3" value="3" class="btn-check" id="p3_3">
                        <label class="btn btn-outline-primary btn-sm" for="p3_3">3</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p3" value="4" class="btn-check" id="p3_4">
                        <label class="btn btn-outline-primary btn-sm" for="p3_4">4</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p3" value="5" class="btn-check" id="p3_5">
                        <label class="btn btn-outline-primary btn-sm" for="p3_5">5</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p3" value="6" class="btn-check" id="p3_6">
                        <label class="btn btn-outline-secondary btn-sm" for="p3_6">6</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p3" value="7" class="btn-check" id="p3_7">
                        <label class="btn btn-outline-info btn-sm" for="p3_7">7</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p3" value="8" class="btn-check" id="p3_8">
                        <label class="btn btn-outline-success btn-sm" for="p3_8">8</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p3" value="9" class="btn-check" id="p3_9">
                        <label class="btn btn-outline-warning btn-sm" for="p3_9">9</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p3" value="10" class="btn-check" id="p3_10">
                        <label class="btn btn-outline-danger btn-sm" for="p3_10">10</label>
                      </td>
                      <td class="text-center">
                        <span class="badge score-badge small" name="level_p3"></span>
                      </td>
                    </tr>
                    <tr>
                      <td class="fw-semibold">
                        Conocimiento del trabajo
                        <br><small class="text-muted">Domina los conocimientos técnicos y procesos de su área.</small>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p4" value="1" class="btn-check" id="p4_1">
                        <label class="btn btn-outline-primary btn-sm" for="p4_1">1</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p4" value="2" class="btn-check" id="p4_2">
                        <label class="btn btn-outline-primary btn-sm" for="p4_2">2</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p4" value="3" class="btn-check" id="p4_3">
                        <label class="btn btn-outline-primary btn-sm" for="p4_3">3</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p4" value="4" class="btn-check" id="p4_4">
                        <label class="btn btn-outline-primary btn-sm" for="p4_4">4</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p4" value="5" class="btn-check" id="p4_5">
                        <label class="btn btn-outline-primary btn-sm" for="p4_5">5</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p4" value="6" class="btn-check" id="p4_6">
                        <label class="btn btn-outline-secondary btn-sm" for="p4_6">6</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p4" value="7" class="btn-check" id="p4_7">
                        <label class="btn btn-outline-info btn-sm" for="p4_7">7</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p4" value="8" class="btn-check" id="p4_8">
                        <label class="btn btn-outline-success btn-sm" for="p4_8">8</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p4" value="9" class="btn-check" id="p4_9">
                        <label class="btn btn-outline-warning btn-sm" for="p4_9">9</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p4" value="10" class="btn-check" id="p4_10">
                        <label class="btn btn-outline-danger btn-sm" for="p4_10">10</label>
                      </td>
                      <td class="text-center">
                        <span class="badge score-badge small" name="level_p4"></span>
                      </td>
                    </tr>
                    <tr>
                      <td class="fw-semibold">
                        Organización
                        <br><small class="text-muted">Planifica y organiza eficientemente su trabajo y recursos.</small>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p5" value="1" class="btn-check" id="p5_1">
                        <label class="btn btn-outline-primary btn-sm" for="p5_1">1</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p5" value="2" class="btn-check" id="p5_2">
                        <label class="btn btn-outline-primary btn-sm" for="p5_2">2</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p5" value="3" class="btn-check" id="p5_3">
                        <label class="btn btn-outline-primary btn-sm" for="p5_3">3</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p5" value="4" class="btn-check" id="p5_4">
                        <label class="btn btn-outline-primary btn-sm" for="p5_4">4</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p5" value="5" class="btn-check" id="p5_5">
                        <label class="btn btn-outline-primary btn-sm" for="p5_5">5</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p5" value="6" class="btn-check" id="p5_6">
                        <label class="btn btn-outline-secondary btn-sm" for="p5_6">6</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p5" value="7" class="btn-check" id="p5_7">
                        <label class="btn btn-outline-info btn-sm" for="p5_7">7</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p5" value="8" class="btn-check" id="p5_8">
                        <label class="btn btn-outline-success btn-sm" for="p5_8">8</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p5" value="9" class="btn-check" id="p5_9">
                        <label class="btn btn-outline-warning btn-sm" for="p5_9">9</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p5" value="10" class="btn-check" id="p5_10">
                        <label class="btn btn-outline-danger btn-sm" for="p5_10">10</label>
                      </td>
                      <td class="text-center">
                        <span class="badge score-badge small" name="level_p5"></span>
                      </td>
                    </tr>
                    <tr>
                      <td class="fw-semibold">
                        Innovación y creatividad
                        <br><small class="text-muted">Aplica soluciones innovadoras y creativas en su trabajo.</small>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p6" value="1" class="btn-check" id="p6_1">
                        <label class="btn btn-outline-primary btn-sm" for="p6_1">1</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p6" value="2" class="btn-check" id="p6_2">
                        <label class="btn btn-outline-primary btn-sm" for="p6_2">2</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p6" value="3" class="btn-check" id="p6_3">
                        <label class="btn btn-outline-primary btn-sm" for="p6_3">3</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p6" value="4" class="btn-check" id="p6_4">
                        <label class="btn btn-outline-primary btn-sm" for="p6_4">4</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p6" value="5" class="btn-check" id="p6_5">
                        <label class="btn btn-outline-primary btn-sm" for="p6_5">5</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p6" value="6" class="btn-check" id="p6_6">
                        <label class="btn btn-outline-secondary btn-sm" for="p6_6">6</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p6" value="7" class="btn-check" id="p6_7">
                        <label class="btn btn-outline-info btn-sm" for="p6_7">7</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p6" value="8" class="btn-check" id="p6_8">
                        <label class="btn btn-outline-success btn-sm" for="p6_8">8</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p6" value="9" class="btn-check" id="p6_9">
                        <label class="btn btn-outline-warning btn-sm" for="p6_9">9</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="p6" value="10" class="btn-check" id="p6_10">
                        <label class="btn btn-outline-danger btn-sm" for="p6_10">10</label>
                      </td>
                      <td class="text-center">
                        <span class="badge score-badge small" name="level_p6"></span>
                      </td>
                    </tr>
                    <!-- Subtotal row for Productividad -->
                    <tr class="table-info fw-bold">
                      <td class="text-center">SUBTOTAL PRODUCTIVIDAD</td>
                      <td class="text-center" colspan="10">
                        <div class="d-flex justify-content-center align-items-center gap-3">
                          <div>
                            <span class="text-muted small">Puntos:</span>
                            <span class="badge bg-primary area-subtotal" id="subtotal_productividad">0</span>
                            <span class="text-muted small">/ 60</span>
                          </div>
                          <div>
                            <span class="text-muted small">Porcentaje:</span>
                            <span class="badge bg-success area-percent" id="percent_productividad">0%</span>
                          </div>
                        </div>
                      </td>
                      <td class="text-center">
                        <i class="bi bi-calculator text-muted"></i>
                      </td>
                    </tr>

                    <!-- Competencias Específicas Section -->
                    <tr class="table-success">
                      <td colspan="12" class="fw-bold text-center">
                        <i class="bi bi-star-fill me-2"></i>
                        COMPETENCIAS ESPECÍFICAS
                        <small class="ms-2" id="competenciasCargoLabel" style="font-weight: normal;"></small>
                      </td>
                    </tr>
                    <!-- Contenedor dinámico de competencias -->
                    <tbody id="competenciasEspecificasContainer">
                    <tr>
                      <td class="fw-semibold">
                        Pensamiento estratégico
                        <br><small class="text-muted">Demuestra capacidad de análisis y planificación estratégica en sus funciones.</small>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce1" value="1" class="btn-check" id="ce1_1">
                        <label class="btn btn-outline-primary btn-sm" for="ce1_1">1</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce1" value="2" class="btn-check" id="ce1_2">
                        <label class="btn btn-outline-primary btn-sm" for="ce1_2">2</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce1" value="3" class="btn-check" id="ce1_3">
                        <label class="btn btn-outline-primary btn-sm" for="ce1_3">3</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce1" value="4" class="btn-check" id="ce1_4">
                        <label class="btn btn-outline-primary btn-sm" for="ce1_4">4</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce1" value="5" class="btn-check" id="ce1_5">
                        <label class="btn btn-outline-primary btn-sm" for="ce1_5">5</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce1" value="6" class="btn-check" id="ce1_6">
                        <label class="btn btn-outline-secondary btn-sm" for="ce1_6">6</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce1" value="7" class="btn-check" id="ce1_7">
                        <label class="btn btn-outline-info btn-sm" for="ce1_7">7</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce1" value="8" class="btn-check" id="ce1_8">
                        <label class="btn btn-outline-success btn-sm" for="ce1_8">8</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce1" value="9" class="btn-check" id="ce1_9">
                        <label class="btn btn-outline-warning btn-sm" for="ce1_9">9</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce1" value="10" class="btn-check" id="ce1_10">
                        <label class="btn btn-outline-danger btn-sm" for="ce1_10">10</label>
                      </td>
                      <td class="text-center">
                        <span class="badge score-badge small" name="level_ce1"></span>
                      </td>
                    </tr>
                    <tr>
                      <td class="fw-semibold">
                        Comunicación efectiva
                        <br><small class="text-muted">Transmite información de manera clara, precisa y oportuna.</small>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce2" value="1" class="btn-check" id="ce2_1">
                        <label class="btn btn-outline-primary btn-sm" for="ce2_1">1</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce2" value="2" class="btn-check" id="ce2_2">
                        <label class="btn btn-outline-primary btn-sm" for="ce2_2">2</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce2" value="3" class="btn-check" id="ce2_3">
                        <label class="btn btn-outline-primary btn-sm" for="ce2_3">3</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce2" value="4" class="btn-check" id="ce2_4">
                        <label class="btn btn-outline-primary btn-sm" for="ce2_4">4</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce2" value="5" class="btn-check" id="ce2_5">
                        <label class="btn btn-outline-primary btn-sm" for="ce2_5">5</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce2" value="6" class="btn-check" id="ce2_6">
                        <label class="btn btn-outline-secondary btn-sm" for="ce2_6">6</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce2" value="7" class="btn-check" id="ce2_7">
                        <label class="btn btn-outline-info btn-sm" for="ce2_7">7</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce2" value="8" class="btn-check" id="ce2_8">
                        <label class="btn btn-outline-success btn-sm" for="ce2_8">8</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce2" value="9" class="btn-check" id="ce2_9">
                        <label class="btn btn-outline-warning btn-sm" for="ce2_9">9</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce2" value="10" class="btn-check" id="ce2_10">
                        <label class="btn btn-outline-danger btn-sm" for="ce2_10">10</label>
                      </td>
                      <td class="text-center">
                        <span class="badge score-badge small" name="level_ce2"></span>
                      </td>
                    </tr>
                    <tr>
                      <td class="fw-semibold">
                        Manejo de conflictos
                        <br><small class="text-muted">Gestiona y resuelve conflictos de manera constructiva y profesional.</small>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce3" value="1" class="btn-check" id="ce3_1">
                        <label class="btn btn-outline-primary btn-sm" for="ce3_1">1</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce3" value="2" class="btn-check" id="ce3_2">
                        <label class="btn btn-outline-primary btn-sm" for="ce3_2">2</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce3" value="3" class="btn-check" id="ce3_3">
                        <label class="btn btn-outline-primary btn-sm" for="ce3_3">3</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce3" value="4" class="btn-check" id="ce3_4">
                        <label class="btn btn-outline-primary btn-sm" for="ce3_4">4</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce3" value="5" class="btn-check" id="ce3_5">
                        <label class="btn btn-outline-primary btn-sm" for="ce3_5">5</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce3" value="6" class="btn-check" id="ce3_6">
                        <label class="btn btn-outline-secondary btn-sm" for="ce3_6">6</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce3" value="7" class="btn-check" id="ce3_7">
                        <label class="btn btn-outline-info btn-sm" for="ce3_7">7</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce3" value="8" class="btn-check" id="ce3_8">
                        <label class="btn btn-outline-success btn-sm" for="ce3_8">8</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce3" value="9" class="btn-check" id="ce3_9">
                        <label class="btn btn-outline-warning btn-sm" for="ce3_9">9</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce3" value="10" class="btn-check" id="ce3_10">
                        <label class="btn btn-outline-danger btn-sm" for="ce3_10">10</label>
                      </td>
                      <td class="text-center">
                        <span class="badge score-badge small" name="level_ce3"></span>
                      </td>
                    </tr>
                    <tr>
                      <td class="fw-semibold">
                        Liderazgo
                        <br><small class="text-muted">Demuestra capacidad de liderazgo y orientación de equipos.</small>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce4" value="1" class="btn-check" id="ce4_1">
                        <label class="btn btn-outline-primary btn-sm" for="ce4_1">1</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce4" value="2" class="btn-check" id="ce4_2">
                        <label class="btn btn-outline-primary btn-sm" for="ce4_2">2</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce4" value="3" class="btn-check" id="ce4_3">
                        <label class="btn btn-outline-primary btn-sm" for="ce4_3">3</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce4" value="4" class="btn-check" id="ce4_4">
                        <label class="btn btn-outline-primary btn-sm" for="ce4_4">4</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce4" value="5" class="btn-check" id="ce4_5">
                        <label class="btn btn-outline-primary btn-sm" for="ce4_5">5</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce4" value="6" class="btn-check" id="ce4_6">
                        <label class="btn btn-outline-secondary btn-sm" for="ce4_6">6</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce4" value="7" class="btn-check" id="ce4_7">
                        <label class="btn btn-outline-info btn-sm" for="ce4_7">7</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce4" value="8" class="btn-check" id="ce4_8">
                        <label class="btn btn-outline-success btn-sm" for="ce4_8">8</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce4" value="9" class="btn-check" id="ce4_9">
                        <label class="btn btn-outline-warning btn-sm" for="ce4_9">9</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce4" value="10" class="btn-check" id="ce4_10">
                        <label class="btn btn-outline-danger btn-sm" for="ce4_10">10</label>
                      </td>
                      <td class="text-center">
                        <span class="badge score-badge small" name="level_ce4"></span>
                      </td>
                    </tr>
                    <tr>
                      <td class="fw-semibold">
                        Trabajo en equipo
                        <br><small class="text-muted">Colabora eficientemente y mantiene relaciones interpersonales positivas.</small>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce5" value="1" class="btn-check" id="ce5_1">
                        <label class="btn btn-outline-primary btn-sm" for="ce5_1">1</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce5" value="2" class="btn-check" id="ce5_2">
                        <label class="btn btn-outline-primary btn-sm" for="ce5_2">2</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce5" value="3" class="btn-check" id="ce5_3">
                        <label class="btn btn-outline-primary btn-sm" for="ce5_3">3</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce5" value="4" class="btn-check" id="ce5_4">
                        <label class="btn btn-outline-primary btn-sm" for="ce5_4">4</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce5" value="5" class="btn-check" id="ce5_5">
                        <label class="btn btn-outline-primary btn-sm" for="ce5_5">5</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce5" value="6" class="btn-check" id="ce5_6">
                        <label class="btn btn-outline-secondary btn-sm" for="ce5_6">6</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce5" value="7" class="btn-check" id="ce5_7">
                        <label class="btn btn-outline-info btn-sm" for="ce5_7">7</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce5" value="8" class="btn-check" id="ce5_8">
                        <label class="btn btn-outline-success btn-sm" for="ce5_8">8</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce5" value="9" class="btn-check" id="ce5_9">
                        <label class="btn btn-outline-warning btn-sm" for="ce5_9">9</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce5" value="10" class="btn-check" id="ce5_10">
                        <label class="btn btn-outline-danger btn-sm" for="ce5_10">10</label>
                      </td>
                      <td class="text-center">
                        <span class="badge score-badge small" name="level_ce5"></span>
                      </td>
                    </tr>
                    <tr>
                      <td class="fw-semibold">
                        Adaptabilidad
                        <br><small class="text-muted">Se adapta fácilmente a cambios y nuevas situaciones.</small>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce6" value="1" class="btn-check" id="ce6_1">
                        <label class="btn btn-outline-primary btn-sm" for="ce6_1">1</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce6" value="2" class="btn-check" id="ce6_2">
                        <label class="btn btn-outline-primary btn-sm" for="ce6_2">2</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce6" value="3" class="btn-check" id="ce6_3">
                        <label class="btn btn-outline-primary btn-sm" for="ce6_3">3</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce6" value="4" class="btn-check" id="ce6_4">
                        <label class="btn btn-outline-primary btn-sm" for="ce6_4">4</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce6" value="5" class="btn-check" id="ce6_5">
                        <label class="btn btn-outline-primary btn-sm" for="ce6_5">5</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce6" value="6" class="btn-check" id="ce6_6">
                        <label class="btn btn-outline-secondary btn-sm" for="ce6_6">6</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce6" value="7" class="btn-check" id="ce6_7">
                        <label class="btn btn-outline-info btn-sm" for="ce6_7">7</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce6" value="8" class="btn-check" id="ce6_8">
                        <label class="btn btn-outline-success btn-sm" for="ce6_8">8</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce6" value="9" class="btn-check" id="ce6_9">
                        <label class="btn btn-outline-warning btn-sm" for="ce6_9">9</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce6" value="10" class="btn-check" id="ce6_10">
                        <label class="btn btn-outline-danger btn-sm" for="ce6_10">10</label>
                      </td>
                      <td class="text-center">
                        <span class="badge score-badge small" name="level_ce6"></span>
                      </td>
                    </tr>
                    <tr>
                      <td class="fw-semibold">
                        Orientación al cliente
                        <br><small class="text-muted">Enfoca sus esfuerzos en satisfacer las necesidades del cliente interno y externo.</small>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce7" value="1" class="btn-check" id="ce7_1">
                        <label class="btn btn-outline-primary btn-sm" for="ce7_1">1</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce7" value="2" class="btn-check" id="ce7_2">
                        <label class="btn btn-outline-primary btn-sm" for="ce7_2">2</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce7" value="3" class="btn-check" id="ce7_3">
                        <label class="btn btn-outline-primary btn-sm" for="ce7_3">3</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce7" value="4" class="btn-check" id="ce7_4">
                        <label class="btn btn-outline-primary btn-sm" for="ce7_4">4</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce7" value="5" class="btn-check" id="ce7_5">
                        <label class="btn btn-outline-primary btn-sm" for="ce7_5">5</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce7" value="6" class="btn-check" id="ce7_6">
                        <label class="btn btn-outline-secondary btn-sm" for="ce7_6">6</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce7" value="7" class="btn-check" id="ce7_7">
                        <label class="btn btn-outline-info btn-sm" for="ce7_7">7</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce7" value="8" class="btn-check" id="ce7_8">
                        <label class="btn btn-outline-success btn-sm" for="ce7_8">8</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce7" value="9" class="btn-check" id="ce7_9">
                        <label class="btn btn-outline-warning btn-sm" for="ce7_9">9</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="ce7" value="10" class="btn-check" id="ce7_10">
                        <label class="btn btn-outline-danger btn-sm" for="ce7_10">10</label>
                      </td>
                      <td class="text-center">
                        <span class="badge score-badge small" name="level_ce7"></span>
                      </td>
                    </tr>
                    </tbody>
                    <!-- Fin contenedor dinámico -->
                    <!-- Subtotal row for Competencias -->
                    <tr class="table-info fw-bold">
                      <td class="text-center">SUBTOTAL COMPETENCIAS ESPECÍFICAS</td>
                      <td class="text-center" colspan="10">
                        <div class="d-flex justify-content-center align-items-center gap-3">
                          <div>
                            <span class="text-muted small">Puntos:</span>
                            <span class="badge bg-primary area-subtotal" id="subtotal_competencias">0</span>
                            <span class="text-muted small">/ <span id="max_competencias">70</span></span>
                          </div>
                          <div>
                            <span class="text-muted small">Porcentaje:</span>
                            <span class="badge bg-success area-percent" id="percent_competencias">0%</span>
                          </div>
                        </div>
                      </td>
                      <td class="text-center">
                        <i class="bi bi-calculator text-muted"></i>
                      </td>
                    </tr>

                    <!-- Otros Factores Section -->
                    <tr class="table-warning">
                      <td colspan="12" class="fw-bold text-center">
                        <i class="bi bi-plus-circle me-2"></i>
                        OTROS FACTORES
                      </td>
                    </tr>
                    <tr>
                      <td class="fw-semibold">
                        Presentación personal
                        <br><small class="text-muted">Mantiene una imagen profesional adecuada para su cargo.</small>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="o1" value="1" class="btn-check" id="o1_1">
                        <label class="btn btn-outline-primary btn-sm" for="o1_1">1</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="o1" value="2" class="btn-check" id="o1_2">
                        <label class="btn btn-outline-primary btn-sm" for="o1_2">2</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="o1" value="3" class="btn-check" id="o1_3">
                        <label class="btn btn-outline-primary btn-sm" for="o1_3">3</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="o1" value="4" class="btn-check" id="o1_4">
                        <label class="btn btn-outline-primary btn-sm" for="o1_4">4</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="o1" value="5" class="btn-check" id="o1_5">
                        <label class="btn btn-outline-primary btn-sm" for="o1_5">5</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="o1" value="6" class="btn-check" id="o1_6">
                        <label class="btn btn-outline-secondary btn-sm" for="o1_6">6</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="o1" value="7" class="btn-check" id="o1_7">
                        <label class="btn btn-outline-info btn-sm" for="o1_7">7</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="o1" value="8" class="btn-check" id="o1_8">
                        <label class="btn btn-outline-success btn-sm" for="o1_8">8</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="o1" value="9" class="btn-check" id="o1_9">
                        <label class="btn btn-outline-warning btn-sm" for="o1_9">9</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="o1" value="10" class="btn-check" id="o1_10">
                        <label class="btn btn-outline-danger btn-sm" for="o1_10">10</label>
                      </td>
                      <td class="text-center">
                        <span class="badge score-badge small" name="level_o1"></span>
                      </td>
                    </tr>
                    <tr>
                      <td class="fw-semibold">
                        Manejo de información confidencial
                        <br><small class="text-muted">Protege y maneja responsablemente la información confidencial de la empresa.</small>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="o2" value="1" class="btn-check" id="o2_1">
                        <label class="btn btn-outline-primary btn-sm" for="o2_1">1</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="o2" value="2" class="btn-check" id="o2_2">
                        <label class="btn btn-outline-primary btn-sm" for="o2_2">2</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="o2" value="3" class="btn-check" id="o2_3">
                        <label class="btn btn-outline-primary btn-sm" for="o2_3">3</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="o2" value="4" class="btn-check" id="o2_4">
                        <label class="btn btn-outline-primary btn-sm" for="o2_4">4</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="o2" value="5" class="btn-check" id="o2_5">
                        <label class="btn btn-outline-primary btn-sm" for="o2_5">5</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="o2" value="6" class="btn-check" id="o2_6">
                        <label class="btn btn-outline-secondary btn-sm" for="o2_6">6</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="o2" value="7" class="btn-check" id="o2_7">
                        <label class="btn btn-outline-info btn-sm" for="o2_7">7</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="o2" value="8" class="btn-check" id="o2_8">
                        <label class="btn btn-outline-success btn-sm" for="o2_8">8</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="o2" value="9" class="btn-check" id="o2_9">
                        <label class="btn btn-outline-warning btn-sm" for="o2_9">9</label>
                      </td>
                      <td class="text-center">
                        <input type="radio" name="o2" value="10" class="btn-check" id="o2_10">
                        <label class="btn btn-outline-danger btn-sm" for="o2_10">10</label>
                      </td>
                      <td class="text-center">
                        <span class="badge score-badge small" name="level_o2"></span>
                      </td>
                    </tr>
                    <!-- Subtotal row for Otros Factores -->
                    <tr class="table-info fw-bold">
                      <td class="text-center">SUBTOTAL OTROS FACTORES</td>
                      <td class="text-center" colspan="10">
                        <div class="d-flex justify-content-center align-items-center gap-3">
                          <div>
                            <span class="text-muted small">Puntos:</span>
                            <span class="badge bg-primary area-subtotal" id="subtotal_otros">0</span>
                            <span class="text-muted small">/ 20</span>
                          </div>
                          <div>
                            <span class="text-muted small">Porcentaje:</span>
                            <span class="badge bg-success area-percent" id="percent_otros">0%</span>
                          </div>
                        </div>
                      </td>
                      <td class="text-center">
                        <i class="bi bi-calculator text-muted"></i>
                      </td>
                    </tr>

                    <!-- Total Final Row -->
                    <tr class="table-dark text-white fw-bold">
                      <td class="text-center fs-6">
                        <i class="bi bi-trophy-fill me-2"></i>
                        PUNTAJE TOTAL FINAL
                      </td>
                      <td class="text-center" colspan="10">
                        <div class="d-flex justify-content-center align-items-center gap-4">
                          <div>
                            <span class="small">TOTAL:</span>
                            <span class="badge bg-light text-dark fs-6 px-3" id="gran_total_puntos">0</span>
                            <span class="small">/ 200 pts</span>
                          </div>
                          <div>
                            <span class="small">PORCENTAJE FINAL:</span>
                            <span class="badge bg-warning text-dark fs-6 px-3" id="gran_total_porcentaje">0%</span>
                          </div>
                          <div>
                            <span class="small">NIVEL:</span>
                            <span class="badge bg-info text-white fs-6 px-3" id="gran_total_nivel">N/A</span>
                          </div>
                        </div>
                      </td>
                      <td class="text-center">
                        <i class="bi bi-award-fill text-warning fs-4"></i>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Final Score Summary Section -->
          <div class="card mb-4 shadow border-success">
            <div class="card-header bg-success text-white">
              <h6 class="mb-0 fw-bold">
                <i class="bi bi-calculator me-2"></i>
                PUNTUACIÓN FINAL - RESUMEN
              </h6>
            </div>
            <div class="card-body">
              <div class="row g-3">
                <!-- Subtotals Summary -->
                <div class="col-md-8">
                  <h6 class="fw-bold mb-3 text-success">
                    <i class="bi bi-bar-chart me-2"></i>
                    Resumen de Subtotales por Área
                  </h6>
                  <div class="row g-2 mb-3">
                    <div class="col-sm-6 col-md-3">
                      <div class="bg-light p-2 rounded text-center">
                        <small class="text-muted d-block">Conducta Laboral</small>
                        <span class="fw-bold text-primary" id="subtotal-conducta-display">0/50</span>
                        <div><small class="text-muted" id="percent-conducta-display">0%</small></div>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                      <div class="bg-light p-2 rounded text-center">
                        <small class="text-muted d-block">Productividad</small>
                        <span class="fw-bold text-info" id="subtotal-productividad-display">0/60</span>
                        <div><small class="text-muted" id="percent-productividad-display">0%</small></div>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                      <div class="bg-light p-2 rounded text-center">
                        <small class="text-muted d-block">Competencias</small>
                        <span class="fw-bold text-warning" id="subtotal-competencias-display">0/70</span>
                        <div><small class="text-muted" id="percent-competencias-display">0%</small></div>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                      <div class="bg-light p-2 rounded text-center">
                        <small class="text-muted d-block">Otros</small>
                        <span class="fw-bold text-secondary" id="subtotal-otros-display">0/20</span>
                        <div><small class="text-muted" id="percent-otros-display">0%</small></div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Total Score Display -->
                <div class="col-md-4">
                  <div class="bg-gradient text-black p-4 rounded text-center" id="total-score-container" style="background: linear-gradient(135deg, #28a745, #20c997);">
                    <h6 class="fw-bold mb-2">
                      <i class="bi bi-trophy me-2"></i>
                      PUNTUACIÓN TOTAL
                    </h6>
                    <div class="display-6 fw-bold mb-1" id="final-score-display">0/200</div>
                    <div class="h5 mb-0" id="final-percentage-display">0%</div>
                    <small id="performance-level-display">Sin evaluar</small>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Improvement Plan Section (Hidden by default) -->
          <div class="card mb-4 shadow border-warning" id="improvement-plan-section" style="display: none;">
            <div class="card-header bg-warning text-dark">
              <h6 class="mb-0 fw-bold">
                <i class="bi bi-exclamation-triangle me-2"></i>
                PLAN DE MEJORA REQUERIDO
              </h6>
            </div>
            <div class="card-body">
              <div class="alert alert-warning mb-3" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Atención:</strong> El puntaje obtenido es menor al 70%, por lo que se requiere establecer un plan de mejora.
              </div>
              
              <div class="row g-3">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label fw-semibold">
                      <i class="bi bi-target me-2"></i>
                      Áreas Específicas a Mejorar
                    </label>
                    <textarea class="form-control" name="areas_mejorar" rows="4" placeholder="Identifique las áreas específicas que requieren mejora basándose en los resultados de la evaluación..."></textarea>
                  </div>
                  
                  <div class="mb-3">
                    <label class="form-label fw-semibold">
                      <i class="bi bi-list-check me-2"></i>
                      Acciones Concretas Propuestas
                    </label>
                    <textarea class="form-control" name="acciones_propuestas" rows="4" placeholder="Liste las acciones específicas que se implementarán para mejorar el desempeño..."></textarea>
                  </div>
                </div>
                
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label fw-semibold">
                      <i class="bi bi-calendar-event me-2"></i>
                      Plazo para Implementación
                    </label>
                    <select class="form-select" name="plazo_implementacion">
                      <option value="">Seleccionar plazo</option>
                      <option value="1_mes">1 mes</option>
                      <option value="2_meses">2 meses</option>
                      <option value="3_meses">3 meses</option>
                      <option value="6_meses">6 meses</option>
                      <option value="otro">Otro (especificar en comentarios)</option>
                    </select>
                  </div>
                  
                  <div class="mb-3">
                    <label class="form-label fw-semibold">
                      <i class="bi bi-person-check me-2"></i>
                      Responsable del Seguimiento
                    </label>
                    <input type="text" class="form-control" name="responsable_seguimiento" placeholder="Nombre del responsable de dar seguimiento al plan">
                  </div>
                  
                  <div class="mb-3">
                    <label class="form-label fw-semibold">
                      <i class="bi bi-arrow-repeat me-2"></i>
                      Fecha de Próxima Revisión
                    </label>
                    <input type="date" class="form-control" name="fecha_proxima_revision">
                  </div>
                </div>
              </div>
              
              <div class="mb-3">
                <label class="form-label fw-semibold">
                  <i class="bi bi-chat-square-dots me-2"></i>
                  Comentarios Adicionales del Plan de Mejora
                </label>
                <textarea class="form-control" name="comentarios_plan_mejora" rows="3" placeholder="Comentarios adicionales sobre el plan de mejora, recursos necesarios, apoyo requerido, etc."></textarea>
              </div>
            </div>
          </div>

          <!-- Questionnaire Section (Solo visual - no se guarda) -->
          <div class="card mb-4 shadow">
            <div class="card-header bg-dark text-white">
              <h6 class="mb-0 fw-bold">
                <i class="bi bi-question-circle me-2"></i>
                CUESTIONARIO GENERAL (Referencia Visual)
              </h6>
            </div>
            <div class="card-body">
              <div class="alert alert-info mb-3" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Nota:</strong> Esta sección es solo para referencia. Solo se guardarán los porcentajes de las 4 áreas de evaluación.
              </div>
              <p class="text-muted mb-3">Con base en la evaluación realizada anteriormente, conteste las siguientes preguntas con sus propias palabras.</p>
              
              <div class="mb-3">
                <label class="form-label fw-semibold">¿Es idóneo el empleado para el tipo de trabajo realizado?</label>
                <small class="text-muted d-block mb-2">(Si su respuesta es negativa, por favor explique por qué)</small>
                <textarea class="form-control" name="pregunta_idoneo" rows="3" 
                          placeholder="Describa si el empleado es adecuado para su cargo actual..."></textarea>
              </div>
              
              <div class="mb-3">
                <label class="form-label fw-semibold">Evaluación Global</label>
                <small class="text-muted d-block mb-2">A pesar de que el empleado haya tenido errores, cuál cree usted que sería la evaluación global, según su buen criterio.</small>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-check mb-2">
                      <input class="form-check-input" type="radio" name="evaluacion_global" id="eval_excelente" value="Excelente">
                      <label class="form-check-label fw-semibold text-success" for="eval_excelente">
                        <i class="bi bi-star-fill me-1"></i>Excelente
                      </label>
                    </div>
                    <div class="form-check mb-2">
                      <input class="form-check-input" type="radio" name="evaluacion_global" id="eval_muy_bueno" value="Muy bueno">
                      <label class="form-check-label fw-semibold text-primary" for="eval_muy_bueno">
                        <i class="bi bi-hand-thumbs-up me-1"></i>Muy bueno
                      </label>
                    </div>
                    <div class="form-check mb-2">
                      <input class="form-check-input" type="radio" name="evaluacion_global" id="eval_bueno" value="Bueno">
                      <label class="form-check-label fw-semibold text-info" for="eval_bueno">
                        <i class="bi bi-check-circle me-1"></i>Bueno
                      </label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-check mb-2">
                      <input class="form-check-input" type="radio" name="evaluacion_global" id="eval_regular" value="Regular">
                      <label class="form-check-label fw-semibold text-warning" for="eval_regular">
                        <i class="bi bi-dash-circle me-1"></i>Regular
                      </label>
                    </div>
                    <div class="form-check mb-2">
                      <input class="form-check-input" type="radio" name="evaluacion_global" id="eval_deficiente" value="Deficiente">
                      <label class="form-check-label fw-semibold text-danger" for="eval_deficiente">
                        <i class="bi bi-x-circle me-1"></i>Deficiente
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="mb-3">
                <label class="form-label fw-semibold">¿Contrataría al colaborador indefinidamente?</label>
                <div class="mt-2">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="contrataria_si" name="contrataria_si">
                    <label class="form-check-label fw-semibold text-success" for="contrataria_si">
                      <i class="bi bi-check-circle me-1"></i>Sí
                    </label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="contrataria_no" name="contrataria_no">
                    <label class="form-check-label fw-semibold text-danger" for="contrataria_no">
                      <i class="bi bi-x-circle me-1"></i>No
                    </label>
                  </div>
                </div>
              </div>
              
              <div class="mb-3">
                <label class="form-label fw-semibold">Fortalezas del empleado</label>
                <small class="text-muted d-block mb-2">¿Cuáles considera que son las principales fortalezas y puntos fuertes del colaborador?</small>
                <textarea class="form-control" name="fortalezas_empleado" rows="3" 
                          placeholder="Mencione las fortalezas, habilidades destacadas y aspectos positivos del empleado..."></textarea>
              </div>
              
              <div class="mb-3">
                <label class="form-label fw-semibold">Puntos Débiles</label>
                <small class="text-muted d-block mb-2">¿En qué aspectos opina usted que debe mejorar el empleado?</small>
                <textarea class="form-control" name="puntos_debiles" rows="3" 
                          placeholder="Mencione áreas específicas de mejora..."></textarea>
              </div>
              
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-semibold">Comentarios del Evaluado</label>
                  <textarea class="form-control" name="comentarios_evaluado" rows="3" 
                            placeholder="Comentarios del colaborador evaluado..."></textarea>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-semibold">Comentarios del Evaluador</label>
                  <textarea class="form-control" name="comentarios_evaluador" rows="3" 
                            placeholder="Observaciones adicionales del evaluador..."></textarea>
                </div>
              </div>
            </div>
          </div>

          <!-- Terms and Conditions Acceptance -->
          <div class="card mb-4 border-info shadow">
            <div class="card-header bg-info text-white">
              <h6 class="mb-0 fw-bold">
                <i class="bi bi-shield-check me-2"></i>
                ACEPTACIÓN DE TÉRMINOS Y CONDICIONES
              </h6>
            </div>
            <div class="card-body">
              <div class="alert alert-info" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Importante:</strong> Al completar esta evaluación, usted acepta que la información proporcionada es veraz y que entiende las implicaciones del proceso evaluativo.
              </div>
              
              <div class="row g-3">
                <div class="col-12">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="acepta_veracidad" name="acepta_veracidad" required>
                    <label class="form-check-label fw-semibold" for="acepta_veracidad">
                      <i class="bi bi-check-circle me-1"></i>
                      Acepto que toda la información proporcionada en esta evaluación es veraz y completa
                    </label>
                    <div class="invalid-feedback">Debe aceptar la veracidad de la información para continuar.</div>
                  </div>
                </div>
                
                <div class="col-12">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="acepta_confidencialidad" name="acepta_confidencialidad" required>
                    <label class="form-check-label fw-semibold" for="acepta_confidencialidad">
                      <i class="bi bi-lock me-1"></i>
                      Entiendo que esta información será tratada de manera confidencial y utilizada únicamente para fines de evaluación de desempeño
                    </label>
                    <div class="invalid-feedback">Debe aceptar el tratamiento confidencial para continuar.</div>
                  </div>
                </div>
                
                <div class="col-12">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="acepta_politicas" name="acepta_politicas" required>
                    <label class="form-check-label fw-semibold" for="acepta_politicas">
                      <i class="bi bi-clipboard-check me-1"></i>
                      Acepto las políticas internas de evaluación de desempeño de KMSOLUTIONS S.A. y sus consecuencias
                    </label>
                    <div class="invalid-feedback">Debe aceptar las políticas internas para continuar.</div>
                  </div>
                </div>
                
                <div class="col-12">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="acepta_mejora" name="acepta_mejora" required>
                    <label class="form-check-label fw-semibold" for="acepta_mejora">
                      <i class="bi bi-arrow-up-circle me-1"></i>
                      Me comprometo a participar activamente en cualquier plan de mejora que se derive de esta evaluación
                    </label>
                    <div class="invalid-feedback">Debe aceptar el compromiso de mejora para continuar.</div>
                  </div>
                </div>
              </div>
              
              <hr class="my-3">
              
              <div class="text-center">
                <p class="mb-2"><strong>Firmas digitales de aceptación:</strong></p>
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">Firma del Evaluado:</label>
                    <input type="text" class="form-control" name="firma_evaluado" placeholder="Nombre completo del evaluado" required>
                    <div class="invalid-feedback">La firma del evaluado es requerida.</div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">Firma del Evaluador:</label>
                    <input type="text" class="form-control" name="firma_evaluador" placeholder="Nombre completo del evaluador" required>
                    <div class="invalid-feedback">La firma del evaluador es requerida.</div>
                  </div>
                </div>
                <small class="text-muted mt-2 d-block">
                  <i class="bi bi-calendar-event me-1"></i>
                  Fecha de aceptación: <span id="acceptanceDate"></span>
                </small>
              </div>
            </div>
          </div>

          <!-- Notification Settings -->
          <div class="card mb-4 border-warning">
            <div class="card-header bg-warning">
              <h6 class="mb-0 fw-bold">
                <i class="bi bi-bell me-2"></i>
                CONFIGURACIÓN DE NOTIFICACIONES
              </h6>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <label class="form-label fw-semibold">Recordatorio automático al jefe inmediato:</label>
                  <div class="mt-2">
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" id="dias_75" name="dias_notificar" value="75">
                      <label class="form-check-label fw-semibold" for="dias_75">
                        <i class="bi bi-calendar-event me-1"></i>75 días
                      </label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" id="dias_365" name="dias_notificar" value="365">
                      <label class="form-check-label fw-semibold" for="dias_365">
                        <i class="bi bi-calendar-date me-1"></i>365 días (1 año)
                      </label>
                    </div>
                  </div>
                  <small class="text-muted">Se enviará notificación automática al correo del evaluador según el periodo seleccionado</small>
                </div>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="card shadow-lg">
            <div class="card-body text-center">
              <div class="d-flex justify-content-center gap-3 flex-wrap">
                <button type="button" class="btn btn-outline-secondary" onclick="clearForm()">
                  <i class="bi bi-arrow-clockwise me-2"></i>
                  Limpiar Formulario
                </button>
                <button type="button" class="btn btn-info" onclick="showPreview()">
                  <i class="bi bi-eye me-2"></i>
                  Vista Previa
                </button>
                <button type="button" class="btn btn-warning no-print" onclick="window.print()">
                  <i class="bi bi-printer me-2"></i>
                  Imprimir
                </button>
                <button type="submit" class="btn btn-danger btn-lg">
                  <i class="bi bi-save me-2"></i>
                  Guardar Evaluación
                </button>
              </div>
              
              <!-- Progress Info -->
              <div class="mt-3">
                <small class="text-muted">
                  <i class="bi bi-info-circle me-1"></i>
                  Complete la evaluación seleccionando los puntajes para cada criterio
                </small>
              </div>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-3 mt-5">
    <div class="container">
      <small>
        &copy; 2025 KMSOLUTIONS S.A. - Sistema de Evaluación de Desempeño v1.0 
        | GTH-PA-P001-F056
      </small>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../Estilos/app.js"></script>
  <script>
    // Mostrar fecha actual en sección de firmas
    document.addEventListener('DOMContentLoaded', function() {
      const acceptanceDateElement = document.getElementById('acceptanceDate');
      if (acceptanceDateElement) {
        const today = new Date();
        const dateString = today.toLocaleDateString('es-ES', {
          weekday: 'long',
          year: 'numeric',
          month: 'long', 
          day: 'numeric'
        });
        acceptanceDateElement.textContent = dateString;
      }
      
      // Esperar un momento para que todos los elementos se rendericen
      setTimeout(() => {
        console.log('Iniciando carga de catálogos después de timeout...');
        cargarDepartamentos();
        cargarCargos();
      }, 500);
      
      // Event listeners para cargar listas cuando se abren los modales
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
      
      // Event listener para cargar competencias específicas según cargo seleccionado
      const selectCargo = document.getElementById('selectCargo');
      if (selectCargo) {
        selectCargo.addEventListener('change', function() {
          const cargoId = this.value;
          const cargoTexto = this.options[this.selectedIndex].text;
          
          if (cargoId && cargoTexto !== 'Seleccione un cargo') {
            console.log('Cargo seleccionado:', cargoTexto, 'ID:', cargoId);
            cargarCompetenciasPorCargo(cargoTexto);
          }
        });
      }
    });

    // =====================================================
    // FUNCIONES PARA CATÁLOGOS DINÁMICOS
    // =====================================================
    
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
    // FUNCIÓN PARA CARGAR COMPETENCIAS ESPECÍFICAS POR CARGO
    // =====================================================
    
    async function cargarCompetenciasPorCargo(nombreCargo) {
      console.log('=== Cargando competencias para cargo:', nombreCargo, '===');
      
      const container = document.getElementById('competenciasEspecificasContainer');
      const cargoLabel = document.getElementById('competenciasCargoLabel');
      
      if (!container) {
        console.error('ERROR: No se encontró competenciasEspecificasContainer');
        return;
      }
      
      // Actualizar label con nombre del cargo
      if (cargoLabel) {
        cargoLabel.textContent = '(' + nombreCargo + ')';
      }
      
      try {
        const response = await fetch('../catalogos.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'action=obtener_competencias_cargo&nombre_cargo=' + encodeURIComponent(nombreCargo)
        });
        
        const data = await response.json();
        console.log('Respuesta competencias:', data);
        
        if (data.success && data.competencias && Array.isArray(data.competencias)) {
          console.log('✓ Competencias recibidas:', data.competencias.length);
          console.log('✓ Es específico:', data.es_especifico);
          
          // Generar HTML para las competencias
          let html = '';
          data.competencias.forEach((comp, index) => {
            const competenciaId = 'ce' + (index + 1);
            html += `
              <tr>
                <td class="fw-semibold">
                  ${comp.nombre}
                  <br><small class="text-muted">${comp.descripcion}</small>
                </td>
                <td class="text-center">
                  <input type="radio" name="${competenciaId}" value="1" class="btn-check" id="${competenciaId}_1">
                  <label class="btn btn-outline-primary btn-sm" for="${competenciaId}_1">1</label>
                </td>
                <td class="text-center">
                  <input type="radio" name="${competenciaId}" value="2" class="btn-check" id="${competenciaId}_2">
                  <label class="btn btn-outline-primary btn-sm" for="${competenciaId}_2">2</label>
                </td>
                <td class="text-center">
                  <input type="radio" name="${competenciaId}" value="3" class="btn-check" id="${competenciaId}_3">
                  <label class="btn btn-outline-primary btn-sm" for="${competenciaId}_3">3</label>
                </td>
                <td class="text-center">
                  <input type="radio" name="${competenciaId}" value="4" class="btn-check" id="${competenciaId}_4">
                  <label class="btn btn-outline-primary btn-sm" for="${competenciaId}_4">4</label>
                </td>
                <td class="text-center">
                  <input type="radio" name="${competenciaId}" value="5" class="btn-check" id="${competenciaId}_5">
                  <label class="btn btn-outline-primary btn-sm" for="${competenciaId}_5">5</label>
                </td>
                <td class="text-center">
                  <input type="radio" name="${competenciaId}" value="6" class="btn-check" id="${competenciaId}_6">
                  <label class="btn btn-outline-secondary btn-sm" for="${competenciaId}_6">6</label>
                </td>
                <td class="text-center">
                  <input type="radio" name="${competenciaId}" value="7" class="btn-check" id="${competenciaId}_7">
                  <label class="btn btn-outline-info btn-sm" for="${competenciaId}_7">7</label>
                </td>
                <td class="text-center">
                  <input type="radio" name="${competenciaId}" value="8" class="btn-check" id="${competenciaId}_8">
                  <label class="btn btn-outline-success btn-sm" for="${competenciaId}_8">8</label>
                </td>
                <td class="text-center">
                  <input type="radio" name="${competenciaId}" value="9" class="btn-check" id="${competenciaId}_9">
                  <label class="btn btn-outline-warning btn-sm" for="${competenciaId}_9">9</label>
                </td>
                <td class="text-center">
                  <input type="radio" name="${competenciaId}" value="10" class="btn-check" id="${competenciaId}_10">
                  <label class="btn btn-outline-danger btn-sm" for="${competenciaId}_10">10</label>
                </td>
                <td class="text-center">
                  <span class="badge score-badge small" name="level_${competenciaId}"></span>
                </td>
              </tr>`;
          });
          
          container.innerHTML = html;
          
          // Actualizar el máximo de puntos según cantidad de competencias
          const numCompetencias = data.competencias.length;
          const maxPuntos = numCompetencias * 10;
          const maxCompetenciasSpan = document.getElementById('max_competencias');
          if (maxCompetenciasSpan) {
            maxCompetenciasSpan.textContent = maxPuntos;
          }
          
          // Actualizar la configuración del área de competencias en app.areas
          if (window.app && window.app.areas && window.app.areas.competencias) {
            // Actualizar los campos dinámicamente según la cantidad de competencias
            const fieldsArray = [];
            for (let i = 1; i <= numCompetencias; i++) {
              fieldsArray.push('ce' + i);
            }
            window.app.areas.competencias.fields = fieldsArray;
            window.app.areas.competencias.maxScore = maxPuntos;
            console.log('✓ Configuración de competencias actualizada:', window.app.areas.competencias);
          }
          
          // Recalcular subtotal cuando se seleccione alguna opción
          container.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function(e) {
              if (e.target.checked && window.app) {
                window.app.updateScoreBadge(e.target.name, parseInt(e.target.value));
                window.app.calculateAreaSubtotal('competencias');
              }
            });
          });
          
          console.log('✓ Competencias cargadas exitosamente');
        } else {
          console.error('✗ Error al cargar competencias:', data.mensaje || 'Datos inválidos');
          container.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Error al cargar competencias</td></tr>';
        }
      } catch (error) {
        console.error('Error fetch competencias:', error);
        container.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Error de conexión: ' + error.message + '</td></tr>';
      }
    }
    
    // =====================================================
    // FUNCIONES PARA NUEVOS DEPARTAMENTOS
    // =====================================================
    
    async function guardarNuevoDepartamento() {
      const form = document.getElementById('formNuevoDepartamento');
      const formData = new FormData(form);
      
      const nombre = formData.get('nombre_departamento').trim();
      const descripcion = formData.get('descripcion_departamento').trim();
      
      if (!nombre) {
        mostrarAlerta('El nombre del departamento es requerido', 'danger');
        return;
      }
      
      try {
        const response = await fetch('../catalogos.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            accion: 'agregar_departamento',
            nombre: nombre,
            descripcion: descripcion
          })
        });
        
        const data = await response.json();
        
        if (data.exito) {
          // Cerrar modal
          const modal = bootstrap.Modal.getInstance(document.getElementById('modalNuevoDepartamento'));
          modal.hide();
          
          // Limpiar formulario
          form.reset();
          
          // Recargar departamentos
          await cargarDepartamentos();
          
          // Seleccionar el nuevo departamento
          document.getElementById('selectDepartamento').value = data.nombre;
          
          mostrarAlerta(data.mensaje, 'success');
        } else {
          mostrarAlerta(data.mensaje, 'danger');
        }
      } catch (error) {
        console.error('Error:', error);
        mostrarAlerta('Error al guardar departamento', 'danger');
      }
    }
    
    // =====================================================
    // FUNCIONES PARA NUEVOS CARGOS
    // =====================================================
    
    async function guardarNuevoCargo() {
      const form = document.getElementById('formNuevoCargo');
      const formData = new FormData(form);
      
      const nombre = formData.get('nombre_cargo').trim();
      const descripcion = formData.get('descripcion_cargo').trim();
      
      if (!nombre) {
        mostrarAlerta('El nombre del cargo es requerido', 'danger');
        return;
      }
      
      try {
        const response = await fetch('../catalogos.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            accion: 'agregar_cargo',
            nombre: nombre,
            descripcion: descripcion
          })
        });
        
        const data = await response.json();
        
        if (data.exito) {
          // Cerrar modal
          const modal = bootstrap.Modal.getInstance(document.getElementById('modalNuevoCargo'));
          modal.hide();
          
          // Limpiar formulario
          form.reset();
          
          // Recargar cargos
          await cargarCargos();
          
          // Seleccionar el nuevo cargo
          document.getElementById('selectCargo').value = data.nombre;
          
          mostrarAlerta(data.mensaje, 'success');
        } else {
          mostrarAlerta(data.mensaje, 'danger');
        }
      } catch (error) {
        console.error('Error:', error);
        mostrarAlerta('Error al guardar cargo', 'danger');
      }
    }
    
    // =====================================================
    // FUNCIONES PARA NUEVOS DEPARTAMENTOS DEL COLABORADOR
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
    // FUNCIONES PARA NUEVOS CARGOS DEL COLABORADOR
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
    
    // =====================================================
    // FUNCIONES PARA NUEVOS DEPARTAMENTOS DEL EVALUADOR
    // =====================================================
    
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
          headers: { 'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            accion: 'agregar_departamento',
            nombre: nombre,
            descripcion: descripcion
          })
        });
        
        const data = await response.json();
        
        if (data.exito) {
          // Cerrar modal
          const modal = bootstrap.Modal.getInstance(document.getElementById('modalNuevoDepartamentoEvaluador'));
          modal.hide();
          
          // Limpiar formulario
          form.reset();
          
          // Recargar departamentos en ambos selectores
          await cargarDepartamentos();
          
          // Seleccionar el nuevo departamento en el selector del evaluador
          document.getElementById('selectDepartamentoEvaluador').value = data.nombre;
          
          mostrarAlerta(data.mensaje, 'success');
        } else {
          mostrarAlerta(data.mensaje, 'danger');
        }
      } catch (error) {
        console.error('Error:', error);
        mostrarAlerta('Error al guardar departamento', 'danger');
      }
    }
    
    // =====================================================
    // FUNCIONES PARA NUEVOS CARGOS DEL EVALUADOR
    // =====================================================
    
    async function guardarNuevoCargoEvaluador() {
      const form = document.getElementById('formNuevoCargoEvaluador');
      const formData = new FormData(form);
      
      const nombre = formData.get('nombre_cargo').trim();
      const descripcion = formData.get('descripcion_cargo').trim();
      
      if (!nombre) {
        mostrarAlerta('El nombre del cargo es requerido', 'danger');
        return;
      }
      
      try {
        const response = await fetch('../catalogos.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            accion: 'agregar_cargo',
            nombre: nombre,
            descripcion: descripcion
          })
        });
        
        const data = await response.json();
        
        if (data.exito) {
          // Cerrar modal
          const modal = bootstrap.Modal.getInstance(document.getElementById('modalNuevoCargoEvaluador'));
          modal.hide();
          
          // Limpiar formulario
          form.reset();
          
          // Recargar cargos en ambos selectores
          await cargarCargos();
          
          // Seleccionar el nuevo cargo en el selector del evaluador
          document.getElementById('selectCargoEvaluador').value = data.nombre;
          
          mostrarAlerta(data.mensaje, 'success');
        } else {
          mostrarAlerta(data.mensaje, 'danger');
        }
      } catch (error) {
        console.error('Error:', error);
        mostrarAlerta('Error al guardar cargo', 'danger');
      }
    }
    
    // =====================================================
    // FUNCIÓN AUXILIAR PARA MOSTRAR ALERTAS
    // =====================================================
    
    function mostrarAlerta(mensaje, tipo = 'info') {
      const alertContainer = document.getElementById('alertContainer');
      
      const alertDiv = document.createElement('div');
      alertDiv.className = `alert alert-${tipo} alert-dismissible fade show`;
      alertDiv.innerHTML = `
        <i class="bi bi-${tipo === 'success' ? 'check-circle' : tipo === 'danger' ? 'exclamation-triangle' : 'info-circle'}"></i>
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      `;
      
      alertContainer.appendChild(alertDiv);
      
      // Auto-dismiss después de 5 segundos
      setTimeout(() => {
        if (alertDiv.parentNode) {
          alertDiv.remove();
        }
      }, 5000);
    }
  </script>

  <!-- Modal Nuevo Departamento -->
  <div class="modal fade" id="modalNuevoDepartamento" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">
            <i class="bi bi-building-add me-2"></i>
            Agregar Nuevo Departamento
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="formNuevoDepartamento">
            <div class="mb-3">
              <label class="form-label fw-semibold">Nombre del Departamento: *</label>
              <input type="text" class="form-control" name="nombre_departamento" required maxlength="100">
              <div class="form-text">Máximo 100 caracteres</div>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Descripción:</label>
              <textarea class="form-control" name="descripcion_departamento" rows="3" placeholder="Descripción opcional del departamento..."></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" onclick="guardarNuevoDepartamento()">
            <i class="bi bi-check-circle me-1"></i>Guardar
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Nuevo Cargo -->
  <div class="modal fade" id="modalNuevoCargo" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title">
            <i class="bi bi-person-badge me-2"></i>
            Agregar Nuevo Cargo
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="formNuevoCargo">
            <div class="mb-3">
              <label class="form-label fw-semibold">Nombre del Cargo: *</label>
              <input type="text" class="form-control" name="nombre_cargo" required maxlength="100">
              <div class="form-text">Máximo 100 caracteres</div>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Descripción:</label>
              <textarea class="form-control" name="descripcion_cargo" rows="3" placeholder="Descripción opcional del cargo..."></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-success" onclick="guardarNuevoCargo()">
            <i class="bi bi-check-circle me-1"></i>Guardar
          </button>
        </div>
      </div>
    </div>
  </div>

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

</body>
</html>
