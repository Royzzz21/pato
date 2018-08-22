<?


include_once '../config.php';
$_CFG[left_menu_file]="$www_dir/admin/left.stat.php";

admin_login_check();

if(!$search[startdate]){
	$search[startdate]=date('Y-m-d');
	$search[enddate]=date('Y-m-d');
}


include '../header.admin.php';
?>

<div id=ctl00_mvContentWidth class=subCtn>
	
광고효과

<? include "$www_dir/adv/stat.common.php"; ?>

</div>

<?
include '../footer.admin.php';
?>