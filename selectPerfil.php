<?php
session_start();
require_once __DIR__ . '/conexion.php';

header('Content-Type: application/json; charset=utf-8');

$usuario_id = !empty($_GET['usuario_id']) ? (int)$_GET['usuario_id'] : (int)($_SESSION['usuario_id'] ?? 0);

$usuario = null;

if ($usuario_id > 0) {
    $sql = "SELECT * FROM `usuarios` WHERE `usuario_id` = ? LIMIT 1";

    $stmt = $conexion->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();
        if ($usuario) {
            unset($usuario['contraseña']);
        }
        $stmt->close();
    }
}

$conexion->close();

echo json_encode($usuario, JSON_UNESCAPED_UNICODE);
?>