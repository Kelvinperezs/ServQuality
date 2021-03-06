<?php 
if(!defined('OSTCLIENTINC') || !is_object($thisclient) || !$thisclient->isValid()) die('ElArteDeGanar.com');

//Get ready for some deep shit.
$qstr='&'; //Query string collector
$status=null;
if($_REQUEST['status']) { //Query string status has nothing to do with the real status used below.
    $qstr.='status='.urlencode($_REQUEST['status']);
    //Status we are actually going to use on the query...making sure it is clean!
    switch(strtolower($_REQUEST['status'])) {
     case 'open':
	$ger_status = 'Abierto';
	$status=$_REQUEST['status'];
	break;
     case 'closed':
	$ger_status = 'Cerrado';
        $status=$_REQUEST['status'];
        break;
     default:
        $status=''; //ignore
    }
}

//Restrict based on email of the user...STRICT!
$qwhere =' WHERE ticket.email='.db_input($thisclient->getEmail());

//STATUS
if($status){
    $qwhere.=' AND status='.db_input($status);    
}
//Admit this crap sucks...but who cares??
$sortOptions=array('date'=>'ticket.created','ID'=>'ticketID','pri'=>'priority_id','dept'=>'dept_name');
$orderWays=array('DESC'=>'DESC','ASC'=>'ASC');

//Sorting options...
if($_REQUEST['sort']) {
        $order_by =$sortOptions[$_REQUEST['sort']];
}
if($_REQUEST['order']) {
    $order=$orderWays[$_REQUEST['order']];
}
if($_GET['limit']){
    $qstr.='&limit='.urlencode($_GET['limit']);
}

$order_by =$order_by?$order_by:'ticket.created';
$order=$order?$order:'DESC';
$pagelimit=$_GET['limit']?$_GET['limit']:PAGE_LIMIT;
$page=($_GET['p'] && is_numeric($_GET['p']))?$_GET['p']:1;

$qselect = 'SELECT ticket.ticket_id,ticket.ticketID,ticket.dept_id,isanswered,ispublic,subject,name '.
           ',dept_name,status,source,priority_id,ticket.created,ticket.updated,staff.firstname,staff.lastname';
$qfrom=' FROM ('.TICKET_TABLE.' ticket'.
       ' LEFT JOIN '.DEPT_TABLE.' dept ON ticket.dept_id=dept.dept_id )'.
       ' LEFT JOIN '.STAFF_TABLE.' staff ON ticket.staff_id=staff.staff_id';  

//Pagenation stuff....wish MYSQL could auto pagenate (something better than limit)
$total=db_count('SELECT count(*) '.$qfrom.' '.$qwhere);
$pageNav=new Pagenate($total,$page,$pagelimit);
$pageNav->setURL('view.php',$qstr.'&sort='.urlencode($_REQUEST['sort']).'&order='.urlencode($_REQUEST['order']));

//Ok..lets roll...create the actual query
$qselect.=' ,count(attach_id) as attachments ';
$qfrom.=' LEFT JOIN '.TICKET_ATTACHMENT_TABLE.' attach ON  ticket.ticket_id=attach.ticket_id ';
$qgroup=' GROUP BY ticket.ticket_id';
$query="$qselect $qfrom $qwhere $qgroup ORDER BY $order_by $order LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();
//echo $query;
$tickets_res = db_query($query);
$showing=db_num_rows($tickets_res)?$pageNav->showing():"";
$results_type=($status)?($ger_status).'Tickets':'Tickets';
$negorder=$order=='DESC'?'ASC':'DESC'; //Negate the sorting..
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
<div style="margin: 10px 0 60px 0;">
 <table width="100%" border="0" cellspacing=0 cellpadding=0 align="center">
    <tr>
        <td width="60%" class="msg"><?php echo $showing?>&nbsp;&nbsp;<?php echo $results_type?></td>
        <td nowrap >
            <a href="view.php?status=open"><img src="images/view_open_btn.gif" alt="Ver Abiertos" border=0></a>            
            <a href="view.php?status=closed"><img src="images/view_closed_btn.gif" alt="Ver Cerrados" border=0></a>            
            <a href=""><img src="images/refresh_btn.gif" alt="Resfrescar P&aacute;gina" border=0></a>
        </td>
    </tr>
 </table>
 <table width="100%" border="0" cellspacing=0 cellpadding=2>
    <tr><td>
     <table border="0" cellspacing=0 cellpadding=2 class="tgrid" align="center">
        <tr>
	        <th width="70" nowrap>
                <a href="view.php?sort=ID&order=<?php echo $negorder?><?php echo $qstr?>" title="Ordenar por Ticket-ID <?php echo $negorder?>">Registro #</a></th>
	        <th width="100">
                <a href="view.php?sort=date&order=<?php echo $negorder?><?php echo $qstr?>" title="Ordenar por Fecha <?php echo $negorder?>">Creado</a></th>
		<th width="110" align="Left">Actualizado</th>
            <th width="100">Estado</th>
            <th width="250">Asunto</th>
            <th width="*">
                <a href="view.php?sort=dept&order=<?php echo $negorder?><?php echo $qstr?>" title="Ordenar por Departamento <?php echo $negorder?>">Departamento</a></th>
		<!-- <th width="150">Email</th> -->
        </tr>
        <?php 
        $class = "row1";
        $total=0;
        if($tickets_res && ($num=db_num_rows($tickets_res))):
            $defaultDept=Dept::getDefaultDeptName();
            while ($row = db_fetch_array($tickets_res)) {
                $dept=$row['ispublic']?$row['dept_name']:$defaultDept; //Don't show hidden/non-public depts.
                $subject=Format::htmlchars(Format::truncate($row['subject'],40));
                $registroID=$row['ticketID'];
                if($row['isanswered'] && !strcasecmp($row['status'],'open')) {
                    $subject="<b>$subject</b>";
                    $registroID="<b>$ticketID</b>";
                }
                $stati = $row['status'];
		switch(strtolower($stati)){ //Status is overloaded
    			case 'open':
       			    $stati='Abierto';
    			    break;
    			case 'closed':
        		    $stati='Cerrado';
    			    break;
		}
	        ?>
            <tr class="<?php echo $class?> " id="<?php echo $row['ticketID']?>">
                <td align="center" title="<?php echo $row['email']?>" nowrap>
                    <a class="Icon <?php echo strtolower($row['source'])?>Ticket" title="<?php echo $row['email']?>" href="view.php?id=<?php echo $row['ticketID']?>">
                        <?php echo $ticketID?></a></td>
                <td nowrap>&nbsp;<?php echo Format::db_date($row['created'])?></td>
	        <td>&nbsp;<?php echo Format::db_datetime($row['updated'])?></td>
                <td>&nbsp;<?php echo $stati?></td>
                <td>&nbsp;<a href="view.php?id=<?php echo $row['ticketID']?>"><?php echo $subject?></a>
                    &nbsp;<?php echo $row['attachments']?"<span class='Icon file'>&nbsp;</span>":''?></td>
                <td nowrap>&nbsp;<?php echo Format::truncate($dept,30)?></td>
	<!-- <td>&nbsp;<?php  /*=Format::truncate($row['email'],40) */ ?></td> -->
            </tr>
            <?php 
            $class = ($class =='row2') ?'row1':'row2';
            } //end of while.
        else: //not tickets found!! ?> 
            <tr class="<?php echo $class?>"><td colspan=7><b>No se ha encontrado ningun Registro.</b></td></tr>
        <?php 
        endif; ?>
     </table>
    </td></tr>
    <tr><td>
    <?php 
    if($num>0 && $pageNav->getNumPages()>1){ //if we actually had any tickets returned?>
     <tr><td style="text-align:left;padding-left:20px">P&aacute;gina:<?php echo $pageNav->getPageLinks()?>&nbsp;</td></tr>
    <?php }?>
 </table>
</div>
<?php 
