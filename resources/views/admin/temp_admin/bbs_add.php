<?



include '../config.php';

$_CFG[left_menu_file]="$www_dir/admin/left.bbs.php";

// 인증체크
admin_login_check();

//debugreq();

// 헤더
include '../header.admin.php';


/*
mysql> desc bbs_group;
+------------+-----------+------+-----+---------+----------------+
| Field      | Type      | Null | Key | Default | Extra          |
+------------+-----------+------+-----+---------+----------------+
| idx        | int(11)   |      | PRI | NULL    | auto_increment |
| bbs_id | char(50)  |      | UNI |         |                |
| is_open    | int(11)   |      |     | 0       |                |
| tot_board  | int(11)   |      |     | 0       |                |
| group_desc | char(200) |      |     |         |                |
+------------+-----------+------+-----+---------+----------------+
*/

//--------------------------------------------
// 필수입력항목 정의
// 변경폼 & 변경
if($a=='mod_form' || $a=='update'){
	$req_item=array();
	$req_item[bbs_id]=1;

}
// 등록폼 & 등록
else {
	$req_item=array();
	$req_item[bbs_id]=1;

}



// 헤더
include '../../header.admin.php';

//debugreq();


// 테이블 지정
$tbl_prefix=$_CFG[table_prefix];
$tbl="{$tbl_prefix}student";


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


//print_r($_REQUEST);
$a=$_REQUEST['a'];

// 명령처리
if($a){

	// 게시판 생성
	if($a=='add'){

		/*
		 *
		 * Array ( [sel_type] => [login_id_check] => 0 [bbs_id_check] => 1 [a] => add [memberUserTop_user_seq] => 0 [memberUserTop_user_type_cd] => P [memberUserTop_user_status_cd] => NORMAL [memberUserTop_photo_file_seq] => [memberUserTop_self_email] => [board_type] => studio [group_idx] => [orig_bbs_id] => [bbs_id] => house [tmpNickNm] => [bbs_name] => house )
		 *
		print_r($_REQUEST);
		exit;
		*/



		// 계정 생성을 위한 데이타 준비
		$acc_data=array();

		// 이름
		$acc_data[id]=$_REQUEST['bbs_id'];
		$acc_data[group_idx]=$_REQUEST['group_idx'];
		$acc_data[bbs_name]=$_REQUEST['bbs_name'];
		$acc_data[board_type]=$_REQUEST['board_type'];
		$acc_data[bbs_guest_write]=$_REQUEST['bbs_guest_write'];

		//Array ( [id] => 62 [group_idx] => 11 [bbs_name] => TV 영상 [board_type] => board ) 
		//print_r($acc_data);exit;
		//Array ( [sel_type] => [login_id_check] => 0 [bbs_id_check] => 0 [a] => add [memberUserTop_user_seq] => 0 [memberUserTop_user_type_cd] => P [memberUserTop_user_status_cd] => NORMAL [memberUserTop_photo_file_seq] => [memberUserTop_self_email] => [board_type] => board [orig_bbs_id] => [bbs_id] => zzzz [tmpNickNm] => [user_id] => 1 [user_name] => 1 [user_info] => YTo0OntzOjM6ImlkeCI7czoyOiIxNCI7czo0OiJuYW1lIjtzOjE6IjEiO3M6NDoibmljayI7TjtzOjQ6InR5cGUiO3M6MToiMSI7fQ== [user_lt] => 1329686139 [user_hash] => ffef98bca9d7df49a3c0530216055a53 )

		// 계정 생성.
		$ret=zoi_create_bbs($acc_data,$cafe_id);
		//exit;

		//echo $ret;
		//exit;
		if($ret){
			//msgbox('등록이 완료되었습니다.');
			go2("bbs_list.php");
			exit;

		}
		else {
			msgbox('등록이 실패되었습니다.');
			go_back();
			exit;
		}

	}

	// 게시판 수정
	else if($a=='update'){

		// 데이타 준비
		$acc_data=array();
		//$acc_data[board_type]=$board_type;
		$acc_data[group_idx]=$group_idx;
		$acc_data[bbs_name]=$bbs_name;
		$acc_data[bbs_guest_write]=$bbs_guest_write;

		// 업데이트.
		//$ret=bbs_update($acc_data,"cafe_id='kms' and id='$bbs_id'");
		$ret=bbs_update($acc_data,"id='$bbs_id'");

		//echo $ret;
		if($ret){
			//msgbox('업데이트가 완료되었습니다.');
			go2("bbs_list.php");
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
			if(f.bbs_id.value==f.orig_bbs_id.value){
				return true;
			}
			return false;
		}

		// 저장
		function goSave(){

			var f=document.getElementById('memberForm');

			<?
			// bbs_id 검사
			if($req_item[bbs_id]){
				?>
			// 신규등록이면 id체크를 해야합니다!
			if(f.a.value=='add'){
				// id 체크
				if(f.bbs_id_check.value=='1'){
					//alert('id유효함');
				}
				else {
					alert('id중복확인이 필요합니다.');
					return;

				}
			}

			// 이름 확인
			if(f.bbs_id.value.length<1){
				alert('이름을 입력하세요.');
				return;
			}


			<?
			
		}
		?>


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
						$('#bbs_id').val('');
						alert('이미 등록된 이름입니다. 다른 이름을 사용하세요.');
						setOrigNick();
					}
					else {
						$('#bbs_id_check').val('1');
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
		<input type="hidden" id="bbs_id_check" name="bbs_id_check" value='0' />

		<?
		if($a=='mod_form'){
			?>
			<input type="hidden" name="a" value='update'>
			<input type=hidden name=idx value="<?=$idx?>">
			<?
			$data=$db->fa("select * from lst_board_{$cafe_id} where id='$id'");
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
					Board

					<? if($a=='mod_form'){ ?>
						revision
					<? } else { ?>
						register
					<? } ?>
				</td>
				<!--td align="right" ><span class="route">ㆍ홈 &gt; <a href="#">사용자관리 &gt; <a href="#">학부모관리 &gt; 회원정보등록(학부모/일반)</a></span></td-->
			</tr>
		</table>

		<table width="" border="0" cellspacing="0" cellpadding="0">

			<tr>
				<td>
					<table width="100%" border="0" cellpadding="0" cellspacing="15" class="t020">

						<tr>
							<td width=200>Board type</td>
							<td width=400>
								<? if($a=='mod_form'){ ?>
									<?

									//print_r($data);
									//Array ( [idx] => 1071 [cafe_id] => [bbs_name] => 가맹상담실 [regtt] => 1344314826 [id] => franchise [have_heap] => 0 [board_type] => counsel [group_idx] => 11 [seq] => 0 )

									?>
									<?=$board_types[$data[board_type]]?>
									<?
								}
								else { ?>
									<select name='board_type' id='board_type'>
										<?foreach($board_types as $k=>$v){?>
											<option value='<?=$k?>'><?=$v?></option>
										<? } ?>
									</select>
									<script>
										<? if($a=='mod_form' && $data[board_type]){ ?>
										document.getElementById('board_type').value='<?=$data[board_type]?>';
										<? }?>
									</script>
								<? } ?>
							</td>
						</tr>

						<!-- 그룹선택 -->
						<tr>
							<td>Group</td>
							<td>
								<select name=group_idx>
									<?
									// 그룹선택
									$r=$db->query("select * from bbs_group");
									if(mysql_num_rows($r)){
										while($group_data=mysql_fetch_array($r)){
											?><option value="<?=$group_data[idx]?>"><?=$group_data[group_name]?></option>
											<?
										}
									}
									else {
										?><option value=''>You can choose a group.</option>
										<?
									}
									?>
								</select>
							</td>
						</tr>

						<!-- 게시판 아이디 -->
						<tr>
							<td width="19%" class="col_nm">Board ID</td>
							<td colspan="3">

								<? if($a=='mod_form'){ ?>
									<input type=hidden name="orig_bbs_id" value="<?=$data[id]?>">
									<input type=hidden name="bbs_id" value="<?=$data[id]?>">
									<?=$data[id]?>
								<? } else {?>
									<input type=hidden name="orig_bbs_id" value="">

									<input name="bbs_id" value="<?=$data[id]?>" type="text" class="textarea" style = "width:120;" onFocus="this.select()" id="bbs_id">
									<input type="button" class="btn_type4" value="중복확인"  onclick="parent_check_nick($('#bbs_id').val());" style="cursor:hand" />
									<input type="hidden" name="tmpNickNm" value=""> 1-8 Hangul characters, 2 to 16 of the English upper and lower case letters, numbers, - only, '_' can '.

								<? } ?>


							</td>
						</tr>

						<!-- 게시판 이름 -->
						<tr>
							<td width="19%" class="col_nm">Board name</td>
							<td colspan="3">

								<input type="text" name="bbs_name" value="<?=$data[bbs_name]?>">

							</td>
						</tr>

						<tr>
							<td width="19%" class="col_nm">Allow non-writing</td>
							<td colspan="3">

								<input type="checkbox" name="bbs_guest_write" value='1' <?=($data[bbs_guest_write]?'checked':'')?>>Allowed

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
									<span class="button blue"><a href="javascript:goSave();">submit</a></span>
									<!--
									<span class="button blue"><a href="javascript:goReset();">다시입력</a></span>
									-->
									<span class="button blue"><a href="javascript:history.go(-1);">cancel</a></span>
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