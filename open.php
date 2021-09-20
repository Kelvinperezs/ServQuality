<?php
require('client.inc.php');
define('SOURCE','Web'); //fuente de entrada.
$inc='open.inc.php';    
$errors=array();
if($_POST):
    $_POST['deptId']=$_POST['emailId']=0; 
    if(!$thisuser && $cfg->enableCaptcha()){
        if(!$_POST['captcha'])
            $errors['captcha']='Introduce el texto que aparece en la imagen.';
        elseif(strcmp($_SESSION['captcha'],md5($_POST['captcha'])))
            $errors['captcha']='Ese no era el texto - Intentalo de Nuevo';
    }
    //crea entrada y comprueba errores..
    if(($ticket=Ticket::create($_POST,$errors,SOURCE))){
        $msg='Ticket Creado';
        if($thisclient && $thisclient->isValid()) //conecta para ver el ticket y una nueva creacion.
            @header('Location: tickets.php?id='.$ticket->getExtId());
       
        $inc='thankyou.inc.php';
    }else{
        $errors['err']=$errors['err']?$errors['err']:'No se puede crear el Ticket. Corrije los siguientes errores y vuelve a intentarlo';
    }
endif;

//pag
require(CLIENTINC_DIR.'header.inc.php');
require(CLIENTINC_DIR.$inc);
require(CLIENTINC_DIR.'footer.inc.php');
?>
