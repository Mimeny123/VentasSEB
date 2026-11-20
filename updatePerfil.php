<?php
session_start();
require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id      = !empty($_POST['usuario_id']) ? (int)$_POST['usuario_id'] : (int)($_SESSION['usuario_id'] ?? 0);
    $usuario         = trim($_POST['usuario'] ?? '');
    $nombre_completo = trim($_POST['nombre_completo'] ?? '');
    $gmail           = trim($_POST['gmail'] ?? '');
    $telefono        = trim($_POST['telefono'] ?? '');

    if ($usuario_id <= 0) {
        echo "<script>
                alert('Sesión no válida. Inicia sesión nuevamente.');
                window.location.href = 'sign.html';
            </script>";
        exit;
    }

    // Asegurar que la tabla 'usuarios' tenga la columna 'imagen'
    $col = $conexion->query("SHOW COLUMNS FROM `usuarios` LIKE 'imagen'");
    if ($col && $col->num_rows === 0) {
        $conexion->query("ALTER TABLE `usuarios` ADD `imagen` VARCHAR(400) DEFAULT NULL");
    }

    $rutaImagenPerfil = '';
    if (isset($_FILES['imagen_perfil']) && $_FILES['imagen_perfil']['error'] === UPLOAD_ERR_OK) {
        $carpetaDestino = __DIR__ . '/imagenes/';
        if (!is_dir($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }

        $nombreOriginal = basename($_FILES['imagen_perfil']['name']);
        $nombreSeguro   = 'perfil_' . $usuario_id . '_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $nombreOriginal);
        $rutaCompleta   = $carpetaDestino . $nombreSeguro;

        if (move_uploaded_file($_FILES['imagen_perfil']['tmp_name'], $rutaCompleta)) {
            $rutaImagenPerfil = 'imagenes/' . $nombreSeguro;
        }
    }

    $partes   = explode(' ', $nombre_completo, 2);
    $nombre   = trim($partes[0] ?? '');
    $apellido = trim($partes[1] ?? '');

    if ($rutaImagenPerfil !== '') {
        $stmt = $conexion->prepare("UPDATE `usuarios` SET `usuario` = ?, `nombre` = ?, `apellido` = ?, `gmail` = ?, `telefono` = ?, `imagen` = ? WHERE `usuario_id` = ?");
        if ($stmt) {
            $stmt->bind_param("ssssssi", $usuario, $nombre, $apellido, $gmail, $telefono, $rutaImagenPerfil, $usuario_id);
        }
    } else {
        $stmt = $conexion->prepare("UPDATE `usuarios` SET `usuario` = ?, `nombre` = ?, `apellido` = ?, `gmail` = ?, `telefono` = ? WHERE `usuario_id` = ?");
        if ($stmt) {
            $stmt->bind_param("sssssi", $usuario, $nombre, $apellido, $gmail, $telefono, $usuario_id);
        }
    }

    if ($stmt && $stmt->execute()) {
        $stmt->close();
        $conexion->close();
        header("Location: perfil.html");
        exit;
    } else {
        $error = $stmt ? $stmt->error : $conexion->error;
        if ($stmt) $stmt->close();
        $conexion->close();
        echo "<script>
                alert('Error al actualizar perfil: " . addslashes($error) . "');
                window.history.back();
            </script>";
        exit;
    }
}

header("Location: perfil.html");
exit;
?>