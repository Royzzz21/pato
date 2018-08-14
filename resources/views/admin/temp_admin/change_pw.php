<?


include_once '../config.php';
$_CFG[left_menu_file]="$www_dir/admin/left.member.php";

admin_login_check();



include '../header.admin.php';

if($pw){
	//echo $pw;
	//exit;

	// 패스워드 맞는지 검사
	if(!pw_chk($_USER[id],$pw)){
		msgbox("비밀번호가 틀립니다!");
	}
	else if(strlen(trim($new_pw))==0 || strcmp($new_pw,$new_pw2)){
		msgbox("비밀번호를 다시 입력해 주십시오.");		
	}
	else {
		// 변경처리
		change_pw($_USER[id],$new_pw);
		msgbox("변경되었습니다.");
		//go2($then_go);
		//exit;
	}
}


?>




		   
		  <style>
		  .comm_box_pchk { border:1px solid #dde2e8; background-color:#f4f8fc;  padding:5px 5px 5px 5px; width:455px; margin-top:20px; }
		  .in01_box_pchk { width:450px; float:left; border:1px solid #e4e4e4; background-color:#fff; margin-top:0px; height:60px;}
		  .pop_input_070[type~="text"] {border:1px solid #888; padding:4px 2px 2px 2px; background-color:#fff; color:#000; margin-left:2px; margin-right:5px; width:160px;}
		  </style>

		  
		  <form name="pw_form" method="post" action="<?=$phpself?>">
		  <input type=hidden name=then_go value=<?=$phpself?>>
		   <div class="comm_box_pchk">
		     <div>
			      <div style="padding-left:20px; padding-top:17px;">
				  
				  <p> <b>current password </b>  : <input type="password" name="pw" class="pop_input_070"></p>
				  </div>
				  <div style="padding-left:20px; padding-top:17px;">
				  
				  <p> <b>new password</b>  : <input type="password" name="new_pw" class="pop_input_070"></p>
				  </div>
				  <div style="padding-left:20px; padding-top:17px;">
				  
				  <p> <b>new password (confirm) </b>  : <input type="password" name="new_pw2" class="pop_input_070"><a href="javascript:document.pw_form.submit();"><img src="/img/btn_pw_chk.gif"></a></p>
				  </div>
			</div>
		   </div>
		   </form>





		

<?
include '../footer.admin.php';
?>