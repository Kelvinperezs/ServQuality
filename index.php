<?php
/*********************************************************************
    index.php
**********************************************************************/
require('client.inc.php');
//Nosotros sólo estamos mostrando página de destino para los usuarios que no han iniciado sesión.
if($thisclient && is_object($thisclient) && $thisclient->isValid()) {
    require('tickets.php');
    exit;
}


require(CLIENTINC_DIR.'header.inc.php');
?>
<div id="index">
<h1 align="center">Bienvenido a la oficina de Control de Calidad de ARTISOL</h1>
<p class="big" align="center">TRABAJO DE CALIDAD </p>
<hr />
<br />
<div class="lcol">
  <img src="./images/new_ticket_icon.jpg" width="48" height="48" align="left" style="padding-bottom:150px;">
  <h3>Abrir un Ticket Nuevo</h3>
<p>Por favor, facilite el mayor número de detalles posibles. Si desea actualizar una petición puede utilizar el formulario a la derecha.</p>
Para Abrir un Registro nuevo haga clic en el botón
<br /><br />
  <form method="link" action="open.php">
  <input type="submit" class="button2" value="Abrir Ticket Nuevo">
  </form>
</div>
<div class="rcol">
  <img src="./images/ticket_status_icon.jpg" width="48" height="48" align="left" style="padding-bottom:150px;">
  <h3>Comprobar estado de los Tickets</h3>Proporcionamos los archivos y el historial de todas sus solicitudes de soporte completo con detalles. 
  <br /><br />
  <form class="status_form" action="login.php" method="post">
    <fieldset>
      <label>Email:</label>
      <input type="text" name="lemail">
    </fieldset>
    <fieldset>
     <label>Ticket ID:</label>
     <input type="text" name="lticket">
    </fieldset>
    <fieldset>
        <label>&nbsp;</label>
         <input type="submit" class="button2" value="Ver Estado">
    </fieldset>
  </form>
</div>
<div class="clear"></div>
<br />
</div>
<br />
<?php require(CLIENTINC_DIR.'footer.inc.php'); ?>
