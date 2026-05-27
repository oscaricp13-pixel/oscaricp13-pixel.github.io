<?php

header('Content-Type: application/json');

$conexion = new mysqli("localhost", "root", "", "yedi");

if($conexion->connect_error){
    die(json_encode(["success" => false, "mensaje" => "Error de conexión"]));
}

$data     = json_decode(file_get_contents("php://input"), true);
$nombre   = trim($data['nombre']   ?? '');
$usuario  = trim($data['usuario']  ?? '');
$password = trim($data['password'] ?? '');
$rol      = trim($data['rol']      ?? '');

if(!$nombre || !$usuario || !$password || !$rol){
    echo json_encode(["success" => false, "mensaje" => "Faltan campos"]);
    exit;
}

// Normalizar rol a minúsculas para consistencia
// 'Comprador' → 'comprador' | 'Vendedor' → 'vendedor'
$rol = strtolower($rol);

// Verificar si ya existe
$check = $conexion->prepare("SELECT id FROM usuarios WHERE usuario = ?");
$check->bind_param("s", $usuario);
$check->execute();
$check->store_result();

if($check->num_rows > 0){
    echo json_encode(["success" => false, "mensaje" => "El usuario ya existe"]);
    exit;
}
$check->close();

// Insertar con prepared statement (evita SQL injection)
$stmt = $conexion->prepare(
    "INSERT INTO usuarios (nombre, usuario, password, rol) VALUES (?, ?, ?, ?)"
);
$stmt->bind_param("ssss", $nombre, $usuario, $password, $rol);

if($stmt->execute()){
    echo json_encode(["success" => true]);
}else{
    echo json_encode(["success" => false, "mensaje" => "Error: " . $stmt->error]);
}

$stmt->close();
$conexion->close();

?>
