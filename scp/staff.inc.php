<?php
/*************************************************************************
    staff.inc.php
**********************************************************************/
if(basename($_SERVER['SCRIPT_NAME'])==basename(__FILE__)) die('Kwaheri rafiki!'); //Say hi to our friend..
if(!file_exists('../main.inc.php')) die('Error Fatal... Busca soporte t&eacute;cnico');
define('ROOT_PATH','../'); //Path to the root dir.
require_once('../main.inc.php');

if(!defined('INCLUDE_DIR')) die('Error Fatal');

/*Algunos incluyen define más específica al personal única */
define('STAFFINC_DIR',INCLUDE_DIR.'staff/');
define('SCP_DIR',str_replace('//','/',dirname(__FILE__).'/'));

/* Definir etiqueta que incluyen archivos pueden comprobar */
define('OSTSCPINC',TRUE);
define('OSTSTAFFINC',TRUE);

/* Las tablas utilizadas por el personal solamente */
define('KB_PREMADE_TABLE',TABLE_PREFIX.'kb_premade');


/* incluir lo que se necesita en el panel de control personal */

require_once(INCLUDE_DIR.'class.staff.php');
require_once(INCLUDE_DIR.'class.nav.php');


/* Primera orden del día es ver si el usuario ha iniciado sesión en y con una sesión válida.
    El usuario debe ser válido más allá de este punto
    SÓLO súper administradores pueden acceder al servicio de asistencia en línea Estado.
*/

function staffLoginPage($msg) {
    $_SESSION['_staff']['auth']['dest']=THISPAGE;
    $_SESSION['_staff']['auth']['msg']=$msg;
    require(SCP_DIR.'login.php');
    exit;
}

$thisuser = new StaffSession($_SESSION['_staff']['userID']); /*siempre recargar???*/
//1) es el usuario se conectó para && real es personal.
if(!is_object($thisuser) || !$thisuser->getId() || !$thisuser->isValid()){
    $msg=(!$thisuser || !$thisuser->isValid())?'Se requiere autenticaci&oacute;n':'Se a desconectado por inactividad';
    staffLoginPage($msg);
    exit;
}

//2) si no el sistema súper admin..check y estado del grupo
if(!$thisuser->isadmin()){
    if($cfg->isHelpDeskOffline()){
        staffLoginPage('Sistema Deshabilitado');
        exit;
    }

    if(!$thisuser->isactive() || !$thisuser->isGroupActive()) {
        staffLoginPage('Acceso Denegado. Contacta al Administrador.');
        exit;
    }
}

//Mantener la actividad sesión activa
$thisuser->refreshSession();
//Compensado zona horaria del personal de Ajuste.
$_SESSION['TZ_OFFSET']=$thisuser->getTZoffset();
$_SESSION['daylight']=$thisuser->observeDaylight();

define('AUTO_REFRESH_RATE',$thisuser->getRefreshRate()*60);

//Clear some vars. we use in all pages.
$errors=array();
$msg=$warn=$sysnotice='';
$tabs=array();
$submenu=array();

if(defined('THIS_VERSION') && strcasecmp($cfg->getVersion(),THIS_VERSION)) {
    $errors['err']=$sysnotice=sprintf('Versi&oacute;n del Script %s En la base de datos la versi&oacute;n es %s',THIS_VERSION,$cfg->getVersion());
}elseif($cfg->isHelpDeskOffline()){
    $sysnotice='<strong>El Sistema esta en modo inactivo</strong> - El Centro de Ayuda esta deshabilitado. Solo los Administradores tienen acceso al Panel de Control.';
    $sysnotice.=' <a href="admin.php?t=pref">Habilitar</a>.';
}

$nav = new StaffNav(strcasecmp(basename($_SERVER['SCRIPT_NAME']),'admin.php')?'staff':'admin');
//Compruebe si hay cambio de contraseña forzada.
if($thisuser->forcePasswdChange()){
    require('profile.php'); //profile.php debe solicitar este archivo como requerir una vez para evitar problemas.
    exit;
}


?>
