<?php
mysqli_report(MYSQLI_REPORT_OFF);

$servidor = "127.0.0.1";
$usuario = "root";
$password = "";
$base_datos = "ventasSEB";

// 1. Conecta a MySQL (si 127.0.0.1 falla en Mac, intenta con localhost)
$conexion = new mysqli($servidor, $usuario,$password);
if ($conexion->connect_error) {$conexion = new mysqli("localhost", $usuario,$password);
}
if ($conexion->connect_error) {
    die("Error de conexión a MySQL: " . $conexion->connect_error);
}

$conexion->set_charset("utf8mb4");

// 2. Crea y selecciona la base de datos ventasSEB
$conexion->query("CREATE DATABASE IF NOT EXISTS `$base_datos` DEFAULT CHARACTER SET utf8mb4");
$conexion->select_db($base_datos);

// 3. Importa ventasSEB_db.sql si la base está vacía
$archivo_sql = __DIR__ . "/ventasSEB_db.sql";
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

// 4. Cuando se envía el formulario por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Asegura que usuario_id tenga AUTO_INCREMENT y edad sea DATE por si faltaba en la tabla
    $conexion->query("ALTER TABLE `usuarios` MODIFY `usuario_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT");

    $user      = $_POST['user'] ?? '';$nombre    = $_POST['nombre'] ?? '';$apellido  = $_POST['apellido'] ?? '';$edad      = !empty($_POST['edad']) ? "'" . $_POST['edad'] . "'" : "NULL";
    $gmail     =$_POST['gmail'] ?? '';
    $telefono  =$_POST['telefono'] ?? '';
    $pass      =$_POST['password'] ?? '';
    $repitPass =$_POST['repitPassword'] ?? '';

    if ($pass !==$repitPass) {
        echo "<script>
                alert('Las contraseñas no coinciden.');
                window.history.back();
            </script>";
        exit;
    }

    $insertar = "INSERT INTO `usuarios` (`usuario`, `gmail`, `nombre`, `apellido`, `edad`, `contraseña`, `telefono`) VALUES ('$user', '$gmail', '$nombre', '$apellido',$edad, '$pass', '$telefono')";

    if ($conexion->query($insertar)) {
        // Redirige de inmediato al index (o a la página anterior) sin mostrar alertas
        $pagina_anterior = $_SERVER['HTTP_REFERER'] ?? 'index.html';
        header("Location: $pagina_anterior");
        exit;
    } else {
        // Si llega a haber un error en la base de datos, opcionalmente puedes dejar el alert solo para los errores
        echo"<script>
                alert('Error al guardar: " . addslashes($conexion->error) . "');
                window.history.back();
            </script>";
        exit;
    }
}

// 5. Si entras directo a datos.php, muestra los usuarios actuales
header('Content-Type: application/json; charset=utf-8');
$resultado =$conexion->query("SELECT * FROM usuarios");
$datos = [];
if ($resultado) {
    while ($fila =$resultado->fetch_assoc()) {
        $datos[] =$fila;
    }
}
echo json_encode($datos);$conexion->close();
?>