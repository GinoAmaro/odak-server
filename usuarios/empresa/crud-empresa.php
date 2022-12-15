<?php

require('../../conexion.php');
$conexionBD = Conectar();

require('../../login/correo.php');

if (isset($_GET["categoria"])) {
    $data = json_decode(file_get_contents("php://input"));
    $consulta = $_GET["categoria"];
    $sqlodak = mysqli_query($conexionBD, "SELECT * FROM categoria WHERE descripcion LIKE '%$consulta%'");
    if (mysqli_num_rows($sqlodak) > 0) {
        $categoria = mysqli_fetch_all($sqlodak, MYSQLI_ASSOC);
        echo json_encode($categoria);
        exit();
    } else {
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
    $titulo_descripcion = $data->titulo_descripcion;
    $descripcion = $data->descripcion;
    $imagen_logo = $data->imagen_logo;
    $imagen_fondo = $data->imagen_fondo;
    $twitter = $data->twitter;
    $facebook = $data->facebook;
    $whatsapp = $data->whatsapp;
    $instagram = $data->instagram;
    $linkedin = $data->linkedin;
    $sql = "INSERT INTO `empresa`( `rut`, `nombre_fantasia`, `categoria`, `comuna`, `direccion`, `telefono`, `correo`,`titulo_descripcion`, `descripcion`, `imagen_logo`, `imagen_fondo`, `twitter`, `facebook`, `whatsapp`, `instagram`, `linkedin`)
     VALUES ('$rut','$nombre_fantasia',$categoria,$comuna,'$direccion','$telefono','$correo','$titulo_descripcion','$descripcion','$imagen_logo','$imagen_fondo','$twitter','$facebook','$whatsapp','$instagram','$linkedin')";

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
    $consulta = "SELECT e.id,e.rut,e.nombre_fantasia,c.descripcion as 'categoria',co.descripcion as 'comuna' ,e.direccion,e.telefono,e.correo,e.titulo_descripcion,e.descripcion,e.imagen_logo,e.imagen_fondo,e.twitter,e.facebook,e.whatsapp,e.instagram,e.linkedin
    FROM empresa e, categoria c, comuna co
    WHERE (e.categoria=c.id) AND (e.comuna=co.id) AND e.id=" . $_GET["consultarEmpresa"];
    $sqlodak = mysqli_query($conexionBD, $consulta);
    if (mysqli_num_rows($sqlodak) > 0) {
        $empresa = mysqli_fetch_all($sqlodak, MYSQLI_ASSOC);
        echo json_encode($empresa);
        exit();
    } else {
        echo json_encode(["mensaje" => 'no se encontró la empresa']);
    }
}

if (isset($_GET["consultarParaEditar"])) {
    $consulta = "SELECT * FROM empresa WHERE id=" . $_GET["consultarParaEditar"];
    $sqlodak = mysqli_query($conexionBD, $consulta);
    if (mysqli_num_rows($sqlodak) > 0) {
        $empresa = mysqli_fetch_all($sqlodak, MYSQLI_ASSOC);
        echo json_encode($empresa);
        exit();
    } else {
        echo json_encode(["mensaje" => 'no se encontró la empresa']);
    }
}

if (isset($_GET["actualizarEmpresa"])) {
    $data = json_decode(file_get_contents("php://input"));
    $id = $data->id;
    $rut = $data->rut;
    $nombre_fantasia = $data->nombre_fantasia;
    $categoria = $data->categoria;
    $comuna = $data->comuna;
    $direccion = $data->direccion;
    $telefono = $data->telefono;
    $correo = $data->correo;
    $titulo_descripcion = $data->titulo_descripcion;
    $descripcion = $data->descripcion;
    $imagen_logo = $data->imagen_logo;
    $imagen_fondo = $data->imagen_fondo;
    $twitter = $data->twitter;
    $facebook = $data->facebook;
    $whatsapp = $data->whatsapp;
    $instagram = $data->instagram;
    $linkedin = $data->linkedin;
    $sql = "UPDATE empresa SET rut='$rut', nombre_fantasia='$nombre_fantasia', categoria=$categoria, comuna=$comuna, direccion='$direccion', telefono='$telefono',
            correo='$correo', titulo_descripcion='$titulo_descripcion', descripcion='$descripcion', imagen_logo='$imagen_logo', imagen_fondo='$imagen_fondo',
            twitter='$twitter', facebook='$facebook', whatsapp='$whatsapp', instagram='$instagram', linkedin='$linkedin'
            WHERE id=$id";
    $sqlodak = mysqli_query($conexionBD, $sql);
    if ($sqlodak) {
        echo json_encode(["mensaje" => 'Cambios realizados']);
    } else {
        echo json_encode(["mensaje" => 'Error en la sintaxis']);
    }

    exit();
}

if (isset($_GET["cotizarEmpresa"])) {
    $data = json_decode(file_get_contents("php://input"));
    $empresa = $data->empresa;
    $cliente = $data->cliente;
    $correo_cliente = $data->correo_cliente;
    $telefono_cliente = $data->telefono_cliente;
    $solicitud_cliente = $data->solicitud_cliente;
    $fecha = Hora($fecha);

    $sql = "INSERT INTO cotizacion (empresa,cliente,correo_cliente,telefono_cliente,solicitud_cliente,fecha_solicitud,estado,decision)
            VALUES ($empresa,'$cliente','$correo_cliente','$telefono_cliente','$solicitud_cliente','$fecha',0,'Pendiente')";
    $sqlodak = mysqli_query($conexionBD, $sql);

    $sqlseguimiento = "INSERT INTO seguimiento (cotizacion,fecha,descripcion,estado)
                       VALUES ((SELECT MAX(id) FROM cotizacion WHERE empresa=$empresa),'$fecha','Cotización solicitada','Pendiente')";
    $sqlodakSeguimiento = mysqli_query($conexionBD, $sqlseguimiento);

    $sqlodakCorreo = mysqli_query($conexionBD, "SELECT c.id as 'id',c.correo_cliente as 'correo_cliente',c.solicitud_cliente as 'solicitud_cliente',c.cliente as 'cliente'
                                                FROM seguimiento s,cotizacion c
                                                WHERE (c.id=s.cotizacion) AND cotizacion=(SELECT MAX(id) FROM cotizacion WHERE empresa=$empresa)");
    if (mysqli_num_rows($sqlodakCorreo) > 0) {
        $respuesta = mysqli_fetch_all($sqlodakCorreo, MYSQLI_ASSOC);
        $id_seguimiento = $respuesta[0]['id'];
        $correo = $respuesta[0]['correo_cliente'];
        $descripcion = $respuesta[0]['solicitud_cliente'];
        $clienteCotizacion = $respuesta[0]['cliente'];
        echo enviarSeguimiento($correo, $clienteCotizacion, $id_seguimiento, $descripcion);
    }
    if ($sqlodak) {
        echo json_encode(["mensaje" => 'Cotización Enviada']);
    } else {
        echo json_encode(["mensaje" => 'Error en la sintaxis']);
    }
}

if (isset($_GET["landingEmpresa"])) {
    $consulta = "SELECT id, imagen_logo, nombre_fantasia FROM empresa ORDER BY id DESC LIMIT 4;";
    $sqlodak = mysqli_query($conexionBD, $consulta);
    if (mysqli_num_rows($sqlodak) > 0) {
        $empresa = mysqli_fetch_all($sqlodak, MYSQLI_ASSOC);
        echo json_encode($empresa);
        exit();
    } else {
        echo json_encode(["mensaje" => 'no se encontró la empresa']);
        exit();
    }
}

if (isset($_GET["contarCotizacion"])) {
    $consulta = "SELECT empresa, COUNT(ID) AS 'cantidad' FROM cotizacion WHERE (empresa=" . $_GET["contarCotizacion"] . ") AND decision='Pendiente';";
    $sqlodak = mysqli_query($conexionBD, $consulta);
    if (mysqli_num_rows($sqlodak) > 0) {
        $empresa = mysqli_fetch_all($sqlodak, MYSQLI_ASSOC);
        echo json_encode($empresa);
        exit();
    } else {
        echo json_encode(["mensaje" => 'no se encontró la empresa']);
    }
}

if (isset($_GET["contarTareas"])) {
    $consulta = "SELECT c.empresa as 'empresa',COUNT(t.id) as 'cantidad'
                 FROM cotizacion c, tareas t
                 WHERE (t.cotizacion=c.id) AND (c.empresa=" . $_GET["contarTareas"] . ") AND (T.fk_estado <> 3)";
    $sqlodak = mysqli_query($conexionBD, $consulta);
    if (mysqli_num_rows($sqlodak) > 0) {
        $empresa = mysqli_fetch_all($sqlodak, MYSQLI_ASSOC);
        echo json_encode($empresa);
        exit();
    } else {
        echo json_encode(["mensaje" => 'no hay tareas pendientes']);
    }
}

if (isset($_GET["grillaEmpresa"])) {
    $consulta = "SELECT e.id as 'id', e.rut as 'rut', e.nombre_fantasia as 'nombre_fantasia', e.categoria as 'categoria', e.comuna as 'comuna', e.direccion as 'direccion',
                    e.telefono as 'telefono', e.correo as 'correo', e.titulo_descripcion as 'titulo_descripcion', e.descripcion as 'descripcion', e.twitter as 'twitter',
                    e.facebook as 'facebook', e.whatsapp as 'whatsapp', e.instagram as 'instagram', e.linkedin as 'linkedin', e.imagen_logo as 'imagen_logo', e.imagen_fondo as 'imagen_fondo'
                 FROM referencias r,empresa e
                 WHERE ((r.empresa=e.id) AND  (r.descripcion like '%" . $_GET["grillaEmpresa"] . "%')) OR (e.descripcion like '%" . $_GET["grillaEmpresa"] . "%')
                 GROUP BY e.id;";
    $sqlodak = mysqli_query($conexionBD, $consulta);
    if (mysqli_num_rows($sqlodak) > 0) {
        $empresa = mysqli_fetch_all($sqlodak, MYSQLI_ASSOC);
        echo json_encode($empresa);
        exit();
    } else {
        echo json_encode(["mensaje" => 'referencias no encontradas']);
    }
}


if (isset($_GET["agregarReferencia"])) {
    $data = json_decode(file_get_contents("php://input"));
    $empresa = $data->empresa;
    $descripcion = $data->descripcion;

    $sql = "INSERT INTO referencias (empresa,descripcion) VALUES ($empresa,'$descripcion')";
    $sqlodak = mysqli_query($conexionBD, $sql);
    if ($sqlodak) {
        echo json_encode(["mensaje" => 'Referencia Agregada']);
    } else {
        echo json_encode(["mensaje" => 'Error en la sintaxis']);
    }
}

if (isset($_GET["buscarReferencia"])) {
    $consulta = "SELECT * FROM referencias WHERE empresa=" . $_GET["buscarReferencia"];
    $sqlodak = mysqli_query($conexionBD, $consulta);
    if (mysqli_num_rows($sqlodak) > 0) {
        $empresa = mysqli_fetch_all($sqlodak, MYSQLI_ASSOC);
        echo json_encode($empresa);
        exit();
    } else {
        echo json_encode(["mensaje" => 'referencias no encontradas']);
    }
}

if (isset($_GET["borrarReferencia"])) {
    $sqlodak = mysqli_query($conexionBD, "DELETE FROM referencias WHERE id=" . $_GET["borrarReferencia"]);
    if ($sqlodak) {
        echo json_encode(["success" => 1]);
        exit();
    } else {
        echo json_encode(["success" => 0]);
    }
}

if (isset($_GET["listarCotizaciones"])) {
    $consulta = "SELECT id,empresa,cliente,correo_cliente,telefono_cliente,solicitud_cliente,fecha_solicitud 
                 FROM cotizacion WHERE (empresa=" . $_GET["listarCotizaciones"] . ") AND (estado=0) AND decision='Pendiente'";
    $sqlodak = mysqli_query($conexionBD, $consulta);
    if (mysqli_num_rows($sqlodak) > 0) {
        $empresa = mysqli_fetch_all($sqlodak, MYSQLI_ASSOC);
        echo json_encode($empresa);
        exit();
    } else {
        echo json_encode(["mensaje" => 'no hay nuevas cotizaciones']);
    }
}

if (isset($_GET["listarColaboradoresCotizacion"])) {
    $consulta = "SELECT id, CONCAT(nombre,' ',apellidos) as 'nombre'
                 FROM usuario
                 WHERE (empresa=" . $_GET["listarColaboradoresCotizacion"] . ") AND (tipo=3) AND (estado=1) ";
    $sqlodak = mysqli_query($conexionBD, $consulta);
    if (mysqli_num_rows($sqlodak) > 0) {
        $empresa = mysqli_fetch_all($sqlodak, MYSQLI_ASSOC);
        echo json_encode($empresa);
        exit();
    } else {
        echo json_encode(["mensaje" => 'no hay colaboradores']);
    }
}

if (isset($_GET["resolverCotizacion"])) {
    $data = json_decode(file_get_contents("php://input"));
    $id = $data->id;
    $decision = $data->decision;
    $colaborador = $data->colaborador;
    $fecha = Hora($fecha);

    $desicion = "UPDATE cotizacion SET decision='$decision' WHERE id=$id";
    $sqlodak = mysqli_query($conexionBD, $desicion);

    $desicion = "INSERT INTO seguimiento (cotizacion,fecha,descripcion,estado) VALUES ($id,'$fecha','Respuesta Empresa','$decision')";
    $sqlodak = mysqli_query($conexionBD, $desicion);

    if ($decision == 'Aceptada') {
        $tarea = "INSERT INTO tareas (cotizacion,descripcion_tarea,fk_categoria,inicio_tarea,fk_prioridad,fk_estado,contenedor_id,usuario_id)
                  VALUES ($id,(SELECT solicitud_cliente FROM cotizacion WHERE id=$id),1,'$fecha',3,1,1,$colaborador)";
        $sqlodak = mysqli_query($conexionBD, $tarea);
        $enviarTarea = "SELECT u.correo as 'correo',CONCAT(u.nombre,' ',u.apellidos) as 'nombre',e.nombre_fantasia as 'nombre_fantasia',t.descripcion_tarea as 'descripcion_tarea'
                        FROM tareas t, usuario u, empresa e, cotizacion c
                        WHERE (t.usuario_id=u.id) AND (t.cotizacion=c.id) AND (c.empresa=e.id) AND (t.usuario_id=$colaborador) AND (c.id=$id)";
        $sqlodak = mysqli_query($conexionBD, $enviarTarea);
        if (mysqli_num_rows($sqlodak) > 0) {
            $Respuestatarea = mysqli_fetch_all($sqlodak, MYSQLI_ASSOC);
            $correo = $Respuestatarea[0]['correo'];
            $nombre = $Respuestatarea[0]['nombre'];
            $nombre_fantasia = $Respuestatarea[0]['nombre_fantasia'];
            $descripcion_tarea = $Respuestatarea[0]['descripcion_tarea'];
            enviarTarea($correo, $nombre, $nombre_fantasia, $descripcion_tarea);
        }
    }

    $consulta = "SELECT correo_cliente,cliente,id,decision FROM cotizacion WHERE id=$id";

    $sqlodakRespuesta = mysqli_query($conexionBD, $consulta);
    if (mysqli_num_rows($sqlodakRespuesta) > 0) {
        $respuesta = mysqli_fetch_all($sqlodakRespuesta, MYSQLI_ASSOC);
        $correo = $respuesta[0]['correo_cliente'];
        $usuario = $respuesta[0]['cliente'];
        $seguimiento = $respuesta[0]['id'];
        $descripcion = $respuesta[0]['decision'];
        echo enviarRepuesta($correo, $usuario, $seguimiento, $descripcion);
    }

    if ($decision == 'Aceptada') {
        echo json_encode(["mensaje" => 'Solicitud Aceptada']);
    } else {
        echo json_encode(["mensaje" => 'Solicitud Rechazada']);
    }
}

if (isset($_GET["buscarCotizacion"])) {
    $consulta = "SELECT e.nombre_fantasia as 'nombre_fantasia', c.cliente as 'cliente', c.correo_cliente as 'correo_cliente', c.solicitud_cliente as 'solicitud_cliente', c.fecha_solicitud as 'fecha_solicitud'
    FROM cotizacion c, empresa e
    WHERE (c.empresa=e.id) AND (c.id=" . $_GET["buscarCotizacion"] . ")";
    $sqlodak = mysqli_query($conexionBD, $consulta);
    if (mysqli_num_rows($sqlodak) > 0) {
        $empresa = mysqli_fetch_all($sqlodak, MYSQLI_ASSOC);
        echo json_encode($empresa);
        exit();
    } else {
        echo json_encode(["mensaje" => 'Numero de seguimiento no encontrado']);
    }
}

if (isset($_GET["buscarSeguimiento"])) {
    $consulta = "SELECT fecha, descripcion, estado FROM seguimiento WHERE cotizacion=" . $_GET["buscarSeguimiento"];
    $sqlodak = mysqli_query($conexionBD, $consulta);
    if (mysqli_num_rows($sqlodak) > 0) {
        $empresa = mysqli_fetch_all($sqlodak, MYSQLI_ASSOC);
        echo json_encode($empresa);
        exit();
    } else {
        echo json_encode(["mensaje" => 'Numero de seguimiento no encontrado']);
    }
}
