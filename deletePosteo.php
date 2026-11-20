<?php
session_start();
require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id    = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
    $usuario_id = !empty($_POST['usuario_id']) ? (int)$_POST['usuario_id'] : (int)($_SESSION['usuario_id'] ?? 0);

    if ($post_id > 0 && $usuario_id > 0) {
        // 1. Eliminar reportes asociados al posteo por si la tabla reportes tiene clave foránea
        $stmtRep = $conexion->prepare("DELETE FROM `reportes` WHERE `post_id` = ?");
        if ($stmtRep) {
            $stmtRep->bind_param("i", $post_id);
            $stmtRep->execute();
            $stmtRep->close();
        }

        // 2. Eliminar el posteo asegurando que pertenezca al usuario logueado
        $stmt = $conexion->prepare("DELETE FROM `posteos` WHERE `post_id` = ? AND `usuario_id` = ?");
        if ($stmt) {
            $stmt->bind_param("ii", $post_id, $usuario_id);
            if (!$stmt->execute()) {
                $error = $stmt->error;
                $stmt->close();
                $conexion->close();
                echo "<script>
                        alert('Error al eliminar: " . addslashes($error) . "');
                        window.history.back();
                    </script>";
                exit;
            }
            $stmt->close();
        }
    }
    $conexion->close();
}

header("Location: perfil.html");
exit;
?>