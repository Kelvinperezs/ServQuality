<?php 
if(!defined('OSTSCPINC') || !@$thisuser->isStaff()) die('Acceso Denegado');

//Get ready for some deep shit..(I admit..this could be done better...but the shit just works... so shutup for now).

$qstr='&'; //Query string collector
if($_REQUEST['status']) { //Query string status has nothing to do with the real status used below; gets overloaded.
    $qstr.='status='.urlencode($_REQUEST['status']);
}

//See if this is a search
$search=$_REQUEST['a']=='search'?true:false;
$searchTerm='';
//make sure the search query is 3 chars min...defaults to no query with warning message
if($search) {
  $searchTerm=$_REQUEST['query'];
  if( ($_REQUEST['query'] && strlen($_REQUEST['query'])<3) 
      || (!$_REQUEST['query'] && isset($_REQUEST['basic_search'])) ){ //Why do I care about this crap...
      $search=false; //Instead of an error page...default back to regular query..with no search.
      $errors['err']='El termino de b&uacute;squeda debe tener al menos 3 caracteres';
      $searchTerm='';
  }
}
$showoverdue=$showanswered=false;
$staffId=0; //Nothing for now...TODO: Allow admin and manager to limit tickets to single staff level.
//Get status we are actually going to use on the query...making sure it is clean!
$status=null;
switch(strtolower($_REQUEST['status'])){ //Status is overloaded
    case 'open':
        $status='open';
	$ger_status='abierto';
        break;
    case 'closed':
        $status='closed';
	$ger_status='cerrado';
        break;
    case 'overdue':
        $status='open';
	$ger_status='abierto';
        $showoverdue=true;
        $results_type='Tickets Vencidos';
        break;
    case 'assigned':
        //$status='Open'; //
        $staffId=$thisuser->getId();
        break;
    case 'answered':
        $status='open';
	$ger_status='abierto';
        $showanswered=true;
        $results_type='Tickets Respondidos';
        break;
    default:
        if(!$search)
            $status='open';
	    $ger_status='abierto';
}

// This sucks but we need to switch queues on the fly! depending on stats fetched on the parent.
if($stats) { 
    if(!$stats['open'] && (!$status || $status=='open')){
        if(!$cfg->showAnsweredTickets() && $stats['answered']) {
             $status='open';
	     $ger_status='abierto';
             $showanswered=true;
             $results_type='Tickets Respondidos';
        }elseif(!$stats['answered']) { //no open or answered tickets (+-queue?) - show closed tickets.???
            $status='closed';
	    $ger_status='cerrado';
            $results_type='Tickets Cerrados';
        }
    }
}

$qwhere ='';
/* DEPTS
   STRICT DEPARTMENTS BASED (a.k.a Categories) PERM. starts the where 
   if dept returns nothing...show only tickets without dept which could mean..none?
   Note that dept selected on search has nothing to do with departments allowed.
   User can also see tickets assigned to them regardless of the ticket's dept.
*/
$depts=$thisuser->getDepts(); //if dept returns nothing...show only tickets without dept which could mean..none...and display an error. huh?
if(!$depts or !is_array($depts) or !count($depts)){
    //if dept returns nothing...show only orphaned tickets (without dept) which could mean..none...and display an error.
    $qwhere =' WHERE ticket.dept_id IN ( 0 ) ';
}else if($thisuser->isadmin()){
    //user allowed acess to all departments.
    $qwhere =' WHERE 1'; // Brain fart...can not thing of a better way other than selecting all depts + 0 ..wasted query in my book?
}else{
    //limited depts....user can access tickets assigned to them regardless of the dept.
    $qwhere =' WHERE (ticket.dept_id IN ('.implode(',',$depts).') OR ticket.staff_id='.$thisuser->getId().')';
}


//STATUS
if($status){
    $qwhere.=' AND status='.db_input(strtolower($status));    
}

//Sub-statuses Trust me!
if($staffId && ($staffId==$thisuser->getId())) { //Staff's assigned tickets.
    $results_type='Tickets Asignados';
    $qwhere.=' AND ticket.staff_id='.db_input($staffId);    
}elseif($showoverdue) { //overdue
    $qwhere.=' AND isoverdue=1 ';
}elseif($showanswered) { ////Answered
    $qwhere.=' AND isanswered=1 ';
}elseif(!$search && !$cfg->showAnsweredTickets() && !strcasecmp($status,'open')) {
    $qwhere.=' AND isanswered=0 ';
}
 

//Show assigned?? Admin can not be limited. Dept managers see all tickets within the dept.
if(!$cfg->showAssignedTickets() && !$thisuser->isadmin()) {
    $qwhere.=' AND (ticket.staff_id=0 OR ticket.staff_id='.db_input($thisuser->getId()).' OR dept.manager_id='.db_input($thisuser->getId()).') ';
}


//Search?? Somebody...get me some coffee 
$deep_search=false;
if($search):
    $qstr.='&a='.urlencode($_REQUEST['a']);
    $qstr.='&t='.urlencode($_REQUEST['t']);
    if(isset($_REQUEST['advance_search'])){ //advance search box!
        $qstr.='&advance_search=Search';
    }

    //query
    if($searchTerm){
        $qstr.='&query='.urlencode($searchTerm);
        $queryterm=db_real_escape($searchTerm,false); //escape the term ONLY...no quotes.
        if(is_numeric($searchTerm)){
            $qwhere.=" AND ticket.ticketID LIKE '$queryterm%'";
        }elseif(strpos($searchTerm,'@') && Validator::is_email($searchTerm)){ //pulling all tricks!
            $qwhere.=" AND ticket.email='$queryterm'";
        }else{//Deep search!
            //This sucks..mass scan! search anything that moves! 
            
            $deep_search=true;
            if($_REQUEST['stype'] && $_REQUEST['stype']=='FT') { //Using full text on big fields.
                $qwhere.=" AND ( ticket.email LIKE '%$queryterm%'".
                            " OR ticket.name LIKE '%$queryterm%'".
                            " OR ticket.subject LIKE '%$queryterm%'".
                            " OR note.title LIKE '%$queryterm%'".
                            " OR MATCH(message.message)   AGAINST('$queryterm')".
                            " OR MATCH(response.response) AGAINST('$queryterm')".
                            " OR MATCH(note.note) AGAINST('$queryterm')".
                            ' ) ';
            }else{
                $qwhere.=" AND ( ticket.email LIKE '%$queryterm%'".
                            " OR ticket.name LIKE '%$queryterm%'".
                            " OR ticket.subject LIKE '%$queryterm%'".
                            " OR message.message LIKE '%$queryterm%'".
                            " OR response.response LIKE '%$queryterm%'".
                            " OR note.note LIKE '%$queryterm%'".
                            " OR note.title LIKE '%$queryterm%'".
                            ' ) ';
            }
        }
    }
    //department
    if($_REQUEST['dept'] && ($thisuser->isadmin() || in_array($_REQUEST['dept'],$thisuser->getDepts()))) {
    //This is dept based search..perm taken care above..put the sucker in.
        $qwhere.=' AND ticket.dept_id='.db_input($_REQUEST['dept']);
        $qstr.='&dept='.urlencode($_REQUEST['dept']);
    }
    //dates
    $startTime  =($_REQUEST['startDate'] && (strlen($_REQUEST['startDate'])>=8))?strtotime($_REQUEST['startDate']):0;
    $endTime    =($_REQUEST['endDate'] && (strlen($_REQUEST['endDate'])>=8))?strtotime($_REQUEST['endDate']):0;
    if( ($startTime && $startTime>time()) or ($startTime>$endTime && $endTime>0)){
        $errors['err']='Intervalo de Fecha invalido, la selecci&oacute;n sera ignorada.';
        $startTime=$endTime=0;
    }else{
        //Have fun with dates.
        if($startTime){
            $qwhere.=' AND ticket.created>=FROM_UNIXTIME('.$startTime.')';
            $qstr.='&startDate='.urlencode($_REQUEST['startDate']);
                        
        }
        if($endTime){
            $qwhere.=' AND ticket.created<=FROM_UNIXTIME('.$endTime.')';
            $qstr.='&endDate='.urlencode($_REQUEST['endDate']);
        }
}

endif;

//I admit this crap sucks...but who cares??
$sortOptions=array('date'=>'ticket.created','ID'=>'ticketID','pri'=>'priority_urgency','dept'=>'dept_name');
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
if(!$order_by && $showanswered) {
    $order_by='ticket.lastresponse DESC, ticket.created'; //No priority sorting for answered tickets.
}elseif(!$order_by && !strcasecmp($status,'closed')){
    $order_by='ticket.closed DESC, ticket.created'; //No priority sorting for closed tickets.
}


$order_by =$order_by?$order_by:'priority_urgency,effective_date DESC ,ticket.created';
$order=$order?$order:'DESC';
$pagelimit=$_GET['limit']?$_GET['limit']:$thisuser->getPageLimit();
$pagelimit=$pagelimit?$pagelimit:PAGE_LIMIT; //true default...if all fails.
$page=($_GET['p'] && is_numeric($_GET['p']))?$_GET['p']:1;


$qselect = 'SELECT DISTINCT ticket.ticket_id,lock_id,ticketID,ticket.dept_id,ticket.staff_id,subject,name,email,dept_name '.
           ',ticket.status,ticket.source,isoverdue,isanswered,ticket.created,pri.* ,count(attach.attach_id) as attachments ';
$qfrom=' FROM '.TICKET_TABLE.' ticket '.
       ' LEFT JOIN '.DEPT_TABLE.' dept ON ticket.dept_id=dept.dept_id ';

if($search && $deep_search) {
    $qfrom.=' LEFT JOIN '.TICKET_MESSAGE_TABLE.' message ON (ticket.ticket_id=message.ticket_id )';
    $qfrom.=' LEFT JOIN '.TICKET_RESPONSE_TABLE.' response ON (ticket.ticket_id=response.ticket_id )';
    $qfrom.=' LEFT JOIN '.TICKET_NOTE_TABLE.' note ON (ticket.ticket_id=note.ticket_id )';
}

$qgroup=' GROUP BY ticket.ticket_id';
//get ticket count based on the query so far..
$total=db_count("SELECT count(DISTINCT ticket.ticket_id) $qfrom $qwhere");
//pagenate
$pageNav=new Pagenate($total,$page,$pagelimit);
$pageNav->setURL('tickets.php',$qstr.'&sort='.urlencode($_REQUEST['sort']).'&order='.urlencode($_REQUEST['order']));
//
//Ok..lets roll...create the actual query
//ADD attachment,priorities and lock crap
$qselect.=' ,count(attach.attach_id) as attachments, IF(ticket.reopened is NULL,ticket.created,ticket.reopened) as effective_date';
$qfrom.=' LEFT JOIN '.TICKET_PRIORITY_TABLE.' pri ON ticket.priority_id=pri.priority_id '.
        ' LEFT JOIN '.TICKET_LOCK_TABLE.' tlock ON ticket.ticket_id=tlock.ticket_id AND tlock.expire>NOW() '.
        ' LEFT JOIN '.TICKET_ATTACHMENT_TABLE.' attach ON  ticket.ticket_id=attach.ticket_id ';

$query="$qselect $qfrom $qwhere $qgroup ORDER BY $order_by $order LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();
//echo $query;
$tickets_res = db_query($query);
$showing=db_num_rows($tickets_res)?$pageNav->showing():"";
if(!$results_type) {
    $results_type=($search)?'Resultados de la B&uacute;squeda':ucfirst($ger_status).'';
}
$negorder=$order=='DESC'?'ASC':'DESC'; //Negate the sorting..

//Permission  setting we are going to reuse.
$canDelete=$canClose=false;
$canDelete=$thisuser->canDeleteTickets();
$canClose=$thisuser->canCloseTickets();
$basic_display=!isset($_REQUEST['advance_search'])?true:false;

//YOU BREAK IT YOU FIX IT.
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
<!-- SEARCH FORM START -->
<div id='basic' style="display:<?php echo $basic_display?'block':'none'?>">
    <form action="tickets.php" method="get">
    <input type="hidden" name="a" value="search">
    <table>
        <tr>
            <td>B&uacute;squeda: </td>
            <td><input type="text" id="query" name="query" size=30 value="<?php echo Format::htmlchars($_REQUEST['query'])?>"></td>
            <td><input type="submit" name="basic_search" class="button" value="Buscar">
             &nbsp;[<a href="#" onClick="showHide('basic','advance'); return false;">B&uacute;squeda Avanzada</a>] </td>
        </tr>
    </table>
    </form>
</div>
<div id='advance' style="display:<?php echo $basic_display?'none':'block'?>">
 <form action="tickets.php" method="get">
 <input type="hidden" name="a" value="search">
  <table>
    <tr>
        <td>B&uacute;squeda: </td><td><input type="text" id="query" name="query" value="<?php echo Format::htmlchars($_REQUEST['query'])?>"></td>
        <td>Departamento:</td>
        <td><select name="dept"><option value=0>Todos los Departamentos</option>
            <?php 
                //Showing only departments the user has access to...
                $sql='SELECT dept_id,dept_name FROM '.DEPT_TABLE;
                if(!$thisuser->isadmin())
                    $sql.=' WHERE dept_id IN ('.implode(',',$thisuser->getDepts()).')';
                
                $depts= db_query($sql);
                while (list($deptId,$deptName) = db_fetch_row($depts)){
                $selected = ($_GET['dept']==$deptId)?'selected':''; ?>
                <option value="<?php echo $deptId?>"<?php echo $selected?>><?php echo $deptName?></option>
            <?php 
            }?>
            </select>
        </td>
        <td>Estado:</td><td>
    
        <select name="status">
            <option value='any' selected >Cualquiera</option>
            <option value="open" <?php echo !strcasecmp($_REQUEST['status'],'Open')?'selected':''?>>Abierto</option>
            <option value="overdue" <?php echo !strcasecmp($_REQUEST['status'],'overdue')?'selected':''?>>Vencido</option>
            <option value="closed" <?php echo !strcasecmp($_REQUEST['status'],'Closed')?'selected':''?>>Cerrado</option>
        </select>
        </td>
     </tr>
    </table>
    <div>
        Fecha desde:
        &nbsp;<input id="sd" name="startDate" value="<?php echo Format::htmlchars($_REQUEST['startDate'])?>" 
                onclick="event.cancelBubble=true;calendar(this);" autocomplete=OFF>
            <a href="#" onclick="event.cancelBubble=true;calendar(getObj('sd')); return false;"><img src='images/cal.png'border=0 alt=""></a>
            &nbsp;&nbsp; Hasta &nbsp;&nbsp;
            <input id="ed" name="endDate" value="<?php echo Format::htmlchars($_REQUEST['endDate'])?>" 
                onclick="event.cancelBubble=true;calendar(this);" autocomplete=OFF >
                <a href="#" onclick="event.cancelBubble=true;calendar(getObj('ed')); return false;"><img src='images/cal.png'border=0 alt=""></a>
            &nbsp;&nbsp;
    </div>
    <table>
    <tr>
       <td>Tipo:</td>
       <td>       
        <select name="stype">
            <option value="LIKE" <?php echo (!$_REQUEST['stype'] || $_REQUEST['stype'] == 'LIKE') ?'selected':''?>>Scan (%)</option>
            <option value="FT"<?php echo  $_REQUEST['stype'] == 'FT'?'selected':''?>>Texto Compl.</option>
        </select>
 

       </td>
       <td>Ordenar por:</td><td>
        <?php  
         $sort=$_GET['sort']?$_GET['sort']:'date';
        ?>
        <select name="sort">
    	    <option value="ID" <?php echo  $sort== 'ID' ?'selected':''?>>Numero de Ticket</option>
            <option value="pri" <?php echo  $sort == 'pri' ?'selected':''?>>Prioridad</option>
            <option value="date" <?php echo  $sort == 'date' ?'selected':''?>>Fecha</option>
            <option value="dept" <?php echo  $sort == 'dept' ?'selected':''?>>Departamento</option>
        </select>
        <select name="order">
            <option value="DESC"<?php echo  $_REQUEST['order'] == 'DESC' ?'selected':''?>>Ascendente</option>
            <option value="ASC"<?php echo  $_REQUEST['order'] == 'ASC'?'selected':''?>>Descentente</option>
        </select>
       </td>
        <td>Resultados por P&aacute;gina:</td><td>
        <select name="limit">
        <?php 
         $sel=$_REQUEST['limit']?$_REQUEST['limit']:15;
         for ($x = 5; $x <= 25; $x += 5) {?>
            <option  value="<?php echo $x?>" <?php echo ($sel==$x )?'selected':''?>><?php echo $x?></option>
        <?php }?>
        </select>
     </td>
     <td>
     <input type="submit" name="advance_search" class="button" value="Buscar">
       &nbsp;[ <a href="#" onClick="showHide('advance','basic'); return false;" >B&uacute;squeda B&aacute;sica </a> ]
    </td>
  </tr>
 </table>
 </form>
</div>
<script type="text/javascript">

    var options = {
        script:"ajax.php?api=tickets&f=search&limit=10&",
        varname:"input",
        shownoresults:false,
        maxresults:10,
        callback: function (obj) { document.getElementById('query').value = obj.id; document.forms[0].submit();}
    };
    var autosug = new bsn.AutoSuggest('query', options);
</script>
<!-- SEARCH FORM END -->
<div style="margin-bottom:20px">
 <table width="100%" border="0" cellspacing=0 cellpadding=0 align="center">
    <tr>
        <td width="80%" class="msg" >&nbsp;<b><?php echo $showing?>&nbsp;&nbsp;&nbsp;<?php echo $results_type?></b></td>
        <td nowrap style="text-align:right;padding-right:20px;">
            <a href=""><img src="images/refresh.gif" alt="Refrescar" border=0></a>
        </td>
    </tr>
 </table>
 <table width="100%" border="0" cellspacing=1 cellpadding=2>
    <form action="tickets.php" method="POST" name='tickets' onSubmit="return checkbox_checker(this,1,0);">
    <input type="hidden" name="a" value="mass_process" >
    <input type="hidden" name="status" value="<?php echo $statusss?>" >
    <tr><td>
       <table width="100%" border="0" cellspacing=0 cellpadding=2 class="dtable" align="center">
        <tr>
            <?php if($canDelete || $canClose) {?>
	        <th width="8px">&nbsp;</th>
            <?php }?>
	        <th width="70" >
                <a href="tickets.php?sort=ID&order=<?php echo $negorder?><?php echo $qstr?>" title="Ordenar por Ticket ID <?php echo $negorder?>">Ticket</a></th>
	        <th width="70">
                <a href="tickets.php?sort=date&order=<?php echo $negorder?><?php echo $qstr?>" title="Ordenar por Fecha <?php echo $negorder?>">Fecha</a></th>
	        <th width="280">Asunto</th>
	        <th width="120">
                <a href="tickets.php?sort=dept&order=<?php echo $negorder?><?php echo $qstr?>" title="Ordenar por Departamento <?php echo $negorder?>">Departamento</a></th>
	        <th width="70">
                <a href="tickets.php?sort=pri&order=<?php echo $negorder?><?php echo $qstr?>" title="Ordenar por Prioridad <?php echo $negorder?>">Prioridad</a></th>
            <th width="180" >De</th>
        </tr>
        <?php 
        $class = "row1";
        $total=0;
        if($tickets_res && ($num=db_num_rows($tickets_res))):
            while ($row = db_fetch_array($tickets_res)) {
                $tag=$row['staff_id']?'assigned':'openticket';
                $flag=null;
               if($row['lock_id']){
                    $flag='locked';
		    $ger_flag='bloqueado';
	       }
                elseif($row['staff_id']){
                    $flag='assigned';
		    $ger_flag='asignado';
		}
                elseif($row['isoverdue']){
                    $flag='overdue';
		    $ger_flag='vencido';
		}

                $tid=$row['ticketID'];
                $subject = Format::truncate($row['subject'],40);
                if(!strcasecmp($row['status'],'open') && !$row['isanswered'] && !$row['lock_id']) {
                    $tid=sprintf('<b>%s</b>',$tid);
                    //$subject=sprintf('<b>%s</b>',Format::truncate($row['subject'],40)); // Making the subject bold is too much for the eye
                }
                ?>
            <tr class="<?php echo $class?> " id="<?php echo $row['ticket_id']?>">
                <?php if($canDelete || $canClose) {?>
                <td align="center" class="nohover">
                    <input type="checkbox" name="tids[]" value="<?php echo $row['ticket_id']?>" onClick="highLight(this.value,this.checked);">
                </td>
                <?php }?>
                <td align="center" title="<?php echo $row['email']?>" nowrap>
                  <a class="Icon <?php echo strtolower($row['source'])?>Ticket" title="<?php echo $row['source']?> Ticket: <?php echo $row['email']?>" 
                    href="tickets.php?id=<?php echo $row['ticket_id']?>"><?php echo $tid?></a></td>
                <td align="center" nowrap><?php echo Format::db_date($row['created'])?></td>
                <td><a <?php if($flag) { ?> class="Icon <?php echo $flag?>Ticket" title="<?php echo ucfirst($ger_flag)?> Ticket" <?php }?> 
                    href="tickets.php?id=<?php echo $row['ticket_id']?>"><?php echo $subject?></a>
                    &nbsp;<?php echo $row['attachments']?"<span class='Icon file'>&nbsp;</span>":''?></td>
                <td nowrap><?php echo Format::truncate($row['dept_name'],30)?></td>
                <td class="nohover" align="center" style="background-color:<?php echo $row['priority_color']?>;"><?php echo $row['priority_desc']?></td>
                <td nowrap><?php echo Format::truncate($row['name'],22,strpos($row['name'],'@'))?>&nbsp;</td>
            </tr>
            <?php 
            $class = ($class =='row2') ?'row1':'row2';
            } //end of while.
        else: //not tickets found!! ?> 
            <tr class="<?php echo $class?>"><td colspan=8><b>La consulta a devuelto 0 resultados.</b></td></tr>
        <?php 
        endif; ?>
       </table>
    </td></tr>
    <?php 
    if($num>0){ //if we actually had any tickets returned.
    ?>
        <tr><td style="padding-left:20px">
            <?php if($canDelete || $canClose) { ?>
            Seleccionar:
                <a href="#" onclick="return select_all(document.forms['tickets'],true)">Todos</a>&nbsp;|&nbsp;
                <a href="#" onclick="return toogle_all(document.forms['tickets'],true)">Invertir Selecci&oacute;n</a>&nbsp;|&nbsp;
		<a href="#" onclick="return reset_all(document.forms['tickets'])">Ninguno</a>&nbsp;&nbsp;
            <?php }?>
            P&aacute;gina:<?php echo $pageNav->getPageLinks()?>
        </td></tr>
        <?php  if($canClose or $canDelete) { ?>
        <tr><td align="center"> <br>
            <?php 
            $status=$_REQUEST['status']?$_REQUEST['status']:$status;

            //If the user can close the ticket...mass reopen is allowed.
            //If they can delete tickets...they are allowed to close--reopen..etc.
            switch (strtolower($status)) {
                case 'closed': ?>
                    <input class="button" type="submit" name="reopen" value="Reabrir"
                        onClick=' return confirm("&iquest;Est&aacute; seguro que desea reabrir los Tickets seleccionados?");'>
                    <?php 
                    break;
                case 'open':
                case 'answered':
                case 'assigned':
                    ?>
                    <input class="button" type="submit" name="overdue" value="Vencido"
                        onClick=' return confirm("&iquest;Est&aacute; seguro que desea marcar los Tickets seleccionados comovencidos?");'>
                    <input class="button" type="submit" name="close" value="Cerrar"
                        onClick=' return confirm("&iquest;Est&aacute; seguro que desea cerrar los Tickets seleccionados?");'>
                    <?php 
                    break;
                default: //search??
                    ?>
                    <input class="button" type="submit" name="close" value="Cerrar"
                        onClick=' return confirm("&iquest;Est&aacute; seguro que desea cerrar los Tickets seleccionados?");'>
                    <input class="button" type="submit" name="reopen" value="Reabrir"
                        onClick=' return confirm("&iquest;Est&aacute; seguro que desea reabrir los Tickets seleccionados?");'>
            <?php 
            }
            if($canDelete) {?>
                <input class="button" type="submit" name="delete" value="Eliminar" 
                    onClick=' return confirm("&iquest;Est&aacute; seguro que desea eliminar los Tickets seleccionados?");'>
            <?php }?>
        </td></tr>
        <?php  }
    } ?>
    </form>
	 <form action="reporte_PDF.php" method="post" name="form1">
	<input type="submit" value="Reporte General" id="reporte">
	</form>
	<form action="abierto_PDF.php" method="post" name="form1">
	<input type="submit" value="Reporte Abiertos" id="reporte">
	</form>
	<form action="cerrado_PDF.php" method="post" name="form1">
	<input type="submit" value="Reporte Cerrados" id="reporte">
	</form>
 </table>
 
</div>

<?php
