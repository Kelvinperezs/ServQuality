<?php

require('staff.inc.php');
ignore_user_abort(1);//solitario!
@set_time_limit(0); //inutil cuando el modo seguro esta en 
$data=sprintf ("%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%",
        71,73,70,56,57,97,1,0,1,0,128,255,0,192,192,192,0,0,0,33,249,4,1,0,0,0,0,44,0,0,0,0,1,0,1,0,0,2,2,68,1,0,59);
$datasize=strlen($data);
header('Content-type:  image/gif');
header('Cache-Control: no-cache, must-revalidate');
header("Content-Length: $datasize");
header('Connection: Close');
print $data;

ob_start(); //Mantenga la salida de la imagen limpia. Ocultar. 
//TODO:hacer cron db para permitir mejoras en los datos/llamadas directas.
//Nosotros no queremos generar cron en cada carga de página grabamos la última llamada cron en el período de sesiones por usuario
$sec=time()-$_SESSION['lastcroncall'];
if($sec>180): //usuario puede llamar cron una vez cada 3 minutos.
require_once(INCLUDE_DIR.'class.cron.php');    
Cron::TicketMonitor(); //tiempo de boletos : Vamos a entradas del tiempo nunca independientemente de la configuración de cron. 
if($cfg && $cfg->enableAutoCron()){ //solo puede recuperar entradas sin autocron esta habilitado
!
    Cron::MailFetcher();  
    Sys::log(LOG_DEBUG,'Autocron','cron job executed ['.$thisuser->getUserName().']');
}    
$_SESSION['lastcroncall']=time();
endif;
$output = ob_get_contents();
ob_end_clean();
?>
