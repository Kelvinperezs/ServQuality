<?php
/*********************************************************************
    logout.php
**********************************************************************/

require('client.inc.php');
//Estamos verificando para asegurarse que el usuario está conectado antes de un cierre de sesión para evitar la sesión trucos de restablecimiento en exceso de los inicios de sesión
$_SESSION['_client']=array();
session_unset();
session_destroy();
header('Location: index.php');
require('index.php');
?>
