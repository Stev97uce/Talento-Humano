<?php
/**
 * Configuración y funciones para envío de correos - VERSIÓN ACTUALIZADA
 * Servidor SMTP: mx1.kmsolutionsec.com:587 
 */

// Configuración del servidor de correo SMTP
class ConfigCorreo {
    // Configuración específica del servidor KM Solutions EC
    const SMTP_HOST = 'mx1.kmsolutionsec.com';      // Servidor de correo principal
    const SMTP_HOST_IP = '192.168.19.108';          // IP del servidor (backup)
    const SMTP_PORT = 587;                          // Puerto SMTP correcto (no 8422=SSH)
    const SMTP_PORT_ALT = 8422;                     // Puerto SSH - no usar para SMTP
    const SMTP_SECURE = 'tls';                      // Encriptación TLS
    const DOMAIN = '@kmsolutionsec.com';
    
    // Cuenta del sistema para envío automático
    const SISTEMA_EMAIL = 'evaluaciones@kmsolutionsec.com';
    const SISTEMA_PASSWORD = 'Kms.2025';
    const SISTEMA_NOMBRE = 'Sistema de Evaluaciones - KM Solutions EC';
}

/**
 * Clase SMTP directa para envío confiable usando puerto 587
 */
class SMTPDirecto {
    private $socket;
    
    public function enviar($para, $asunto, $mensaje) {
        try {
            if (!$this->conectar()) return false;
            if (!$this->autenticar()) return false;
            if (!$this->enviarMensaje($para, $asunto, $mensaje)) return false;
            
            $this->desconectar();
            return true;
            
        } catch (Exception $e) {
            error_log("SMTP Error: " . $e->getMessage());
            return false;
        }
    }
    
    private function conectar() {
        $this->socket = @fsockopen(ConfigCorreo::SMTP_HOST, ConfigCorreo::SMTP_PORT, $errno, $errstr, 10);
        
        if (!$this->socket) {
            error_log("Error conectando SMTP: {$errno} - {$errstr}");
            return false;
        }
        
        stream_set_timeout($this->socket, 10);
        
        $respuesta = $this->leerRespuesta();
        if (!str_starts_with($respuesta, '220')) {
            error_log("Respuesta SMTP inesperada: " . trim($respuesta));
            return false;
        }
        
        // EHLO
        $this->enviarComando("EHLO " . gethostname());
        $respuesta = $this->leerRespuesta(true);
        if (!str_starts_with($respuesta, '250')) {
            error_log("Error en EHLO: " . trim($respuesta));
            return false;
        }
        
        // STARTTLS si está disponible
        if (str_contains($respuesta, 'STARTTLS')) {
            $this->enviarComando("STARTTLS");
            $respuesta_tls = $this->leerRespuesta();
            
            if (str_starts_with($respuesta_tls, '220')) {
                if (stream_socket_enable_crypto($this->socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                    // Nuevo EHLO después de TLS
                    $this->enviarComando("EHLO " . gethostname());
                    $this->leerRespuesta(true);
                } else {
                    error_log("No se pudo activar cifrado TLS");
                    return false;
                }
            }
        }
        
        return true;
    }
    
    private function autenticar() {
        $this->enviarComando("AUTH LOGIN");
        $respuesta = $this->leerRespuesta();
        if (!str_starts_with($respuesta, '334')) {
            error_log("Error en AUTH LOGIN: " . trim($respuesta));
            return false;
        }
        
        // Usuario
        $this->enviarComando(base64_encode(ConfigCorreo::SISTEMA_EMAIL));
        $respuesta = $this->leerRespuesta();
        if (!str_starts_with($respuesta, '334')) {
            error_log("Error con usuario: " . trim($respuesta));
            return false;
        }
        
        // Contraseña
        $this->enviarComando(base64_encode(ConfigCorreo::SISTEMA_PASSWORD));
        $respuesta = $this->leerRespuesta();
        
        if (!str_starts_with($respuesta, '235')) {
            error_log("Fallo de autenticación: " . trim($respuesta));
            return false;
        }
        
        return true;
    }
    
    private function enviarMensaje($para, $asunto, $mensaje) {
        // MAIL FROM
        $this->enviarComando("MAIL FROM: <" . ConfigCorreo::SISTEMA_EMAIL . ">");
        $respuesta = $this->leerRespuesta();
        if (!str_starts_with($respuesta, '250')) {
            error_log("Error en MAIL FROM: " . trim($respuesta));
            return false;
        }
        
        // RCPT TO
        $this->enviarComando("RCPT TO: <{$para}>");
        $respuesta = $this->leerRespuesta();
        if (!str_starts_with($respuesta, '250')) {
            error_log("Error en RCPT TO: " . trim($respuesta));
            return false;
        }
        
        // DATA
        $this->enviarComando("DATA");
        $respuesta = $this->leerRespuesta();
        if (!str_starts_with($respuesta, '354')) {
            error_log("Error en DATA: " . trim($respuesta));
            return false;
        }
        
        // Mensaje completo
        $mensaje_completo = $this->construirMensaje($para, $asunto, $mensaje);
        fwrite($this->socket, $mensaje_completo . "\r\n.\r\n");
        
        $respuesta = $this->leerRespuesta();
        if (!str_starts_with($respuesta, '250')) {
            error_log("Mensaje rechazado: " . trim($respuesta));
            return false;
        }
        
        return true;
    }
    
    private function construirMensaje($para, $asunto, $mensaje) {
        $headers = [
            "From: " . ConfigCorreo::SISTEMA_NOMBRE . " <" . ConfigCorreo::SISTEMA_EMAIL . ">",
            "To: {$para}",
            "Subject: {$asunto}",
            "Date: " . date('r'),
            "Message-ID: <" . uniqid() . "@kmsolutionsec.com>",
            "MIME-Version: 1.0",
            "Content-Type: text/html; charset=UTF-8",
            "Content-Transfer-Encoding: 8bit"
        ];
        
        return implode("\r\n", $headers) . "\r\n\r\n" . $mensaje;
    }
    
    private function enviarComando($comando) {
        fwrite($this->socket, $comando . "\r\n");
    }
    
    private function leerRespuesta($multilinea = false) {
        $respuesta = '';
        
        do {
            $linea = fgets($this->socket, 1024);
            $respuesta .= $linea;
            
            if (!$multilinea || (strlen($linea) >= 4 && $linea[3] != '-')) {
                break;
            }
        } while ($linea);
        
        return $respuesta;
    }
    
    private function desconectar() {
        if ($this->socket) {
            $this->enviarComando("QUIT");
            $this->leerRespuesta();
            fclose($this->socket);
        }
    }
}

/**
 * Envía notificación al jefe inmediato sobre nueva evaluación
 */
function enviarNotificacionJefe($datosColaborador, $datosEvaluador) {
    try {
        // Completar email del evaluador si no tiene dominio
        $emailJefe = $datosEvaluador['email_eva'];
        if (!str_contains($emailJefe, '@')) {
            $emailJefe .= ConfigCorreo::DOMAIN;
        }
        
        $asunto = "Nueva evaluación asignada - " . $datosColaborador['nom_col'] . " " . $datosColaborador['apell_col'];
        
        $mensaje = generarMensajeNotificacion($datosColaborador, $datosEvaluador);
        
        return enviarCorreo($emailJefe, $asunto, $mensaje);
        
    } catch (Exception $e) {
        error_log("Error enviando notificación: " . $e->getMessage());
        return false;
    }
}

/**
 * Genera el mensaje HTML de notificación
 */
function generarMensajeNotificacion($colaborador, $evaluador) {
    $html = "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Nueva Evaluación Asignada</title>
        <style>
            .container { max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; background-color: #a19c9cff; padding: 20px; }
            .header { background: linear-gradient(135deg, #2c5aa0, #1e3a8a); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background-color: white; padding: 30px; border-radius: 0 0 10px 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
            .info-box { background-color: #cdcfcfff; border-left: 4px solid #2c5aa0; padding: 15px; margin: 20px 0; border-radius: 5px; }
            .btn { display: inline-block; background-color: #2c5aa0; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; margin: 10px 0; }
            .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
            h1 { margin: 0; font-size: 28px; }
            h2 { color: #2c5aa0; margin-top: 0; }
            h3 { color: #1e3a8a; }
            h4 { color: #2c5aa0; margin-bottom: 10px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Nueva Evaluación Asignada</h1>
                <p>Sistema de Evaluaciones - KM Solutions EC</p>
            </div>
            
            <div class='content'>
                <h2>Estimado/a {$evaluador['nom_eva']} {$evaluador['apell_eva']},</h2>
                
                <p>Se le ha asignado una nueva evaluación para completar en el sistema.</p>
                
                <div class='info-box'>
                    <h4>Colaborador a Evaluar:</h4>
                    <p><strong>Nombre:</strong> {$colaborador['nom_col']} {$colaborador['apell_col']}</p>
                    " . (isset($colaborador['cargo_col']) ? "<p><strong>Cargo:</strong> {$colaborador['cargo_col']}</p>" : "") . "
                    " . (isset($colaborador['area_col']) ? "<p><strong>Área:</strong> {$colaborador['area_col']}</p>" : "") . "
                    <p><strong>Fecha de Ingreso:</strong> " . (isset($colaborador['fech_ing_col']) ? date('d/m/Y', strtotime($colaborador['fech_ing_col'])) : 'No especificada') . "</p>
                    <p><strong>Email:</strong> " . ($colaborador['email_col'] ?? 'No especificado') . "</p>
                </div>
                
                <div class='info-box'>
                    <h4>Información Importante:</h4>
                    <p>• Esta evaluación ha sido creada el día de hoy</p>
                    <p>• Deberá completar el proceso de evaluación en el sistema</p>
                    <p>• La evaluación incluye: Conducta Laboral, Productividad, Competencias y Otros Factores</p>
                </div>
                
                <p><strong>Nota:</strong> Por favor complete esta evaluación a la brevedad posible. El sistema le permitirá ingresar los porcentajes correspondientes a cada área evaluada.</p>
                
                <p>Si tiene alguna consulta, no dude en contactar al departamento de Talento Humano.</p>
                
                <p>Saludos cordiales,<br>
                <strong>Departamento de Talento Humano</strong><br>
                KM Solutions EC</p>
            </div>
            
            <div class='footer'>
                <p>rrhh@kmsolutionsec.com | +593 XX XXX XXXX</p>
                <p>Este es un mensaje automático del Sistema de Evaluaciones</p>
            </div>
        </div>
    </body>
    </html>";
    
    return $html;
}

/**
 * Función principal para enviar correos usando SMTP directo (puerto 587)
 */
function enviarCorreo($para, $asunto, $mensaje) {
    try {
        $smtp = new SMTPDirecto();
        $resultado = $smtp->enviar($para, $asunto, $mensaje);
        
        if ($resultado) {
            registrarEnvio($para, $asunto, 'exitoso');
            return true;
        } else {
            throw new Exception("Error en SMTP directo");
        }
        
    } catch (Exception $e) {
        error_log("Error enviando correo: " . $e->getMessage());
        registrarEnvio($para, $asunto, 'fallido: ' . $e->getMessage());
        return false;
    }
}

/**
 * Registra intentos de envío para auditoría
 */
function registrarEnvio($destinatario, $asunto, $resultado) {
    $fecha = date('Y-m-d H:i:s');
    $log_entry = "[{$fecha}] Para: {$destinatario} | Asunto: {$asunto} | Resultado: {$resultado}";
    
    // Log a archivo en lugar de consola para evitar interferir con JSON
    $log_file = __DIR__ . '/logs/email_log.txt';
    $log_dir = dirname($log_file);
    if (!is_dir($log_dir)) {
        @mkdir($log_dir, 0755, true);
    }
    @file_put_contents($log_file, $log_entry . "\n", FILE_APPEND | LOCK_EX);
}

/**
 * Verifica conectividad con el servidor SMTP
 */
function verificarConectividadSMTP() {
    $socket = @fsockopen(ConfigCorreo::SMTP_HOST, ConfigCorreo::SMTP_PORT, $errno, $errstr, 5);
    
    if ($socket) {
        fclose($socket);
        return true;
    }
    
    return false;
}

/**
 * Validar formato de email
 */
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Obtener configuración de correo para diagnósticos
 */
function obtenerConfiguracionCorreo() {
    return [
        'servidor' => ConfigCorreo::SMTP_HOST,
        'puerto' => ConfigCorreo::SMTP_PORT,
        'usuario' => ConfigCorreo::SISTEMA_EMAIL,
        'conectividad' => verificarConectividadSMTP() ? 'OK' : 'FALLO'
    ];
}

?>