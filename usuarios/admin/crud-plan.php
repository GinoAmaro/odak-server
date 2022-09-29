<?php

require('../../conexion.php');
$conexionBD = Conectar();

if (isset($_GET["agregarPlan"])) {
    $data = json_decode(file_get_contents("php://input"));
    $descripcion = $data->descripcion;
    $costo = $data->costo;
    $detalle = $data->detalle;
    $usuario_cantidad = $data->usuario_cantidad;
    $estado = $data->estado;
    if (($descripcion != "") && ($costo != "")) {
        $sqlodak = mysqli_query($conexionBD, "INSERT INTO plan (descripcion,costo,detalle,usuario_cantidad,estado) VALUES('$descripcion',$costo,'$detalle',$usuario_cantidad,$estado)");
        echo json_encode(["success" => 0]);
    }
    exit();
}

if (isset($_GET["consultarPlan"])) {
    $sqlodak = mysqli_query($conexionBD, "SELECT * FROM plan WHERE id=" . $_GET["consultarPlan"]);
    if (mysqli_num_rows($sqlodak) > 0) {
        $planes = mysqli_fetch_all($sqlodak, MYSQLI_ASSOC);
        echo json_encode($planes);
        exit();
    } else {
        echo json_encode(["success" => 0]);
    }
}

if (isset($_GET["borrarPlan"])) {
    $sqlodak = mysqli_query($conexionBD, "DELETE FROM plan WHERE id=" . $_GET["borrarPlan"]);
    if ($sqlodak) {
        echo json_encode(["success" => 1]);
        exit();
    } else {
        echo json_encode(["success" => 0]);
    }
}

if (isset($_GET["actualizarPlan"])) {
    $data = json_decode(file_get_contents("php://input"));
    $id = (isset($data->id)) ? $data->id : $_GET["actualizarPlan"];
    $descripcion = $data->descripcion;
    $costo = $data->costo;
    $detalle = $data->detalle;
    $usuario_cantidad = $data->usuario_cantidad;
    $estado = $data->estado;

    $sqlodak = mysqli_query($conexionBD, "UPDATE plan SET descripcion='$descripcion', costo=$costo, detalle='$detalle', usuario_cantidad=$usuario_cantidad, estado='$estado' WHERE id='$id'");
    echo json_encode(["success" => 1]);
    exit();
}


$sqlodak = mysqli_query($conexionBD, "SELECT * FROM plan ");
if (mysqli_num_rows($sqlodak) > 0) {
    $planes = mysqli_fetch_all($sqlodak, MYSQLI_ASSOC);
    echo json_encode($planes);
} else {
    echo json_encode([["success" => 0]]);
}
