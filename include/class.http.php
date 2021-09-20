<?php
/*********************************************************************
    class.http.php
**********************************************************************/
class Http {
    
    function header_code_verbose($code) {
        switch($code):
        case 200: return '200 OK';
        case 204: return '204 Sin contenido';
        case 401: return '401 No autorizada';
        case 403: return '403 Prohibida';
        case 405: return '405 Metodo no permitido';
        case 416: return '416 Rango solicitado no es Satisfactible';
        default:  return '500 Error del Servidor';
        endswitch;
    }
    
    function response($code,$content,$contentType='text/html',$charset='UTF-8') {
		
        header('HTTP/1.1 '.Http::header_code_verbose($code));
		header('Status: '.Http::header_code_verbose($code)."\r\n");
		header("Connection: Close\r\n");
		header("Content-Type: $contentType; charset=$charset\r\n");
        header('Content-Length: '.strlen($content)."\r\n\r\n");
       	print $content;
        exit;
    }
	
	function redirect($url,$delay=0,$msg='') {

        if(strstr($_SERVER['SERVER_SOFTWARE'], 'IIS')){
            header("Refresh: $delay; URL=$url");
        }else{
            header("Location: $url");
        }
        exit;
    }
}
?>
