// KMSOLUTIONS S.A. - Frontend JavaScript v2.0
class EvaluationApp {
    constructor() {
        this.config = {
            autoSaveInterval: 30000,
            version: '2.0'
        };
        
        // Score mapping and areas definition
        this.scoreMap = {
            1: { value: 1, level: 'Deficiente', class: 'bg-danger' },
            2: { value: 2, level: 'Deficiente', class: 'bg-danger' },
            3: { value: 3, level: 'Deficiente', class: 'bg-danger' },
            4: { value: 4, level: 'Deficiente', class: 'bg-danger' },
            5: { value: 5, level: 'Deficiente', class: 'bg-danger' },
            6: { value: 6, level: 'Regular', class: 'bg-warning' },
            7: { value: 7, level: 'Bueno', class: 'bg-info' },
            8: { value: 8, level: 'Muy bueno', class: 'bg-primary' },
            9: { value: 9, level: 'Excelente', class: 'bg-success' },
            10: { value: 10, level: 'Excelente', class: 'bg-success' }
        };
        
        this.areas = {
            conducta: { fields: ['c1', 'c2', 'c3', 'c4', 'c5'], maxScore: 50 },
            productividad: { fields: ['p1', 'p2', 'p3', 'p4', 'p5', 'p6'], maxScore: 60 },
            competencias: { fields: ['ce1', 'ce2', 'ce3', 'ce4', 'ce5', 'ce6', 'ce7'], maxScore: 70 },
            otros: { fields: ['o1', 'o2'], maxScore: 20 }
        };
        
        this.state = {
            currentEvaluationId: null,
            isSubmitting: false,
            isDirty: false,
            apiStatus: 'connected'
        };
        
        this.init();
    }
    
    init() {
        console.log('🚀 KMSOLUTIONS Evaluation App v2.0');
        this.displayCurrentDate();
        this.setupEventListeners();
        this.initializeForm();
        this.setupFormValidation();
        this.setupAutoSave();
        this.loadSavedData();
    }
    
    displayCurrentDate() {
        const el = document.getElementById('currentDate');
        if (el) {
            const now = new Date();
            el.textContent = now.toLocaleDateString('es-ES', {
                year: 'numeric', month: 'long', day: 'numeric'
            });
        }
        
        // Also set acceptance date for terms and conditions
        const acceptanceEl = document.getElementById('acceptanceDate');
        if (acceptanceEl) {
            const now = new Date();
            acceptanceEl.textContent = now.toLocaleDateString('es-ES', {
                year: 'numeric', month: 'long', day: 'numeric',
                hour: '2-digit', minute: '2-digit'
            });
        }
    }
    
    setupEventListeners() {
        console.log('=== Setting up event listeners ===');
        const form = document.getElementById('evaluationForm');
        console.log('Form found:', form);
        
        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleSubmit();
            });
        }
        
        // Mark dirty on changes
        const inputs = document.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('input', () => this.markDirty());
            input.addEventListener('change', () => this.markDirty());
        });
        
        // Setup evaluation matrix listeners
        this.setupEvaluationListeners();
        
        // Global functions
        window.clearForm = () => this.clearForm();
        window.showPreview = () => this.showPreview();
        
        // Prevent navigation
        window.addEventListener('beforeunload', (e) => {
            if (this.state.isDirty) {
                e.preventDefault();
                e.returnValue = '¿Salir sin guardar?';
                return e.returnValue;
            }
        });
    }
    
    initializeForm() {
        const fechaInput = document.querySelector('input[name="fecha"]');
        if (fechaInput && !fechaInput.value) {
            fechaInput.value = new Date().toISOString().split('T')[0];
        }
    }
    
    setupFormValidation() {
        const form = document.getElementById('evaluationForm');
        if (!form) return;
        
        form.classList.add('needs-validation');
        
        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            field.addEventListener('blur', () => this.validateField(field));
            field.addEventListener('input', () => this.clearFieldError(field));
        });
    }
    
    setupAutoSave() {
        if (this.config.autoSaveInterval > 0) {
            setInterval(() => {
                if (this.state.isDirty && !this.state.isSubmitting) {
                    this.autoSave();
                }
            }, this.config.autoSaveInterval);
        }
    }
    
    async handleSubmit() {
        if (this.state.isSubmitting) return;
        
        const form = document.getElementById('evaluationForm');
        if (!form) return;
        
        if (!this.validateForm()) {
            this.showAlert('Complete todos los campos requeridos.', 'danger');
            return;
        }
        
        this.setSubmitting(true);
        
        try {
            const formData = new FormData(form);
            
            // Solo enviar datos básicos y porcentajes (tabla matriz simplificada)
            const cleanFormData = this.createCleanFormData(formData);
            this.addCalculatedPercentages(cleanFormData);
            
            const response = await fetch('../guardar.php', {
                method: 'POST',
                body: cleanFormData
            });
            
            const result = await response.json();
            
            if (result.exito) {
                this.clearDirty();
                this.showAlert(result.mensaje, 'success');
                this.clearSavedData();
                
                // Reset form after successful submission
                setTimeout(() => {
                    form.reset();
                    form.classList.remove('was-validated');
                    this.initializeForm();
                }, 2000);
                
            } else {
                this.showAlert(result.mensaje, 'danger');
            }
            
        } catch (error) {
            this.showAlert(`Error de conexión: ${error.message}`, 'danger');
        } finally {
            this.setSubmitting(false);
        }
    }
    
    showAlert(message, type = 'info') {
        const container = document.getElementById('alertContainer');
        if (!container) {
            console.log(`${type}: ${message}`);
            return;
        }
        
        const alertId = 'alert-' + Date.now();
        const icons = {
            success: 'check-circle',
            danger: 'exclamation-circle',
            warning: 'exclamation-triangle',
            info: 'info-circle'
        };
        
        const html = `
            <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="bi bi-${icons[type]} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        container.insertAdjacentHTML('afterbegin', html);
        
        if (type === 'success') {
            setTimeout(() => {
                const alert = document.getElementById(alertId);
                if (alert) alert.remove();
            }, 5000);
        }
    }
    
    clearForm() {
        if (this.state.isDirty) {
            if (!confirm('¿Limpiar formulario? Se perderán los datos.')) {
                return;
            }
        }
        
        const form = document.getElementById('evaluationForm');
        if (form) {
            form.reset();
            form.classList.remove('was-validated');
            
            const fields = form.querySelectorAll('.is-valid, .is-invalid');
            fields.forEach(field => {
                field.classList.remove('is-valid', 'is-invalid');
            });
            
            this.clearDirty();
            this.clearSavedData();
            this.initializeForm();
            this.showAlert('Formulario limpiado.', 'info');
        }
    }
    
    showPreview() {
        const data = this.collectFormData();
        
        const preview = window.open('', '_blank', 'width=800,height=600');
        preview.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Vista Previa - Datos del Colaborador</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    .preview-section { margin-bottom: 2rem; }
                    .preview-label { font-weight: bold; color: #dc3545; }
                    .preview-value { margin-bottom: 0.5rem; }
                </style>
            </head>
            <body class="p-4">
                <div class="container">
                    <h2 class="text-danger mb-4">
                        <i class="bi bi-eye me-2"></i>
                        Vista Previa - Datos del Colaborador
                    </h2>
                    
                    <div class="preview-section">
                        <h4 class="preview-label">Información General</h4>
                        <div class="preview-value"><strong>Fecha:</strong> ${data.fecha || 'No especificada'}</div>
                    </div>
                    
                    <div class="preview-section">
                        <h4 class="preview-label">Datos del Colaborador</h4>
                        <div class="preview-value"><strong>Nombres:</strong> ${data.colaborador_nombres || 'No especificado'}</div>
                        <div class="preview-value"><strong>Apellidos:</strong> ${data.colaborador_apellidos || 'No especificado'}</div>
                        <div class="preview-value"><strong>Cargo:</strong> ${data.colaborador_cargo || 'No especificado'}</div>
                        <div class="preview-value"><strong>Departamento:</strong> ${data.colaborador_departamento || 'No especificado'}</div>
                        <div class="preview-value"><strong>Fecha de Ingreso:</strong> ${data.colaborador_fecha_ingreso || 'No especificada'}</div>
                        <div class="preview-value"><strong>Email:</strong> ${data.colaborador_email || 'No especificado'}</div>
                    </div>
                    
                    <div class="preview-section">
                        <h4 class="preview-label">Datos del Evaluador</h4>
                        <div class="preview-value"><strong>Nombre:</strong> ${data.evaluador_nombre || 'No especificado'}</div>
                        <div class="preview-value"><strong>Cargo:</strong> ${data.evaluador_cargo || 'No especificado'}</div>
                        <div class="preview-value"><strong>Departamento:</strong> ${data.evaluador_departamento || 'No especificado'}</div>
                        <div class="preview-value"><strong>Email:</strong> ${data.evaluador_email || 'No especificado'}</div>
                    </div>
                    
                    <div class="preview-section">
                        <h4 class="preview-label">Período de Evaluación</h4>
                        <div class="preview-value"><strong>Desde:</strong> ${data.periodo_desde || 'No especificado'}</div>
                        <div class="preview-value"><strong>Hasta:</strong> ${data.periodo_hasta || 'No especificado'}</div>
                        <div class="preview-value"><strong>Período de Prueba:</strong> ${data.periodo_prueba || 'No especificado'}</div>
                        <div class="preview-value"><strong>Tipo de Evaluación:</strong> ${data.tipo_eval || 'No especificado'}</div>
                    </div>
                    
                    <div class="mt-4">
                        <button class="btn btn-secondary" onclick="window.close()">
                            <i class="bi bi-x me-1"></i>
                            Cerrar
                        </button>
                        <button class="btn btn-primary" onclick="window.print()">
                            <i class="bi bi-printer me-1"></i>
                            Imprimir
                        </button>
                    </div>
                </div>
            </body>
            </html>
        `);
        preview.document.close();
    }
    
    // State methods
    markDirty() {
        if (!this.state.isDirty) {
            this.state.isDirty = true;
        }
    }
    
    clearDirty() {
        this.state.isDirty = false;
    }
    
    setSubmitting(submitting) {
        this.state.isSubmitting = submitting;
        const form = document.getElementById('evaluationForm');
        const btn = form?.querySelector('button[type="submit"]');
        
        if (btn) {
            btn.disabled = submitting;
            btn.innerHTML = submitting ? 
                '<i class="bi bi-hourglass-split me-2"></i>Guardando...' :
                '<i class="bi bi-save me-2"></i>Guardar Colaborador';
        }
    }
    
    // Validation
    validateForm() {
        const form = document.getElementById('evaluationForm');
        if (!form) return false;
        
        let valid = true;
        const required = form.querySelectorAll('[required]');
        
        required.forEach(field => {
            if (!field.value.trim()) {
                valid = false;
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
                field.classList.add('is-valid');
            }
        });
        
        form.classList.add('was-validated');
        return valid;
    }
    
    validateField(field) {
        const valid = field.value.trim() !== '';
        
        if (valid) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
        } else {
            field.classList.remove('is-valid');
            field.classList.add('is-invalid');
        }
        
        return valid;
    }
    
    clearFieldError(field) {
        field.classList.remove('is-invalid', 'is-valid');
    }
    
    // Data collection
    collectFormData() {
        const form = document.getElementById('evaluationForm');
        const formData = new FormData(form);
        const data = {};
        
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }
        
        // Handle checkboxes
        const checkboxes = form.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(cb => {
            data[cb.name] = cb.checked;
        });
        
        // Handle radio buttons
        const radios = form.querySelectorAll('input[type="radio"]:checked');
        radios.forEach(radio => {
            data[radio.name] = radio.value;
        });
        
        return data;
    }
    
    // Auto-save
    autoSave() {
        const data = this.collectFormData();
        try {
            localStorage.setItem('kms_evaluation_autosave', JSON.stringify({
                data: data,
                timestamp: Date.now()
            }));
        } catch (error) {
            console.warn('Auto-save failed:', error);
        }
    }
    
    loadSavedData() {
        try {
            const saved = localStorage.getItem('kms_evaluation_autosave');
            if (saved) {
                const { data, timestamp } = JSON.parse(saved);
                const hours = (Date.now() - timestamp) / (1000 * 60 * 60);
                
                if (hours < 24) {
                    if (confirm('¿Restaurar datos guardados anteriormente?')) {
                        this.populateForm(data);
                        this.showAlert('Datos restaurados automáticamente.', 'info');
                    }
                } else {
                    this.clearSavedData();
                }
            }
        } catch (error) {
            this.clearSavedData();
        }
    }
    
    clearSavedData() {
        try {
            localStorage.removeItem('kms_evaluation_autosave');
        } catch (error) {
            console.warn('Clear saved data failed:', error);
        }
    }
    
    populateForm(data) {
        const form = document.getElementById('evaluationForm');
        if (!form) return;
        
        Object.keys(data).forEach(key => {
            const field = form.querySelector(`[name="${key}"]`);
            if (field) {
                if (field.type === 'checkbox') {
                    field.checked = data[key];
                } else if (field.type === 'radio') {
                    if (field.value === data[key]) {
                        field.checked = true;
                    }
                } else {
                    field.value = data[key];
                }
            }
        });
        
        this.markDirty();
    }
    
    // ===== EVALUATION MATRIX FUNCTIONS =====
    
    setupEvaluationListeners() {
        console.log('Setting up evaluation matrix listeners...');
        
        // Add listeners to all radio buttons in evaluation matrix
        Object.keys(this.areas).forEach(areaName => {
            const area = this.areas[areaName];
            area.fields.forEach(fieldName => {
                for (let i = 1; i <= 10; i++) {
                    const radio = document.getElementById(`${fieldName}_${i}`);
                    if (radio) {
                        radio.addEventListener('change', (e) => {
                            if (e.target.checked) {
                                this.updateScoreBadge(fieldName, parseInt(e.target.value));
                                this.calculateAreaSubtotal(areaName);
                            }
                        });
                    }
                }
            });
        });
    }
    
    updateScoreBadge(fieldName, score) {
        const badge = document.querySelector(`[name="level_${fieldName}"]`);
        if (badge && this.scoreMap[score]) {
            const scoreInfo = this.scoreMap[score];
            badge.textContent = scoreInfo.level;
            badge.className = `badge score-badge small ${scoreInfo.class} text-white`;
        }
    }
    
    calculateAreaSubtotal(areaName) {
        const area = this.areas[areaName];
        if (!area) return;
        
        let totalScore = 0;
        let completedFields = 0;
        
        area.fields.forEach(fieldName => {
            const checkedRadio = document.querySelector(`input[name="${fieldName}"]:checked`);
            if (checkedRadio) {
                totalScore += parseInt(checkedRadio.value);
                completedFields++;
            }
        });
        
        // Update subtotal display
        const subtotalEl = document.getElementById(`subtotal_${areaName}`);
        const percentEl = document.getElementById(`percent_${areaName}`);
        
        if (subtotalEl) {
            subtotalEl.textContent = totalScore;
        }
        
        if (percentEl) {
            const percentage = area.maxScore > 0 ? ((totalScore / area.maxScore) * 100).toFixed(1) : 0;
            percentEl.textContent = `${percentage}%`;
        }
        
        // Update overall totals
        this.calculateOverallTotal();
    }
    
    calculateOverallTotal() {
        let grandTotal = 0;
        let maxPossible = 0;
        
        // Calculate area totals
        const areaTotals = {};
        const areaPercentages = {};
        
        Object.keys(this.areas).forEach(areaName => {
            const area = this.areas[areaName];
            const subtotalEl = document.getElementById(`subtotal_${areaName}`);
            
            let areaScore = 0;
            if (subtotalEl) {
                areaScore = parseInt(subtotalEl.textContent) || 0;
            }
            
            areaTotals[areaName] = areaScore;
            areaPercentages[areaName] = area.maxScore > 0 ? ((areaScore / area.maxScore) * 100).toFixed(1) : '0.0';
            
            grandTotal += areaScore;
            maxPossible += area.maxScore;
        });
        
        // Update individual area displays in summary section
        const areaDisplays = {
            'conducta': {
                subtotal: 'subtotal-conducta-display',
                percent: 'percent-conducta-display'
            },
            'productividad': {
                subtotal: 'subtotal-productividad-display', 
                percent: 'percent-productividad-display'
            },
            'competencias': {
                subtotal: 'subtotal-competencias-display',
                percent: 'percent-competencias-display'
            },
            'otros': {
                subtotal: 'subtotal-otros-display',
                percent: 'percent-otros-display'
            }
        };
        
        Object.keys(areaDisplays).forEach(areaName => {
            const displays = areaDisplays[areaName];
            const area = this.areas[areaName];
            
            if (area && displays) {
                const subtotalEl = document.getElementById(displays.subtotal);
                const percentEl = document.getElementById(displays.percent);
                
                if (subtotalEl) {
                    subtotalEl.textContent = `${areaTotals[areaName] || 0}/${area.maxScore}`;
                }
                if (percentEl) {
                    percentEl.textContent = `${areaPercentages[areaName]}%`;
                }
            }
        });
        
        // Update grand total displays - Final Score Summary
        const finalScoreEl = document.getElementById('final-score-display');
        const finalPercentageEl = document.getElementById('final-percentage-display');
        const performanceLevelEl = document.getElementById('performance-level-display');
        
        if (finalScoreEl) {
            finalScoreEl.textContent = `${grandTotal}/${maxPossible}`;
        }
        
        if (finalPercentageEl && maxPossible > 0) {
            const percentage = ((grandTotal / maxPossible) * 100).toFixed(1);
            finalPercentageEl.textContent = `${percentage}%`;
            
            // Update performance level and styling
            if (performanceLevelEl) {
                let nivel = 'Sin evaluar';
                let color = '#6c757d';
                
                if (percentage >= 90) {
                    nivel = 'Excelente';
                    color = '#28a745';
                } else if (percentage >= 80) {
                    nivel = 'Muy Bueno';
                    color = '#17a2b8';
                } else if (percentage >= 70) {
                    nivel = 'Bueno';
                    color = '#ffc107';
                } else if (percentage >= 60) {
                    nivel = 'Regular';
                    color = '#fd7e14';
                } else if (percentage > 0) {
                    nivel = 'Deficiente';
                    color = '#dc3545';
                }
                
                performanceLevelEl.textContent = nivel;
                performanceLevelEl.style.color = color;
            }
            
            // Update total score container background
            const totalContainer = document.getElementById('total-score-container');
            if (totalContainer) {
                let gradient = 'linear-gradient(135deg, #28a745, #20c997)'; // Default green
                
                if (percentage < 70) {
                    gradient = 'linear-gradient(135deg, #dc3545, #fd7e14)'; // Red to orange
                } else if (percentage < 80) {
                    gradient = 'linear-gradient(135deg, #ffc107, #fd7e14)'; // Yellow to orange
                } else if (percentage < 90) {
                    gradient = 'linear-gradient(135deg, #17a2b8, #20c997)'; // Blue to teal
                }
                
                totalContainer.style.background = gradient;
            }
            
            // Show/hide improvement plan
            this.updateImprovementPlan(percentage);
        }
        
        // Update legacy displays if they exist
        const totalPuntosEl = document.getElementById('gran_total_puntos');
        const totalPorcentajeEl = document.getElementById('gran_total_porcentaje');
        
        if (totalPuntosEl) {
            totalPuntosEl.textContent = grandTotal;
        }
        if (totalPorcentajeEl && maxPossible > 0) {
            const percentage = ((grandTotal / maxPossible) * 100).toFixed(1);
            totalPorcentajeEl.textContent = `${percentage}%`;
        }
        
        console.log(`Overall Total: ${grandTotal}/${maxPossible} (${((grandTotal/maxPossible)*100).toFixed(1)}%)`);
    }
    
    updateImprovementPlan(percentage) {
        const improvementSection = document.getElementById('improvement-plan-section');
        
        if (improvementSection) {
            if (percentage > 0 && percentage < 70) {
                improvementSection.style.display = 'block';
            } else {
                improvementSection.style.display = 'none';
            }
        }
    }
    
    createCleanFormData(originalFormData) {
        // Solo enviar datos básicos requeridos + porcentajes (tabla matriz)
        const cleanData = new FormData();
        
        // Datos básicos del colaborador y evaluador (requeridos)
        const requiredFields = [
            'nombres', 'apellidos', 'cargo', 'departamento', 'fecha_ingreso', 'email',
            'evaluador_nombres', 'evaluador_apellidos', 'eval_cargo', 'eval_departamento', 'evaluador_email',
            'periodo_prueba', 'tipo_evaluacion', 'fecha'
        ];
        
        requiredFields.forEach(field => {
            if (originalFormData.has(field)) {
                cleanData.append(field, originalFormData.get(field));
            }
        });
        
        console.log('Datos limpios preparados para envío (solo básicos + porcentajes)');
        return cleanData;
    }

    addCalculatedPercentages(formData) {
        // Calcular y agregar porcentajes por área
        Object.keys(this.areas).forEach(areaName => {
            const area = this.areas[areaName];
            let totalScore = 0;
            let completedFields = 0;
            
            area.fields.forEach(fieldName => {
                const checkedRadio = document.querySelector(`input[name="${fieldName}"]:checked`);
                if (checkedRadio) {
                    totalScore += parseInt(checkedRadio.value);
                    completedFields++;
                }
            });
            
            // Calcular porcentaje para esta área
            const percentage = area.maxScore > 0 ? ((totalScore / area.maxScore) * 100).toFixed(2) : 0;
            
            // Mapear nombres de áreas a nombres de campos en PHP
            const fieldMappings = {
                'conducta': 'porc_conducta_laboral',
                'productividad': 'porc_productividad', 
                'competencias': 'porc_competencias',
                'otros': 'porc_otros_factores'
            };
            
            if (fieldMappings[areaName]) {
                formData.append(fieldMappings[areaName], percentage);
                console.log(`${areaName}: ${totalScore}/${area.maxScore} = ${percentage}%`);
            }
        });
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    const app = new EvaluationApp();
    window.app = app;
    console.log('EvaluationApp initialized successfully!');
});