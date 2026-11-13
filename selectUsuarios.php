<?php
session_start();
require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario    = trim($_POST['usuario'] ?? '');
    $contrasena = trim($_POST['contraseña'] ?? '');

    if (empty($usuario) || empty($contrasena)) {
        echo "<script>
            alert('Por favor, completa todos los campos.');
            window.history.back();
        </script>";
        exit;
    }

    // Pedimos también usuario_id y filtramos por el nombre de usuario
    $stmt = $conexion->prepare("SELECT `usuario_id`, `usuario`, `contraseña` FROM `usuarios` WHERE `usuario` = ? LIMIT 1");
    
    if ($stmt) {
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($fila = $resultado->fetch_assoc()) {
            // Verifica si la contraseña coincide (soporta texto plano o hash con password_verify)
            if ($contrasena === $fila['contraseña'] || password_verify($contrasena, $fila['contraseña'])) {
                
                // 1. Lo guardamos en la sesión de PHP
                $_SESSION['usuario_id'] = $fila['usuario_id'];
                $id = (int)$fila['usuario_id'];

                $stmt->close();
                $conexion->close();

                // 2. Lo guardamos en localStorage para todas las páginas HTML y volvemos al inicio
                echo "<script>
                    localStorage.setItem('usuario_id', '$id');
                    window.location.href = '/index.html';
                </script>";
                exit;
            }
        }
        $stmt->close();
    }

    $conexion->close();
    echo "<script>
        alert('Usuario o contraseña incorrectos.');
        window.history.back();
    </script>";
    exit;
}
?> 