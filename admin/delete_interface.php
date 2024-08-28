<?php
// delete_interface.php

session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

require_once '../config/db.php'; // Ajusta la ruta según tu estructura

// Obtener la lista de eventos disponibles
$stmt = $conn->prepare('SELECT id, nombre_evento FROM eventos');
$stmt->execute();
$stmt->bind_result($id, $nombre_evento);
$eventos = [];

while ($stmt->fetch()) {
    $eventos[] = ['id' => $id, 'nombre_evento' => $nombre_evento];
}

$stmt->close();
?>

<form action="delete_result.php" method="POST">
    <div class="form-group">
        <label for="evento_id">Seleccione el evento para eliminar los resultados:</label>
        <select class="form-control" id="evento_id" name="evento_id" required>
            <option value="">Seleccione un evento</option>
            <?php foreach ($eventos as $evento): ?>
                <option value="<?php echo $evento['id']; ?>"><?php echo htmlspecialchars($evento['nombre_evento']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-danger">Eliminar Resultados</button>
</form>
