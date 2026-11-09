<?php
// Evitar que PHP 8.2 detenga el script con excepciones fatales
mysqli_report(MYSQLI_REPORT_OFF);

$usuario    = "root";
$password   = "";
$base_datos = "ventasSEB";

// 1. Intentar conectar por 127.0.0.1 (Windows/TCP) y si falla por localhost (Socket XAMPP Mac)
$conexion = @new mysqli("127.0.0.1", $usuario, $password);
if ($conexion->connect_error) {
    $conexion = @new mysqli("localhost", $usuario, $password);
}

if ($conexion->connect_error) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    die(json_encode([
        "error" => "No se pudo conectar a MySQL. Verifica que MySQL esté iniciado en el panel de XAMPP. Detalle: " . $conexion->connect_error
    ], JSON_UNESCAPED_UNICODE));
}

$conexion->set_charset("utf8mb4");

// 2. Crear la base de datos si no existe y seleccionarla
$conexion->query("CREATE DATABASE IF NOT EXISTS `$base_datos` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
$conexion->select_db($base_datos);

// 3. Importar ventasSEB_db.sql automáticamente si no están las 4 tablas
$archivo_sql = __DIR__ . "/ventasSEB_db.sql";
$tablas = $conexion->query("SHOW TABLES");

if ($tablas && $tablas->num_rows < 4 && file_exists($archivo_sql)) {
    // Flexibilizar modo SQL para que fechas como '0000-00-00' no corten la importación de los AUTO_INCREMENT
    $conexion->query("SET SESSION sql_mode = 'NO_AUTO_VALUE_ON_ZERO'");
    
    $sql_contenido = file_get_contents($archivo_sql);
    $sql_contenido = str_replace("'0000-00-00'", "NULL", $sql_contenido);

    if ($conexion->multi_query($sql_contenido)) {
        do {
            if ($res = $conexion->store_result()) {
                $res->free();
            }
        } while ($conexion->more_results() && $conexion->next_result());
    }
}