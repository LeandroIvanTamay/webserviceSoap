<?php
require_once('core/nusoap-0.9.5/lib/nusoap.php');
//require_once('controladores/establecimientos.php');
require_once('controladores/reservaciones.php');



// Create una instancia de soap
$server = new soap_server();
//nombre del webServce y namespace
$server->configureWSDL('server', 'urn:server');
$server->wsdl->schemaTargetNamespace = 'urn:server';
// decalaracion o publicacion del metodo
$server->register('usuario.post',
array('parametros' => 'xsd:Array'), // parameter
array('return' => 'xsd:Array'),     // output
'urn:server',                        // namespace
'urn:server#helloServer',            // soapaction
'rpc',                               // style
'encoded',                           // use
'Just say hello');                   // description
// Use the request to invoke the service
 
//$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA: '';
//$server->service($HTTP_RAW_POST_DATA);
//Hace el servicoo 
$server->service(file_get_contents("php://input"));

?>