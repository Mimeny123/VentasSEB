<?php
require_once __DIR__ . '/conexion.php';

session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $motivo_id = isset($_POST['motivo_id']) ? (int)$_POST['motivo_id'] : 1;
    $descripcion = trim($_POST['descripcion'] ?? '');
    $usuario_id = isset($_SESSION['usuario_id']) ? (int)$_SESSION['usuario_id'] : 1;
    $post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 1;

    if (empty($motivo_id)) {
        echo"<script>
            alert('El motivo del reporte es obligatorio.');
            window.history.back();
        </script>";
        exit;
    }

    $stmt = $conexion->prepare("INSERT INTO `reportes` (`motivo_id`, `descripcion`, `usuario_id`, `post_id`) VALUES (?,?,?,?)");

    if ($stmt) {
        $stmt->bind_param("isii", $motivo_id,$descripcion,$usuario_id,$post_id);

        if($stmt ->execute()) {
            $stmt->close();
            $conexion->close();
            header("Location: /index.html");
            exit;
        }
        else {
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

header("Location: /insertReportes.html");
exit;