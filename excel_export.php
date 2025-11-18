<?php
/**
 * Exportador Simple de Evaluaciones - Para integrar en consultas
 * Genera descarga directa de Excel
 */

require_once 'conexion.php';

// Verificar que se solicite la exportación
if (!isset($_GET['exportar']) || $_GET['exportar'] !== 'excel') {
    header('Location: index.php');
    exit;
}

try {
    $conexion = conectarDB();
    
    if (!$conexion) {
        throw new Exception("Error de conexión a la base de datos");
    }
    
    // Consulta completa de evaluaciones
    $sql = "
    SELECT 
        -- Información general
        ir.id_info_reg AS 'ID Evaluación',
        DATE_FORMAT(ir.fe_crea, '%d/%m/%Y %H:%i') AS 'Fecha Creación',
        DATE_FORMAT(ir.fe_actu, '%d/%m/%Y %H:%i') AS 'Fecha Actualización',
        CASE WHEN ir.activo = 1 THEN 'Activo' ELSE 'Inactivo' END AS 'Estado',
        CASE 
            WHEN ir.periodo_prueba = 'si' THEN 'Sí' 
            WHEN ir.periodo_prueba = 'no' THEN 'No'
            ELSE 'No especificado'
        END AS 'Período Prueba',
        CASE 
            WHEN ir.tipo_evaluacion = 'semestral' THEN 'Semestral'
            WHEN ir.tipo_evaluacion = 'anual' THEN 'Anual'
            ELSE 'No especificado'
        END AS 'Tipo Evaluación',
        
        -- Colaborador
        COALESCE(c.nom_col, 'Sin nombres') AS 'Colaborador Nombres',
        COALESCE(c.apell_col, 'Sin apellidos') AS 'Colaborador Apellidos',
        CONCAT(COALESCE(c.nom_col, ''), ' ', COALESCE(c.apell_col, '')) AS 'Colaborador Completo',
        COALESCE(carg_col.nom_car, 'Sin cargo') AS 'Colaborador Cargo',
        COALESCE(dept_col.nom_dep, 'Sin departamento') AS 'Colaborador Departamento',
        COALESCE(DATE_FORMAT(c.fech_ing_col, '%d/%m/%Y'), 'Sin fecha') AS 'Colaborador Fecha Ingreso',
        COALESCE(NULLIF(TRIM(c.email_col), ''), 'Sin email') AS 'Colaborador Email',
        
        -- Evaluador
        COALESCE(e.nom_eva, 'Sin nombres') AS 'Evaluador Nombres',
        COALESCE(e.apell_eva, 'Sin apellidos') AS 'Evaluador Apellidos',
        CONCAT(COALESCE(e.nom_eva, ''), ' ', COALESCE(e.apell_eva, '')) AS 'Evaluador Completo',
        COALESCE(carg_eva.nom_car, 'Sin cargo') AS 'Evaluador Cargo',
        COALESCE(dept_eva.nom_dep, 'Sin departamento') AS 'Evaluador Departamento',
        COALESCE(NULLIF(TRIM(e.email_eva), ''), 'Sin email') AS 'Evaluador Email',
        
        -- Porcentajes de evaluación
        CONCAT(FORMAT(COALESCE(m.porc_cond_lab, 0), 2), '%') AS 'Conducta Laboral',
        CONCAT(FORMAT(COALESCE(m.porc_prod, 0), 2), '%') AS 'Productividad',
        CONCAT(FORMAT(COALESCE(m.porc_comp, 0), 2), '%') AS 'Competencias Específicas',
        CONCAT(FORMAT(COALESCE(m.porc_otros, 0), 2), '%') AS 'Otros Factores',
        
        -- Promedio general calculado
        CONCAT(FORMAT(
            ROUND(
                (COALESCE(m.porc_cond_lab, 0) + 
                 COALESCE(m.porc_prod, 0) + 
                 COALESCE(m.porc_comp, 0) + 
                 COALESCE(m.porc_otros, 0)) / 4, 2
            ), 2
        ), '%') AS 'Promedio General',
        
        -- Clasificación de desempeño
        CASE 
            WHEN ROUND(
                (COALESCE(m.porc_cond_lab, 0) + 
                 COALESCE(m.porc_prod, 0) + 
                 COALESCE(m.porc_comp, 0) + 
                 COALESCE(m.porc_otros, 0)) / 4, 2
            ) >= 90 THEN 'Excelente'
            WHEN ROUND(
                (COALESCE(m.porc_cond_lab, 0) + 
                 COALESCE(m.porc_prod, 0) + 
                 COALESCE(m.porc_comp, 0) + 
                 COALESCE(m.porc_otros, 0)) / 4, 2
            ) >= 80 THEN 'Muy Bueno'
            WHEN ROUND(
                (COALESCE(m.porc_cond_lab, 0) + 
                 COALESCE(m.porc_prod, 0) + 
                 COALESCE(m.porc_comp, 0) + 
                 COALESCE(m.porc_otros, 0)) / 4, 2
            ) >= 70 THEN 'Bueno'
            WHEN ROUND(
                (COALESCE(m.porc_cond_lab, 0) + 
                 COALESCE(m.porc_prod, 0) + 
                 COALESCE(m.porc_comp, 0) + 
                 COALESCE(m.porc_otros, 0)) / 4, 2
            ) >= 60 THEN 'Regular'
            ELSE 'Necesita Mejora'
        END AS 'Clasificación Desempeño'
        
    FROM info_registro ir
    LEFT JOIN colaborador c ON ir.id_info_reg = c.id_info_reg
    LEFT JOIN evaluador e ON ir.id_info_reg = e.id_info_reg
    LEFT JOIN cargos carg_col ON c.id_cargo = carg_col.id_cargo
    LEFT JOIN departamentos dept_col ON c.id_departamento = dept_col.id_dep
    LEFT JOIN cargos carg_eva ON e.id_cargo = carg_eva.id_cargo
    LEFT JOIN departamentos dept_eva ON e.id_departamento = dept_eva.id_dep
    LEFT JOIN matriz m ON ir.id_info_reg = m.id_info_reg
    
    WHERE ir.activo = 1
      AND c.id_colaborador IS NOT NULL 
      AND e.id_evaluador IS NOT NULL
    ORDER BY ir.fe_crea DESC
    ";
    
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $evaluaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($evaluaciones)) {
        throw new Exception("No hay evaluaciones para exportar");
    }
    
    // Configurar headers para descarga Excel
    $fecha_archivo = date('Y-m-d_H-i-s');
    $nombre_archivo = "Evaluaciones_KMS_" . $fecha_archivo . ".csv";
    
    // Limpiar cualquier output previo
    if (ob_get_level()) {
        ob_end_clean();
    }
    
    header('Content-Type: text/csv; charset=UTF-8');
    header("Content-Disposition: attachment; filename=\"{$nombre_archivo}\"");
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Expires: 0');
    
    // Abrir salida para CSV
    $output = fopen('php://output', 'w');
    
    // BOM UTF-8 para Excel
    fputs($output, "\xEF\xBB\xBF");
    
    // Escribir encabezados (usar las claves del primer registro)
    if (!empty($evaluaciones)) {
        $encabezados = array_keys($evaluaciones[0]);
        fputcsv($output, $encabezados, ';');
    }
    
    // Escribir datos
    foreach ($evaluaciones as $evaluacion) {
        // Convertir valores NULL a cadena vacía y limpiar caracteres especiales
        $fila_limpia = array_map(function($valor) {
            if (is_null($valor)) {
                return '';
            }
            // Convertir a string y limpiar caracteres problemáticos
            $valor_limpio = (string)$valor;
            $valor_limpio = str_replace(["\r", "\n", "\t"], ' ', $valor_limpio);
            return $valor_limpio;
        }, $evaluacion);
        
        fputcsv($output, $fila_limpia, ';');
    }
    
    // Cerrar archivo
    fclose($output);
    
    // Log de exportación
    error_log("Exportación Excel: {$nombre_archivo} - " . count($evaluaciones) . " registros - Usuario IP: " . $_SERVER['REMOTE_ADDR']);
    
} catch (Exception $e) {
    // En caso de error, mostrar página de error
    http_response_code(500);
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Error en Exportación</title>
        <style>
            body { 
                font-family: Arial, sans-serif; 
                background: #f8f9fa; 
                padding: 50px; 
                text-align: center; 
            }
            .error-container { 
                background: white; 
                padding: 40px; 
                border-radius: 10px; 
                box-shadow: 0 4px 6px rgba(0,0,0,0.1); 
                max-width: 500px; 
                margin: 0 auto; 
            }
            .error-icon { font-size: 64px; color: #dc3545; margin-bottom: 20px; }
            h2 { color: #dc3545; margin-bottom: 20px; }
            .mensaje { color: #666; margin-bottom: 30px; }
            .btn { 
                background: #007bff; 
                color: white; 
                padding: 12px 24px; 
                text-decoration: none; 
                border-radius: 5px; 
                display: inline-block; 
            }
            .btn:hover { background: #0056b3; }
        </style>
    </head>
    <body>
        <div class='error-container'>
            <div class='error-icon'>⚠️</div>
            <h2>Error en la Exportación</h2>
            <div class='mensaje'>
                No se pudo generar el archivo Excel.<br>
                <strong>Motivo:</strong> " . htmlspecialchars($e->getMessage()) . "
            </div>
            <a href='javascript:history.back()' class='btn'>← Volver a Consultas</a>
        </div>
    </body>
    </html>";
}

?>