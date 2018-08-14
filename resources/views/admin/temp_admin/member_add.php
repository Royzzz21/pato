<?



include '../config.php';

$_CFG[left_menu_file]="$www_dir/admin/left.member.php";

// 인증체크
admin_login_check();

if($popup_mode){
	define('POPUP_MODE',1);
}


// 헤더
include '../header.admin.php';


// 테이블 지정
$tbl_prefix=$_CFG[table_prefix];
$tbl="member";

// 명령
if($a){
	// 상태 변경
	if($a=='changeapprstatus' && $ac_idx){
		if($ac_approved=='0'){
			$new_val='1';
		}
		else {
			$new_val='0';
		}

		$db->query("update $tbl set ac_approved='$new_val' where ac_idx='$ac_idx'");

	}

}

$member_data=$db->fa("select * from member where ac_id='$ac_id'");

$is_mod=1;
$_USER[id]=$member_data[ac_id];

if($member_data[ac_type]==100){
	

?>

<? include "$www_dir/member/member_form.php"; ?>
<?
} else {
?>
<? include "$www_dir/member/member_form.adv.php"; ?>
<? } ?>




<?
// 푸터
include '../footer.admin.php';
?>