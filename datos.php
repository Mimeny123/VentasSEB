<?php
mysqli_report(MYSQLI_REPORT_OFF);

$servidor = "127.0.0.1";
$usuario = "root";
$password = "";
$base_datos = "ventasSEB";

// 1. Conexión y carga automática de ventasSEB_db.sql si la base está vacía
$conexion = new mysqli($servidor, $usuario,$password);
if ($conexion->connect_error) {
    die("Fallo de conexión: " . $conexion->connect_error);
}

$conexion->query("CREATE DATABASE IF NOT EXISTS `$base_datos` DEFAULT CHARACTER SET utf8mb4");
$conexion->select_db($base_datos);

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

// Detecta cuando presionan tu botón name="botonRegistro"
if (isset($_POST['botonRegistro'])) {
    $user      =$_POST['user'];
    $nombre    =$_POST['nombre'];
    $apellido  =$_POST['apellido'];
    $edad      =$_POST['edad'];
    $gmail     =$_POST['gmail'];
    $telefono  =$_POST['telefono'];
    $pass      =$_POST['password'];
    $repitPass =$_POST['repitPassword'];

    // 1. Verifica si las dos contraseñas coinciden
    if ($pass !==$repitPass) {
        // Si NO son iguales, muestra un aviso y vuelve al formulario sin borrar lo que escribió
        echo "<script>
                alert('Las contraseñas no coinciden. Inténtalo de nuevo.');
                window.history.back();
            </script>";
        exit;
    }

    // 2. Si coinciden, guarda los datos en la tabla usuarios (solo se guarda $pass en la columna contraseña)
    $insertar = "INSERT INTO usuarios (usuario, nombre, apellido, edad, gmail, telefono, `contraseña`) xVALUES ('$user', '$nombre', '$apellido', '$edad', '$gmail', '$telefono', '$pass')";

    $ejecutar = mysqli_query($conexion,$insertar);

    // 3. Recarga la página del formulario al terminar
    $pagina_anterior =$_SERVER['HTTP_REFERER'] ?? 'index.html';
    header("Location: $pagina_anterior");
    exit;
}


// 3. Si entras a datos.php para consultar datos, devuelve el JSON normal
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