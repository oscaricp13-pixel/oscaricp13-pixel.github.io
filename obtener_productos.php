<?php

header('Content-Type: application/json');

$conexion = new mysqli(
    "localhost",
    "root",
    "",
    "yedi"
);

if($conexion->connect_error){
    echo json_encode([]);
    exit;
}

$sql = "SELECT * FROM productos";

$resultado = $conexion->query($sql);

$productos = [];

while($fila = $resultado->fetch_assoc()){

    $productos[] = [
        "name"  => $fila['nombre'],
        "price" => (float)$fila['precio'],
        "img"   => $fila['imagen'],
        "desc"  => $fila['descripcion']
    ];
}

echo json_encode($productos);

$conexion->close();

?>
