<?php 
if(!defined('OSTCLIENTINC')) die('ElArteDeGanar.com');

$e=Format::input($_POST['lemail']?$_POST['lemail']:$_GET['e']);
$t=Format::input($_POST['lticket']?$_POST['lticket']:$_GET['t']);
?>
<div>
    <?php  if($errors['err']) {?>
        <p align="center" id="errormessage"><?php  echo  $errors['err']?></p>
    <?php  }elseif($warn) {?>
        <p class="warnmessage"><?php  echo  $warn ?></p>
    <?php  }?>
</div>
<div style="margin:5px 0px 100px 0;text-align:center; width:100%;">
    <p align="center">
        Para ver el estado de un ticket, proporciona a continuaci&oacute;n tus datos de acceso..<br/>
	Si es la primera que te pones en contacto con nosotros o has perdido el ID del Ticket, <a href="open.php">Clic Aqu&iacute;</a> Para abrir un ticket nuevo.
    </p>
    <span class="error"><?php  echo  Format::htmlchars($loginmsg)?></span>
    <form action="login.php" method="post">
    <table cellspacing="1" cellpadding="5" border="0" bgcolor="#000000" align="center">
        <tr bgcolor="#EEEEEE"> 
            <td>E-Mail:</td><td><input type="text" name="lemail" size="25" value="<?php  echo $e ?>"></td>
            <td>Ticket ID:</td><td><input type="text" name="lticket" size="10" value="<?php  echo $t ?>"></td>
            <td><input class="button" type="submit" value="Ver Estado"></td>
        </tr>
    </table>
    </form>
</div>
