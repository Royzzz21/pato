<?


include_once '../config.php';
$_CFG[left_menu_file]="$www_dir/admin/left.member.php";

admin_login_check();



include '../header.admin.php';

if($a=='update'){

	// 업데이트 데이타 준비
	$acc_data=array();

	// 이름
	$acc_data[tm_name]=$tm_name;
	// 전화번호
	$acc_data[tm_phone]=$tm_phone;
	// 계좌
	$acc_data[tm_bank]=$tm_bank;
	// 도메인
	$acc_data[tm_domain]=$tm_domain;

	if($is_admin && $orig_id){
		$ret=update_account($acc_data,"ac_id='$orig_id'");
	}
	else {
		// 계정 업데이트.
		$ret=update_account($acc_data,"ac_id='$_USER[id]'");
	}
	if($ret){
		msgbox("변경되었습니다.");
	}
	

}


$tm_data=$db->fa("select * from member where ac_id='$_USER[id]'");

?>




		   
		  <style>
		  .comm_box_pchk { border:1px solid #dde2e8; background-color:#f4f8fc;  padding:5px 5px 5px 5px; width:455px; margin-top:20px; }
		  .in01_box_pchk { width:450px; float:left; border:1px solid #e4e4e4; background-color:#fff; margin-top:0px; height:60px;}
		  .pop_input_070[type~="text"] {border:1px solid #888; padding:4px 2px 2px 2px; background-color:#fff; color:#000; margin-left:2px; margin-right:5px; width:160px;}
		  </style>

		  
		  <form name="pw_form" method="post" action="<?=$phpself?>">
		  <input type=hidden name=a value=update>
		  <input type=hidden name=then_go value=<?=$phpself?>>
		   <div class="comm_box_pchk">
		     <div>
			      <div style="padding-left:20px; padding-top:17px;">
				  
				  <p> <b>업체명 </b>  : <input type="text" name="tm_name" class="pop_input_070" value="<?=$tm_data[tm_name]?>"></p>
				  </div>
				  <div style="padding-left:20px; padding-top:17px;">

				  <p> <b>전화번호 </b>  : <input type="text" name="tm_phone" class="pop_input_070" value="<?=$tm_data[tm_phone]?>"></p>
				  </div>
				  <div style="padding-left:20px; padding-top:17px;">
				  
				  <p> <b>입금계좌번호 </b>  : <input type="text" name="tm_bank" class="pop_input_070" value="<?=$tm_data[tm_bank]?>"></p>
				  </div>
				  <div style="padding-left:20px; padding-top:17px;">
				  
				  <p> <b>부여받은 사이트 주소 </b>  : <input type="text" name="tm_domain" class="pop_input_070" value="<?=$tm_data[tm_domain]?>"><a href="javascript:document.pw_form.submit();"><img src="/img/btn_pw_chk.gif"></a> (특별한 경우가 아니면  사이트주소를 변경하지마십시오. 협의없이 변경하면 문제가 발생할수 있습니다.)</p>
				  </div>
			</div>
		   </div>
		   </form>





		

<?
include '../footer.admin.php';
?>