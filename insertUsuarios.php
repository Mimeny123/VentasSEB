<?php
require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user      = trim($_POST['user'] ?? '');
    $nombre    = trim($_POST['nombre'] ?? '');
    $apellido  = trim($_POST['apellido'] ?? '');
    $edad      = !empty($_POST['edad']) ? $_POST['edad'] : null;
    $gmail     = trim($_POST['gmail'] ?? '');
    $telefono  = trim($_POST['telefono'] ?? '');
    $pass      = $_POST['password'] ?? '';
    $repitPass = $_POST['repitPassword'] ?? '';

    if ($pass !== $repitPass) {
        echo "<script>
                alert('Las contraseñas no coinciden.');
                window.history.back();
            </script>";
        exit;
    }

    $stmt = $conexion->prepare("INSERT INTO `usuarios` (`usuario`, `gmail`, `nombre`, `apellido`, `edad`, `contraseña`, `telefono`) VALUES (?, ?, ?, ?, ?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("sssssss", $user, $gmail, $nombre, $apellido, $edad, $pass, $telefono);
        
        if ($stmt->execute()) {
            $stmt->close();
            $conexion->close();
            header("Location: /index.html");
            exit;
        } else {
            $error = $stmt->error;
            $stmt->close();
            $conexion->close();
            echo "<script>
                    alert('Error al guardar usuario: " . addslashes($error) . "');
                    window.history.back();
                </script>";
            exit;
        }
    }
}

header("Location: /sign.html");
exit;