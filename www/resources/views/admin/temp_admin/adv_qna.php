<?



include '../config.php';
$_CFG[left_menu_file]="$www_dir/admin/left.adv.php";

// 인증체크
admin_login_check();



// 헤더
include '../header.admin.php';



?>




<table cellpadding="0" cellspacing="0" width="790">
<tbody><tr>
<td class="big_ti_020" align="left" height="35px;"><img src="/img/admin/tit_blet_b.gif">1:1 상담</td>
</tr>
</tbody></table>





<?
$write_priv=1;
$id='adv_qna';
include $www_dir.'/bbs/bbs.php';

?>




<?
// 푸터
include '../footer.admin.php';
?>