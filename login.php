<?php

require_once('main.inc.php');
if(!defined('INCLUDE_DIR')) die('Error Fatal');
define('CLIENTINC_DIR',INCLUDE_DIR.'client/');
define('OSTCLIENTINC',TRUE); 

require_once(INCLUDE_DIR.'class.client.php');
require_once(INCLUDE_DIR.'class.ticket.php');
//estamos listo
$loginmsg='Se Requiere Autenticación';
if($_POST && (!empty($_POST['lemail']) && !empty($_POST['lticket']))):
    $loginmsg='Requiere Autenticaci&oacute;n';
    $email=trim($_POST['lemail']);
    $ticketID=trim($_POST['lticket']);
    //$ _SESSION ['_ Cliente'] = array (); # Descomente para desactivar huelgas de inicio de sesión.
    
    //Compruebe el tiempo para el último máximo fallidos huelga entrada intento.
    $loginmsg='Datos incorrectos';
    if($_SESSION['_client']['laststrike']) {
        if((time()-$_SESSION['_client']['laststrike'])<$cfg->getClientLoginTimeout()) {
            $loginmsg='Excesivos intentos fallidos de inicio de sesi&oacute;n';
            $errors['err']='Has llegado al m&aacute;ximo de intentos de conexi&oacute;n fallidos. Intentalo m&aacute;s tarde otra vez o crea <a href="open.php">Un Ticket Nuevo</a>';
        }else{ //Tiempo de espera ha terminado.
            //Restablecer el contador para la próxima ronda de intentos después de que el tiempo de espera.
            $_SESSION['_client']['laststrike']=null;
            $_SESSION['_client']['strikes']=0;
        }
    }
    //A ver si podemos podido recuperar Identificación del ticket local asociada con el identificador dado
    if(!$errors && is_numeric($ticketID) && Validator::is_email($email) && ($tid=Ticket::getIdByExtId($ticketID))) {
        //En este momento sabemos que el ticket es válido.
        $ticket= new Ticket($tid);
        //TODO: 1)Verificar la tiempo del ticket es ... 3 meses máximo ?? 2) Debe ser las últimas 5 entradas?? 
        //Compruebe la dirección de correo electrónico dada.
        if($ticket->getId() && strcasecmp($ticket->getEMail(),$email)==0){
            //válido ... crear la sesión buenas  para el cliente.
            $user = new ClientSession($email,$ticket->getId());
            $_SESSION['_client']=array(); //claro.
            $_SESSION['_client']['userID']   =$ticket->getEmail(); //Email
            $_SESSION['_client']['key']      =$ticket->getExtId(); //Ticket ID --actúa como contraseña cuando se utiliza con el correo electrónico. véase más arriba.
            $_SESSION['_client']['token']    =$user->getSessionToken();
            $_SESSION['TZ_OFFSET']=$cfg->getTZoffset();
            $_SESSION['daylight']=$cfg->observeDaylightSaving();
            //Log login info...
            $msg=sprintf("%s/%s Conectado [%s]",$ticket->getEmail(),$ticket->getExtId(),$_SERVER['REMOTE_ADDR']);
            Sys::log(LOG_DEBUG,'User login',$msg);
            //redirigir entradas.php
            session_write_close();
            session_regenerate_id();
            @header("Location: tickets.php");
            require_once('tickets.php'); //Por si acaso. de cabecera ya ha sido enviada de error.
            exit;
        }
    }
    //Si llegamos a este punto sabemos que la entrada.
    $_SESSION['_client']['strikes']+=1;
    if(!$errors && $_SESSION['_client']['strikes']>$cfg->getClientMaxLogins()) {
        $loginmsg='Acceso Denegado';
        $errors['err']='&iquest;Olvidaste tus datos de acceso? Abre <a href="open.php">Un Ticket Nuevo</a>.';
        $_SESSION['_client']['laststrike']=time();
        $alert='Excessive login attempts by a client?'."\n".
                'Email: '.$_POST['lemail']."\n".'Ticket#: '.$_POST['lticket']."\n".
                'IP: '.$_SERVER['REMOTE_ADDR']."\n".'Hora:'.date('j M, Y, g:i a T')."\n\n".
                'Attempts #'.$_SESSION['_client']['strikes'];
        Sys::log(LOG_ALERT,'Excessive login attempts (client)',$alert,($cfg->alertONLoginError()));
    }elseif($_SESSION['_client']['strikes']%2==0){ //Entre cada dos intento de acceso no como una advertencia.
        $alert='Email: '.$_POST['lemail']."\n".'Ticket #: '.$_POST['lticket']."\n".'IP: '.$_SERVER['REMOTE_ADDR'].
               "\n".'Hora: '.date('j M, Y, g:i a T')."\n\n".'Attempts #'.$_SESSION['_client']['strikes'];
        Sys::log(LOG_WARNING,'Failed login attempt (client)',$alert);
    }
endif;
require(CLIENTINC_DIR.'header.inc.php');
require(CLIENTINC_DIR.'login.inc.php');
require(CLIENTINC_DIR.'footer.inc.php');
?>
