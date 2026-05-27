<?php

header('Content-Type: application/json');

$conexion = new mysqli("localhost", "root", "", "yedi");

if($conexion->connect_error){
    echo json_encode([]);
    exit;
}

// Devuelve TODOS los compradores — sin filtrar por mensajes previos
// LOWER() cubre 'comprador', 'Comprador', 'cliente', 'Cliente'
$result = $conexion->query("
    SELECT usuario, nombre
    FROM usuarios
    WHERE LOWER(rol) IN ('comprador', 'cliente')
    ORDER BY nombre
");

$lista = [];
while($fila = $result->fetch_assoc()){
    $lista[] = $fila;
}

echo json_encode($lista);
$conexion->close();

?>
