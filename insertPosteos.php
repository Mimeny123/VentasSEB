<?php
require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo      = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio      = isset($_POST['precio']) ? (int)$_POST['precio'] : 0;
    $imagen      = trim($_POST['imagen'] ?? '');
    $mp          = trim($_POST['mp'] ?? '');
    $usuario_id  = isset($_POST['usuario_id']) ? (int)$_POST['usuario_id'] : 1;

    if (empty($titulo) |  $precio <= 0) {
        echo "<script>
                alert('El título y un precio válido son obligatorios.');
                window.history.back();
            </script>";
        exit;
    }

    $stmt = $conexion->prepare("INSERT INTO `posteos` (`titulo`, `descripcion`, `precio`, `imagen`, `estado`, `mp`, `usuario_id`) VALUES (?, ?, ?, ?, ?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("ssisssi", $titulo, $descripcion, $precio, $imagen, $estado, $mp, $usuario_id);

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
                    alert('Error al publicar: " . addslashes($error) . "');
                    window.history.back();
                </script>";
            exit;
        }
    }
}

header("Location: /nuevosPosteos.html");
exit;