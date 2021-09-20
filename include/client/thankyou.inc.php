<?php 
if(!defined('OSTCLIENTINC') || !is_object($ticket)) die('ElArteDeGanar.com'); //Say bye to our friend..

//Please customize the message below to fit your organization speak!
?>
<div>
    <?php if($errors['err']) {?>
        <p align="center" id="errormessage"><?php echo $errors['err']?></p>
    <?php }elseif($msg) {?>
        <p align="center" id="infomessage"><?php echo $msg?></p>
    <?php }elseif($warn) {?>
        <p id="warnmessage"><?php echo $warn?></p>
    <?php }?>
</div>
<div style="margin:5px 100px 100px 0;">Estimado
    <?php echo Format::htmlchars($ticket->getName())?>,<br>
    <p>
     Gracias por colaborar con nuestro departamento de Atenci&oacute;n al Usuario.<br>
     El sistema ha creado un "ticket" asociado a tu consulta y contactaremos contigo si es necesario.
     </p>
          
    <?php if($cfg->autoRespONNewTicket()){ ?>
    <p>El c&oacute;digo del ticket se ha enviado a tu direcci&oacute;n de correo electr&oacute;nico <b><?php echo $ticket->getEmail()?></b> 
        Este c&oacute;digo es imprescindible para consultar el estado de tu Ticket online
    </p>
    <p>
     Si necesitas enviar informaci&oacute;n adicional sobre la misma consulta, sigue las instrucciones incluidas en el correo electr&oacute;nico.
    </p>
    <?php }?>
    <p>El Departamento de Calidad</p>
</div>
<?php 
unset($_POST); //clear to avoid re-posting on back button??
?>
