<?php

header('Content-Type: application/json');

$conexion = new mysqli("localhost", "root", "", "yedi");

if($conexion->connect_error){
    echo json_encode([]);
    exit;
}

$usuario1 = trim($_GET['usuario1'] ?? '');
$usuario2 = trim($_GET['usuario2'] ?? '');
$desde_id = intval($_GET['desde_id'] ?? 0);

if(!$usuario1 || !$usuario2){
    echo json_encode([]);
    exit;
}

$stmt = $conexion->prepare("
    SELECT id, de_usuario, para_usuario, mensaje,
           DATE_FORMAT(fecha,'%H:%i') AS hora
    FROM mensajes
    WHERE id > ?
    AND (
        (de_usuario = ? AND para_usuario = ?)
        OR
        (de_usuario = ? AND para_usuario = ?)
    )
    ORDER BY fecha ASC
    LIMIT 100
");

if(!$stmt){
    echo json_encode([]);
    exit;
}

$stmt->bind_param("issss",
    $desde_id,
    $usuario1, $usuario2,
    $usuario2, $usuario1
);

$stmt->execute();
$result = $stmt->get_result();

$mensajes = [];
while($fila = $result->fetch_assoc()){
    $mensajes[] = $fila;
}

echo json_encode($mensajes);
$stmt->close();
$conexion->close();

?>
