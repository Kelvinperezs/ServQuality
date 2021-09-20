<?php
/*********************************************************************
    api.inc.php
**********************************************************************/
//códigos de salida sufijo ver /usr/include/sysexits.h 
define('EX_DATAERR', 65);       /*error de formato de datos */
define('EX_NOINPUT', 66);       /* no se puede abrir la entrada */
define('EX_UNAVAILABLE', 69);   /* servicio no disponible */
define('EX_IOERR', 74);         /* error de entrada / salida */
define('EX_TEMPFAIL',75);       /* insuficiencia temporal; Se invita al usuario a intentar */
define('EX_NOPERM',  77);       /* permiso denegado */
define('EX_CONFIG',  78);       /* error de configuración */

define('EX_SUCCESS',0);         /* éxito */

if(!file_exists('../main.inc.php')) exit(EX_CONFIG);
require_once('../main.inc.php');
if(!defined('INCLUDE_DIR')) exit(EX_CONFIG);

require_once(INCLUDE_DIR.'class.http.php');
require_once(INCLUDE_DIR.'class.api.php');

define('OSTAPIINC',TRUE); // Definir etiqueta que incluyen archivos pueden comprobar

$remotehost=(isset($_SERVER['HTTP_HOST']) || isset($_SERVER['REMOTE_ADDR']))?TRUE:FALSE;
/* La salida de la API ayudante */
function api_exit($code,$msg='') {
    global $remotehost,$cfg;
    
    if($code!=EX_SUCCESS) {
        //ocurrió un error...
        $_SESSION['api']['errors']+=1;
        $_SESSION['api']['time']=time();
        Sys::log(LOG_WARNING,"API error - code #$code",$msg);
        //echo "API Error:.$msg";
    }
    if($remotehost){
        switch($code) {
        case EX_SUCCESS:
            Http::response(200,$code,'text/plain');
            break;
        case EX_UNAVAILABLE:
            Http::response(405,$code,'text/plain');
            break;
        case EX_NOPERM:
            Http::response(403,$code,'text/plain');
            break;
        case EX_DATAERR:
        case EX_NOINPUT:
        default:
            Http::response(416,$code,'text/plain');
        }
    }
    exit($code);
}

//Hosts remotos necesitan autorización.
if($remotehost) {

    $ip=$_SERVER['REMOTE_ADDR'];
    $key=$_SERVER['HTTP_USER_AGENT']; //tirando todo tricks.
    //Hasta 10 errores consecutivos permite ... antes de un tiempo de espera de 5 minutos.
    //Un error más durante el tiempo de espera y el tiempo de espera se inicia un nuevo reloj
    if($_SESSION['api']['errors']>10 && (time()-$_SESSION['api']['time'])<=5*60) { // tiempo fuera!
        api_exit(EX_NOPERM,"Error de tiempo de espera [$ip] en el Host Remoto #".$_SESSION['api']['errors']);
    }
    //Compruebe clave de API y ip
    if(!Validator::is_ip($ip) || !Api::validate($key,$ip)) { 
        api_exit(EX_NOPERM,'Host Remoto Desconocido ['.$ip.'] o clave Api invalida['.$key.']');
    }
    //En este momento sabemos que el host remoto está permitido / IP.
    $_SESSION['api']['errors']=0; //errores claros para la sesión.
}
?>
