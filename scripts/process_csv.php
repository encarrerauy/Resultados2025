<?php
// Incluye el archivo de configuración
require_once 'config.php';

// Obtiene los datos del evento desde la URL (enviados desde admin.php)
$nombre_evento = $_GET['nombre_evento'];
$fecha_evento = $_GET['fecha_evento'];
$descripcion = $_GET['descripcion'];
$csv_file = $_GET['csv_file'];

// Inserta el evento en la base de datos
$stmt = $conn->prepare("INSERT INTO eventos (nombre_evento, fecha_evento, descripcion) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nombre_evento, $fecha_evento, $descripcion);
$stmt->execute();

// Obtiene el ID del evento recién insertado
$evento_id = $conn->insert_id;

// Abre el archivo CSV y lo procesa
if (($handle = fopen($csv_file, "r")) !== FALSE) {
    // Salta la primera línea (cabecera)
    fgetcsv($handle);

    // Procesa cada fila del archivo CSV
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $numero = $data[0];
        $nombre = $data[1];
        $pos_general = $data[2];
        $pos_categoria = $data[3];
        $modalidad = $data[4];
        $categoria = $data[5];
        $tiempo = $data[6];
        $sexo = $data[7];
        $edad = $data[8];
        $pos_modalidad = $data[9];
        $velocidad_tb = $data[10];
        $ritmo_tb = $data[11];

        // Inserta los datos del corredor en la base de datos
        $stmt = $conn->prepare("INSERT INTO resultados (evento_id, numero, nombre, pos_general, pos_categoria, modalidad, categoria, tiempo, sexo, edad, pos_modalidad, velocidad_tb, ritmo_tb) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissssssiiids", $evento_id, $numero, $nombre, $pos_general, $pos_categoria, $modalidad, $categoria, $tiempo, $sexo, $edad, $pos_modalidad, $velocidad_tb, $ritmo_tb);
        $stmt->execute();
    }
    fclose($handle);

    echo "El archivo CSV se ha procesado correctamente.";
// Opción 1: Redirigir al usuario de vuelta a la página de administración después de 3 segundos
header("Refresh: 3; url=../admin/admin.php");
echo "<br>Serás redirigido en 3 segundos...";

// Opción 2: Mostrar un botón para regresar manualmente a la página de administración
echo '<br><br><a href="../admin/admin.php" class="btn btn-primary">Volver a la Administración</a>';



} else {
    echo "No se pudo abrir el archivo CSV.";
}
?>
