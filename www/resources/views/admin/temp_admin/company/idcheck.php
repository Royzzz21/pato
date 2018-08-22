<?
include '../config.php';
$tot=$db->fa1("select count(*) from company where com_id='$id'");
echo $tot;
?>