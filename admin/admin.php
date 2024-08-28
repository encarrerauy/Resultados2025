<?php
session_start();
require_once '../scripts/config.php'; // Ruta corregida

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Eliminar un evento y sus resultados relacionados
if (isset($_POST['delete_event'])) {
    $id = $_POST['id'];

    // Eliminar los resultados asociados al evento
    $stmt = $conn->prepare("DELETE FROM resultados WHERE evento_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Eliminar el evento de la tabla de eventos
    $stmt = $conn->prepare("DELETE FROM eventos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: admin.php");
    exit();
}

// Subir un archivo CSV y guardar los resultados
if (isset($_POST['submit_csv'])) {
    $nombre_evento = $_POST['nombre_evento'];
    $fecha_evento = $_POST['fecha_evento'];
    $descripcion = $_POST['descripcion'];
    $filename = $_FILES['csv_file']['tmp_name'];

    if ($_FILES['csv_file']['size'] > 0) {
        $file = fopen($filename, 'r');
        
        // Insertar el evento en la tabla de eventos
        $stmt = $conn->prepare("INSERT INTO eventos (nombre_evento, fecha_evento, descripcion) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre_evento, $fecha_evento, $descripcion);
        $stmt->execute();
        $event_id = $stmt->insert_id;

        // Leer el archivo CSV y almacenar los resultados en la base de datos
        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
            $stmt = $conn->prepare("INSERT INTO resultados (evento_id, numero, nombre, pos_general, pos_categoria, modalidad, categoria, tiempo, sexo, edad, pos_modalidad, velocidad_tb, ritmo_tb) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iissssssiiids", $event_id, $getData[0], $getData[1], $getData[2], $getData[3], $getData[4], $getData[5], $getData[6], $getData[7], $getData[8], $getData[9], $getData[10], $getData[11]);
            $stmt->execute();
        }
        
        fclose($file);
        
        header("Location: admin.php");
        exit();
    }
}

// Obtener los eventos para mostrar en la página
$events = $conn->query("SELECT * FROM eventos ORDER BY fecha_evento DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Eventos</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Administración de Eventos</h1>

        <!-- Formulario para subir un nuevo archivo CSV -->
        <form action="admin.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre_evento">Nombre del Evento</label>
                <input type="text" name="nombre_evento" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="fecha_evento">Fecha del Evento</label>
                <input type="date" name="fecha_evento" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción del Evento</label>
                <textarea name="descripcion" class="form-control" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="csv_file">Archivo CSV</label>
                <input type="file" name="csv_file" class="form-control-file" required>
            </div>
            <button type="submit" name="submit_csv" class="btn btn-primary">Subir Archivo CSV</button>
        </form>

        <hr>

        <!-- Listado de eventos existentes -->
        <h2 class="mb-4">Eventos Existentes</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Evento</th>
                    <th>Fecha del Evento</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $events->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nombre_evento']; ?></td>
                        <td><?php echo $row['fecha_evento']; ?></td>
                        <td><?php echo $row['descripcion']; ?></td>
                        <td>
                            <form action="admin.php" method="post" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este evento? Esta acción no se puede deshacer.');" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete_event" class="btn btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
