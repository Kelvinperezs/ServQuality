<?php  defined('OSTSCPINC') or die('Ruta Invalida'); ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>osTicket:: Inicio de sesi&oacute;n</title>
<link rel="stylesheet" href="css/login.css" type="text/css" />
<meta name="robots" content="noindex" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
</head>	
<body id="loginBody">
<div id="header">
</div>
<div id="loginBox">
	<h1 id="logo"><a href="index.php">Panel de Control</a></h1>
	<h1><?php echo $msg?></h1>
	<br />
	<form action="login.php" method="post">
	<input type="hidden" name=do value="scplogin" />
    <table border=0 align="center">
        <tr><td width=100px align="right"><b>Usuario</b>:</td><td><input type="text" name="username" id="name" value="" /></td></tr>
        <tr><td align="right"><b>Contrase&ntilde;a</b>:</td><td><input type="password" name="passwd" id="pass" /></td></tr>
        <tr><td>&nbsp;</td><td>&nbsp;&nbsp;<input class="submit" type="submit" name="submit" value="Entrar" /></td></tr>
    </table>
</form>
</div>

</body>
</html>
