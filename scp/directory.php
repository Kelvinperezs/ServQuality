<?php


require('staff.inc.php');
$nav->setTabActive('directory');
$nav->addSubMenu(array('desc'=>'Miembro del Staff','href'=>'directory.php','iconclass'=>'staff'));

$WHERE=' WHERE isvisible=1 ';
$sql=' SELECT staff.staff_id,staff.dept_id, firstname,lastname,email,phone,phone_ext,mobile,dept_name,onvacation '.
     ' FROM '.STAFF_TABLE.' staff LEFT JOIN  '.DEPT_TABLE.' USING(dept_id)';
if($_POST && $_POST['a']=='search') {
    $searchTerm=$_POST['query']; 
    if($searchTerm){
        $query=db_real_escape($searchTerm,false); //escapar  sólo ... sin comillas.
        if(is_numeric($searchTerm)){
            $WHERE.=" AND staff.phone LIKE '%$query%'";
        }elseif(strpos($searchTerm,'@') && Validator::is_email($searchTerm)){
            $WHERE.=" AND staff.email='$query'";
        }else{
            $WHERE.=" AND ( staff.email LIKE '%$query%'".
                         " OR staff.lastname LIKE '%$query%'".
                         " OR staff.firstname LIKE '%$query%'".
                        ' ) ';
        }
    }
    if($_POST['dept'] && is_numeric($_POST['dept'])) {
        $WHERE.=' AND staff.dept_id='.db_input($_POST['dept']);
    }
}

$users=db_query("$sql $WHERE ORDER BY lastname,firstname");

require_once(STAFFINC_DIR.'header.inc.php');
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
<div align="left">
    <form action="directory.php" method="POST" >
    <input type='hidden' name='a' value='search'>
    Buscar por :&nbsp;<input type="text" name="query" value="<?php echo Format::htmlchars($_REQUEST['query'])?>">
    Dpto.
    <select name="dept">
            <option value=0>Todos los Departamentos</option>
            <?php 
            $depts= db_query('SELECT dept_id,dept_name FROM '.DEPT_TABLE);
            while (list($deptId,$deptName) = db_fetch_row($depts)){
                $selected = ($_POST['dept']==$deptId)?'selected':''; ?>
                <option value="<?php echo $deptId?>"<?php echo $selected?>><?php echo $deptName?></option>
           <?php }?>
    </select>
    &nbsp;
    <input type="submit" name="search" class="button" value="Buscar">
    </form>
</div>
<?php  if($users && db_num_rows($users)):?>
<div class="msg">Miembro del Staff</div>
<table border="0" cellspacing=0 cellpadding=2 class="dtable" width="100%">
    <tr>
        <th width="23%">Nombre</th>
        <th width="18%">Dpto.</th>
        <th width="24%">Email</th>
        <th width="18%">Tel</th>
        <th width="17%">Movil</th>
    </tr>
    <?php 
    $class = 'row1';
    while ($row = db_fetch_array($users)) {
        $name=ucfirst($row['firstname'].' '.$row['lastname']);
        $ext=$row['phone_ext']?'Ext.'.$row['phone_ext']:'';
        ?>
        <tr class="<?php echo $class?>" id="<?php echo $row['staff_id']?>" onClick="highLightToggle(this.id);">
            <td><?php echo $name?>&nbsp;</td>
            <td><?php echo $row['dept_name']?>&nbsp;</td>
            <td><?php echo $row['email']?>&nbsp;</td>
            <td><?php echo Format::phone($row['phone'])?>&nbsp;<?php echo $ext?></td>
            <td><?php echo Format::phone($row['mobile'])?>&nbsp;</td>
        </tr>
        <?php 
        $class = ($class =='row2') ?'row1':'row2';
    }
    ?>
</table>
<?php 
else:
echo "<b>Problemas al mostrar directorio</b>";
endif;
include_once(STAFFINC_DIR.'footer.inc.php');
?>
