<?
include 'config.php';

//if(!$id){
	//$id = $_REQUEST[id];
//}

$id='banner';

/*
Array
(
    [a] => write_process
    [cafe_id] => drtip
    [id] => product_ko
    [mode] => 
    [idx] => 
    [autodist] => 0
    [popup_mode] => 
    [selected_cat] => product1_1
    [bbs_subject] => ghjgj
    [wr_content] => ghfjfghjfghj
    [user_name] => sdf
    [user_id] => admin
    [user_info] => YTo1OntzOjM6ImlkeCI7czoyOiI2MCI7czo0OiJuYW1lIjtzOjM6InNkZiI7czo0OiJuaWNrIjtzOjM6InNkZiI7czo0OiJ0eXBlIjtzOjQ6Ijk5OTkiO3M6NDoiYXBwciI7czoxOiIxIjt9
    [user_lv] => 0
    [user_lt] => 1402380713
    [user_hash] => 5f7da7da02bfcbd205d685350728aa9a
)
print_r($_REQUEST);
*/

include '../header.admin.php';
?>


<table width=100%>
<tr><td>

<?
include($www_dir.'/bbs/bbs.php');
?>


</td></tr></table>

<?
include '../footer.admin.php';

?>