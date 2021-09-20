<?php 
$title=($cfg && is_object($cfg))?$cfg->getTitle():'osTicket :: Centro de Soporte';
header("Content-Type: text/html; charset=UTF-8\r\n");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title><?php  echo Format::htmlchars($title)?></title>
    <link rel="stylesheet" href="./styles/main.css" media="screen">
    <link rel="stylesheet" href="./styles/colors.css" media="screen">
</head>
<body>
<div id="container">
    <div id="header">
        <a id="logo" href="index.php" title="Centro de Soporte"><img src="./images/Imagen122.png" border=0 alt="Centro de Soporte"></a>
        <!--<p><span>Centro de Soporte</span> Con Tickets</p>-->
    </div>
     <ul id="nav">
         <?php                      
         if($thisclient && is_object($thisclient) && $thisclient->isValid()) {?>
         <li><a class="log_out" href="logout.php">Salir</a></li>
         <li><a class="my_tickets" href="tickets.php">Mis Tickets</a></li>
         <?php }else {?>
         <!--<li><a class="manu" href="manual1.pdf">Manual de Usuario</a></li>-->
         <li><a class="ticket_status" href="tickets.php">Consulta de Registros</a></li>
         <?php }?>
         <li><a class="new_ticket" href="open.php">Registro Nuevo</a></li>
         <li><a class="home" href="index.php">Inicio</a></li>

    </ul>
    <div id="content">
