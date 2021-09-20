<?php 
if(!defined('OSTCLIENTINC')) die('ElArteDeGanar.com'); //Say bye to our friend..

$info=($_POST && $errors)?Format::input($_POST):array(); //on error...use the post data
?>
<div>
    <?php  if($errors['err']) {?>
        <p align="center" id="errormessage"><?php  echo $errors['err']?></p>
    <?php  }elseif($msg) {?>
        <p align="center" id="infomessage"><?php  echo $msg?></p>
    <?php  }elseif($warn) {?>
        <p id="warnmessage"><?php  echo $warn?></p>
    <?php  }?>
</div>
<div>Rellene el Formulario para abrir un ticket nuevo.</div><br>
<form action="open.php" method="POST" enctype="multipart/form-data">
<table align="left" cellpadding=2 cellspacing=1 width="90%">
    <tr>
        <th width="20%">Nombre:</th>
        <td>
            <?php if ($thisclient && ($name=$thisclient->getName())) {
                ?>
                <input type="hidden" name="name" value="<?php echo $name?>"><?php echo $name?>
            <?php }else {?>
                <input type="text" name="name" size="25" value="<?php echo $info['name']?>">
	        <?php }?>
            &nbsp;<font class="error">*&nbsp;<?php echo $errors['name']?></font>
        </td>
    </tr>
    <tr>
        <th nowrap >Email:</th>
        <td>
            <?php if ($thisclient && ($email=$thisclient->getEmail())) {
                ?>
                <input type="hidden" name="email" size="25" value="<?php echo $email?>"><?php echo $email?>
            <?php }else {?>             
                <input type="text" name="email" size="25" value="<?php echo $info['email']?>">
            <?php }?>
            &nbsp;<font class="error">*&nbsp;<?php echo $errors['email']?></font>
        </td>
    </tr>
    <tr>
        <td>Tel&eacute;fono:</td>
        <td><input type="text" name="phone" size="25" value="<?php echo $info['phone']?>"> 
&nbsp;Ext&nbsp;<input type="text" name="phone_ext" size="6" value="<?php echo $info['phone_ext']?>">
            &nbsp;<font class="error">&nbsp;<?php echo $errors['phone']?></font></td>
    </tr>
    <tr height=2px><td align="left" colspan=2 >&nbsp;</td></tr>
    <tr>
        <th>Área de Soporte:</th>
        <td>
            <select name="topicId">
                <option value="" selected >Elija uno...</option>
                <?php 
                 $services= db_query('SELECT topic_id,topic FROM '.TOPIC_TABLE.' WHERE isactive=1 ORDER BY topic');
                 if($services && db_num_rows($services)) {
                     while (list($topicId,$topic) = db_fetch_row($services)){
                        $selected = ($info['topicId']==$topicId)?'selected':''; ?>
                        <option value="<?php echo $topicId?>"<?php echo $selected?>><?php echo $topic?></option>
                        <?php 
                     }
                 }else{?>
                    <option value="0" >General</option>
                <?php }?>
            </select>
            &nbsp;<font class="error">*&nbsp;<?php echo $errors['topicId']?></font>
        </td>
    </tr>
    <tr>
        <th>Asunto:</th>
        <td>
            <input type="text" name="subject" size="35" value="<?php echo $info['subject']?>">
            &nbsp;<font class="error">*&nbsp;<?php echo $errors['subject']?></font>
        </td>
    </tr>
    <tr>
        <th valign="top">Descripcion del Problema:</th>
        <td>
            <?php  if($errors['message']) {?> <font class="error"><b>&nbsp;<?php echo $errors['message']?></b></font><br/><?php }?>
            <textarea name="message" cols="35" rows="8" wrap="soft" style="width:85%"><?php echo $info['message']?></textarea></td>
    </tr>
    <?php 
    if($cfg->allowPriorityChange() ) {
      $sql='SELECT priority_id,priority_desc FROM '.TICKET_PRIORITY_TABLE.' WHERE ispublic=1 ORDER BY priority_urgency DESC';
      if(($priorities=db_query($sql)) && db_num_rows($priorities)){ ?>
      <tr>
        <td>Prioridad:</td>
        <td>
            <select name="pri">
              <?php 
                $info['pri']=$info['pri']?$info['pri']:$cfg->getDefaultPriorityId(); //use system's default priority.
                while($row=db_fetch_array($priorities)){ ?>
                    <option value="<?php echo $row['priority_id']?>" <?php echo $info['pri']==$row['priority_id']?'selected':''?> ><?php echo $row['priority_desc']?></option>
              <?php }?>
            </select>
        </td>
       </tr>
    <?php  }
    }?>

    <?php if(($cfg->allowOnlineAttachments() && !$cfg->allowAttachmentsOnlogin())  
                || ($cfg->allowAttachmentsOnlogin() && ($thisclient && $thisclient->isValid()))){
        
        ?>
    <tr>
        <td>Adjunto:</td>
        <td>
            <input type="file" name="attachment"><font class="error">&nbsp;<?php echo $errors['attachment']?></font>
        </td>
    </tr>
    <?php }?>
    <?php if($cfg && $cfg->enableCaptcha() && (!$thisclient || !$thisclient->isValid())) {
        if($_POST && $errors && !$errors['captcha'])
            $errors['captcha']='El C&oacute;digo no es Correcto';
        ?>
    <tr>
        <th valign="top">C&oacute;digo de seguridad:</th>
        <td><img src="captcha.php" border="0" align="left">
 <span>&nbsp;&nbsp;<input type="text" name="captcha" size="7" value="">&nbsp;<i>Escriba el C&oacute;digo que se muestra en la imagen.</i></span><br/>
                <font class="error">&nbsp;<?php echo $errors['captcha']?></font>
        </td>
    </tr>
    <?php }?>
    <tr height=2px><td align="left" colspan=2 >&nbsp;</td></tr>
    <tr>
        <td></td>
        <td>
            <input class="button" type="submit" name="submit_x" value="Enviar Ticket">
            <input class="button" type="reset" value="Restablecer">
            <input class="button" type="button" name="cancel" value="Cancelar" onClick='window.location.href="index.php"'>    
        </td>
    </tr>
</table>
</form>
