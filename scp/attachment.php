<?php

require('staff.inc.php');
//TODO:administrador de alerta sobre cualquier error en este archivo.
if(!$thisuser || !$thisuser->isStaff() || !$_GET['id'] || !$_GET['ref']) die('Acceso Denegado');
$sql='SELECT attach_id,ref_id,ticket.ticket_id,dept_id,file_name,file_key,staff_id,ticket.created FROM '.TICKET_ATTACHMENT_TABLE.
    ' LEFT JOIN '.TICKET_TABLE.' ticket USING(ticket_id) '.
    ' WHERE attach_id='.db_input($_GET['id']);
//ID valida??
if(!($resp=db_query($sql)) || !db_num_rows($resp)) die('Archivo no v&aacute;lido');
list($id,$refid,$tid,$deptID,$filename,$key,$staffId,$createDate)=db_fetch_row($resp);

$hash=MD5($tid*$refid.session_id());
if(!$_GET['ref'] || strcmp($hash,$_GET['ref'])) die('Acceso Denegado');
//Compruebe el acceso de entradas,
if($staffId!=$thisuser->getId() && !$thisuser->canAccessDept($deptID)) die("No tienes permiso para ver este Ticket");

//ver si el archivo es real.



$month=date('my',strtotime($createDate));
$file=rtrim($cfg->getUploadDir(),'/')."/$month/$key".'_'.$filename;
if(!file_exists($file))
    $file=rtrim($cfg->getUploadDir(),'/')."/$key".'_'.$filename;

if(!file_exists($file)) die('No existe el archivo');

$extension =substr($filename,-3);
switch(strtolower($extension))
{
  case "pdf": $ctype="application/pdf"; break;
  case "exe": $ctype="application/octet-stream"; break;
  case "zip": $ctype="application/zip"; break;
  case "doc": $ctype="application/msword"; break;
  case "xls": $ctype="application/vnd.ms-excel"; break;
  case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
  case "gif": $ctype="image/gif"; break;
  case "png": $ctype="image/png"; break;
  case "jpg": $ctype="image/jpg"; break;
  default: $ctype="application/force-download";
}
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public"); 
header("Content-Type: $ctype");
$user_agent = strtolower ($_SERVER["HTTP_USER_AGENT"]);
if ((is_integer(strpos($user_agent,"msie"))) && (is_integer(strpos($user_agent,"win")))) 
{
  header( "Content-Disposition: filename=".basename($filename).";" );
} else {
  header( "Content-Disposition: attachment; filename=".basename($filename).";" );
}
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".filesize($file));
readfile($file);
exit();
?>
    
