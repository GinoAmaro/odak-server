<?php

require('../../conexion.php');
$conexionBD = Conectar();

if (isset($_GET["categoria"])) {
    $data = json_decode(file_get_contents("php://input"));
    $consulta = $_GET["categoria"];
    $sqlodak = mysqli_query($conexionBD, "SELECT * FROM categoria WHERE descripcion LIKE '%$consulta%'");
    if (mysqli_num_rows($sqlodak) > 0) {
        $categoria = mysqli_fetch_all($sqlodak, MYSQLI_ASSOC);
        echo json_encode($categoria);
        exit();
    } else {
        // echo json_encode(["id" => 0]);
        exit();
    }
    exit();
}

if (isset($_GET["registrarEmpresa"])) {
    $data = json_decode(file_get_contents("php://input"));
    $rut = $data->rut;
    $nombre_fantasia = $data->nombre_fantasia;
    $categoria = $data->categoria;
    $comuna = $data->comuna;
    $direccion = $data->direccion;
    $telefono = $data->telefono;
    $correo = $data->correo;
    $descripcion = $data->descripcion;
    $imagen_logo = $data->imagen_logo;
    $imagen_fondo = $data->imagen_fondo;
    $twitter = $data->twitter;
    $facebook = $data->facebook;
    $whatsapp = $data->whatsapp;
    $instagram = $data->instagram;
    $linkedin = $data->linkedin;
    $sql = "INSERT INTO `empresa`( `rut`, `nombre_fantasia`, `categoria`, `comuna`, `direccion`, `telefono`, `correo`, `descripcion`, `imagen_logo`, `imagen_fondo`, `twitter`, `facebook`, `whatsapp`, `instagram`, `linkedin`)
     VALUES ('$rut','$nombre_fantasia',$categoria,$comuna,'$direccion','$telefono','$correo','$descripcion','$imagen_logo','$imagen_fondo','$twitter','$facebook','$whatsapp','$instagram','$linkedin')";

    if ($rut === '') {
        echo json_encode(["mensaje" => 'Falta el rut']);
        exit();
    }

    if ($categoria === '') {
        echo json_encode(["mensaje" => 'Falta la categoria']);
        exit();
    }
    $sqlodak = mysqli_query($conexionBD, $sql);
    if ($sqlodak) {
        echo json_encode(["mensaje" => 'Empresa registrada']);
    } else {
        echo json_encode(["mensaje" => 'Error en la sintaxis']);
    }

    exit();
}

if (isset($_GET["consultarEmpresa"])) {
    $sqlodak = mysqli_query($conexionBD, "SELECT * FROM empresa WHERE id=" . $_GET["consultarEmpresa"]);
    if (mysqli_num_rows($sqlodak) > 0) {
        $planes = mysqli_fetch_all($sqlodak, MYSQLI_ASSOC);
        echo json_encode($planes);
        exit();
    } else {
        echo json_encode(["mensaje" => 'no se encontró la empresa']);
    }
}

if (isset($_GET["editarEmpresa"])) {


}