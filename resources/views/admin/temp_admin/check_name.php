<?



include '../config.php';

$_CFG[left_menu_file]="$www_dir/admin/left.bbs.php";

// 인증체크
admin_login_check();

// 테이블 지정
$tbl_prefix=$_CFG[table_prefix];
$tbl="bbs_group";

//echo $tbl;


$tot=$db->fa1("select count(*) from $tbl where group_name='$id'");

echo $tot;


?>