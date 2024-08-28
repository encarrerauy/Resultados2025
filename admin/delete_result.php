<?php
// delete_result.php

session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

require_once '../config/db.php'; // Ajusta la ruta según tu estructura

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['evento_id'])) {
        $evento_id = intval($_POST['evento_id']);

        $stmt = $conn->prepare('DELETE FROM resultados WHERE evento_id = ?');
        $stmt->bind_param('i', $evento_id);

        if ($stmt->execute()) {
            echo "Los resultados del evento han sido eliminados con éxito.";
        } else {
            echo "Hubo un error al intentar eliminar los resultados.";
        }

        $stmt->close();
    } else {
        echo "No se proporcionó un ID de evento válido.";
    }
} else {
    echo "Método de solicitud no válido.";
}
?>
