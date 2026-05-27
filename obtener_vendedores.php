<?php

header('Content-Type: application/json');

$conexion = new mysqli("localhost", "root", "", "yedi");

if($conexion->connect_error){
    echo json_encode([]);
    exit;
}

// LOWER() cubre 'vendedor', 'Vendedor', 'VENDEDOR'
$result = $conexion->query("
    SELECT usuario, nombre
    FROM usuarios
    WHERE LOWER(rol) = 'vendedor'
    ORDER BY nombre
");

$lista = [];
while($fila = $result->fetch_assoc()){
    $lista[] = $fila;
}

echo json_encode($lista);
$conexion->close();

?>
