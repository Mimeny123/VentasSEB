<?php
header('Content-Type: application/json; charset=utf-8');

$conexion = new mysqli("127.0.0.1", "root", "");

if ($conexion->connect_error) {
    die(json_encode(["error" => "Fallo de conexión: " . $conexion->connect_error]));
}


$conexion->query("CREATE DATABASE IF NOT EXISTS prueba_db");
$conexion->select_db("prueba_db");

$conexion->query("CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    correo VARCHAR(50)
)");

$chequeo = $conexion->query("SELECT COUNT(*) AS total FROM usuarios");
$fila_chequeo = $chequeo->fetch_assoc();
if ($fila_chequeo['total'] == 0) {
    $conexion->query("INSERT INTO usuarios (nombre, correo) VALUES 
        ('Ana', 'ana@trabajo.com'),
        ('Lucas', 'lucas@trabajo.com')");
}

$resultado = $conexion->query("SELECT id, nombre, correo FROM usuarios");
$usuarios = [];

while ($fila = $resultado->fetch_assoc()) {
    $usuarios[] = $fila;
}

echo json_encode($usuarios);
$conexion->close();
?>