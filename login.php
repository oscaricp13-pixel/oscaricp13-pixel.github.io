<?php

// RESPUESTA EN JSON
header('Content-Type: application/json');

// DATOS DE CONEXIÓN MYSQL WORKBENCH
$host = "localhost";
$usuarioBD = "root";
$passwordBD = "";
$baseDatos = "yedi";

// CONEXIÓN
$conexion = new mysqli(
    "localhost",
    "root",
    "",
    "yedi"
);

// VALIDAR CONEXIÓN
if($conexion->connect_error){

    echo json_encode([
        "success" => false,
        "mensaje" => "Error de conexión con MySQL"
    ]);

    exit;
}

// RECIBIR DATOS DEL FETCH
$data = json_decode(
    file_get_contents("php://input"),
    true
);

// VALIDAR DATOS
if(
    !isset($data['usuario']) ||
    !isset($data['password'])
){

    echo json_encode([
        "success" => false,
        "mensaje" => "Faltan datos"
    ]);

    exit;
}

$usuario = $data['usuario'];
$password = $data['password'];

// CONSULTA SQL
$sql = "
SELECT
    nombre,
    usuario,
    rol
FROM usuarios
WHERE usuario = ?
AND password = ?
";

// PREPARAR CONSULTA
$stmt = $conexion->prepare($sql);

$stmt->bind_param(
    "ss",
    $usuario,
    $password
);

$stmt->execute();

// RESULTADO
$resultado = $stmt->get_result();

// VALIDAR LOGIN
if($resultado->num_rows > 0){

    $fila = $resultado->fetch_assoc();

    echo json_encode([

        "success" => true,

        "nombre" => $fila['nombre'],

        "usuario" => $fila['usuario'],

        "rol" => $fila['rol']

    ]);

}else{

    echo json_encode([

        "success" => false,

        "mensaje" => "Usuario o contraseña incorrectos"

    ]);
}

// CERRAR
$stmt->close();
$conexion->close();

?>