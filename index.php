<?php
require_once 'scripts/query_helpers.php';
require_once 'scripts/filters.php';
require_once 'scripts/results_display.php';

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$evento_id = isset($_GET['evento_id']) ? $_GET['evento_id'] : null;
$modalidad = isset($_GET['modalidad']) ? $_GET['modalidad'] : '';
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

$eventos = getEventos($conn);

if ($evento_id) {
    $filtros = getModalidadesCategorias($conn, $evento_id, $modalidad);
    $modalidades = $filtros['modalidades'];
    $categorias = $filtros['categorias'];

    $resultados = getResultados($conn, $evento_id, $modalidad, $categoria, $limit, $offset, $search);
    $total_resultados = getTotalResultados($conn, $evento_id, $modalidad, $categoria);
    $total_paginas = ceil($total_resultados / $limit);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Eventos</title>
    <!-- Incluir Bootstrap desde el CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Incluir Select2 desde el CDN -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Incluir Font Awesome desde el CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Incluir Google Fonts para Montserrat -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <!-- Incluir el archivo CSS personalizado -->
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <!-- Logotipo de la empresa -->
    <div class="logo text-center mb-4">
        <img src="assets/encarrerauy-logonegro.png" alt="Logo Encarrrera.Uy">
    </div>

    <h2>Resultados de Eventos</h2>

    <form method="GET" action="index.php">
        <!-- Dropdown para seleccionar evento -->
        <div class="form-group">
            <label for="evento_id">Selecciona o busca un evento:</label>
            <?php renderEventDropdown($eventos, $evento_id); ?>
        </div>

        <!-- Campo de búsqueda -->
        <div class="form-group">
            <label for="search" class="search-label">Buscar por Número, Nombre o Apellido:</label>
            <input type="text" id="search" name="search" class="form-control" placeholder="Ingrese el número, nombre o apellido">
        </div>

        <!-- Dropdown para seleccionar modalidad -->
        <?php if ($evento_id): ?>
        <div class="form-group">
            <label for="modalidad">Filtrar por modalidad:</label>
            <?php renderModalidadDropdown($modalidades, $modalidad); ?>
        </div>
        <?php endif; ?>

        <!-- Dropdown para seleccionar categoría -->
        <?php if ($modalidad): ?>
        <div class="form-group">
            <label for="categoria">Filtrar por categoría:</label>
            <?php renderCategoriaDropdown($categorias, $categoria); ?>
        </div>
        <?php endif; ?>

        <!-- Selector de cantidad de resultados por página -->
        <div class="form-group">
            <label for="limit">Mostrar:</label>
            <select class="form-control" id="limit" name="limit" onchange="this.form.submit()">
                <option value="10" <?php echo ($limit == 10) ? 'selected' : ''; ?>>10 resultados</option>
                <option value="20" <?php echo ($limit == 20) ? 'selected' : ''; ?>>20 resultados</option>
                <option value="50" <?php echo ($limit == 50) ? 'selected' : ''; ?>>50 resultados</option>
            </select>
        </div>

        <!-- Botón para limpiar los filtros -->
        <?php if ($modalidad || $categoria): ?>
        <div class="form-group">
            <button type="button" class="btn btn-secondary" onclick="limpiarFiltros()">Limpiar Filtros</button>
        </div>
        <?php endif; ?>
    </form>

    <?php if ($evento_id && $resultados): ?>
        <h3 class="mt-5">Resultados</h3>
        <div id="resultados">
            <?php displayResultsTable($resultados); ?>
        </div>

        <!-- Paginación -->
        <nav>
    <ul class="pagination justify-content-center flex-wrap">
        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                <a class="page-link" href="?evento_id=<?php echo $evento_id; ?>&limit=<?php echo $limit; ?>&page=<?php echo $i; ?>&modalidad=<?php echo $modalidad; ?>&categoria=<?php echo $categoria; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
    <?php elseif ($evento_id): ?>
        <div class="alert alert-info mt-5">No se encontraron resultados para este evento.</div>
    <?php endif; ?>
</div>

<!-- Incluir Bootstrap JS desde el CDN -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Incluir Select2 desde el CDN -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Script AJAX para la búsqueda -->
<script>
$(document).ready(function() {
    $('#evento_id').select2({
        placeholder: "Selecciona o busca un evento",
        allowClear: true
    });

    // AJAX para el campo de búsqueda
    $('#search').on('input', function() {
        var searchQuery = $(this).val();
        var evento_id = $('#evento_id').val();
        var modalidad = $('#modalidad').val();
        var categoria = $('#categoria').val();

        $.ajax({
            url: 'buscar.php',
            method: 'GET',
            data: {
                search: searchQuery,
                evento_id: evento_id,
                modalidad: modalidad,
                categoria: categoria
            },
            success: function(data) {
                $('#resultados').html(data);  // Actualizar la tabla de resultados
            }
        });
    });
});

// Función para limpiar los filtros
function limpiarFiltros() {
    var url = new URL(window.location.href);
    url.searchParams.delete('modalidad');
    url.searchParams.delete('categoria');
    window.location.href = url.href;
}
</script>
</body>
</html>
