<?php
/*********************************************************************
    ost-config.php
**********************************************************************/

#Disable direct access.
if(!strcasecmp(basename($_SERVER['SCRIPT_NAME']),basename(__FILE__)) || !defined('ROOT_PATH')) die('ElArteDeGanar.com');

#Install flag
define('OSTINSTALLED',TRUE);
if(OSTINSTALLED!=TRUE){
    if(!file_exists(ROOT_PATH.'setup/install.php')) die('Error: Contact system admin.'); //Something is really wrong!
    //Invoke the installer.
    header('Location: '.ROOT_PATH.'setup/install.php');
    exit;
}

# Encrypt/Decrypt secret key - randomly generated during installation.
define('SECRET_SALT','2521FB5B3FB37E0');

#Default admin email. Used only on db connection issues and related alerts.
define('ADMIN_EMAIL','juan.cadiz236@gmail.com');

#Mysql Login info
define('DBTYPE','mysql');
define('DBHOST','localhost'); 
define('DBNAME','practica');
define('DBUSER','root');
define('DBPASS','123456');

#Table prefix
define('TABLE_PREFIX','ost_');

?>
