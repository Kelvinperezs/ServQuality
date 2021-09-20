<?php
/*********************************************************************
    tickets.php
**********************************************************************/
require('secure.inc.php');
if(!is_object($thisclient) || !$thisclient->isValid()) die('Acceso Denegado'); //Verifique de nuevo.

require_once(INCLUDE_DIR.'class.ticket.php');
$ticket=null;
$inc='tickets.inc.php'; //Página por defecto ... mostrar todos los ticket.
//Compruebe si se da alguna de id...
if(($id=$_REQUEST['id']?$_REQUEST['id']:$_POST['ticket_id']) && is_numeric($id)) {
    //Identificación dado traiga la información de entradas y permitidos.
    $ticket= new Ticket(Ticket::getIdByExtId((int)$id));
    if(!$ticket or !$ticket->getEmail()) {
        $ticket=null; //claro.
        $errors['err']='Acceso Denegado. Posiblemente ID de Ticket Erronea';
    }elseif(strcasecmp($thisclient->getEmail(),$ticket->getEmail())){
        $errors['err']='Violaci&oacute;n de Seguridad. Violaci&oacute;nes repetidas resultar&aacute;n en el bloqueo de tu cuenta.';
        $ticket=null; //claro.
    }else{
        //Todo desprotegido.
        $inc='viewticket.inc.php';
    }
}
//Proceso posterior ... depende $ticket de Entradas.
if($_POST && is_object($ticket) && $ticket->getId()):
    $errors=array();
    switch(strtolower($_POST['a'])){
    case 'postmessage':
        if(strcasecmp($thisclient->getEmail(),$ticket->getEmail())) { //doble control permanente de nuevo!
            $errors['err']='Acceso Denegado. Posiblemente ID de Ticket Erronea';
            $inc='tickets.inc.php'; //Mostrar el tickets.               
        }

        if(!$_POST['message'])
            $errors['message']='Mensaje requerido';
        //comprobar el apego ..si ninguna se ajusta
        if($_FILES['attachment']['name']) {
            if(!$cfg->allowOnlineAttachments()) //Algo malo en la forma ... usuario no debe tener una opción de adjuntar
                $errors['attachment']='Archivo [ '.$_FILES['attachment']['name'].' ] Rechazado';
            elseif(!$cfg->canUploadFileType($_FILES['attachment']['name']))
                $errors['attachment']='Extensi&oacute;n de archivo no v&aacute;lido [ '.$_FILES['attachment']['name'].' ]';
            elseif($_FILES['attachment']['size']>$cfg->getMaxFileSize())
                $errors['attachment']='Archivo muy grande. Max. '.$cfg->getMaxFileSize().' bytes permitidos';
        }
                    
        if(!$errors){
            //Todo desprotegido ... hacer la magia.
            if(($msgid=$ticket->postMessage($_POST['message'],'Web'))) {
                if($_FILES['attachment']['name'] && $cfg->canUploadFiles() && $cfg->allowOnlineAttachments())
                    $ticket->uploadAttachment($_FILES['attachment'],$msgid,'M');
                    
                $msg='Mensaje enviado con &eacute;xito';
            }else{
                $errors['err']='No se a podido enviar el mensaje. Intentalo de nuevo';
            }
        }else{
            $errors['err']=$errors['err']?$errors['err']:'A ocurrido un Error. Intentalo de nuevo';
        }
        break;
    default:
        $errors['err']='Acci&oacute;n desconocida';
    }
    $ticket->reload();
endif;
include(CLIENTINC_DIR.'header.inc.php');
include(CLIENTINC_DIR.$inc);
include(CLIENTINC_DIR.'footer.inc.php');
?>
