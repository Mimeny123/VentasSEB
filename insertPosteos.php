<?php
session_start();
require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo      = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio      = isset($_POST['precio']) ? (int)$_POST['precio'] : 0;
    $mp          = trim($_POST['mp'] ?? '');
    $usuario_id  = !empty($_POST['usuario_id']) ? (int)$_POST['usuario_id'] : (int)($_SESSION['usuario_id'] ?? 0);
    $imagen      = '';

    if (empty($titulo) || $precio <= 0) {
        echo "<script>
                alert('El título y un precio válido son obligatorios.');
                window.history.back();
            </script>";
        exit;
    }

    if ($usuario_id <= 0) {
        echo "<script>
                alert('Sesión no válida. Inicia sesión nuevamente.');
                window.location.href = 'sign.html';
            </script>";
        exit;
    }

    // Crear la carpeta "imagenes" en el proyecto si no existe y guardar el archivo
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $carpetaDestino = __DIR__ . '/imagenes/';
        if (!is_dir($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }

        $nombreOriginal = basename($_FILES['imagen']['name']);
        $nombreSeguro   = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $nombreOriginal);
        $rutaCompleta   = $carpetaDestino . $nombreSeguro;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaCompleta)) {
            $imagen = 'imagenes/' . $nombreSeguro;
        }
    }

    $stmt = $conexion->prepare("INSERT INTO `posteos` (`titulo`, `descripcion`, `precio`, `imagen`, `mp`, `usuario_id`) VALUES (?, ?, ?, ?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("ssissi", $titulo, $descripcion, $precio, $imagen, $mp, $usuario_id);

        if ($stmt->execute()) {
            $stmt->close();
            $conexion->close();
            header("Location: index.html");
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

header("Location: nuevosPosteos.html");
exit;
?>