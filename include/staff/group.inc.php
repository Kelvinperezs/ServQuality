<?php 
if(!defined('OSTADMININC') || !$thisuser->isadmin()) die('Acceso Denegado');

$info=($errors && $_POST)?Format::input($_POST):Format::htmlchars($group);
if($group && $_REQUEST['a']!='new'){
    $title='Editar Grupo: '.$group['group_name'];
    $action='update';
}else {
    $title='Agregar Grupo Nuevo';
    $action='create';
    $info['group_enabled']=isset($info['group_enabled'])?$info['group_enabled']:1; //Default to active 
}

?>
<table width="100%" border="0" cellspacing=0 cellpadding=0>
 <form action="admin.php" method="POST" name="group">
 <input type="hidden" name="do" value="<?php echo $action?>">
 <input type="hidden" name="a" value="<?php echo Format::htmlchars($_REQUEST['a'])?>">
 <input type="hidden" name="t" value="groups">
 <input type="hidden" name="group_id" value="<?php echo $info['group_id']?>">
 <input type="hidden" name="old_name" value="<?php echo $info['group_name']?>">
 <tr><td>
    <table width="100%" border="0" cellspacing=0 cellpadding=2 class="tform">
        <tr class="header"><td colspan=2><?php echo Format::htmlchars($title)?></td></tr>
        <tr class="subheader"><td colspan=2>
            Los permisos de grupo aplican a todos los miembros de un grupo pero no a los administradores y jefes de departamento en algunos casos.
            </td></tr>
        <tr><th>Nombre del Grupo:</th>
            <td><input type="text" name="group_name" size=25 value="<?php echo $info['group_name']?>">
                &nbsp;<font class="error">*&nbsp;<?php echo $errors['group_name']?></font>
                    
            </td>
        </tr>
        <tr>
            <th>Estado del Grupo:</th>
            <td>
                <input type="radio" name="group_enabled"  value="1"   <?php echo $info['group_enabled']?'checked':''?> /> Habilitado
                <input type="radio" name="group_enabled"  value="0"   <?php echo !$info['group_enabled']?'checked':''?> />Deshabilitado
                &nbsp;<font class="error">&nbsp;<?php echo $errors['group_enabled']?></font>
            </td>
        </tr>
        <tr><th valign="top"><br>Acceso Departametos</th>
            <td class="mainTableAlt"><i>Selecciona los departamentos a los que los miembros del grupo pueden acceder ademas del propio.</i>
                &nbsp;<font class="error">&nbsp;<?php echo $errors['depts']?></font><br/>
                <?php 
                //Try to save the state on error...
                $access=($_POST['depts'] && $errors)?$_POST['depts']:explode(',',$info['dept_access']);
                $depts= db_query('SELECT dept_id,dept_name FROM '.DEPT_TABLE.' ORDER BY dept_name');
                while (list($id,$name) = db_fetch_row($depts)){
                    $ck=($access && in_array($id,$access))?'checked':''; ?>
                    <input type="checkbox" name="depts[]" value="<?php echo $id?>" <?php echo $ck?> > <?php echo $name?><br/>
                <?php 
                }?>
                <a href="#" onclick="return select_all(document.forms['group'])">Seleccionar Todos</a>&nbsp;&nbsp;|
                <a href="#" onclick="return reset_all(document.forms['group'])">Ninguno</a>&nbsp;&nbsp; 
            </td>
        </tr>
        <tr><th>Puede Crear Tickets</th>
            <td>
                <input type="radio" name="can_create_tickets"  value="1"   <?php echo $info['can_create_tickets']?'checked':''?> />Si 
                <input type="radio" name="can_create_tickets"  value="0"   <?php echo !$info['can_create_tickets']?'checked':''?> />No
                &nbsp;&nbsp;<i>Capacidad para abrir tickets en nombre de usuarios!</i>
            </td>
        </tr>
        <tr><th>Puede Editar Tickets</th>
            <td>
                <input type="radio" name="can_edit_tickets"  value="1"   <?php echo $info['can_edit_tickets']?'checked':''?> />Si
                <input type="radio" name="can_edit_tickets"  value="0"   <?php echo !$info['can_edit_tickets']?'checked':''?> />No
                &nbsp;&nbsp;<i>Capacidad para editar tickets. Administradores y Jefes de departamento pueden por defecto.</i>
            </td>
        </tr>
        <tr><th>Puede Cerrar Tickets</th>
            <td>
                <input type="radio" name="can_close_tickets"  value="1" <?php echo $info['can_close_tickets']?'checked':''?> />Si
                <input type="radio" name="can_close_tickets"  value="0" <?php echo !$info['can_close_tickets']?'checked':''?> />No
                &nbsp;&nbsp;<i><b>Cierre Masivo:</b> El staff puede cerrar tickets individualmente en todo caso.</i>
            </td>
        </tr>
        <tr><th>Puede transferir tickets</th>
            <td>
                <input type="radio" name="can_transfer_tickets"  value="1" <?php echo $info['can_transfer_tickets']?'checked':''?> />Si
                <input type="radio" name="can_transfer_tickets"  value="0" <?php echo !$info['can_transfer_tickets']?'checked':''?> />No
                &nbsp;&nbsp;<i>Capacidad para transferir tickets de un departamento a otro.</i>
            </td>
        </tr>
        <tr><th>Puede Borrar Tickets</th>
            <td>
                <input type="radio" name="can_delete_tickets"  value="1"   <?php echo $info['can_delete_tickets']?'checked':''?> />Si
                <input type="radio" name="can_delete_tickets"  value="0"   <?php echo !$info['can_delete_tickets']?'checked':''?> />No
                &nbsp;&nbsp;<i><b>Nota:</b> Los tickets borrados NO PUEDEN ser recuperados en ning&uacute;n caso</i>
            </td>
        </tr>
        <tr><th>Puede Bloquear Emails</th>
            <td>
                <input type="radio" name="can_ban_emails"  value="1" <?php echo $info['can_ban_emails']?'checked':''?> />Si
                <input type="radio" name="can_ban_emails"  value="0" <?php echo !$info['can_ban_emails']?'checked':''?> />No
                &nbsp;&nbsp;<i>Capacidad para agregar o quitar Emails de la lista negra a trav&eacute;s de la interface de tickets.</i>
            </td>
        </tr>
        <tr><th>Puede Gestionar Respuestas </th>
            <td>
                <input type="radio" name="can_manage_kb"  value="1" <?php echo $info['can_manage_kb']?'checked':''?> />Si
                <input type="radio" name="can_manage_kb"  value="0" <?php echo !$info['can_manage_kb']?'checked':''?> />No
                &nbsp;&nbsp;<i>Capacidad para agregar, actualizar, deshabilitar y borrar respuestas predefinidas.</i>
            </td>
        </tr>
    </table>
    <tr><td style="padding-left:165px;padding-top:20px;">
        <input class="button" type="submit" name="submit" value="Guardar">
        <input class="button" type="reset" name="reset" value="Restablecer">
        <input class="button" type="button" name="cancel" value="Cancelar" onClick='window.location.href="admin.php?t=groups"'>
        </td>
    </tr>
 </form>
</table>
