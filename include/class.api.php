<?php
/*********************************************************************
    class.api.php
**********************************************************************/
class Api {
  

    function add($ip,&$errors) {
        global $cfg;

        $passphrase=$cfg->getAPIPassphrase();

        if(!$passphrase)
            $errors['err']='Falta la frase secreta de la API.';

        if(!$ip || !Validator::is_ip($ip))
            $errors['ip']='Se requiere una IP v&aacute;lida';
        elseif(Api::getKey($ip))
            $errors['ip']='Clave API para esta IP ya existe';

        $id=0;
        if(!$errors) {
            $sql='INSERT INTO '.API_KEY_TABLE.' SET created=NOW(), updated=NOW(), isactive=1'.
                 ',ipaddr='.db_input($ip).
                 ',apikey='.db_input(strtoupper(md5($ip.md5($passphrase)))); //Seguridad de la clave de API no es tan crítico en el momento 

            if(db_query($sql))
                $id=db_insert_id();

        }

        return $id;
    }

    function setPassphrase($phrase,&$errors) {
        global $cfg;

        if(!$phrase)
            $errors['phrase']='Requerida';
        elseif(str_word_count($_POST['phrase'])<3)
            $errors['phrase']='Debe ser de tres palabras.';
        elseif(!strcmp($cfg->getAPIPassphrase(),$phrase))
            $errors['phrase']='Ya existe';
        else{
            $sql='UPDATE '.CONFIG_TABLE.' SET updated=NOW(), api_passphrase='.db_input($phrase).
                ' WHERE id='.db_input($cfg->getId());
            if(db_query($sql) && db_affected_rows()){
                $cfg->reload();
                return true;
            }

        }

        return false;
    }


    function getKey($ip) {

        $key=null;
        $resp=db_query('SELECT apikey FROM '.API_KEY_TABLE.' WHERE ipaddr='.db_input($ip));
        if($resp && db_num_rows($resp))
            list($key)=db_fetch_row($resp);

        return $key;
    }


    function validate($key,$ip) {

        $resp=db_query('SELECT id FROM '.API_KEY_TABLE.' WHERE ipaddr='.db_input($ip).' AND apikey='.db_input($key));
        return ($resp && db_num_rows($resp))?true:false;

    }
   
}
?>
