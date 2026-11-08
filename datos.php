<?php
mysqli_report(MYSQLI_REPORT_OFF);

$servidor   = "127.0.0.1";
$usuario    = "root";
$password   = "";
$base_datos = "ventasSEB";

// Conexión con fallback a localhost
$conexion = new mysqli($servidor, $usuario, $password);
if ($conexion->connect_error) {
    $conexion = new mysqli("localhost", $usuario, $password);
}
if ($conexion->connect_error) {
    http_response_code(500);
    die(json_encode(["error" => "Error de conexión a MySQL: " . $conexion->connect_error]));
}

$conexion->set_charset("utf8mb4");

// Creación y selección de la base de datos
$conexion->query("CREATE DATABASE IF NOT EXISTS `$base_datos` DEFAULT CHARACTER SET utf8mb4");
$conexion->select_db($base_datos);

// Ejecución inicial del script SQL si la base está vacía
$archivo_sql = __DIR__ . "/ventasSEB_db.sql";
$tablas = $conexion->query("SHOW TABLES");
if ($tablas && $tablas->num_rows === 0 && file_exists($archivo_sql)) {
    $sql_contenido = file_get_contents($archivo_sql);
    if ($conexion->multi_query($sql_contenido)) {
        do {
            if ($res = $conexion->store_result()) {
                $res->free();
            }
        } while ($conexion->more_results() && $conexion->next_result());
    }
}

// Procesar formulario (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user      = $_POST['user'] ?? '';
    $nombre    = $_POST['nombre'] ?? '';
    $apellido  = $_POST['apellido'] ?? '';
    $edad      = !empty($_POST['edad']) ? $_POST['edad'] : null;
    $gmail     = $_POST['gmail'] ?? '';
    $telefono  = $_POST['telefono'] ?? '';
    $pass      = $_POST['password'] ?? '';
    $repitPass = $_POST['repitPassword'] ?? '';

    if ($pass !== $repitPass) {
        echo "<script>
                alert('Las contraseñas no coinciden.');
                window.history.back();
            </script>";
        exit;
    }

    // Consulta preparada: previene inyecciones y errores por comillas en nombres/claves
    $stmt = $conexion->prepare("INSERT INTO `usuarios` (`usuario`, `gmail`, `nombre`, `apellido`, `edad`, `contraseña`, `telefono`) VALUES (?, ?, ?, ?, ?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("sssssss", $user, $gmail, $nombre, $apellido, $edad, $pass, $telefono);
        
        if ($stmt->execute()) {
            $stmt->close();
            $pagina_anterior = $_SERVER['HTTP_REFERER'] ?? 'index.html';
            header("Location: $pagina_anterior");
            exit;
        } else {
            $error = $stmt->error;
            $stmt->close();
            echo "<script>
                    alert('Error al guardar: " . addslashes($error) . "');
                    window.history.back();
                </script>";
            exit;
        }
    } else {
        echo "<script>
                alert('Error al preparar consulta: " . addslashes($conexion->error) . "');
                window.history.back();
            </script>";
        exit;
    }
}

// Obtener registros y responder únicamente con JSON limpio
header('Content-Type: application/json; charset=utf-8');

$resultado = $conexion->query("SELECT * FROM `usuarios`");
$datos = [];

if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $datos[] = $fila;
    }
    $resultado->free();
}

$conexion->close();

echo json_encode($datos, JSON_UNESCAPED_UNICODE);