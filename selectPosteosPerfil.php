<?php
session_start();
require_once __DIR__ . '/conexion.php';

header('Content-Type: application/json; charset=utf-8');

// Tomamos el usuario_id de la sesión de PHP o del parámetro enviado por fetch
$usuario_id = $_SESSION['usuario_id'] ?? ($_GET['usuario_id'] ?? 0);
$usuario_id = (int)$usuario_id;

$posteos = [];

if ($usuario_id > 0) {
    $sql = "SELECT post_id, titulo, descripcion, precio, imagen, fecha, mp, usuario_id 
            FROM `posteos` 
            WHERE usuario_id = ? 
            ORDER BY post_id DESC";

    $stmt = $conexion->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $posteos[] = $fila;
        }
        $stmt->close();
    }
}

$conexion->close();

echo json_encode($posteos, JSON_UNESCAPED_UNICODE);
?>