<?php
require_once 'scripts/config.php';
require_once 'scripts/query_helpers.php';
require_once 'scripts/results_display.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$evento_id = isset($_GET['evento_id']) ? $_GET['evento_id'] : '';
$modalidad = isset($_GET['modalidad']) ? $_GET['modalidad'] : '';
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';

$limit = 50;  // Definir un límite por defecto
$offset = 0;  // Mostrar desde el primer resultado

$resultados = getResultados($conn, $evento_id, $modalidad, $categoria, $limit, $offset, $search);

if (!empty($resultados)) {
    displayResultsTable($resultados);
} else {
    echo '<div class="alert alert-info mt-5">No se encontraron resultados.</div>';
}
?>
