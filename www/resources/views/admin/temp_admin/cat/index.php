<?
/*---------------------------------------------------------
 * 카테고리 테이블 관리
*/

include '../../../config.php';
$curr_menu='bbs';

// 인증체크
na_pageauthcheck();

include $www_dir.'/header.php';


// 테이블 지정
$tbl_prefix=$_CFG[table_prefix];
$tbl="{$tbl_prefix}cat";

/*
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
*/

?>

카테고리 테이블 관리

<?


include $www_dir.'/footer.php';
?>