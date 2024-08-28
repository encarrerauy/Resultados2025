<?php
require_once 'query_helpers.php';

function displayResultsTable($resultados) {
    if ($resultados) {
        echo '<table class="table table-striped table-responsive">';
        echo '<thead><tr>';
        echo '<th>Número</th>';
        echo '<th>Nombre</th>';
        echo '<th>Pos. General</th>';
        echo '<th>Pos. Categoría</th>';
        echo '<th>Modalidad</th>';
        echo '<th>Categoría</th>';
        echo '<th>Tiempo</th>';
        echo '<th>Ver detalles</th>';
        echo '<th>Ver diploma</th>';
        echo '</tr></thead>';
        echo '<tbody>';
        foreach ($resultados as $resultado) {
            echo '<tr>';
            echo '<td>' . $resultado['numero'] . '</td>';
            echo '<td>' . $resultado['nombre'] . '</td>';
            echo '<td>' . $resultado['pos_general'] . '</td>';
            echo '<td>' . $resultado['pos_categoria'] . '</td>';
            echo '<td>' . $resultado['modalidad'] . '</td>';
            echo '<td>' . $resultado['categoria'] . '</td>';
            echo '<td>' . $resultado['tiempo'] . '</td>';
            echo '<td><i class="fa-regular fa-eye"></i></td>';
            echo '<td><i class="fa-solid fa-award"></i></td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<div class="alert alert-info mt-5">No se encontraron resultados para este evento.</div>';
    }
}
?>
