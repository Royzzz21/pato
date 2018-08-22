<?



include '../config.php';

$_CFG[left_menu_file]="$www_dir/admin/left.bbs.php";

// 인증체크
admin_login_check();

//debugreq();

// 헤더
include '../header.admin.php';


// 테이블 지정
$tbl_prefix=$_CFG[table_prefix];
$tbl="bbs_group";


//
//echo $a;
/*
mysql> desc na_account;
+---------+-------------+------+-----+---------+----------------+
| Field   | Type        | Null | Key | Default | Extra          |
+---------+-------------+------+-----+---------+----------------+
| ac_idx  | int(11)     | NO   | PRI | NULL    | auto_increment | 
| ac_id   | char(20)    | YES  | UNI | NULL    |                | 
| ac_pw   | char(32)    | YES  |     | NULL    |                | 
| ac_type | int(11)     | YES  | MUL | NULL    |                | 
+---------+-------------+------+-----+---------+----------------+
*/


// 명령처리
if($a){
	if($a=='add'){

		/*
		print_r($_REQUEST);
		exit;

		Array ( [login_id_check] => 1 [nick_name_check] => 1 [a] => 1 [memberUserTop_user_seq] => 0 [memberUserTop_user_type_cd] => P [memberUserTop_user_status_cd] => NORMAL [memberUserTop_photo_file_seq] => [memberUserTop_self_email] => [acc_id] => awdawd [nick_name] => adadasdsa [tmpNickNm] => [passwd1] => 121212 [passwd2] => 121212 [member_passwd_hint] => [memberUserTop_password_hint_answer] => [member_name] => asdasd [memberUserTop_birth_year] => 2000 [memberUserTop_birth_month] => 01 [memberUserTop_birth_day] => 01 [memberUserTop_email] => [memberUserTop_email_cd1] => [memberUserTop_email_cd] => [memberUserTop_post_cd] => [memberUserTop_addr1] => [memberUserTop_addr2] => [memberUserTop_campus_seq] => 0 [memberUserTop_center_seq] => 0 [memberUserTop_sex_type_cd] => M [memberUserTop_phone_home_area_cd] => [memberUserTop_phone_home_1] => [memberUserTop_phone_home_2] => [memberUserTop_phone_mobile_dv_cd] => [memberUserTop_phone_mobile_1] => [memberUserTop_phone_mobile_2] => [memberUserTop_email_recv_yn] => Y [memberUserTop_sms_recv_yn] => Y [user_id] => 1 [user_name] => 1 [user_info] => YTo0OntzOjM6ImlkeCI7czoyOiIxNCI7czo0OiJuYW1lIjtzOjE6IjEiO3M6NDoibmljayI7TjtzOjQ6InR5cGUiO3M6MToiMSI7fQ== [user_lt] => 1328666149 [user_hash] => 1414808ba39631cc8e4c064043661a1c )
		*/


		// 계정 생성을 위한 데이타 준비
		$acc_data=array();

		// 이름
		$acc_data[group_name]=$group_name;

		// 계정 생성.
		$ret=bbsgroup_add($acc_data);
		
		//echo $ret;
		if($ret){
			//msgbox('등록이 완료되었습니다.');
			go2("bbs_group.php");
			exit;

		}
		else {
			msgbox('등록이 실패되었습니다.');
			go_back();
			exit;
		}
	}
	else if($a=='update'){
		
		// 데이타 준비
		$acc_data=array();
		$acc_data[group_name]=$group_name;

		// 업데이트.
		$ret=bbsgroup_update($acc_data,"idx='$idx'");
		
		//echo $ret;
		if($ret){
			//msgbox('업데이트가 완료되었습니다.');
			go2("bbs_group.php");
			exit;

		}
		else {
			msgbox('업데이트가 실패되었습니다.');
			go_back();
			exit;
		}
	}


}




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

?>


<script>
	// 이메일 선택 변경시
	function onchangeemail(v){
		// 직접입력선택시
		//alert(v);
		if(v=='ETC'){
			document.getElementById('member_email2_div').style.display='block';
		}
		else {
			document.getElementById('member_email2_div').style.display='none';
		}
	}

	// 수정폼에서 닉네임을 원래대로 돌린다.
	function setOrigNick(){
		var f=document.getElementById('memberForm');
		f.nick_name.value=f.orig_nick_name.value;
	}

	// 수정폼에서 닉네임변화없으면
	function isOrigNick(){
		var f=document.getElementById('memberForm');
		if(f.group_name.value==f.orig_group_name.value){
			return true;
		}
		return false;
	}

	// 저장
	function goSave(){
		
		var f=document.getElementById('memberForm');

		<?
		// id검사
		if($req_item[ac_id]){
			?>
			// 신규등록이면 id체크를 해야합니다!
			if(f.a.value=='add'){
				// id 체크
				if(f.login_id_check.value=='1'){
					//alert('id유효함');
				}
				else {
					alert('id중복확인이 필요합니다.');
					return;

				}
			}
			<?
		}
		?>

		// 이름 확인
		if(f.group_name.value.length<1){
			alert('그룹명을 입력하세요.');
			return;
		}

		// 폼전송
		f.submit();

	}

	// 아이디 중복검사
	function parent_check_id(id){
		//alert(id);

		// 4자리 이하라면..
		if(id.length<=4){
			alert('5자리이상 입력해주십시오.');
			return;
		}

		$.ajax({
			type: "GET",
			url: "check_id.php",
			data: "id="+id,
			success: function(msg){
				if(parseInt(msg)>0){
					$('#login_id').val('');
					alert('이미 등록된 id 입니다. 다른 id를 사용하세요.');
				}
				else {
					$('#login_id_check').val('1');
					alert('사용가능합니다.');
				}
				//alert(msg);

				//alert( $('#margin_div'+ac_idx).val());
				//$('#margin_div'+ac_idx).text(new_margin+'%');
				/// $('#margin_div'+ac_idx).show();
				// $('#margin_edit_div'+ac_idx).hide();


				//alert( "업데이트 하였습니다: " + msg );

			}
		});
	}

	// nick 중복검사
	function parent_check_nick(nick){
		//alert(nick);

		// 2자리 미만이면..
		if(nick.length<2){
			alert('2자리이상 입력해주십시오.');
			return;
		}

		// 변화없으면
		if(isOrigNick(nick.value)){
			alert('사용가능합니다.');
			return;
		}

		$.ajax({
			type: "GET",
			url: "check_name.php",
			data: "id="+nick,
			success: function(msg){
				//alert(msg);
				
				if(parseInt(msg)>0){
					$('#group_name').val('');
					alert('이미 등록된 이름입니다. 다른 이름을 사용하세요.');
					setOrigNick();
				}
				else {
					$('#group_name_check').val('1');
					alert('사용가능합니다.');
				}
				//alert(msg);

				//alert( $('#margin_div'+ac_idx).val());
				//$('#margin_div'+ac_idx).text(new_margin+'%');
				/// $('#margin_div'+ac_idx).show();
				// $('#margin_edit_div'+ac_idx).hide();


				//alert( "업데이트 하였습니다: " + msg );

			}
		});
	}
	
</script>


<form id="memberForm" name="memberForm" onsubmit="return true;" action="<?=$phpself?>" method="post" autocomplete="off">
<input type=hidden name=sel_type value="<?=$sel_type?>">

<!-- 유효성검사 -->
<input type="hidden" id="login_id_check" name="login_id_check" value='0' />
<input type="hidden" id="group_name_check" name="group_name_check" value='0' />

<?
if($a=='mod_form'){
	?>
	<input type="hidden" name="a" value='update'>
	<input type=hidden name=idx value="<?=$idx?>">
	<?
	$data=$db->fa("select * from $tbl where idx='$idx'");
}
else {
	?>
	<input type="hidden" name="a" value='add'>
	<?
}

?>


<input type="hidden" name="memberUserTop.user_seq" value="0"/>
<input type="hidden" name="memberUserTop.user_type_cd" value="P"/>
<input type="hidden" name="memberUserTop.user_status_cd" value="NORMAL"/>
<input type="hidden"  name="memberUserTop.photo_file_seq"/>
<input type="hidden"  name="memberUserTop.self_email"/>

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="">
<tr>
  <td height=35 width="50%" class=content_title>
	그룹
	
	<? if($a=='mod_form'){ ?>
		수정
	<? } else { ?>
		등록
	<? } ?>
  </td>
  <!--td align="right" ><span class="route">ㆍ홈 &gt; <a href="#">사용자관리 &gt; <a href="#">학부모관리 &gt; 회원정보등록(학부모/일반)</a></span></td-->
</tr>
</table>

<table width="" border="0" cellspacing="0" cellpadding="0">

  <!--tr>
    <td>&nbsp;</td>
  </tr-->
  <tr>
    <td>
      <table width="100%" border="0" cellpadding="0" cellspacing="15" class="t020">
        
        <tr>
          <td width="19%" class="col_nm">그룹명 <img src="/img/icon/icon_check.gif"></td>
          <td colspan="3">
			<? if($a=='mod_form'){ ?>
			<input type=hidden name="orig_group_name" value="<?=$data[group_name]?>">
			<? } else {?>
			<input type=hidden name="orig_group_name" value="">
			<? } ?>
			
			<input name="group_name" value="<?=$data[group_name]?>" type="text" class="textarea" style = "width:120;" onFocus="this.select()" id="group_name">
			<input type="button" class="btn_type4" value="중복확인"  onclick="parent_check_nick($('#group_name').val());" style="cursor:hand" />
			<input type="hidden" name="tmpNickNm" value=""> 1~8자의 한글, 2~16자의 영문대소문자, 숫자, '-','_'만 가능합니다.
			
          </td>
        </tr>
        
      </table>
    </td>
  </tr>
  <!--tr>
    <td>&nbsp;</td>
  </tr-->
  <tr>
    <td align=center>
	
		<table border="0" cellpadding="0" cellspacing="0">
		<tr>
		  <td height=40><div id="btn_type1">
				<span class="button blue"><a href="javascript:goSave();">확인</a></span>
				<!--
				<span class="button blue"><a href="javascript:goReset();">다시입력</a></span>
				-->
				<span class="button blue"><a href="javascript:history.go(-1);">취소</a></span>
			</div></td>
		</tr>
		</table>
      
    </td>
  </tr>
</table>

</form>

<script>
//f=document.getElementById('memberForm');
//f.center_idx.value='<?=$data[center_idx]?>';
</script>



<?
// 푸터
include '../footer.admin.php';
?>