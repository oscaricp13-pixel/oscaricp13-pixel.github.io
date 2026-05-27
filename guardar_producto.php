<?php

header('Content-Type: application/json');

$conexion = new mysqli(
    "localhost",
    "root",
    "",
    "yedi"
);

if($conexion->connect_error){
    die(json_encode(["success" => false]));
}

$data = json_decode(
    file_get_contents("php://input"),
    true
);

$nombre      = $data['nombre'];
$precio      = $data['precio'];
$imagen      = $data['imagen'];
$descripcion = $data['descripcion'];

$sql = "
INSERT INTO productos
(nombre, precio, imagen, descripcion)
VALUES (?, ?, ?, ?)
";

$stmt = $conexion->prepare($sql);

$stmt->bind_param(
    "sdss",
    $nombre,
    $precio,
    $imagen,
    $descripcion
);

if($stmt->execute()){
    echo json_encode(["success" => true]);
}else{
    echo json_encode([
        "success" => false,
        "error"   => $conexion->error
    ]);
}

$stmt->close();
$conexion->close();

?>
