<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Completar Evaluación - KMSOLUTIONS S.A.</title>
  
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
            <a href="iniciar_evaluacion.php" class="btn btn-outline-light btn-sm me-2">
              <i class="bi bi-plus-circle me-1"></i>Iniciar Evaluación
            </a>
            <a href="evaluacion.php" class="btn btn-outline-light btn-sm active">
              <i class="bi bi-pencil-square me-1"></i>Completar Evaluación
            </a>
          </nav>
          <div class="small">Fecha: <span id="currentDate"></span></div>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Container -->
  <div class="container-fluid py-4" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: calc(100vh - 140px);">
    
    <!-- Form Container -->
    <div class="row justify-content-center">
      <div class="col-12 col-xl-10">
        
        <form id="evaluationForm" method="POST" class="needs-validation" novalidate>
          
          <!-- Selector de Empleado a Evaluar -->
          <div class="card mb-4 shadow border-primary">
            <div class="card-header bg-primary text-white">
              <h6 class="mb-0 fw-bold">
                <i class="bi bi-person-fill me-2"></i>
                SELECCIONAR EMPLEADO A EVALUAR
              </h6>
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-8">
                  <label for="selectEmpleado" class="form-label fw-semibold">
                    <i class="bi bi-person-badge me-1"></i>
                    Empleado a Evaluar:
                  </label>
                  <select class="form-select" id="selectEmpleado" name="id_registro" required>
                    <option value="">Cargando evaluaciones sin evaluar...</option>
                  </select>
                  <div class="invalid-feedback">Por favor seleccione un empleado.</div>
                </div>
                <div class="col-md-4">
                  <div class="alert alert-info mb-0 small">
                    <i class="bi bi-info-circle me-1"></i>
                    <strong>Nota:</strong> Las competencias específicas se cargarán según el cargo.
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Información del Colaborador y Evaluador (oculto hasta seleccionar) -->
          <div id="infoEvaluacionCard" class="card mb-4 shadow border-success" style="display: none;">
            <div class="card-header bg-success text-white">
              <h6 class="mb-0 fw-bold">
                <i class="bi bi-info-circle-fill me-2"></i>
                INFORMACIÓN DE LA EVALUACIÓN
              </h6>
            </div>
            <div class="card-body">
              <div class="row g-4">
                <!-- Colaborador -->
                <div class="col-md-6">
                  <h6 class="fw-bold text-success mb-3">
                    <i class="bi bi-person-badge me-2"></i>
                    COLABORADOR A EVALUAR
                  </h6>
                  <div class="row g-2">
                    <div class="col-12">
                      <label class="small text-muted">Nombres:</label>
                      <div class="fw-semibold" id="colNombres">-</div>
                    </div>
                    <div class="col-12">
                      <label class="small text-muted">Apellidos:</label>
                      <div class="fw-semibold" id="colApellidos">-</div>
                    </div>
                    <div class="col-12">
                      <label class="small text-muted">Cargo:</label>
                      <div class="fw-semibold" id="colCargo">-</div>
                    </div>
                    <div class="col-12">
                      <label class="small text-muted">Departamento:</label>
                      <div class="fw-semibold" id="colDepartamento">-</div>
                    </div>
                    <div class="col-6">
                      <label class="small text-muted">Fecha Ingreso:</label>
                      <div class="fw-semibold" id="colFechaIngreso">-</div>
                    </div>
                    <div class="col-6">
                      <label class="small text-muted">Email:</label>
                      <div class="fw-semibold small" id="colEmail">-</div>
                    </div>
                  </div>
                </div>
                
                <!-- Evaluador -->
                <div class="col-md-6">
                  <h6 class="fw-bold text-primary mb-3">
                    <i class="bi bi-clipboard-check me-2"></i>
                    EVALUADOR
                  </h6>
                  <div class="row g-2">
                    <div class="col-12">
                      <label class="small text-muted">Nombres:</label>
                      <div class="fw-semibold" id="evalNombres">-</div>
                    </div>
                    <div class="col-12">
                      <label class="small text-muted">Apellidos:</label>
                      <div class="fw-semibold" id="evalApellidos">-</div>
                    </div>
                    <div class="col-12">
                      <label class="small text-muted">Cargo:</label>
                      <div class="fw-semibold" id="evalCargo">-</div>
                    </div>
                    <div class="col-12">
                      <label class="small text-muted">Departamento:</label>
                      <div class="fw-semibold" id="evalDepartamento">-</div>
                    </div>
                    <div class="col-12">
                      <label class="small text-muted">Email:</label>
                      <div class="fw-semibold small" id="evalEmail">-</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- INSTRUCCIONES -->
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

          <!-- Botón Guardar Evaluación -->
          <div class="text-center mb-4">
            <button type="submit" class="btn btn-success btn-lg px-5" id="btnGuardarEvaluacion">
              <i class="bi bi-save me-2"></i>
              GUARDAR EVALUACIÓN
            </button>
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
    // =====================================================
    // INICIALIZACIÓN AL CARGAR LA PÁGINA
    // =====================================================
    document.addEventListener('DOMContentLoaded', function() {
      // Actualizar fecha
      const dateElement = document.getElementById('currentDate');
      if (dateElement) {
        const today = new Date();
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        dateElement.textContent = today.toLocaleDateString('es-ES', options);
      }
      
      // Cargar empleados pendientes de evaluación
      cargarEmpleadosPendientes();
    });

    // =====================================================
    // FUNCIÓN PARA CARGAR EMPLEADOS PENDIENTES
    // =====================================================
    async function cargarEmpleadosPendientes() {
      const selectEmpleado = document.getElementById('selectEmpleado');
      
      try {
        const response = await fetch('../guardar.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'action=obtener_evaluaciones_pendientes'
        });
        
        const data = await response.json();
        console.log('Evaluaciones sin evaluar:', data);
        
        if (data.success && data.evaluaciones && data.evaluaciones.length > 0) {
          let html = '<option value="">-- Seleccione una evaluación --</option>';
          data.evaluaciones.forEach(eval => {
            const fecha = eval.fecha ? new Date(eval.fecha + 'T00:00:00').toLocaleDateString('es-ES') : 'Sin fecha';
            html += `<option value="${eval.id_registro}" data-cargo="${eval.cargo}">
              ${eval.nombre_completo} - ${fecha}
            </option>`;
          });
          selectEmpleado.innerHTML = html;
          
          // Event listener para cargar información y competencias al seleccionar
          selectEmpleado.addEventListener('change', async function() {
            const selectedOption = this.options[this.selectedIndex];
            if (this.value && selectedOption) {
              await cargarDetalleEvaluacion(this.value);
              
              const nombreCargo = selectedOption.getAttribute('data-cargo');
              if (nombreCargo) {
                await cargarCompetenciasPorCargo(nombreCargo);
              }
            } else {
              // Ocultar card de información si no hay selección
              document.getElementById('infoEvaluacionCard').style.display = 'none';
            }
          });
        } else {
          selectEmpleado.innerHTML = '<option value="">No hay evaluaciones sin evaluar</option>';
        }
      } catch (error) {
        console.error('Error al cargar evaluaciones:', error);
        selectEmpleado.innerHTML = '<option value="">Error al cargar evaluaciones</option>';
      }
    }

    // =====================================================
    // FUNCIÓN PARA CARGAR DETALLE DE LA EVALUACIÓN
    // =====================================================
    async function cargarDetalleEvaluacion(idRegistro) {
      try {
        const response = await fetch('../guardar.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'action=obtener_detalle_evaluacion&id_registro=' + idRegistro
        });
        
        const data = await response.json();
        console.log('Detalle evaluación:', data);
        
        if (data.success && data.colaborador && data.evaluador) {
          // Mostrar información del colaborador
          document.getElementById('colNombres').textContent = data.colaborador.nom_col || '-';
          document.getElementById('colApellidos').textContent = data.colaborador.apell_col || '-';
          document.getElementById('colCargo').textContent = data.colaborador.cargo || '-';
          document.getElementById('colDepartamento').textContent = data.colaborador.departamento || '-';
          document.getElementById('colFechaIngreso').textContent = data.colaborador.fecha_ingreso_format || '-';
          document.getElementById('colEmail').textContent = data.colaborador.email || '-';
          
          // Mostrar información del evaluador
          document.getElementById('evalNombres').textContent = data.evaluador.nom_eva || '-';
          document.getElementById('evalApellidos').textContent = data.evaluador.apell_eva || '-';
          document.getElementById('evalCargo').textContent = data.evaluador.cargo || '-';
          document.getElementById('evalDepartamento').textContent = data.evaluador.departamento || '-';
          document.getElementById('evalEmail').textContent = data.evaluador.email || '-';
          
          // Mostrar el card de información
          document.getElementById('infoEvaluacionCard').style.display = 'block';
        } else {
          mostrarAlerta('Error al cargar detalles: ' + (data.mensaje || 'Datos incompletos'), 'warning');
        }
      } catch (error) {
        console.error('Error al cargar detalle:', error);
        mostrarAlerta('Error de conexión al cargar detalles', 'danger');
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
          container.innerHTML = '<tr><td colspan="12" class="text-center text-danger">Error al cargar competencias</td></tr>';
        }
      } catch (error) {
        console.error('Error fetch competencias:', error);
        container.innerHTML = '<tr><td colspan="12" class="text-center text-danger">Error de conexión: ' + error.message + '</td></tr>';
      }
    }

    // =====================================================
    // FUNCIÓN PARA GUARDAR EVALUACIÓN
    // =====================================================
    document.getElementById('evaluationForm').addEventListener('submit', async function(e) {
      e.preventDefault();
      
      // Validar formulario
      if (!this.checkValidity()) {
        e.stopPropagation();
        this.classList.add('was-validated');
        mostrarAlerta('Por favor complete todos los campos requeridos', 'warning');
        return;
      }
      
      const idRegistro = document.getElementById('selectEmpleado').value;
      if (!idRegistro) {
        mostrarAlerta('Por favor seleccione un empleado', 'warning');
        return;
      }
      
      // Recolectar todos los puntajes
      const formData = new FormData(this);
      const puntajes = {};
      
      // Conducta Laboral (c1-c5)
      for (let i = 1; i <= 5; i++) {
        const value = formData.get('c' + i);
        puntajes['c' + i] = value ? parseInt(value) : 0;
      }
      
      // Productividad (p1-p6)
      for (let i = 1; i <= 6; i++) {
        const value = formData.get('p' + i);
        puntajes['p' + i] = value ? parseInt(value) : 0;
      }
      
      // Competencias Específicas (ce1-ce7, puede variar según cargo)
      for (let i = 1; i <= 10; i++) {
        const value = formData.get('ce' + i);
        if (value) {
          puntajes['ce' + i] = parseInt(value);
        }
      }
      
      // Otros Factores (o1-o2)
      for (let i = 1; i <= 2; i++) {
        const value = formData.get('o' + i);
        puntajes['o' + i] = value ? parseInt(value) : 0;
      }
      
      // Mostrar loading
      document.getElementById('loadingOverlay').classList.remove('d-none');
      document.getElementById('btnGuardarEvaluacion').disabled = true;
      
      try {
        const response = await fetch('../guardar.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            action: 'completar_evaluacion',
            id_registro: idRegistro,
            puntajes: puntajes
          })
        });
        
        const data = await response.json();
        console.log('Respuesta guardar:', data);
        
        if (data.success) {
          mostrarAlerta('Evaluación guardada exitosamente', 'success');
          setTimeout(() => {
            window.location.reload();
          }, 2000);
        } else {
          mostrarAlerta('Error: ' + (data.mensaje || 'No se pudo guardar la evaluación'), 'danger');
        }
      } catch (error) {
        console.error('Error al guardar:', error);
        mostrarAlerta('Error de conexión: ' + error.message, 'danger');
      } finally {
        document.getElementById('loadingOverlay').classList.add('d-none');
        document.getElementById('btnGuardarEvaluacion').disabled = false;
      }
    });

    // =====================================================
    // FUNCIÓN PARA MOSTRAR ALERTAS
    // =====================================================
    function mostrarAlerta(mensaje, tipo) {
      const alertContainer = document.getElementById('alertContainer');
      const alertDiv = document.createElement('div');
      alertDiv.className = `alert alert-${tipo} alert-dismissible fade show`;
      alertDiv.role = 'alert';
      alertDiv.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      `;
      alertContainer.appendChild(alertDiv);
      
      setTimeout(() => {
        alertDiv.remove();
      }, 5000);
    }
  </script>

</body>
</html>
