<?php
require_once __DIR__ . '/conexion.php';

header('Content-Type: application/json; charset=utf-8');

$sql = "SELECT post_id, titulo, descripcion, precio, imagen, fecha, mp, usuario_id FROM `posteos` ORDER BY post_id DESC";
$resultado = $conexion->query($sql);
$posteos = [];

if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $posteos[] = $fila;
    }
    $resultado->free();
}

$conexion->close();

echo json_encode($posteos, JSON_UNESCAPED_UNICODE);