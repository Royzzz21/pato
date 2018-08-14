<?



include '../config.php';

// 메뉴지정
$curr_menu='/admin/test';

// 인증체크
na_pageauthcheck();

// 헤더
include '../../header.admin.php';



// 테이블 지정
$tbl_prefix=$_CFG[table_prefix];
$tbl="company";






// 창고등록
function company_add($a){
	global $_CFG;
	global $db;

	// query 생성
	$s=make_set($a);
	$q="insert into company set $s";


	// 실행
	return $db->query($q);
}

// 창고업뎃
function company_update($a,$where){
	global $_CFG;
	global $db;

	// 조건이 없으면 업데이트를 막기위해서 취소처리
	if(!$where){
		return 0;
	}

	// query 생성
	$s=make_set($a);
	$q="update company set $s where $where";
	
	// 실행
	return $db->query($q);
}


// 명령처리

if($a){
	if($a=='add' ||$a=='update'){
		
		$acc_data=array();
		//$acc_data[ac_idx]=$_USER[idx];

		// 이름을 입력했는지 체크
		if(!$com_name){
			msgbox('이름을 입력해주시기 바랍니다.');
			go_back();
			exit;
		}

		$acc_data[com_id]=$com_id;
		$acc_data[com_name]=$com_name;
		$acc_data[com_ceoname]=$com_ceoname;

		// 전번
		$acc_data[com_tel]="$com_phone1-$com_phone2-$com_phone3";

		// 모바일
		$acc_data[com_mobile]="$com_mobile1-$com_mobile2-$com_mobile3";

		// 이메일
		$email_domain=($member_email3=='ETC'?$member_email2:$member_email3);
		if($email_domain){
			$acc_data[com_email]=$member_email1.'@'.$email_domain;
		}
		else {
			$acc_data[com_email]='';
		}


		/*
				print_r($_REQUEST);
				exit;

		Array ( [idx] => [a] => add [edit] => [com_name] => 1 [com_ceoname] => 2 [com_phone1] => 3 [com_phone2] => 4 [com_phone3] => 5 [com_mobile1] => 6 [com_mobile2] => 7 [com_mobile3] => 8 [member_email1] => 11 [member_email2] => [member_email3] => chol.com [user_id] => dev@goweb.kr [user_info] => YTo1OntzOjM6ImlkeCI7czoyOiI1NiI7czo0OiJuYW1lIjtzOjA6IiI7czo0OiJuaWNrIjtzOjA6IiI7czo0OiJ0eXBlIjtzOjM6IjEwMCI7czo0OiJhcHByIjtzOjE6IjAiO30= [user_lv] => 9999 [user_lt] => 1372565715 [user_hash] => 70bd4aed01614acf56b56fa63b8e8502 )
		*/

		if($a=='add'){

			$acc_data[regtime]=time();


			// 생성.
			$ret=company_add($acc_data);
			
			//echo $ret;
			if($ret){
				msgbox('등록이 완료되었습니다.');
				go2('company.php');
				exit;
			}
			else {
				msgbox('등록이 실패되었습니다.');
				go_back();
				exit;
			}
		}
		else if($a=='update'){



			// 창고 변경.
			$ret=company_update($acc_data,"idx='$idx'");
			
			//echo $ret;
			if($ret){
				msgbox('변경이 완료되었습니다.');
				go2('company.php');
				exit;

			}
			else {
				msgbox('변경이 실패되었습니다.');
				go_back();
				exit;
			}

		}
	}

}






?>


<script>

	// 저장
	function goSave(){
		var f=document.getElementById('memberForm');


		
		// id 확인
		if(f.com_id.value.length<1){
			alert('회사id를 입력하세요.');
			return;
		}
		else {
			if(f.com_id.value!=f.com_id_orig.value && $('#com_id_check').val()=='0'){
				alert('ID 중복검사를 해주세요.');
				return;

			}

		}

		// 이름 확인
		if(f.com_name.value.length<1){
			alert('회사명을 입력하세요.');
			return;
		}

		if(f.com_ceoname.value.length<1){
			alert('회사 담당자 성명을 입력하세요.');
			return;
		}

		if(f.com_phone1.value.length<1 || f.com_phone2.value.length<1 || f.com_phone3.value.length<1){
			alert('담당자 연락처를 입력하세요.');
			return;
		}


		// 폼전송
		f.submit();

	}

	// 아이디 중복검사
	function company_idcheck(id){
		//alert(id);

		// 2자리 이하라면..
		if(id.length<2){
			alert('2자리이상 입력해주십시오.');
			return;
		}

		$.ajax({
			type: "GET",
			url: "idcheck.php",
			data: "id="+id,
			success: function(msg){
				if(parseInt(msg)>0){
					$('#com_id').val('');
					alert('이미 등록된 id입니다. 다른 id를 사용하세요.');
				}
				else {
					$('#com_id_check').val('1');
					alert('사용가능합니다.');
				}
				

			}
		});
	}



	
</script>


<form id="memberForm" name="memberForm" onsubmit="return true;" action="<?=$phpself?>" method="post" class="t_ex3" style="width:100%">
<input type=hidden name=idx value='<?=$idx?>'>

<input type=hidden name=com_id_check id=com_id_check value='0'>

<?
if($a=='mod_form'){
	?>
	<input type="hidden" name="a" value='update'>
	<input type=hidden name=idx value="<?=$idx?>">
	<?
	//echo "select * from $tbl where ac_id='$ac_id'";
	$data=$db->fa("select * from $tbl where idx='$idx'");
	//print_r($data);exit;

	list($com_phone1,$com_phone2,$com_phone3)=explode('-',$data[com_tel]);
	list($com_mobile1,$com_mobile2,$com_mobile3)=explode('-',$data[com_mobile]);



}
else {
	?>
	<input type="hidden" name="a" value='add'>
	<?
}

?>

<input type=hidden name=com_id_orig id=com_id_orig value='<?=$data[com_id]?>'>

<input type="hidden" name="edit"/>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="btitle01">
        <tr>
          <td width="50%">회사 등록</td>
          <td align="right" >
		  
		  </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
      <table width="100%" class=t_ex1 border="0" cellpadding="0" cellspacing="0" class="table_view">

		<tr>
          <td width="19%" class="col_nm">회사ID</td>
          <td colspan="3">
			<input id="com_id" name="com_id" value="<?=$data[com_id]?>" type="text" class="textarea" style = "width:150;" value="">
			<span class="button bp small"><a href="javascript:company_idcheck($('#com_id').val());">중복확인</a></span>

          </td>
		</tr>


        <tr>
          <td width="19%" class="col_nm">회사명</td>
          <td colspan="3">
            
            
            <input id="com_name" name="com_name" value="<?=$data[com_name]?>" type="text" class="textarea" style = "width:150;" value="">
			<!--
            <input type="button" class="btn_type4" value="중복확인"  onclick="center_check_name($('#center_name').val());" style="cursor:hand" />
			-->
            
          </td>
        </tr>
		<tr>
          <td width="19%" class="col_nm">담당자 성명</td>
          <td colspan="3">
            
            
            <input id="com_ceoname" name="com_ceoname" value="<?=$data[com_ceoname]?>" type="text" class="textarea" style = "width:150;" value="">
           
          </td>
        </tr>
		<tr>
          <td width="19%" class="col_nm">담당자 전화번호</td>
          <td colspan="3">
                        
            <input name="com_phone1" value="<?=$com_phone1?>" type="text" onFocus="this.select()" onBlur="checkKeyNum(this)" class="textarea" id="com_phone1" size="4" maxlength="4"> 
            - <input name="com_phone2" value="<?=$com_phone2?>" type="text" onFocus="this.select()" onBlur="checkKeyNum(this)" class="textarea" id="com_phone2" size="7" maxlength="4">
            - <input name="com_phone3" value="<?=$com_phone3?>" type="text" onFocus="this.select()" onBlur="checkKeyNum(this)" class="textarea" id="com_phone3" size="7" maxlength="4">

          </td>
        </tr>

		<tr>
          <td width="19%" class="col_nm">담당자 핸드폰 </td>
          <td colspan="3">
            
            
            <input name="com_mobile1" value="<?=$com_mobile1?>" type="text" onFocus="this.select()" onBlur="checkKeyNum(this)" class="textarea" id="com_mobile1" size="4" maxlength="4"> 
            - <input name="com_mobile2" value="<?=$com_mobile2?>" type="text" onFocus="this.select()" onBlur="checkKeyNum(this)" class="textarea" id="com_mobile2" size="7" maxlength="4">
            - <input name="com_mobile3" value="<?=$com_mobile3?>" type="text" onFocus="this.select()" onBlur="checkKeyNum(this)" class="textarea" id="com_mobile3" size="7" maxlength="4">

          </td>
        </tr>

		<tr>
          <td width="19%" class="col_nm">담당자 이메일 </td>
          <td colspan="3">
            
            <!--
            <input id="com_email" name="com_email" value="<?=$data[com_email]?>" type="text" class="textarea" style = "width:150;" value="">
			-->

			<div style="float:left;"><input name="member_email1" type="text" class="textarea"  style = "width:120;ime-mode:inactive" onFocus="this.select()" value="" id="member_email1">@</div>
			<div style="float:left;display:none;" id="member_email2_div"><input type="text" name="member_email2" class="textarea" onFocus="this.select()" id="member_email2" style="width:120; ime-mode:inactive;"></div>
			<div style="float:left;">
	            <select name="member_email3" onChange="onchangeemail(this.value);">
			        <option value="">::선택::</option>
		            <option value='ETC' >직접입력</option><option value='chol.com' >chol.com</option><option value='dreamwiz.com' >dreamwiz.com</option><option value='empal.com' >empal.com</option><option value='freechal.com' >freechal.com</option><option value='gmail.com' >gmail.com</option><option value='hanafos.com' >hanafos.com</option><option value='hanmail.net' >hanmail.net</option><option value='hanmir.com' >hanmir.com</option><option value='hitel.net' >hitel.net</option><option value='hotmail.com' >hotmail.com</option><option value='korea.com' >korea.com</option><option value='lycos.co.kr' >lycos.co.kr</option><option value='nate.com' >nate.com</option><option value='naver.com' >naver.com</option><option value='netian.com' >netian.com</option><option value='paran.com' >paran.com</option><option value='yahoo.com' >yahoo.com</option><option value='yahoo.co.kr' >yahoo.co.kr</option>
	            </select>
			</div>

			<div style="clear:both;">ex) "hotmail.com" @는 생략하고 입력하세요.</div>

          </td>
        </tr>
           
           
        
    </table>
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
</tr>
<tr>
  <td>
    <table border="0" align="center" cellpadding="0" cellspacing="0">
      <tr><!-- <a href="javascript:goDeletePlace();"><span>삭제</span></a> -->
        <td ><div id="btn_type1"><span class="button bp"><a href="javascript:goSave();">저장</a></span>
			&nbsp;<span class="button bp"><a id="tmdPlaceWriteForm_" href="company.php">목록</a></span></div></td>
      </tr>
    </table>
  </td>
</tr>
</table>
</form>


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


f=document.getElementById('memberForm');
<?
list($member_email1,$member_email2)=explode('@',$data[com_email]);
$member_email3=$member_email2;
?>


f.member_email1.value='<?=$member_email1?>';
f.member_email2.value='<?=$member_email2?>';

// 이메일 도메인이 셀렉트에 있나 확인
<? if($member_email3){
	?>
	var bEmailExists=false;
	for(var i=0;i<f.member_email3.options.length;i++){
		//alert(f.member_email3.options[i].value);
		if(f.member_email3.options[i].value=='<?=$member_email3?>'){
			bEmailExists=true;
			f.member_email3.value='<?=$member_email3?>';
			//alert('a');
			onchangeemail(f.member_email3.options[i].value);
			break;
		}
	}
	<?
} ?>

//f.member_division.value='<?=$data[member_division]?>';

//f.center_idx.value='<?=$data[center_idx]?>';
</script>



<?
// 푸터
include '../../footer.admin.php';
?>