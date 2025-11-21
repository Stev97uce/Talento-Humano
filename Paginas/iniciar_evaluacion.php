<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Iniciar Evaluación - KMSOLUTIONS S.A.</title>
  
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
      <p class="mt-3">Procesando solicitud...</p>
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

  <!-- Main Container -->
  <div class="container-fluid py-4" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: calc(100vh - 140px);">
    
    <!-- Form Container -->
    <div class="row justify-content-center">
      <div class="col-12 col-xl-8">
        
        <form id="iniciarEvaluacionForm" class="needs-validation" novalidate>
          
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
                  INICIAR NUEVA EVALUACIÓN DE DESEMPEÑO
                </h4>
                <div class="text-end">
                  <label class="form-label fw-bold mb-1">FECHA: *</label>
                  <input type="date" class="form-control form-control-sm" name="fecha" id="fecha" required />
                  <div class="invalid-feedback">Por favor seleccione una fecha.</div>
                </div>
              </div>
              
              <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Instrucciones:</strong> Complete la información del colaborador y evaluador. 
                Al enviar, se creará la solicitud de evaluación y se notificará al evaluador por correo electrónico.
              </div>
            </div>
          </div>

          <!-- Colaborador / Evaluador Section -->
          <div class="row g-3 mb-4">
            <!-- Colaborador -->
            <div class="col-md-6">
              <div class="card border-danger h-100 shadow">
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
                      <input class="form-control" name="nombres" id="nombres" required />
                      <div class="invalid-feedback">Este campo es requerido.</div>
                    </div>
                    <div class="col-6">
                      <label class="form-label fw-semibold">Apellidos: *</label>
                      <input class="form-control" name="apellidos" id="apellidos" required />
                      <div class="invalid-feedback">Este campo es requerido.</div>
                    </div>
                    <div class="col-6">
                      <label class="form-label fw-semibold">Cédula:</label>
                      <input class="form-control" name="cedula" id="cedula" />
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
                      <input type="date" class="form-control" name="fecha_ingreso" id="fecha_ingreso" />
                    </div>
                    <div class="col-12">
                      <label class="form-label fw-semibold">Email (notificaciones):</label>
                      <input type="email" class="form-control" name="email" id="email" placeholder="ejemplo@kmsolutions.com" />
                      <small class="text-muted">El colaborador recibirá una copia de la evaluación</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Evaluador -->
            <div class="col-md-6">
              <div class="card border-dark h-100 shadow">
                <div class="card-header bg-dark text-white">
                  <h6 class="mb-0 fw-bold">
                    <i class="bi bi-person-badge-fill me-2"></i>
                    EVALUADOR (a) - Jefe Inmediato
                  </h6>
                </div>
                <div class="card-body">
                  <div class="row g-3">
                    <div class="col-6">
                      <label class="form-label fw-semibold">Nombres: *</label>
                      <input class="form-control" name="evaluador_nombres" id="evaluador_nombres" required />
                      <div class="invalid-feedback">Este campo es requerido.</div>
                    </div>
                    <div class="col-6">
                      <label class="form-label fw-semibold">Apellidos: *</label>
                      <input class="form-control" name="evaluador_apellidos" id="evaluador_apellidos" required />
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
                      <label class="form-label fw-semibold">Email: *</label>
                      <input type="email" class="form-control" name="evaluador_email" id="evaluador_email" placeholder="ejemplo@kmsolutions.com" required />
                      <div class="invalid-feedback">El email del evaluador es requerido.</div>
                      <small class="text-muted">Se enviará notificación a este correo</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Información del Período de Evaluación -->
          <div class="card mb-4 shadow">
            <div class="card-header bg-info text-white">
              <h6 class="mb-0 fw-bold">
                <i class="bi bi-calendar-range me-2"></i>
                INFORMACIÓN DEL PERÍODO DE EVALUACIÓN
              </h6>
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label fw-semibold">Período de prueba: *</label>
                  <select class="form-select" name="periodo_prueba" id="periodo_prueba" required>
                    <option value="">Seleccione...</option>
                    <option value="1 mes">1 mes</option>
                    <option value="2 meses">2 meses</option>
                    <option value="3 meses">3 meses</option>
                    <option value="6 meses">6 meses</option>
                    <option value="1 año">1 año</option>
                  </select>
                  <div class="invalid-feedback">Este campo es requerido.</div>
                </div>
                <div class="col-md-4">
                  <label class="form-label fw-semibold">Tipo de evaluación: *</label>
                  <select class="form-select" name="tipo_evaluacion" id="tipo_evaluacion" required>
                    <option value="">Seleccione...</option>
                    <option value="Período de Prueba">Período de Prueba</option>
                    <option value="Desempeño Anual">Desempeño Anual</option>
                    <option value="Evaluación Especial">Evaluación Especial</option>
                  </select>
                  <div class="invalid-feedback">Este campo es requerido.</div>
                </div>
                <div class="col-md-4">
                  <label class="form-label fw-semibold">Recordatorio automático:</label>
                  <select class="form-select" name="dias_notificar" id="dias_notificar">
                    <option value="">Sin recordatorio</option>
                    <option value="75">75 días</option>
                    <option value="365">365 días (1 año)</option>
                  </select>
                  <small class="text-muted">Notificación al evaluador</small>
                </div>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="card shadow-lg">
            <div class="card-body">
              <div class="alert alert-warning mb-3">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Importante:</strong> Al enviar esta solicitud, se notificará al evaluador por correo electrónico 
                para que complete la evaluación en el sistema.
              </div>
              
              <div class="d-flex justify-content-center gap-3 flex-wrap">
                <button type="button" class="btn btn-outline-secondary" onclick="limpiarFormulario()">
                  <i class="bi bi-arrow-clockwise me-2"></i>
                  Limpiar Formulario
                </button>
                <button type="submit" class="btn btn-danger btn-lg">
                  <i class="bi bi-send me-2"></i>
                  Enviar Solicitud al Evaluador
                </button>
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
  <script>
    // Mostrar fecha actual
    document.addEventListener('DOMContentLoaded', function() {
      const currentDateElement = document.getElementById('currentDate');
      if (currentDateElement) {
        const today = new Date();
        const dateString = today.toLocaleDateString('es-ES', {
          year: 'numeric',
          month: 'long', 
          day: 'numeric'
        });
        currentDateElement.textContent = dateString;
      }
      
      // Establecer fecha actual en el input
      const fechaInput = document.querySelector('input[name="fecha"]');
      if (fechaInput && !fechaInput.value) {
        fechaInput.value = new Date().toISOString().split('T')[0];
      }
      
      // Cargar catálogos
      setTimeout(() => {
        cargarDepartamentos();
        cargarCargos();
      }, 500);
    });

    // Función para cargar departamentos
    async function cargarDepartamentos() {
      const selectColaborador = document.getElementById('selectDepartamento');
      const selectEvaluador = document.getElementById('selectDepartamentoEvaluador');
      
      try {
        const response = await fetch('../catalogos.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'action=obtener_departamentos'
        });
        
        const data = await response.json();
        
        if (data.success && data.departamentos && Array.isArray(data.departamentos)) {
          const optionsHTML = '<option value="">Seleccione un departamento</option>' +
            data.departamentos.map(dept => 
              `<option value="${dept.id_departamento}">${dept.nom_dep}</option>`
            ).join('');
          
          selectColaborador.innerHTML = optionsHTML;
          selectEvaluador.innerHTML = optionsHTML;
        }
      } catch (error) {
        console.error('Error cargando departamentos:', error);
      }
    }

    // Función para cargar cargos
    async function cargarCargos() {
      const selectColaborador = document.getElementById('selectCargo');
      const selectEvaluador = document.getElementById('selectCargoEvaluador');
      
      try {
        const response = await fetch('../catalogos.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'action=obtener_cargos'
        });
        
        const data = await response.json();
        
        if (data.success && data.cargos && Array.isArray(data.cargos)) {
          const optionsHTML = '<option value="">Seleccione un cargo</option>' +
            data.cargos.map(cargo => 
              `<option value="${cargo.id_cargo}">${cargo.nom_car}</option>`
            ).join('');
          
          selectColaborador.innerHTML = optionsHTML;
          selectEvaluador.innerHTML = optionsHTML;
        }
      } catch (error) {
        console.error('Error cargando cargos:', error);
      }
    }

    // Manejo del envío del formulario
    document.getElementById('iniciarEvaluacionForm').addEventListener('submit', async function(e) {
      e.preventDefault();
      
      // Validar formulario
      if (!this.checkValidity()) {
        e.stopPropagation();
        this.classList.add('was-validated');
        mostrarAlerta('Por favor complete todos los campos requeridos', 'danger');
        return;
      }
      
      // Mostrar loading
      document.getElementById('loadingOverlay').classList.remove('d-none');
      
      try {
        const formData = new FormData(this);
        
        // Enviar datos al backend
        const response = await fetch('../iniciar_evaluacion_backend.php', {
          method: 'POST',
          body: formData
        });
        
        const result = await response.json();
        
        if (result.exito) {
          mostrarAlerta(result.mensaje, 'success');
          setTimeout(() => {
            this.reset();
            this.classList.remove('was-validated');
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

    // Función para limpiar formulario
    function limpiarFormulario() {
      if (confirm('¿Está seguro de limpiar el formulario?')) {
        const form = document.getElementById('iniciarEvaluacionForm');
        form.reset();
        form.classList.remove('was-validated');
        
        // Restablecer fecha actual
        const fechaInput = document.querySelector('input[name="fecha"]');
        if (fechaInput) {
          fechaInput.value = new Date().toISOString().split('T')[0];
        }
      }
    }

    // Función para mostrar alertas
    function mostrarAlerta(mensaje, tipo = 'info') {
      const container = document.getElementById('alertContainer');
      const alertId = 'alert-' + Date.now();
      const iconos = {
        success: 'check-circle',
        danger: 'exclamation-circle',
        warning: 'exclamation-triangle',
        info: 'info-circle'
      };
      
      const html = `
        <div id="${alertId}" class="alert alert-${tipo} alert-dismissible fade show" role="alert">
          <i class="bi bi-${iconos[tipo]} me-2"></i>
          ${mensaje}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      `;
      
      container.insertAdjacentHTML('afterbegin', html);
      
      if (tipo === 'success') {
        setTimeout(() => {
          const alert = document.getElementById(alertId);
          if (alert) alert.remove();
        }, 5000);
      }
    }
  </script>
</body>
</html>
