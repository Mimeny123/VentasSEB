<?php
header('Content-Type: application/json; charset=utf-8');
mysqli_report(MYSQLI_REPORT_OFF); // Evita errores si la base aún no tiene tablas

$servidor = "127.0.0.1";
$usuario = "root";
$password = "";
$base_datos = "ventasSEB";

// Busca el archivo ventasSEB_db.sql (o ventasSEB_db) en tu carpeta
$archivo_sql = __DIR__ . "/ventasSEB_db.sql";
if (!file_exists($archivo_sql)) {$archivo_sql = __DIR__ . "/ventasSEB_db";
}

// 1. Conecta a MySQL
$conexion = new mysqli($servidor, $usuario,$password);

if ($conexion->connect_error) {
    die(json_encode(["error" => "Fallo de conexión: " . $conexion->connect_error]));
}

// 2. Crea la base de datos ventasSEB si no existe y la selecciona
$conexion->query("CREATE DATABASE IF NOT EXISTS `$base_datos` DEFAULT CHARACTER SET utf8mb4");
$conexion->select_db($base_datos);

// 3. Si la base está vacía y ya existe el archivo .sql en la carpeta, lo importa solo
$tablas =$conexion->query("SHOW TABLES");
if ($tablas && $tablas->num_rows == 0 && file_exists($archivo_sql)) {
    $sql_contenido = file_get_contents($archivo_sql);
    if ($conexion->multi_query($sql_contenido)) {
        do {
            if ($res = $conexion->store_result()) {$res->free();
            }
        } while ($conexion->more_results() &&$conexion->next_result());
    }
}

// 4. Consulta tu tabla (si ya existe)
$resultado =$conexion->query("SELECT * FROM usuarios");
$datos = [];

if ($resultado) {
    while ($fila =$resultado->fetch_assoc()) {
        $datos[] =$fila;
    }
}

echo json_encode($datos);$conexion->close();
?>