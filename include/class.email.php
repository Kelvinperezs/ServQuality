<?php
/*********************************************************************
    class.email.php
**********************************************************************/

include_once(INCLUDE_DIR.'class.dept.php');

class Email {
    var $id;
    var $email;
    var $address;
    var $name;

    var $autoresp;
    var $deptId;
    var $priorityId;
    
    var $dept;
    var $info;
    
    function Email($id,$fetch=true){
        $this->id=$id;
        if($fetch)
            $this->load();
    }
    
    function load() {

        if(!$this->id)
            return false;
        
        $sql='SELECT * FROM '.EMAIL_TABLE.' WHERE email_id='.db_input($this->id);
        if(($res=db_query($sql)) && db_num_rows($res)) {
            $info=db_fetch_array($res);
            $this->id=$info['email_id'];
            $this->email=$info['email'];
            $this->name=$info['name'];
            $this->address=$info['name']?($info['name'].'<'.$info['email'].'>'):$info['email'];
            $this->deptId=$info['dept_id'];
            $this->priorityId=$info['priority_id'];
            $this->autoresp=$info['noautoresp']?false:true;
            $this->info=$info;
            return true;
        }
        $this->id=0;

        return false;
    }
  
    function reload() {
        return $this->load();
    }
    
    function getId(){
        return $this->id;
    }

    function getEmail(){
        return $this->email;
    }
    
    function getAddress() {
        return $this->address;
    }
    
    function getName(){
        return $this->name;
    }

    function getPriorityId() {
        return $this->priorityId;
    }

    function getDeptId() {
        return $this->deptId;
    }

    function getDept() {

        if(!$this->dept && $this->dept_id)
            $this->dept= new Dept($this->dept_id);
        
        return $this->dept;
    }

    function autoRespond() {
          return $this->autoresp;
    }
    
    function getInfo() {
        return $this->info;
    }

    function isSMTPEnabled() {
         return $this->info['smtp_active'];

    }

    function getSMTPInfo($active=true){
        $info=array();
        if(!$active || ($active && $this->isSMTPEnabled())){

            $info = array ('host' => $this->info['smtp_host'], 
                           'port' => $this->info['smtp_port'],
                           'auth' => $this->info['smtp_auth'],
                           'username' => $this->info['userid'],
                           'password' =>Misc::decrypt($this->info['userpass'],SECRET_SALT)
                           );
        }

        return $info;
    }

    function update($vars,&$errors) {
        if($this->save($this->getId(),$vars,$errors)){
            $this->reload();
            return true;
        }

        return false;
    }



    function send($to,$subject,$message,$attachment=null) {
        global $cfg;

        //Get SMTP info IF enabled!
        $smtp=array();
        if($this->isSMTPEnabled() && ($info=$this->getSMTPInfo())){ //is SMTP enabled for the current email?
            $smtp=$info;
        }elseif($cfg && ($email=$cfg->getDefaultSMTPEmail()) && $email->isSMTPEnabled()){ //What about global SMTP setting?
            if($cfg->allowSMTPSpoofing() && ($info=$email->getSMTPInfo())){ //If spoofing is allowed..then continue.
                $smtp=$info;
            }elseif($email->getId()!=$this->getId()){//No spoofing allowed. Send it via the default SMTP email.
                return $email->send($to,$subject,$message,$attachment);
            }
        }

        //Get the goodies
        require_once ('Mail.php'); // PEAR Mail package
        require_once ('Mail/mime.php'); // PEAR Mail_Mime packge

        //do some cleanup
        $eol="\n";
        $to=preg_replace("/(\r\n|\r|\n)/s",'', trim($to));
        $subject=stripslashes(preg_replace("/(\r\n|\r|\n)/s",'', trim($subject)));
        $body = stripslashes(preg_replace("/(\r\n|\r)/s", "\n", trim($message)));
        $fromname=$this->getName();
        $from =sprintf('"%s"<%s>',($fromname?$fromname:$this->getEmail()),$this->getEmail());
        $headers = array ('From' => $from,
                          'To' => $to,
                          'Subject' => $subject,
                          'Date'=>date('D, d M Y H:i:s O'),
                          'Message-ID' =>'<'.Misc::randCode(6).''.time().'-'.$this->getEmail().'>',
                          'X-Mailer' =>'osTicket v 1.6',
                          'Content-Type' => 'text/html; charset="UTF-8"'
                          );
        $mime = new Mail_mime();
        $mime->setTXTBody($body);
        //attachment TODO: allow multiple attachments - $attachment should be mixed parts.
        if($attachment && $attachment['file'] && is_readable($attachment['file'])) { //file of mime type.
            $mime->addAttachment($attachment['file'],$attachment['type'],$attachment['name']);
        }
        $options=array('head_encoding' => 'quoted-printable',
                       'text_encoding' => 'quoted-printable',
                       'html_encoding' => 'base64',
                       'html_charset'  => 'utf-8',
                       'text_charset'  => 'utf-8');
        //encode the body
        $body = $mime->get($options);
        //encode the headers.
        $headers = $mime->headers($headers);
        if($smtp){ //Send via SMTP
            $mail = mail::factory('smtp',
                    array ('host' => $smtp['host'],
                           'port' => $smtp['port'],
                           'auth' => $smtp['auth']?true:false,
                           'username' => $smtp['username'],
                           'password' => $smtp['password'],
                           'timeout'  =>20,
                           'debug' => false,
                           ));
            $result = $mail->send($to, $headers, $body);
            if(!PEAR::isError($result))
                return true;

            $alert=sprintf("No se puede enviar correo electr&oacute;nico a trav&eacute;s de %s:%d [%s]\n\n%s\n",$smtp['host'],$smtp['port'],$smtp['username'],$result->getMessage());
            Sys::log(LOG_ALERT,'Error SMTP',$alert,false);
            //print_r($result);
        }

        //No SMTP or it failed....use php's native mail function.
        $mail = mail::factory('mail');
        return PEAR::isError($mail->send($to, $headers, $body))?false:true;

    }

    //sends emails using native php mail function Email::sendmail( ......);
    //Don't use this function if you can help it.
    function sendmail($to,$subject,$message,$from) {
        
        require_once ('Mail.php'); // PEAR Mail package
        require_once ('Mail/mime.php'); // PEAR Mail_Mime packge
        
        $eol="\n";
        $to=preg_replace("/(\r\n|\r|\n)/s",'', trim($to));
        $subject=stripslashes(preg_replace("/(\r\n|\r|\n)/s",'', trim($subject)));
        $body = stripslashes(preg_replace("/(\r\n|\r)/s", "\n", trim($message)));
        $headers = array ('From' =>$from,
                          'To' => $to,
                          'Subject' => $subject,
                          'Message-ID' =>'<'.Misc::randCode(10).''.time().'@osTicket>',
                          'X-Mailer' =>'osTicket v 1.6',
                          'Content-Type' => 'text/html; charset="UTF-8"'
                          );
        $mime = new Mail_mime();
        $mime->setTXTBody($body);
        $options=array('head_encoding' => 'quoted-printable',
                       'text_encoding' => 'quoted-printable',
                       'html_encoding' => 'base64',
                       'html_charset'  => 'utf-8',
                       'text_charset'  => 'utf-8');
        //encode the body
        $body = $mime->get($options);
        //headers
        $headers = $mime->headers($headers);
        $mail = mail::factory('mail');
        return PEAR::isError($mail->send($to, $headers, $body))?false:true;
    }


    function getIdByEmail($email) {
        
        $resp=db_query('SELECT email_id FROM '.EMAIL_TABLE.' WHERE email='.db_input($email));
        if($resp && db_num_rows($resp))   
            list($id)=db_fetch_row($resp);
        
        return $id;
    }

    function create($vars,&$errors) {
        return Email::save(0,$vars,$errors);
    }


    function save($id,$vars,&$errors) {
        global $cfg;
        //very basic checks

        if($id && $id!=$vars['email_id'])
            $errors['err']='Error Interno.';

        if(!$vars['email'] || !Validator::is_email($vars['email'])){
            $errors['email']='Se Requiere un Email Valido';
        }elseif(($eid=Email::getIdByEmail($vars['email'])) && $eid!=$id){
            $errors['email']='Este Email ya existe';
        }elseif(!strcasecmp($cfg->getAdminEmail(),$vars['email'])){
            $errors['email']='Este Email ya se esta usando en la cuenta de Administrador';
        }else{ //make sure the email doesn't belong to any of the staff 
            $sql='SELECT staff_id FROM '.STAFF_TABLE.' WHERE email='.db_input($vars['email']);
            if(($res=db_query($sql)) && db_num_rows($res))
                $errors['email']='Este Email ya se esta usando por un miembro del Staff';
        }


        if(!$vars['dept_id'] || !is_numeric($vars['dept_id']))
            $errors['dept_id']='Debes seleccionar un Departamento';
            
        if(!$vars['priority_id'])
            $errors['priority_id']='Debes seleccionar prioridad';
            
        if($vars['mail_active'] || ($vars['smtp_active'] && $vars['smtp_auth'])) {
            if(!$vars['userid'])
                $errors['userid']='Falta nombre de Usuario';
                
            if(!$vars['userpass'])
                $errors['userpass']='Se Requiere una Contrase&ntilde;a';
        }
        
        if($vars['mail_active']) {
            //Check pop/imapinfo only when enabled.
            if(!function_exists('imap_open'))
                $errors['mail_active']= 'IMAP no existe. PHP debe ser compilado con IMAP.';
            if(!$vars['mail_host'])
                $errors['mail_host']='Se requiere nombre del Host';
            if(!$vars['mail_port'])
                $errors['mail_port']='Se requiere numero de Puerto';
            if(!$vars['mail_protocol'])
                $errors['mail_protocol']='Selecciona Protocolo';
            if(!$vars['mail_fetchfreq'] || !is_numeric($vars['mail_fetchfreq']))
                $errors['mail_fetchfreq']='Se requiere frecuencia de captura';
            if(!$vars['mail_fetchmax'] || !is_numeric($vars['mail_fetchmax']))
                $errors['mail_fetchmax']='Se requiere numero m&aacute;ximo de correos por captura';
            
        }
        
        if($vars['smtp_active']) {
            if(!$vars['smtp_host'])
                $errors['smtp_host']='Se requiere nombre del Host';
            if(!$vars['smtp_port'])
                $errors['smtp_port']='Se requiere numero de puerto';
        }
        
        if(!$errors && ($vars['mail_host'] && $vars['userid'])){
            $sql='SELECT email_id FROM '.EMAIL_TABLE.' WHERE mail_host='.db_input($vars['mail_host']).' AND userid='.db_input($vars['userid']);
            if($id)
                $sql.=' AND email_id!='.db_input($id);
                
            if(db_num_rows(db_query($sql)))
                $errors['userid']=$errors['host']='La combinacion Host/Nombre de usuario ya esta en uso por otro departamento.';
        }
        
        if(!$errors && $vars['mail_active']) {
           
            //note: password is unencrypted at this point...MailFetcher expect plain text.
            $fetcher = new MailFetcher($vars['userid'],$vars['userpass'],$vars['mail_host'],$vars['mail_port'],
                                            $vars['mail_protocol'],$vars['mail_encryption']);
            if(!$fetcher->connect()) {
                $errors['userpass']='<br>No se a podido establecer una conexi&oacute;n, revisa la configuraci&oacute;n de'.$vars['mail_protocol'].'';
                $errors['mail']='<br>'.$fetcher->getLastError();
            }
        }
        
        if(!$errors && $vars['smtp_active']) { //Check SMTP login only.
            require_once 'Mail.php'; // PEAR Mail package
            $smtp = mail::factory('smtp',
                    array ('host' => $vars['smtp_host'],
                           'port' => $vars['smtp_port'],
                           'auth' => $vars['smtp_auth']?true:false,
                           'username' => $vars['userid'],
                           'password' => $vars['userpass'],
                           'timeout'  =>20,
                           'debug' => false,
                           ));
            $mail = $smtp->connect();
            if(PEAR::isError($mail)) {
                $errors['userpass']='<br>No se a podido establecer una conexi&oacute;n, revisa la configuraci&oacute;n de SMTP.';
                $errors['smtp']='<br>'.$mail->getMessage();
            }else{
                $smtp->disconnect(); //Thank you, sir!
            }
        }
        
        if(!$errors) {
            $sql='updated=NOW(),mail_errors=0, mail_lastfetch=NULL'.
                ',email='.db_input($vars['email']).
                ',name='.db_input(Format::striptags($vars['name'])).
                ',dept_id='.db_input($vars['dept_id']).
                ',priority_id='.db_input($vars['priority_id']).
                ',noautoresp='.db_input(isset($vars['noautoresp'])?1:0).
                ',userid='.db_input($vars['userid']).
                ',userpass='.db_input(Misc::encrypt($vars['userpass'],SECRET_SALT)).
                ',mail_active='.db_input($vars['mail_active']).
                ',mail_host='.db_input($vars['mail_host']).
                ',mail_protocol='.db_input($vars['mail_protocol']?$vars['mail_protocol']:'POP').
                ',mail_encryption='.db_input($vars['mail_encryption']).
                ',mail_port='.db_input($vars['mail_port']?$vars['mail_port']:0).
                ',mail_fetchfreq='.db_input($vars['mail_fetchfreq']?$vars['mail_fetchfreq']:0).
                ',mail_fetchmax='.db_input($vars['mail_fetchmax']?$vars['mail_fetchmax']:0).
                ',mail_delete='.db_input(isset($vars['mail_delete'])?$vars['mail_delete']:0).
                ',smtp_active='.db_input($vars['smtp_active']).
                ',smtp_host='.db_input($vars['smtp_host']).
                ',smtp_port='.db_input($vars['smtp_port']?$vars['smtp_port']:0).
                ',smtp_auth='.db_input($vars['smtp_auth']);
            
            if($id){ //update
                $sql='UPDATE '.EMAIL_TABLE.' SET '.$sql.' WHERE email_id='.db_input($id);
                if(!db_query($sql) || !db_affected_rows())
                    $errors['err']='No se a podido actualizar el Email, Error interno';
            }else {
                $sql='INSERT INTO '.EMAIL_TABLE.' SET '.$sql.',created=NOW()';
                if(!db_query($sql) or !($emailID=db_insert_id()))
                    $errors['err']='No se a podido a??adir el Email, Error interno ';
                else
                    return $emailID; //newly created email.
            }
            
        }else{
            $errors['err']='Se an producido Errores, intentelo de nuevo';
        }

        return $errors?FALSE:TRUE;
    }

    function deleteEmail($id) {
        global $cfg;
        //Make sure we are not trying to delete default emails.
        if($id==$cfg->getDefaultEmailId() || $id==$cfg->getAlertEmailId()) //double...double check.
            return 0;

        $sql='DELETE FROM '.EMAIL_TABLE.' WHERE email_id='.db_input($id).' LIMIT 1';
        if(db_query($sql) && ($num=db_affected_rows())){
            // DO SOME HOUSE CLEANING..should be taken care already...but doesn't hurt to make sure.

            //Update Depts using the email to defaults.
            db_query('UPDATE '.DEPT_TABLE.' SET email_id='.db_input($cfg->getDefaultEmailId()).' WHERE email_id='.db_input($id));
            db_query('UPDATE '.DEPT_TABLE.' SET autoresp_email_id=0 WHERE email_id='.db_input($id));
            return $num;
        }
        return 0;
    }



    
}
?>