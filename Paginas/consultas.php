<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Evaluaciones - KMSOLUTIONS S.A.</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../Estilos/styles.css" rel="stylesheet">
</head>
<body class="bg-light">
    
    <!-- Header -->
    <header class="bg-primary text-white py-3 mb-4 shadow">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="h4 mb-0">
                        <i class="bi bi-bar-chart-line me-2"></i>
                        Sistema de Evaluación de Desempeño
                    </h1>
                </div>
                <div class="col-md-6 text-md-end">
                    <nav>
                        <a href="index.php" class="btn btn-outline-light btn-sm me-2">
                            <i class="bi bi-plus-circle me-1"></i>Nueva Evaluación
                        </a>
                        <a href="consultas.php" class="btn btn-light btn-sm">
                            <i class="bi bi-search me-1"></i>Consultar
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        
        <!-- Filtros de Búsqueda -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-funnel me-2"></i>
                    Filtros de Búsqueda
                </h5>
            </div>
            <div class="card-body">
                <form id="filtrosForm" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Colaborador:</label>
                        <input type="text" class="form-control" id="filtro_colaborador" placeholder="Buscar por nombre...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Departamento:</label>
                        <select class="form-select" id="filtro_departamento">
                            <option value="">Cargando departamentos...</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tipo:</label>
                        <select class="form-select" id="filtro_tipo">
                            <option value="">Todos</option>
                            <option value="semestral">Semestral</option>
                            <option value="anual">Anual</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Periodo:</label>
                        <select class="form-select" id="filtro_periodo">
                            <option value="">Todos</option>
                            <option value="si">En prueba</option>
                            <option value="no">Permanente</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn btn-primary w-100" onclick="buscarEvaluaciones()">
                            <i class="bi bi-search me-1"></i>Buscar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Resultados -->
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0 me-3">
                        <i class="bi bi-table me-2"></i>
                        Evaluaciones Registradas
                    </h5>
                    <span class="badge bg-light text-dark" id="contador_resultados">0 evaluaciones</span>
                </div>
                <a href="../excel_export.php?exportar=excel" 
                   class="btn btn-light btn-sm"
                   title="Exportar todas las evaluaciones a Excel"
                   target="_blank">
                    <i class="bi bi-file-earmark-excel me-1"></i>
                    Exportar Excel
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0" id="tablaEvaluaciones">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="bi bi-person me-1"></i>Colaborador</th>
                                <th><i class="bi bi-building me-1"></i>Departamento</th>
                                <th><i class="bi bi-person-badge me-1"></i>Evaluador</th>
                                <th><i class="bi bi-calendar me-1"></i>Fecha</th>
                                <th><i class="bi bi-award me-1"></i>Porcentaje</th>
                                <th><i class="bi bi-speedometer me-1"></i>Nivel</th>
                                <th><i class="bi bi-gear me-1"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="cuerpoTabla">
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-search display-6 d-block mb-2"></i>
                                    Use los filtros para buscar evaluaciones
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal para ver detalles -->
        <div class="modal fade" id="modalDetalles" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-info-circle me-2"></i>
                            Detalles de Evaluación
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="contenidoDetalles">
                        <!-- Contenido dinámico -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal de Confirmación de Eliminación -->
    <div class="modal fade" id="modalEliminar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Confirmar Eliminación
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="bi bi-trash3 display-1 text-danger"></i>
                    </div>
                    <p class="text-center mb-3">
                        ¿Está seguro que desea eliminar la evaluación de <strong id="nombreColaboradorEliminar"></strong>?
                    </p>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>¡Atención!</strong> Esta acción no se puede deshacer. Toda la información de la evaluación se perderá permanentemente.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-danger" onclick="eliminarEvaluacion()">
                        <i class="bi bi-trash me-1"></i>Eliminar Definitivamente
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Función para buscar evaluaciones
        async function buscarEvaluaciones() {
            const filtros = {
                colaborador: document.getElementById('filtro_colaborador').value,
                departamento: document.getElementById('filtro_departamento').value,
                tipo: document.getElementById('filtro_tipo').value,
                periodo: document.getElementById('filtro_periodo').value
            };

            try {
                const response = await fetch('consultar_evaluaciones.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(filtros)
                });
                
                const data = await response.json();
                
                if (data.exito) {
                    mostrarResultados(data.evaluaciones);
                } else {
                    mostrarNotificacion('Error al consultar evaluaciones: ' + data.mensaje, 'error');
                }
                
            } catch (error) {
                console.error('Error:', error);
                mostrarNotificacion('Error de conexión al consultar evaluaciones', 'error');
            }
        }

        function mostrarResultados(evaluaciones) {
            const cuerpoTabla = document.getElementById('cuerpoTabla');
            const contador = document.getElementById('contador_resultados');
            
            contador.textContent = `${evaluaciones.length} evaluaciones`;
            
            if (evaluaciones.length === 0) {
                cuerpoTabla.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-inbox display-6 d-block mb-2"></i>
                            No se encontraron evaluaciones con los filtros aplicados
                        </td>
                    </tr>
                `;
                return;
            }
            
            cuerpoTabla.innerHTML = evaluaciones.map(eval => `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-circle text-primary me-2"></i>
                            <div>
                                <div class="fw-semibold">${eval.colaborador_completo}</div>
                                <small class="text-muted">${eval.colaborador_cargo}</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge bg-secondary">${eval.colaborador_departamento}</span></td>
                    <td>
                        <div>
                            <div class="fw-semibold">${eval.evaluador_completo}</div>
                            <small class="text-muted">${eval.evaluador_cargo}</small>
                        </div>
                    </td>
                    <td>
                        <div>
                            <i class="bi bi-calendar-event me-1"></i>
                            ${new Date(eval.fecha_creacion_evaluacion).toLocaleDateString('es-ES')}
                        </div>
                        <small class="text-muted">
                            ${eval.periodo_prueba === 'si' ? 'Período prueba' : 'Permanente'}
                            | ${eval.tipo_evaluacion}
                        </small>
                    </td>
                    <td>
                        <div class="text-center">
                            <div class="fs-5 fw-bold text-primary">${eval.porcentaje_general || '0.00'}%</div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-${obtenerColorNivel(eval.nivel_desempeno || 'Sin evaluar')}">
                            ${eval.nivel_desempeno || 'Sin evaluar'}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-outline-info" onclick="verDetalles(${eval.id_evaluacion})" title="Ver detalles">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="confirmarEliminar(${eval.id_evaluacion}, '${eval.colaborador_completo}')" title="Eliminar evaluación">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function obtenerColorNivel(nivel) {
            const colores = {
                'Excelente': 'success',
                'Muy Bueno': 'primary', 
                'Bueno': 'info',
                'Regular': 'warning',
                'Deficiente': 'danger',
                'Sin evaluar': 'secondary'
            };
            return colores[nivel] || 'secondary';
        }

        async function verDetalles(id) {
            try {
                const response = await fetch('consultar_evaluaciones.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ detalle: id })
                });
                
                const data = await response.json();
                
                if (data.exito && data.evaluacion) {
                    mostrarDetalleModal(data.evaluacion);
                } else {
                    mostrarNotificacion('Error al cargar detalles: ' + data.mensaje, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarNotificacion('Error de conexión al cargar detalles', 'error');
            }
        }
        
        function mostrarDetalleModal(evaluacion) {
            const contenido = document.getElementById('contenidoDetalles');
            contenido.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary"><i class="bi bi-person me-1"></i>Colaborador</h6>
                        <p><strong>${evaluacion.colaborador_completo}</strong><br>
                           <small class="text-muted">${evaluacion.colaborador_cargo} - ${evaluacion.colaborador_departamento}</small><br>
                           <small class="text-muted"><i class="bi bi-envelope me-1"></i>${evaluacion.colaborador_email || 'Sin email'}</small></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success"><i class="bi bi-person-badge me-1"></i>Evaluador</h6>
                        <p><strong>${evaluacion.evaluador_completo}</strong><br>
                           <small class="text-muted">${evaluacion.evaluador_cargo} - ${evaluacion.evaluador_departamento}</small><br>
                           <small class="text-muted"><i class="bi bi-envelope me-1"></i>${evaluacion.evaluador_email || 'Sin email'}</small></p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <h6><i class="bi bi-briefcase me-1"></i>Conducta Laboral</h6>
                        <div class="progress mb-2">
                            <div class="progress-bar bg-info" style="width: ${evaluacion.conducta_laboral_pct || 0}%"></div>
                        </div>
                        <small>${evaluacion.conducta_laboral_pct || 0}%</small>
                    </div>
                    <div class="col-md-3">
                        <h6><i class="bi bi-graph-up me-1"></i>Productividad</h6>
                        <div class="progress mb-2">
                            <div class="progress-bar bg-success" style="width: ${evaluacion.productividad_pct || 0}%"></div>
                        </div>
                        <small>${evaluacion.productividad_pct || 0}%</small>
                    </div>
                    <div class="col-md-3">
                        <h6><i class="bi bi-star me-1"></i>Competencias</h6>
                        <div class="progress mb-2">
                            <div class="progress-bar bg-warning" style="width: ${evaluacion.competencias_pct || 0}%"></div>
                        </div>
                        <small>${evaluacion.competencias_pct || 0}%</small>
                    </div>
                    <div class="col-md-3">
                        <h6><i class="bi bi-plus-circle me-1"></i>Otros Factores</h6>
                        <div class="progress mb-2">
                            <div class="progress-bar bg-secondary" style="width: ${evaluacion.otros_factores_pct || 0}%"></div>
                        </div>
                        <small>${evaluacion.otros_factores_pct || 0}%</small>
                    </div>
                </div>
                <hr>
                <div class="text-center">
                    <h5>Resultado General</h5>
                    <div class="d-inline-block p-3 rounded bg-light">
                        <div class="display-6 text-primary">${evaluacion.porcentaje_general || 0}%</div>
                        <div class="badge bg-${obtenerColorNivel(evaluacion.nivel_desempeno)} fs-6">${evaluacion.nivel_desempeno}</div>
                    </div>
                </div>
            `;
            
            new bootstrap.Modal(document.getElementById('modalDetalles')).show();
        }
        
        function obtenerColorNivel(nivel) {
            switch (nivel) {
                case 'EXCELENTE': return 'success';
                case 'SATISFACTORIO': return 'info';
                case 'EN DESARROLLO': return 'warning';
                case 'DEFICIENTE': return 'danger';
                default: return 'secondary';
            }
        }
        
        // Variables globales para eliminación
        let idEvaluacionEliminar = null;
        
        function confirmarEliminar(id, nombreColaborador) {
            idEvaluacionEliminar = id;
            document.getElementById('nombreColaboradorEliminar').textContent = nombreColaborador;
            new bootstrap.Modal(document.getElementById('modalEliminar')).show();
        }
        
        async function eliminarEvaluacion() {
            if (!idEvaluacionEliminar) {
                alert('Error: No se ha seleccionado ninguna evaluación');
                return;
            }
            
            try {
                const response = await fetch('consultar_evaluaciones.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        eliminar: idEvaluacionEliminar 
                    })
                });
                
                const data = await response.json();
                
                if (data.exito) {
                    // Cerrar modal
                    bootstrap.Modal.getInstance(document.getElementById('modalEliminar')).hide();
                    
                    // Mostrar notificación de éxito
                    mostrarNotificacion('Evaluación eliminada correctamente', 'success');
                    
                    // Recargar la tabla
                    buscarEvaluaciones();
                    
                    // Resetear variable
                    idEvaluacionEliminar = null;
                } else {
                    mostrarNotificacion('Error al eliminar: ' + data.mensaje, 'error');
                }
                
            } catch (error) {
                console.error('Error:', error);
                mostrarNotificacion('Error de conexión al eliminar la evaluación', 'error');
            }
        }
        
        function mostrarNotificacion(mensaje, tipo = 'info') {
            // Crear elemento de notificación
            const notificacion = document.createElement('div');
            notificacion.className = `alert alert-${tipo === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
            notificacion.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            notificacion.innerHTML = `
                <i class="bi bi-${tipo === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            // Agregar al body
            document.body.appendChild(notificacion);
            
            // Auto-eliminar después de 5 segundos
            setTimeout(() => {
                if (notificacion.parentNode) {
                    notificacion.remove();
                }
            }, 5000);
        }

        // Función para cargar departamentos
        async function cargarDepartamentos() {
            try {
                const response = await fetch('../catalogos.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'action=obtener_departamentos'
                });
                
                const data = await response.json();
                const select = document.getElementById('filtro_departamento');
                
                if (data.success && data.departamentos) {
                    const options = '<option value="">Todos los departamentos</option>' +
                        data.departamentos.map(dept => 
                            `<option value="${dept.nom_dep}">${dept.nom_dep}</option>`
                        ).join('');
                    select.innerHTML = options;
                } else {
                    select.innerHTML = '<option value="">Error al cargar departamentos</option>';
                }
            } catch (error) {
                console.error('Error cargando departamentos:', error);
                document.getElementById('filtro_departamento').innerHTML = '<option value="">Error de conexión</option>';
            }
        }

        // Cargar departamentos y evaluaciones al iniciar
        document.addEventListener('DOMContentLoaded', function() {
            cargarDepartamentos();
            buscarEvaluaciones();
        });
    </script>
</body>
</html>