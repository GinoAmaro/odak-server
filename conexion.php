<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

function Conectar()
{
    $servidor = "localhost";
    $usuario = "root";
    $contrasenia = "";
    $nombreBaseDatos = "odak";
    $conexionBD = new mysqli($servidor, $usuario, $contrasenia, $nombreBaseDatos);
    return $conexionBD;
}

function Hora($Fecha)
{
    date_default_timezone_set('America/Santiago');
    $Fecha = date('Y-m-d G:i:s');
    return $Fecha;
}