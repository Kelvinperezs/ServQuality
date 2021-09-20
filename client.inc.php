<?php
/*********************************************************************
    client.inc.php
**********************************************************************/
    
if(!strcasecmp(basename($_SERVER['SCRIPT_NAME']),basename(__FILE__))) die('kwaheri rafiki!');

if(!file_exists('main.inc.php')) die('Fatal Error.');

require_once('main.inc.php');

if(!defined('INCLUDE_DIR')) die('Fatal error');

/*Algunos incluyen define más específico para cliente sólo */
define('CLIENTINC_DIR',INCLUDE_DIR.'client/');
define('OSTCLIENTINC',TRUE);

//Compruebe el estado del servicio de soporte.
if(!is_object($cfg) || !$cfg->getId() || $cfg->isHelpDeskOffline()) {
    include('./offline.php');
    exit;
}

//Forzado actualización? versión no coincide.
if(defined('THIS_VERSION') && strcasecmp($cfg->getVersion(),THIS_VERSION)) {
    die('El Sistema esta sin servicio, estamos actualizando.');
    exit;
}

/* incluir lo que se necesita en la materia cliente*/
require_once(INCLUDE_DIR.'class.client.php');
require_once(INCLUDE_DIR.'class.ticket.php');
require_once(INCLUDE_DIR.'class.dept.php');

//despejar algunas vars
$errors=array();
$msg='';
$thisclient=null;
//Asegúrese de que el usuario es válido .. antes de hacer cualquier otra cosa.
if($_SESSION['_client']['userID'] && $_SESSION['_client']['key'])
    $thisclient = new ClientSession($_SESSION['_client']['userID'],$_SESSION['_client']['key']);

//imprimir_r($_SESSION);
//es el usuario conectado?
if($thisclient && $thisclient->getId() && $thisclient->isValid()){
     $thisclient->refreshSession();
}

?>
