<?php
$host = "localhost";     
$dbname = "evaluacionesth";  
$username = "root";       
$password = "";

function conectarDB() {
    global $host, $dbname, $username, $password;
    
    try {
        // Crear conexión PDO
        $conexion = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        
        // Configurar manejo de errores
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return $conexion;

    } catch (PDOException $e) {
        echo "❌ Error de conexión: " . $e->getMessage();
        return null;
    }
}
?>