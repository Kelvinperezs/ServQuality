<?php
/*********************************************************************
    secure.inc.php
**********************************************************************/
if(!strcasecmp(basename($_SERVER['SCRIPT_NAME']),basename(__FILE__))) die('Kwaheri rafiki!');
if(!file_exists('client.inc.php')) die('Fatal Error.');
require_once('client.inc.php');
//El usuario debe iniciar sesión en!
if(!$thisclient || !$thisclient->getId() || !$thisclient->isValid()){
    require('./login.php');
    exit;
}
$thisclient->refreshSession();
?>
