<?
$where[]="channel='".$_GET['channel']."'";
if($_GET['page']) $where[]="page='".$_GET['page']."'";
if($where) $where='where '.implode(' and ',$where);
$sql='select '.$_GET['idf'].' as id,'.$_GET['namef'].' as name from _sys_local '.$where.' group by '.$_GET['idf'].'';
echo aryOption($webdb->getList($sql));
?>