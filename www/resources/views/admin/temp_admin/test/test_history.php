<?

include '../config.php';

// 메뉴지정
$curr_menu='/admin/test';

// 인증체크
na_pageauthcheck();
include '../../header.admin.php';

// 테이블 지정
$tbl_prefix=$_CFG[table_prefix];
$tbl="test_history h,member m";


// 명령
if($a){

	if($a=='d' && $answer_seq){
		$db->query("delete from test_history where answer_seq='$answer_seq'");
		//echo "delete from test_history where answer_seq='$answer_seq'";exit;
		msgbox('삭제되었습니다.');
		go2(($then_go?$then_go:$phpself));
	}
/*

	if($a=='d'){
		//print_r($_REQUEST);exit;
		$db->query("delete from $tbl where idx='$idx'");
		//$db->query("delete from na_key_data where pidx='$idx'");


	}
	// 상태 변경
	else if($a=='change_status' && $idx){
		if($new_status=='0'){
			$new_val='1';
		}
		else {
			$new_val='0';
		}

		$db->query("update $tbl set center_sts='$new_val' where idx='$idx'");

	}
*/
}

/*
// 이거 안하면 내 센터 회원들 진단결과가 안나옴.
$r=$db->query("select * from test_history");
while($data=mysql_fetch_array($r)){
	$center_seq=$db->fa1("select center_seq from na_student where ac_idx='$data[USER_SEQ]'");
	$db->query("update test_history set center_seq='$center_seq' where ANSWER_SEQ='$data[ANSWER_SEQ]'");
}
echo 'history.center_seq update ok';
exit;
*/


// 조건 검색
//$where="qtype='SS'"; // SS만셀렉


// 센터에 속한 회원만 검색
//if($ac_type=='21' || $ac_type=='22'){
//	$where="center_seq='$mycenteridx'";
//}
//else {
	
//}


$where="h.user_seq=m.ac_idx and is_done='1'";

// 검색조건은 페이지 이동시에 계속 넘겨야 하므로 미리 만들어둔다.
//$search_urlquery='';
$search_urlquery=make_array2urlquery($search);

// 검색 조건이 있으면 조건을 처리한다.
$search=$_REQUEST[search];
if($search){

	// 센터 검색
	if($search['center_idx']){
		$where.=($where?' and ':'where ');
		$where.="m.CENTER_SEQ='$search[center_idx]'";
	}

	// GM 검색
	if($search['gm_id']){
		$where.=($where?' and ':'where ');
		$where.="h.gm_id='$search[gm_id]'";
	}

	// 검색어가 있으면..
	if($search[search_word]){
		
		// 검색어를 검색할 필드
		if($search[sel_search_type]=='id'){
			$where.=($where?' and ':'');
			$where.="m.ac_id like '%$search[search_word]%'";
		}
		else if($search[sel_search_type]=='name'){
			$where.=($where?' and ':'');
			$where.="m.ac_name like '%$search[search_word]%'";
		}

	}
}

if($where){
	$where=" where ".$where;
}

// 셀렉트조건에 맞는 게시물의 전체 카운트 구하기
//$tot=$db->fa1("select count(*) from member $where");
//echo $where;

//-------------------------
// 조건에 따라 검색
// 1. 카운트 구하고
$tot=$db->fa1("select count(*) from $tbl $where");


$lpp=$_CFG[lpp];
if($lpp <10 || $lpp >100){
	$lpp=20; // 한페이지에 몇줄 뿌리게 되어있는지...
}

$ppp=$_CFG[ppp]; // 페이지링크를 몇개 뿌릴 것인지..
$page=$_REQUEST[page];

//페이지 변수가 없을땐 페이지는 1이다
if(!$page){
	$page=1;
}

$s=($page-1)*$lpp; // 시작번호
$no=$tot-$s; // 가상 번호

// 정렬키
$sortby=$_REQUEST[sortby];
// 정렬 방향
$sortorder=$_REQUEST[sortorder];

if(!$sortby){
	$sortby='ANSWER_SEQ';
}
if(!$sortorder){
	$sortorder='desc';
}

if($sortby){
	$orderby=" order by $sortby $sortorder";
	//$orderby=" order by step asc,seq asc,idx asc";
}

// 페이지링크를 만든다.
$prefix="$PHP_SELF?"."&pidx=$pidx&sortorder=$sort_order&sortby=$sortby&$search_urlquery";
$pagelink=make_pagelink($tot,$page,$prefix,$lpp,$ppp);


// 2. 데이타 셀렉트
$r=$db->query("select * from $tbl $where $orderby limit $s,$lpp");

/*
mysql> desc test_history; 
+-----------------+------------+------+-----+---------+-------+
| Field           | Type       | Null | Key | Default | Extra |
+-----------------+------------+------+-----+---------+-------+
| ANSWER_SEQ      | int(11)    | NO   | PRI | NULL    |       | 
| USER_SEQ        | int(11)    | NO   |     | NULL    |       | 
| CLASS_SEQ       | int(11)    | YES  |     | NULL    |       | 
| SCORE           | float(9,2) | NO   |     | 0.00    |       | 
| REG_DTTM        | char(14)   | NO   |     |         |       | 
| TAKING_SEQ      | int(11)    | YES  |     | NULL    |       | 
| TEACHER_COMMENT | text       | YES  |     | NULL    |       | 
| OPEN_YN         | char(1)    | NO   |     | N       |       | 
+-----------------+------------+------+-----+---------+-------+
*/

/*
mysql> desc test_name;
+-----------+-----------+------+-----+---------+----------------+
| Field     | Type      | Null | Key | Default | Extra          |
+-----------+-----------+------+-----+---------+----------------+
| idx       | int(11)   | NO   | PRI | NULL    | auto_increment | 
| test_name | char(255) | YES  |     | NULL    |                | 
+-----------+-----------+------+-----+---------+----------------+
*/


// test 들의 정보를 얻는다.

$diag_data=$db->r2a($db->query("select * from test_name"),'idx');

// test_history 정보를 얻는다.

?>

<script>

// 진단내역 
//*******************************************************************************
function check_answer_pop(t, seq){
 window.open("/diag/test_start.php?t=" + t + "&answer_seq=" + seq, 'check', 'top=10,left=10,status=no, scrollbars=yes,resizable=no,width=1008, height=710');
}
//*******************************************************************************
//  프로파일 버튼 
//*******************************************************************************
function check_report_pop(t, seq){
 window.open("/diag/show_profile.php?t=" + t + "&answer_seq=" + seq, 'check', 'top=10,left=10,status=no, scrollbars=yes,resizable=no,width=1008, height=740');
}
function delete_answer(seq){
	if(confirm('정말로 삭제할까요?')){
		location.href='<?=$phpself?>?a=d&answer_seq='+seq;
	}
}

</script>
			<!-- SUBSTANCE CONTENTS -->
			<div id="substance">


				<!-- SUB -->
				<div id="sub">
					<div class="loginTopBg loginBg00">
						<h2>회원진단결과&nbsp;<span>진단관리</span></h2>
					</div>
					


					<table>
					<tr>
						<td>
							<form id='mem_search_form' name='mem_search_form' action='<?$PHP_SELF;?>' method='POST'>

							<?=make_selectbox('search[com_idx]',$db->r2a(get_companylist(),'idx','com_name'),$search[com_idx],'회사 선택')?>

							<?//make_selectbox('search[ac_type]',$_CFG[acc_type],$search[ac_type],'모든타입')
							
							?>
							<?
							if($sel_type=='gm'){
								//echo make_selectbox('search[sel_search_type]',array('gm_name'=>'가맹점명','name'=>'이름','id'=>'아이디'),$search[sel_search_type]);
							}
							else {
								echo make_selectbox('search[sel_search_type]',array('name'=>'이름','id'=>'아이디'),$search[sel_search_type]);
							}


							
							?>

							<input type=text id=search_word name=search[search_word] size=20 /><input type=submit value=" 검색 " />

							</form>
						</td>
						<td>
							<!--
							<span>
								<a href="<?=$phpself?>?a=excel&<?=$search_urlquery?>&sel_type=<?=$sel_type?>"><img src=/img/btn_excel.gif border=0></a>
							</span>
							-->
						</td>
					</tr>
					</table>

<!--
					<p class="lgn_text">* <span>학생이름</span>을 클릭하면 하단에 부모님정보가 조회됩니다. </p>
-->

					<table class="t_ex1" border=1 cellpadding=0 cellspacing=0 width=100%>
						<thead>
							<tr>
								<th class=c>NO</th>
								<th class=c>진단명</th>
								<th class=c>성명</th>
								<th class=c>ID</th>
								<th class=c>진단일</th>
								<!--th>생년월일</th-->
								<th class=c>진단내역</th>
								<th class=c>프로파일</th>
								<th class=c>자세히</th>
								<th class=c>삭제하기</th>
								<!--th class="last">공개여부</th-->
							</tr>
						</thead>
						<tbody>
							<!--
							<tr>
								<td>1</td>
								<td>고경애</td>
								<td>notax90</td>
								<td>2009-11-25</td>
								<td>1980-01-01</td>
								<td>0</td>
								<td>0</td>
								<td>고경애(Y)</td>
							</tr>
							-->

<?
	while($data=mysql_fetch_array($r)){

		
		$member_data=$db->fa("select * from member where ac_idx='$data[USER_SEQ]'");

		/*
		mysql> desc old_member;
		+----------------------+--------------+------+-----+---------+-------+
		| Field                | Type         | Null | Key | Default | Extra |
		+----------------------+--------------+------+-----+---------+-------+
		| USER_SEQ             | int(9)       | NO   |     | NULL    |       | 
		| LOGIN_ID             | varchar(20)  | YES  |     | NULL    |       | 
		| PASSWORD             | varchar(50)  | YES  |     | NULL    |       | 
		| PASSWORD_HINT_ANSWER | varchar(50)  | YES  |     | NULL    |       | 
		| PASSWORD_HINT_CD     | varchar(30)  | YES  |     | NULL    |       | 
		| USER_NM              | varchar(50)  | YES  |     | NULL    |       | 
		| USER_TYPE_CD         | varchar(30)  | YES  |     | NULL    |       | 
		| NICK_NM              | varchar(100) | YES  |     | NULL    |       | 
		| SEX_TYPE_CD          | varchar(30)  | YES  |     | NULL    |       | 
		| BIRTH_DATE           | char(8)      | YES  |     | NULL    |       | 
		| EMAIL                | varchar(100) | YES  |     | NULL    |       | 
		| POST_CD              | varchar(7)   | YES  |     | NULL    |       | 
		| ADDR1                | varchar(100) | YES  |     | NULL    |       | 
		| ADDR2                | varchar(100) | YES  |     | NULL    |       | 
		| PHONE_HOME_AREA_CD   | varchar(30)  | YES  |     | NULL    |       | 
		| PHONE_HOME_1         | varchar(50)  | YES  |     | NULL    |       | 
		| PHONE_HOME_2         | varchar(50)  | YES  |     | NULL    |       | 
		| PHONE_MOBILE_DV_CD   | varchar(30)  | YES  |     | NULL    |       | 
		| PHONE_MOBILE_1       | varchar(50)  | YES  |     | NULL    |       | 
		| PHONE_MOBILE_2       | varchar(50)  | YES  |     | NULL    |       | 
		| MOBILE_COMPANY_CD    | varchar(30)  | YES  |     | NULL    |       | 
		| USER_JOIN_TYPE_CD    | varchar(30)  | YES  |     | NULL    |       | 
		| USER_JOIN_REASON_CD  | varchar(30)  | YES  |     | NULL    |       | 
		| EMAIL_RECV_YN        | char(1)      | YES  |     | NULL    |       | 
		| SMS_RECV_YN          | char(1)      | YES  |     | NULL    |       | 
		| PHOTO_FILE_SEQ       | int(9)       | YES  |     | NULL    |       | 
		| USER_APROVE_YN       | char(1)      | YES  |     | NULL    |       | 
		| USER_SECEDE_YN       | char(1)      | YES  |     | NULL    |       | 
		| USER_STATUS_CD       | varchar(30)  | YES  |     | NULL    |       | 
		| HOBBY                | varchar(100) | YES  |     | NULL    |       | 
		| LIKE_SUBJECT         | varchar(100) | YES  |     | NULL    |       | 
		| SELF_EMAIL           | varchar(50)  | YES  |     | NULL    |       | 
		| ORG_CD               | char(10)     | YES  |     | NULL    |       | 
		| SCHOOL_GRADE_CD_NO   | int(2)       | YES  |     | NULL    |       | 
		| SCHOOL_YEAR_CD_NO    | int(2)       | YES  |     | NULL    |       | 
		| SCHOOL_CLASS_NM      | varchar(50)  | YES  |     | NULL    |       | 
		| SCHOOL_int           | int(9)       | YES  |     | NULL    |       | 
		| COURSE_CD            | varchar(50)  | YES  |     | NULL    |       | 
		| REG_SEQ              | int(9)       | YES  |     | NULL    |       | 
		| REG_DTTM             | char(14)     | YES  |     | NULL    |       | 
		| MOD_SEQ              | int(9)       | YES  |     | NULL    |       | 
		| MOD_DTTM             | char(14)     | YES  |     | NULL    |       | 
		| CAMPUS_SEQ           | int(9)       | YES  |     | NULL    |       | 
		| CENTER_SEQ           | int(9)       | YES  |     | NULL    |       | 
		| SCHOOL_NM            | varchar(500) | YES  |     | NULL    |       | 
		| SCHOOL_YEAR          | int(11)      | YES  |     | NULL    |       | 
		| SCHOOL_YEARS_GB      | varchar(30)  | YES  |     | NULL    |       | 
		| SCHOOL_GRADE_GB      | varchar(30)  | YES  |     | NULL    |       | 
		| INCOME_LEVEL_GB      | varchar(30)  | YES  |     | NULL    |       | 
		| TEACH_SEQ            | int(9)       | YES  |     | NULL    |       | 
		| APPKEY               | varchar(20)  | YES  |     | NULL    |       | 
		| CLASS_SEQ            | int(9)       | YES  |     | NULL    |       | 
		| APPKEY2              | varchar(20)  | YES  |     | NULL    |       | 
		| APPKEY3              | varchar(20)  | YES  |     | NULL    |       | 
		| SCHOOL_NUMBER        | varchar(100) | YES  |     | NULL    |       | 
		+----------------------+--------------+------+-----+---------+-------+

		*/

		?>
							<tr>
								<td class=c><?=$no?></td>
								<td class=c><?
							//			echo $data[qtype];
										//$zoo_diag[$data[pidx]][test_name]

										echo $_CFG[franz_diag][$data[qtype]];
								?></td>
								<td class=c><?=$member_data['ac_name']?></td>
								<td class=c><?=$member_data['ac_id']?></td>
								<td class=c ><?
									//$member_data[REG_DTTM]
									echo date('Y-m-d',$data[reg_time]);
								?></td>
								<!--td><?
									//$member_data[REG_DTTM]
									if($member_data[BIRTH_DATE]){
										$y=substr($member_data[BIRTH_DATE],0,4);
										$m=substr($member_data[BIRTH_DATE],4,2);
										$d=substr($member_data[BIRTH_DATE],6,2);
										echo "$y-$m-$d";
									}
								?></td-->
								<td class=c align=center><img src=http://test.tmdedu.com/img/btn_ok.gif border=0 onclick="check_answer_pop('<?=$data[qtype]?>','<?=$data['ANSWER_SEQ']?>');" style="cursor:pointer;"></td>
								<td class=c align=center><img src=http://test.tmdedu.com/img/btn_ok.gif border=0 onclick="check_report_pop('<?=$data[qtype]?>','<?=$data['ANSWER_SEQ']?>');" style="cursor:pointer;"></td>
								<td class=c align=center><a href=diag_detail.php?answer_seq=<?=$data['ANSWER_SEQ']?>><img src=http://test.tmdedu.com/img/btn_ok.gif border=0 style="cursor:pointer;"></a></td>

								<td class=c align=center><span class="button bp small"><a href="javascript:delete_answer('<?=$data['ANSWER_SEQ']?>');" style="cursor:pointer;">삭제</a></span></td>
								<!--td><?=$data[OPEN_YN]?></td-->
							</tr>
		<?
		$no--;
	}
?>
						</tbody>
					</table>
<br>

					<center>
						<?=$pagelink?>
					</center>


					<div  style="display:none;">
					<p class="blt_text">부모님 정보</p>
					<table class="lgn_table cBlue" summary="데이터 테이블">
						<caption>데이터 테이블</caption>
						<thead>
							<tr>
								<th>NO</th>
								<th>성명</th>
								<th>ID</th>
								<th>가입일</th>
								<th>자녀확인</th>
								<th>학부모진단</th>
								<th class="last">비고</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="7">학부모 정보가 없습니다.</td>
							</tr>
						</tbody>
					</table>
					</div>


				</div>
				<!-- //SUB -->
			</div>
			<!-- //SUBSTANCE CONTENTS -->

	<? include '../../footer.admin.php';?>