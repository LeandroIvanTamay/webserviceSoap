<?php
require_once('core/nusoap-0.9.5/lib/nusoap.php');
require_once('core/VistaJson.php');

$cliente = new nusoap_client("http://localhost/webserviceRestaurantesSoap/server.php",false);

//$elmer = array('id_estab'=>12,'opcion'=>"all",'controlador'=>"establecimientos");
//envio por post desde mvc
$cuerpo= file_get_contents('php://input');
//decodifica lo que recibe
$array= json_decode($cuerpo);

//almacena el resultado de la llama en esta variable
$respuesta = $cliente->call('usuario.post',array('parametros'=>$array));

//imprimir como json
$vista = new VistaJson();

$vista->imprimir($respuesta);
?>


