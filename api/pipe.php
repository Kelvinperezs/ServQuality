#!/usr/bin/php -q
<?php
/*********************************************************************
    pipe.php
**********************************************************************/
@chdir(realpath(dirname(__FILE__)).'/'); //Cambiar dir.
ini_set('memory_limit', '256M'); //La preocupación aquí es tener suficiente mem de mensajes de correo electrónico con archivos adjuntos.
require('api.inc.php');
require_once(INCLUDE_DIR.'class.mailparse.php');
require_once(INCLUDE_DIR.'class.email.php');

//Hacer ducto Seguro está habilitado!
if(!$cfg->enableEmailPiping())
    api_exit(EX_UNAVAILABLE,'Email piping no est&aacute; habilitado - compruebe la configuraci&oacute;n de MTA.');
//Obtén la entrada
$data=isset($_SERVER['HTTP_HOST'])?file_get_contents('php://input'):file_get_contents('php://stdin');
if(empty($data)){
    api_exit(EX_NOINPUT,'Sin Datos');
}

//Analiza el correo electrónico.
$parser= new Mail_Parse($data);
if(!$parser->decode()){ //Código De ... devuelve falso en los errores de decodificación
    api_exit(EX_DATAERR,'Analisis de Email fallido ['.$parser->getError()."]\n\n".$data);    
}



//Cheque de dirección. asegúrese de que no es una dirección prohibida.
$fromlist = $parser->getFromAddressList();
//Compruebe si hay errores de análisis en la dirección FROM.
if(!$fromlist || PEAR::isError($fromlist)){
    api_exit(EX_DATAERR,'direcci&oacute;n FROM invalida ['.$fromlist?$fromlist->getMessage():''."]\n\n".$data);
}

$from=$fromlist[0]; //defecto.
foreach($fromlist as $fromobj){
    if(!Validator::is_email($fromobj->mailbox.'@'.$fromobj->host))
        continue;
    $from=$fromobj;
    break;
}

//A Dirección: Trate de averiguar la dirección de correo electrónico asociada con el mensaje.
$tolist = $parser->getToAddressList();
foreach ($tolist as $toaddr){
    if(($emailId=Email::getIdByEmail($toaddr->mailbox.'@'.$toaddr->host))){
        //Hemos encontrado correo electrónico de destino.
        break;
    }
}
if(!$emailId && ($cclist=$parser->getCcAddressList())) {
    foreach ($cclist as $ccaddr){
        if(($emailId=Email::getIdByEmail($ccaddr->mailbox.'@'.$ccaddr->host))){
            break;
        }
    }
}
//TODO: Opciones para rechazar mensajes de correo electrónico sin un juego Para tratar de db? Puede ser que era CCO? Política actual: Si la tubería, aceptamos la política

require_once(INCLUDE_DIR.'class.ticket.php'); //Ahora necesitamos este chico malo!

$var=array();
$deptId=0;
$name=trim($from->personal,'"');
if($from->comment && $from->comment[0])
    $name.=' ('.$from->comment[0].')';
$subj=utf8_encode($parser->getSubject());
if(!($body=Format::stripEmptyLines($parser->getBody())) && $subj)
    $body=$subj;

$var['mid']=$parser->getMessageId();
$var['email']=$from->mailbox.'@'.$from->host;
$var['name']=$name?utf8_encode($name):$var['email'];
$var['emailId']=$emailId?$emailId:$cfg->getDefaultEmailId();
$var['subject']=$subj?$subj:'[Sin Asunto]';
$var['message']=utf8_encode(Format::stripEmptyLines($body));
$var['header']=$parser->getHeader();
$var['pri']=$cfg->useEmailPriority()?$parser->getPriority():0;

$ticket=null;
if(preg_match ("[[#][0-9]{1,10}]",$var['subject'],$regs)) {
    $extid=trim(preg_replace("/[^0-9]/", "", $regs[0]));
    $ticket= new Ticket(Ticket::getIdByExtId($extid));
    //Permitir mensajes de correo electrónico no coincidentes ?? Por ahora el infierno NO.
    if(!is_object($ticket) || strcasecmp($ticket->getEmail(),$var['email']))
        $ticket=null;
}        
$errors=array();
$msgid=0;
if(!$ticket){ //Nuevas entradas...
    $ticket=Ticket::create($var,$errors,'email');
    if(!is_object($ticket) || $errors){
        api_exit(EX_DATAERR,'Creaci&oacute;n de Ticket Fallida'.implode("\n",$errors)."\n\n");
    }
    $msgid=$ticket->getLastMsgId();
}else{
    $message=$var['message'];
    //Strip quoted reply...TODO:averiguar cómo los clientes de correo hacen sin etiqueta especial ..
    if($cfg->stripQuotedReply() && ($tag=$cfg->getReplySeparator()) && strpos($var['message'],$tag))
        list($message)=split($tag,$var['message']);
    //mensaje .... mensaje ¡Mensaje hace la limpieza.
    if(!($msgid=$ticket->postMessage($message,'Email',$var['mid'],$var['header']))) {
        api_exit(EX_DATAERR,"No se puede enviar el mensaje \n\n $message\n");
    }
}
//Ticket creado ... guardar los archivos adjuntos si está habilitado.
if($cfg->allowEmailAttachments()) {                   
    if($attachments=$parser->getAttachments()){
        //impresión  ($ archivos adjuntos);
        foreach($attachments as $k=>$attachment){
            if($attachment['filename'] && $cfg->canUploadFileType($attachment['filename'])) {
                $ticket->saveAttachment($attachment['filename'],$attachment['body'],$msgid,'M');
            }
        }
    }
}
api_exit(EX_SUCCESS);
?>
