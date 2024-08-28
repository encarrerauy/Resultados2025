<?php
require_once 'query_helpers.php';

function renderEventDropdown($eventos, $evento_id) {
    echo '<select class="form-control" id="evento_id" name="evento_id" required onchange="this.form.submit()">';
    echo '<option value="">-- Selecciona un evento --</option>';
    foreach ($eventos as $evento) {
        echo '<option value="' . $evento['id'] . '"' . ($evento_id == $evento['id'] ? 'selected' : '') . '>';
        echo $evento['nombre_evento'] . ' - ' . date('d/m/Y', strtotime($evento['fecha_evento']));
        echo '</option>';
    }
    echo '</select>';
}

function renderModalidadDropdown($modalidades, $modalidad) {
    if (!empty($modalidades)) {
        echo '<select class="form-control" id="modalidad" name="modalidad" onchange="this.form.submit()">';
        echo '<option value="">-- Todas las modalidades --</option>';
        foreach ($modalidades as $mod) {
            echo '<option value="' . $mod . '"' . ($modalidad == $mod ? 'selected' : '') . '>';
            echo $mod;
            echo '</option>';
        }
        echo '</select>';
    }
}

function renderCategoriaDropdown($categorias, $categoria) {
    if (!empty($categorias)) {
        echo '<select class="form-control" id="categoria" name="categoria" onchange="this.form.submit()">';
        echo '<option value="">-- Todas las categorías --</option>';
        foreach ($categorias as $cat) {
            echo '<option value="' . $cat . '"' . ($categoria == $cat ? 'selected' : '') . '>';
            echo $cat;
            echo '</option>';
        }
        echo '</select>';
    }
}
?>
