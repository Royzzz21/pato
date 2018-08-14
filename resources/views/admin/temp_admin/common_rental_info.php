<?


include_once '../config.php';
$_CFG[left_menu_file]="$www_dir/admin/left.member.php";

admin_login_check();



include '../header.admin.php';

if($a){

	//change_pw($_USER[id],$new_pw);
	$db->query("replace into config set name='common_info',val='$new_info'");
	msgbox("변경되었습니다.");


}


?>


	<form name="pw_form" method="post" action="<?=$phpself?>">
		<input type=hidden name=a value=1>

		<div class="comm_box_pchk">
			<div>
			  <div style="padding-left:20px; padding-top:17px;">
			  
				  <p> <b>공통 안내사항</b>  :</p>
				  <textarea name="new_info" class="pop_input_070" style="width:500px;height:300px;"><?=$db->fa1("select val from config where name='common_info'")?></textarea>
				  <a href="javascript:document.pw_form.submit();"><img src="/img/btn_pw_chk.gif"></a></p>
			  </div>
			</div>
		</div>
	</form>


<?
include '../footer.admin.php';
?>