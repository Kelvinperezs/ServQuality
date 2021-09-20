<?php

require('staff.inc.php');


ini_set('display_errors','0'); //Disable error display
ini_set('display_startup_errors','0');

//TODO: desactivar el acceso directo a través del navegador ? i, e Todo pedido debe haber CONSULTE? 

if(!defined('INCLUDE_DIR'))	Http::response(500,'config error');

if(!$thisuser || !$thisuser->isValid()) {
	Http::response(401,'Acceso Denegado. IP '.$_SERVER['REMOTE_ADDR']);
	exit;
}

//---------comprobar   necesaria --------//
if(!$_REQUEST['api'] || !$_REQUEST['f']){
    Http::response(416,'Parametros incorrectos');
    exit;
}

define('OSTAJAXINC',TRUE);
$file='ajax.'.Format::file_name(strtolower($_REQUEST['api'])).'.php';
if(!file_exists(INCLUDE_DIR.$file)){
    Http::response(405,'M&eacute;todo incorrecto');
    exit;
}

$class=ucfirst(strtolower($_REQUEST['api'])).'AjaxAPI';
$func=$_REQUEST['f'];

if(is_callable($func)){ //si la función es B4 exigible incluimos la obra archivo de origen con el usuario...
Http::response(500,'Esto es seguridad ajax assjax '.$_SERVER['REMOTE_ADDR']);
exit;
}
require(INCLUDE_DIR.$file);

if(!is_callable(array($class,$func))){
 Http::response(416,'M&eacute;todo/llamada no v&aacute;lida '.Format::htmlchars($func));
 exit;
}

$response=@call_user_func(array($class,$func),$_REQUEST);
Http::response(200,$response);
exit;
?>
