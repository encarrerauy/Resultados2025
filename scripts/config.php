<?php
$servername = "localhost";
$username = "encarrera_result25";
$password = "5Oq=\$kFPjrm^";
$dbname = "encarrera_result25";

// Crea la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
