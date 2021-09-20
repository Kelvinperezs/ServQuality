<?php 
if(!defined('OSTCLIENTINC') || !is_object($thisclient) || !is_object($ticket)) die('ElArteDeGanar.com'); //bye..see ya
//Double check access one last time...
if(strcasecmp($thisclient->getEmail(),$ticket->getEmail())) die('Acceso Denegado');

$info=($_POST && $errors)?Format::input($_POST):array(); //Re-use the post info on error...savekeyboards.org

$dept = $ticket->getDept();
//Making sure we don't leak out internal dept names
$dept=($dept && $dept->isPublic())?$dept:$cfg->getDefaultDept();
//We roll like that...
$stati = $ticket->getStatus();
switch(strtolower($stati)){ //Status is overloaded
    case 'open':
        $ger_status='Abierto';
        break;
    case 'closed':
        $ger_status='Cerrado';
        break;
    default:
        $ger_status='Abierto';
}
?>
<table width="100%" cellpadding="1" cellspacing="0" border="0">
    <tr><td colspan=2 width=100% class="msg">Registro #<?php echo $ticket->getExtId()?> 
        &nbsp;<a href="view.php?id=<?php echo $ticket->getExtId()?>" title="Refrescar P&aacute;gina"><span class="Icon refresh">&nbsp;</span></a></td></tr> 
    <tr>
       <td width=50%>	
        <table align="center" class="infotable" cellspacing="1" cellpadding="3" width="100%" border=0>
	        <tr>
				<th width="107" >Estado Registro:</th>
				<td width="313"><?php echo $ger_status?></td>
			</tr>
            <tr>
                <th>Departamento:</th>
                <td><?php echo Format::htmlchars($dept->getName())?></td>
            </tr>
			<tr>
                <th>Fecha Registro:</th>
                <td><?php echo Format::db_datetime($ticket->getCreateDate())?></td>
            </tr>
		</table>
	   </td>
	   <td width=50% valign="top">
        <table align="center" class="infotable" cellspacing="1" cellpadding="3" width="100%" border=0>
            <tr>
                <th width="100">Nombre:</th>
                <td><?php echo Format::htmlchars($ticket->getName())?></td>
            </tr>
            <tr>
                <th width="100">Email:</th>
                <td><?php echo $ticket->getEmail()?></td>
            </tr>
            <tr>
                <th>Tel&eacute;fono:</th>
                <td><?php echo Format::phone($ticket->getPhoneNumber())?></td>
            </tr>
        </table>
       </td>
    </tr>
</table>
<div class="msg">Asunto: <?php echo Format::htmlchars($ticket->getSubject())?></div>
<div>
    <?php if($errors['err']) {?>
        <p align="center" id="errormessage"><?php echo $errors['err']?></p>
    <?php }elseif($msg) {?>
        <p align="center" id="infomessage"><?php echo $msg?></p>
    <?php }?>
</div>
<br>
<div align="left">
    <span class="Icon thread">Historial del Registro</span>
    <div id="ticketthread">
        <?php 
	    //get messages
        $sql='SELECT msg.*, count(attach_id) as attachments  FROM '.TICKET_MESSAGE_TABLE.' msg '.
            ' LEFT JOIN '.TICKET_ATTACHMENT_TABLE.' attach ON  msg.ticket_id=attach.ticket_id AND msg.msg_id=attach.ref_id AND ref_type=\'M\' '.
            ' WHERE  msg.ticket_id='.db_input($ticket->getId()).
            ' GROUP BY msg.msg_id ORDER BY created';
	    $msgres =db_query($sql);
	    while ($msg_row = db_fetch_array($msgres)):
		    ?>
		    <table align="center" class="message" cellspacing="0" cellpadding="1" width="100%" border=0>
		        <tr><th><?php echo Format::fecha($msg_row['created'])?></th></tr>
                <?php if($msg_row['attachments']>0){ ?>
                <tr class="header"><td><?php echo $ticket->getAttachmentStr($msg_row['msg_id'],'M')?></td></tr> 
                <?php }?>
                <tr class="info">
                    <td><?php echo Format::display($msg_row['message'])?></td></tr>
		    </table>
            <?php 
            //get answers for messages
            $sql='SELECT resp.*,count(attach_id) as attachments FROM '.TICKET_RESPONSE_TABLE.' resp '.
                ' LEFT JOIN '.TICKET_ATTACHMENT_TABLE.' attach ON  resp.ticket_id=attach.ticket_id AND resp.response_id=attach.ref_id AND ref_type=\'R\' '.
                ' WHERE msg_id='.db_input($msg_row['msg_id']).' AND resp.ticket_id='.db_input($ticket->getId()).
                ' GROUP BY resp.response_id ORDER BY created';
            //echo $sql;
		    $resp =db_query($sql);
		    while ($resp_row = db_fetch_array($resp)) {
                $respID=$resp_row['response_id'];
                $name=$cfg->hideStaffName()?'staff':Format::htmlchars($resp_row['staff_name']);
                ?>
    		    <table align="center" class="response" cellspacing="0" cellpadding="1" width="100%" border=0>
    		        <tr>
    			        <th><?php echo Format::fecha($resp_row['created'])?>&nbsp;-&nbsp;<?php echo $name?></th></tr>
                    <?php if($resp_row['attachments']>0){ ?>
                    <tr class="header">
                        <td><?php echo $ticket->getAttachmentStr($respID,'R')?></td></tr>
                                    
                    <?php }?>
			        <tr class="info">
				        <td> <?php echo Format::display($resp_row['response'])?></td></tr>
		        </table>
		    <?php 
		    } //endwhile...response loop.
            $msgid =$msg_row['msg_id'];
        endwhile; //message loop.
     ?>
    </div>
</div>
<div>
    <div align="center">
        <?php if($_POST && $errors['err']) {?>
            <p align="center" id="errormessage"><?php echo $errors['err']?></p>
        <?php }elseif($msg) {?>
            <p align="center" id="infomessage"><?php echo $msg?></p>
        <?php }?>
    </div> 
    <div id="reply" style="padding:10px 0 20px 40px;">
        <?php if($ticket->isClosed()) {?>
        <div class="msg">Al enviar una respuesta el Registro sera reabierto.</div>
        <?php }?>
        <form action="view.php?id=<?php echo $id?>#reply" name="reply" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $ticket->getExtId()?>">
            <input type="hidden" name="respid" value="<?php echo $respID?>">
            <input type="hidden" name="a" value="postmessage">
            <div align="left">
                Escribe tu mensaje <font class="error">*&nbsp;<?php echo $errors['message']?></font><br/>
                <textarea name="message" id="message" cols="60" rows="7" wrap="soft"><?php echo $info['message']?></textarea>
            </div>
            <?php  if($cfg->allowOnlineAttachments()) {?>
            <div align="left">
                Adjuntar Archivo<br><input type="file" name="attachment" id="attachment" size=30px value="<?php echo $info['attachment']?>" /> 
                    <font class="error">&nbsp;<?php echo $errors['attachment']?></font>
            </div>
            <?php }?>
            <div align="left"  style="padding:10px 0 10px 0;">
                <input class="button" type='submit' value='Responder' />
                <input class="button" type='reset' value='Restablecer' />
                <input class="button" type='button' value='Cancelar' onClick='window.location.href="view.php"' />
            </div>
        </form>
    </div>
</div>
<br><br>
