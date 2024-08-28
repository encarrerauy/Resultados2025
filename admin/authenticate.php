<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    // Si no ha iniciado sesión, redirigir a la página de inicio de sesión
    header("Location: login.php");
    exit();
}

// Si ha iniciado sesión, continuar con la ejecución de la página
?>
