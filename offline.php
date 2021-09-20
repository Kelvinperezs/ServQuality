<?php
/*********************************************************************
    offline.php
**********************************************************************/
require_once('client.inc.php');
if($cfg && !$cfg->isHelpDeskOffline()) { 
    @header('Location: index.php'); //Redirigir si el sistema está en línea.
    include('index.php');
    exit;
}
?>
<html>
<head>
<title>Support Ticket System</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" rightmargin="0" topmargin="0">
<table width="60%" cellpadding="5" cellspacing="0" border="0">
	<tr><td>
        <p>
         <h3>Sistema de Soporte Fuera de L&iacute;nea</h3>
         
         El Sistema esta temporalmente deshabilitado.<br>
         Intentalo en unos minutos otra vez.
        </p>
    </td></tr>
</table>
</body>
</html>
