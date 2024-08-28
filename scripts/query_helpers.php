<?php
require_once 'config.php';

function getEventos($conn) {
    $eventos = [];
    $sql = "SELECT id, nombre_evento, fecha_evento FROM eventos ORDER BY fecha_evento DESC";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $eventos[] = $row;
    }
    return $eventos;
}

function getModalidadesCategorias($conn, $evento_id, $modalidad = null) {
    $modalidades = [];
    $categorias = [];
    
    $sql = "SELECT DISTINCT modalidad, categoria FROM resultados WHERE evento_id = ?";
    $params = [$evento_id];
    $types = "i";
    
    if ($modalidad) {
        $sql .= " AND modalidad = ?";
        $params[] = $modalidad;
        $types .= "s";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        if (!in_array($row['modalidad'], $modalidades)) {
            $modalidades[] = $row['modalidad'];
        }
        if (!in_array($row['categoria'], $categorias)) {
            $categorias[] = $row['categoria'];
        }
    }

    return ['modalidades' => $modalidades, 'categorias' => $categorias];
}

function getResultados($conn, $evento_id, $modalidad, $categoria, $limit, $offset, $search = '') {
    $resultados = [];
    $sql = "SELECT numero, nombre, pos_general, pos_categoria, modalidad, categoria, tiempo 
            FROM resultados 
            WHERE evento_id = ? ";
    
    $params = [$evento_id];
    $types = "i";
    
    if ($modalidad) {
        $sql .= "AND modalidad = ? ";
        $params[] = $modalidad;
        $types .= "s";
    }
    if ($categoria) {
        $sql .= "AND categoria = ? ";
        $params[] = $categoria;
        $types .= "s";
    }
    if ($search) {
        $sql .= "AND (numero LIKE ? OR nombre LIKE ?) ";
        $params[] = '%' . $search . '%';
        $params[] = '%' . $search . '%';
        $types .= "ss";
    }
    
    $sql .= "LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    $types .= "ii";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $resultados[] = $row;
    }

    return $resultados;
}

function getTotalResultados($conn, $evento_id, $modalidad, $categoria) {
    $sql = "SELECT COUNT(*) as total FROM resultados WHERE evento_id = ?" . 
           ($modalidad ? " AND modalidad = ?" : "") . 
           ($categoria ? " AND categoria = ?" : "");
    
    $stmt = $conn->prepare($sql);
    
    $params = [$evento_id];
    $types = "i";
    
    if ($modalidad) {
        $params[] = $modalidad;
        $types .= "s";
    }
    if ($categoria) {
        $params[] = $categoria;
        $types .= "s";
    }
    
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $total = $result->fetch_assoc()['total'];

    return $total;
}
?>
