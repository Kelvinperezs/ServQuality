<?php

require_once('../main.inc.php');
if(!defined('INCLUDE_DIR')) die('Error Fatal. Que rollo');

require_once(INCLUDE_DIR.'class.staff.php');

$msg=$_SESSION['_staff']['auth']['msg'];
$msg=$msg?$msg:'Se Requiere Autenticaci&oacute;n';
if($_POST && (!empty($_POST['username']) && !empty($_POST['passwd']))){
    //$ _SESSION ['_ Personal'] = array (); #Descomente Desactivar huelgas de inicio de sesión.
    $msg='Datos Incorrectos';
    if($_SESSION['_staff']['laststrike']) {
        if((time()-$_SESSION['_staff']['laststrike'])<$cfg->getStaffLoginTimeout()) {
            $msg='Excesivos intentos fallidos de inicio de sesi&oacute;n';
            $errors['err']='Has llegado al m&aacute;ximo de intentos de conexi&oacute;n fallidos.';
        }else{ //Tiempo de espera ha terminado.
            //Restablecer el contador para la próxima ronda de intentos después de que el tiempo de espera.
            $_SESSION['_staff']['laststrike']=null;
            $_SESSION['_staff']['strikes']=0;
        }
    }
    if(!$errors && ($user=new StaffSession($_POST['username'])) && $user->getId() && $user->check_passwd($_POST['passwd'])){
        //actualizar última entrada.
        db_query('UPDATE '.STAFF_TABLE.' SET lastlogin=NOW() WHERE staff_id='.db_input($user->getId()));
        //Averigua dónde se dirige el usuario - destino!
        $dest=$_SESSION['_staff']['auth']['dest'];
        //configuracion de sesion !
        $_SESSION['_staff']=array(); 
        $_SESSION['_staff']['userID']=$_POST['username'];
        $user->refreshSession(); //establece.
        $_SESSION['TZ_OFFSET']=$user->getTZoffset();
        $_SESSION['daylight']=$user->observeDaylight();
        Sys::log(LOG_DEBUG,'Inicio de sesi&oacute;n de Staff',sprintf("%s Identificado como [%s]",$user->getUserName(),$_SERVER['REMOTE_ADDR'])); //Debug.
        //Redirigida al destino original. (asegúrese de que no está redirigiendo a la página iniciar sesión.)
        $dest=($dest && (!strstr($dest,'login.php') && !strstr($dest,'ajax.php')))?$dest:'index.php';
        session_write_close();
        session_regenerate_id();
        @header("Location: $dest");
        require_once('index.php'); //Encabezado por si acaso está en mal estado.
        exit;
    }
    $_SESSION['_staff']['strikes']+=1;
    if(!$errors && $_SESSION['_staff']['strikes']>$cfg->getStaffMaxLogins()) {
        $msg='Acceso Denegado';
        $errors['err']='&iquest;Olvidaste tus datos de conexi&oacute;n?. Contacta con el Administrador';
        $_SESSION['_staff']['laststrike']=time();
        $alert='&iquest;Excesivos intentos de conexión por un miembro del personal?'."\n".
               'Nombre de usuario: '.$_POST['username']."\n".'IP: '.$_SERVER['REMOTE_ADDR']."\n".'TIME: '.date('M j, Y, g:i a T')."\n\n".
               'Intentos #'.$_SESSION['_staff']['strikes']."\n".'Tiempo de espera: '.($cfg->getStaffLoginTimeout()/60)." Minutos \n\n";
        Sys::log(LOG_ALERT,'Excesivos intentos de conexi&oacute;n (Miembro del Staff)',$alert,($cfg->alertONLoginError()));
    }elseif($_SESSION['_staff']['strikes']%2==0){ //Entre cada dos intento de acceso no como una advertencia.
        $alert='Nombre de usuario: '.$_POST['username']."\n".'IP: '.$_SERVER['REMOTE_ADDR'].
               "\n".'Hora: '.date('M j, Y, g:i a T')."\n\n".'Intentos #'.$_SESSION['_staff']['strikes'];
        Sys::log(LOG_WARNING,'Intento de inicio de sesi&oacute;n fallido (Miembro del Staff)',$alert);
    }
}
define("OSTSCPINC",TRUE); 
$login_err=($_POST)?true:false;
include_once(INCLUDE_DIR.'staff/login.tpl.php');
?>