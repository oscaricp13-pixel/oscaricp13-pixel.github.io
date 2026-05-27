<?php

header('Content-Type: application/json');

$conexion = new mysqli("localhost", "root", "", "yedi");

if($conexion->connect_error){
    echo json_encode(["success" => false, "error" => "Conexión fallida"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$de   = trim($data['de']      ?? '');
$para = trim($data['para']    ?? '');
$msg  = trim($data['mensaje'] ?? '');

if(!$de || !$para || !$msg){
    echo json_encode(["success" => false, "error" => "Faltan datos: de=$de para=$para"]);
    exit;
}

$stmt = $conexion->prepare(
    "INSERT INTO mensajes (de_usuario, para_usuario, mensaje) VALUES (?, ?, ?)"
);

if(!$stmt){
    echo json_encode(["success" => false, "error" => "Prepare: " . $conexion->error]);
    exit;
}

$stmt->bind_param("sss", $de, $para, $msg);

if($stmt->execute()){
    echo json_encode(["success" => true, "id" => $stmt->insert_id]);
}else{
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conexion->close();

?>
