<?php 
/*********************************************************************
    main.inc.php
**********************************************************************/    
    
    #Deshabilitar el acceso directo.
    if(!strcasecmp(basename($_SERVER['SCRIPT_NAME']),basename(__FILE__))) die('ElArteDeGanar.com');

    #Desactivar Globals si está habilitado .... antes info carga config
    if(ini_get('register_globals')) {
       ini_set('register_globals',0);
       foreach($_REQUEST as $key=>$val)
           if(isset($$key))
               unset($$key);
    }

    #Desactivar url abierto && url include
    ini_set('allow_url_fopen', 0);
    ini_set('allow_url_include', 0);

    #Desactivar session id en url.
    ini_set('session.use_trans_sid', 0);
    #No cache
    ini_set('session.cache_limiter', 'nocache');
    #Cookies
    //ini_set('session.cookie_path','/ticket/');

    #Informe de errores ... Buena idea para habilitar los informes de error en un archivo. _errors visualización es decir deben establecerse en false
    error_reporting(E_ALL ^ E_NOTICE); //Respeto todo lo que se encuentra en php.ini (administrador del sistema sabe mejor??)
    #No mostrar errores
    ini_set('display_errors',0);
    ini_set('display_startup_errors',0);

    //Iniciar la sesión
    session_start();

    #Establecer constantes Dir
    if(!defined('ROOT_PATH')) define('ROOT_PATH','./'); //root path. directorios dam
    define('ROOT_DIR',str_replace('\\\\', '/', realpath(dirname(__FILE__))).'/'); #Obtén camino real para root dir --- Linux y Windows
    define('INCLUDE_DIR',ROOT_DIR.'include/'); //Cambie esto si incluyen se mueve fuera de la trayectoria de la banda.
    define('PEAR_DIR',INCLUDE_DIR.'pear/');
    define('SETUP_DIR',INCLUDE_DIR.'setup/');
  
    /*############## No modificar con cualquier otra cosa más allá de este punto, a menos que realmente sabe lo que está haciendo ##############*/

    #versión actual..
    define('THIS_VERSION','1.6 ST'); //Los cambios desde la versión a versión.

    #información de configuración de carga
    $configfile='';
    if(file_exists(ROOT_DIR.'ostconfig.php')) //Antiguo instala antes de v 1.6 RC5
        $configfile=ROOT_DIR.'ostconfig.php';
    elseif(file_exists(INCLUDE_DIR.'settings.php')) //Archivo de configuración VIEJO .. v 1.6 RC5
        $configfile=INCLUDE_DIR.'settings.php';
    elseif(file_exists(INCLUDE_DIR.'ost-config.php')) //NUEVA archivo config v 1.6 estable ++
        $configfile=INCLUDE_DIR.'ost-config.php';
    elseif(file_exists(ROOT_DIR.'include/'))
        header('Location: '.ROOT_PATH.'setup/');

    if(!$configfile || !file_exists($configfile)) die('<b>Error al cargar la configuraci&oacute;n. Ponte en contacto con el administrador.</b>');

    require($configfile);
    define('CONFIG_FILE',$configfile); //utilizado en administrador .php para comprobar permiso.
   
   //Path separator
    if(!defined('PATH_SEPARATOR')){
        if(strpos($_ENV['OS'],'Win')!==false || !strcasecmp(substr(PHP_OS, 0, 3),'WIN'))
            define('PATH_SEPARATOR', ';' ); //Windows
        else 
            define('PATH_SEPARATOR',':'); //Linux
    }

    //Set incluye caminos. Sobrescribir las rutas predeterminadas.
    ini_set('include_path', './'.PATH_SEPARATOR.INCLUDE_DIR.PATH_SEPARATOR.PEAR_DIR);
   

    #incluir archivos requeridos
    require(INCLUDE_DIR.'class.usersession.php');
    require(INCLUDE_DIR.'class.pagenate.php'); //Página  ayudante!
    require(INCLUDE_DIR.'class.sys.php'); //cargador del sistema de configuración del registrador.
    require(INCLUDE_DIR.'class.misc.php');
    require(INCLUDE_DIR.'class.http.php');
    require(INCLUDE_DIR.'class.format.php'); //ayudantes de formato
    require(INCLUDE_DIR.'class.validator.php'); //Clase para ayudar con la validación de entrada forma básica ... por favor ayuda a mejorarla.
    require(INCLUDE_DIR.'mysql.php');

    #GUIÓN DE EJECUCIÓN ACTUAL.
    define('THISPAGE',Misc::currentURL());

    #página predeterminada
    define('PAGE_LIMIT',20);

    # Esto es para apoyar instalaciones antiguas. sin sal secreto.
    if(!defined('SECRET_SALT')) define('SECRET_SALT',md5(TABLE_PREFIX.ADMIN_EMAIL));

    #Sesión relacionados
    define('SESSION_SECRET', MD5(SECRET_SALT)); //No es que nunca más útil...
    define('SESSION_TTL', 86400); // Por defecto 24 horas
   
    define('DEFAULT_PRIORITY_ID',1);
    define('EXT_TICKET_ID_LEN',6); //TVenta de entradas a crear. cuando comienza a recibir las colisiones. Se aplica sólo en las identificaciones de boletos al azar.

    #Las tablas se utilizan Sytema amplia
    define('CONFIG_TABLE',TABLE_PREFIX.'config');
    define('SYSLOG_TABLE',TABLE_PREFIX.'syslog');

    define('STAFF_TABLE',TABLE_PREFIX.'staff');
    define('DEPT_TABLE',TABLE_PREFIX.'department');
    define('TOPIC_TABLE',TABLE_PREFIX.'help_topic');
    define('GROUP_TABLE',TABLE_PREFIX.'groups');
   
    define('TICKET_TABLE',TABLE_PREFIX.'ticket');
    define('TICKET_NOTE_TABLE',TABLE_PREFIX.'ticket_note');
    define('TICKET_MESSAGE_TABLE',TABLE_PREFIX.'ticket_message');
    define('TICKET_RESPONSE_TABLE',TABLE_PREFIX.'ticket_response');
    define('TICKET_ATTACHMENT_TABLE',TABLE_PREFIX.'ticket_attachment');
    define('TICKET_PRIORITY_TABLE',TABLE_PREFIX.'ticket_priority');
    define('TICKET_LOCK_TABLE',TABLE_PREFIX.'ticket_lock');
  
    define('EMAIL_TABLE',TABLE_PREFIX.'email');
    define('EMAIL_TEMPLATE_TABLE',TABLE_PREFIX.'email_template');
    define('BANLIST_TABLE',TABLE_PREFIX.'email_banlist');
    define('API_KEY_TABLE',TABLE_PREFIX.'api_key');
    define('TIMEZONE_TABLE',TABLE_PREFIX.'timezone'); 
   
    #Conéctese a la base de datos && obtener la configuración a la base de datos
    $ferror=null;
    if (!db_connect(DBHOST,DBUSER,DBPASS) || !db_select_database(DBNAME)) {
        $ferror='No se a podido establecer una conexi&oacute;n con la Base de Datos';
    }elseif(!($cfg=Sys::getConfig())){
        $ferror='No se puede cargar informaci&oacute;n de configuraci&oacute;n de la BD. Busca soporte técnico.';
    }

    if($ferror){ //error fatal
        Sys::alertAdmin('osTicket Fatal Error',$ferror); //intentar alertar administrador.
        die("<b>Error Fatal:</b> Ponte en Contacto con el Administrador."); //error genérico.
        exit;
    }
    //En Eso
    $cfg->init();
    //Establecer zona horaria por defecto ... personal sobrescribirlo.
    $_SESSION['TZ_OFFSET']=$cfg->getTZoffset();
    $_SESSION['daylight']=$cfg->observeDaylightSaving();

    #Limpie cotizaciones.
    if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
        $_POST=Format::strip_slashes($_POST);
        $_GET=Format::strip_slashes($_GET);
        $_REQUEST=Format::strip_slashes($_REQUEST);
    }
?>
