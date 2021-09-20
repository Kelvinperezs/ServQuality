<?php
/*********************************************************************
    cron.php
**********************************************************************/
@chdir(realpath(dirname(__FILE__)).'/'); //Cambiar dir.
require('api.inc.php');
require_once(INCLUDE_DIR.'class.cron.php');
Cron::run();
Sys::log(LOG_DEBUG,'Cron Job','Cron Job Externo ejecutado ['.$_SERVER['REMOTE_ADDR'].']');
?>
