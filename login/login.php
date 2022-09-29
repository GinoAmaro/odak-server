<?php

require __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require('../conexion.php');
$conexionBD = Conectar();


if (isset($_GET["login"])) {
    $data = json_decode(file_get_contents("php://input"));
    $correo = $data->correo;
    $contrasena = $data->contrasena;
    $sqlodak = mysqli_query($conexionBD, "SELECT * FROM usuario WHERE correo='$correo'");
    if (mysqli_num_rows($sqlodak) == 0) {
        echo json_encode(["mensaje" => 'Correo no registrado']);
        exit();
    }
    $sqlodak = mysqli_query($conexionBD, "SELECT * FROM usuario WHERE correo='$correo' and contrasena=md5('$contrasena')");
    if (mysqli_num_rows($sqlodak) > 0) {
        $login = mysqli_fetch_all($sqlodak, MYSQLI_ASSOC);
        $id = $login[0]['id'];
        $payload = [
            'iat' => 1356999924,
            'exp' => time() + 10000,
            'id' => $id
        ];
        $key = 'ODAK';
        $jwt = JWT::encode($payload, $key, 'HS256');
        $sqlodak = mysqli_query($conexionBD, "UPDATE usuario SET token='" . $jwt . "' WHERE id='$id'");
        $sqlodak = mysqli_query($conexionBD, "SELECT * FROM usuario WHERE correo='" . $correo . "'");
        mysqli_num_rows($sqlodak);
        $login = mysqli_fetch_all($sqlodak, MYSQLI_ASSOC);
        echo json_encode($login);
        exit();
    } else {
        echo json_encode(["mensaje" => 'Contraseña incorrecta']);
        exit();
    }
}

if (isset($_GET["agregarUsuario"])) {
    $data = json_decode(file_get_contents("php://input"));
    $nombre = $data->nombre;
    $apellidos = $data->apellidos;
    $correo = $data->correo;
    $contrasena = $data->contrasena;

    $sqlodak = mysqli_query($conexionBD, "SELECT * FROM usuario WHERE correo='$correo'");
    if (mysqli_num_rows($sqlodak) > 0) {
        echo json_encode(["mensaje" => 'este correo ya existe']);
        exit();
    }

    if (($nombre != "") && ($apellidos != "") && ($correo != "") && ($contrasena != "")) {
        $sqlodak = mysqli_query($conexionBD, "INSERT INTO usuario (nombre,apellidos,correo,contrasena,empresa,tipo,estado,token) VALUES('$nombre','$apellidos','$correo',md5('$contrasena'),null,2,1,null)");
        echo json_encode(["success" => 0]);
    }
    exit();
}

if (isset($_GET["idUsuario"])) {
    $jwt = $_GET["idUsuario"];
    $key = 'ODAK';
    $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    if (!$decoded) {
        echo json_encode([["mensaje" => 'token no es correcto']]);
        exit();
    }
    try {
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    } catch (error) {
        echo json_encode([["mensaje" => 'validar']]);
        exit();
    }
    $id = $decoded->id;
    $sqlodak = mysqli_query($conexionBD, "SELECT * FROM usuario WHERE id='$id'");
    if (mysqli_num_rows($sqlodak) > 0) {
        $idUsuario = mysqli_fetch_all($sqlodak, MYSQLI_ASSOC);
        echo json_encode($idUsuario);
    } else {
        echo json_encode([["mensaje" => 'validar']]);
    }
}
