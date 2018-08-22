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
$tbl="test_advice";

// 명령
if($a){
	/*
	mysql> desc test_advice;
	+-----------------+---------------+------+-----+---------+-------+
	| Field           | Type          | Null | Key | Default | Extra |
	+-----------------+---------------+------+-----+---------+-------+
	| SUBAREA_SEQ     | int(9)        | NO   |     | NULL    |       | 
	| AREA_SEQ        | int(9)        | NO   |     | NULL    |       | 
	| SUBAREA_NAME    | varchar(100)  | YES  |     | NULL    |       | 
	| SUBAREA_EXPLAIN | varchar(1000) | YES  |     | NULL    |       | 
	| MIN_SCORE       | float(5,2)    | YES  |     | NULL    |       | 
	| MAX_SCORE       | float(5,2)    | YES  |     | NULL    |       | 
	| TOP10_SCORE     | float(5,2)    | YES  |     | NULL    |       | 
	| TOP1_SCORE      | float(5,2)    | YES  |     | NULL    |       | 
	| SUBAREA_ORDER   | float         | NO   |     | 1       |       | 
	| REG_SEQ         | int(9)        | YES  |     | NULL    |       | 
	| REG_DTTM        | char(14)      | NO   |     |         |       | 
	| MIDDLE_START    | float(5,2)    | YES  |     | NULL    |       | 
	| MIDDLE_END      | float(5,2)    | YES  |     | NULL    |       | 
	| TOP_START       | float(5,2)    | YES  |     | NULL    |       | 
	| TOP_END         | float(5,2)    | YES  |     | NULL    |       | 
	+-----------------+---------------+------+-----+---------+-------+
	*/

	$point_val='';

	// 새로입력 or 업데이트
	if($a=='add' || $a=='update'){

		if($a=='add'){
			$advice0_txt=trim($advice0);
			$advice1_txt=trim($advice1);
			$advice2_txt=trim($advice2);

			$advice0_txt=mysql_real_escape_string($advice0_txt);
			$advice1_txt=mysql_real_escape_string($advice1_txt);
			$advice2_txt=mysql_real_escape_string($advice2_txt);
						
			$q="insert into test_advice set MIDDLE_START='$MIDDLE_START',MIDDLE_END='$MIDDLE_END',TOP_START='$TOP_START',TOP_END='$TOP_END',advice0='$advice0_txt',advice1='$advice1_txt',advice2='$advice2_txt',SUBAREA_NAME='$advice_name'";
			//echo $q;exit;


			// 문제를 업뎃
			$db->query($q);
			go2($phpself);
			exit;
		}



	}


	if($a=='save_changes'){
		foreach($advice0 as $k=>$v){
			$idx=$k;
		//	echo $middle_start[$k];
		//	echo $middle_end[$k];
		//	echo $top_start[$k];
		//	echo $top_end[$k];
		//	exit;


			$advice0_txt=trim($advice0[$k]);
			$advice1_txt=trim($advice1[$k]);
			$advice2_txt=trim($advice2[$k]);

			$advice0_txt=mysql_real_escape_string($advice0_txt);
			$advice1_txt=mysql_real_escape_string($advice1_txt);
			$advice2_txt=mysql_real_escape_string($advice2_txt);
						
			$q="update test_advice set middle_start='$middle_start[$k]',middle_end='$middle_end[$k]',top_start='$top_start[$k]',top_end='$top_end[$k]',advice0='$advice0_txt',advice1='$advice1_txt',advice2='$advice2_txt' where idx='$idx'";

			//echo $q;exit;


			// 문제를 업뎃
			$db->query($q);

		}
	}




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
}


// 조건 검색
$where=''; // ac_type=2 학생

// 검색조건은 페이지 이동시에 계속 넘겨야 하므로 미리 만들어둔다.
//$search_urlquery='';
$search_urlquery=make_array2urlquery($search);

// 검색 조건이 있으면 조건을 처리한다.
$search=$_REQUEST[search];
if($search){

	// 계정타입이 지정되었으면..
	if($search['ac_type']){
		$where.=($where?' and ':'where ');
		$where.="ac_type='$search[ac_type]'";
	}

	// 승인여부 검색
	if($search['ac_approved']!=''){
		$where.=($where?' and ':'where ');
		$where.="ac_approved='$search[ac_approved]'";
	}

	// 검색어가 있으면..
	if($search[search_word]){
		
		// 검색어를 검색할 필드
		if($search[sel_search_type]=='id'){
			$where.=($where?' and ':'where ');
			$where.="ac_id like '%$search[search_word]%'";
		}
		else if($search[sel_search_type]=='name'){
			$where.=($where?' and ':'where ');
			$where.="ac_name like '%$search[search_word]%'";
		}
	}
}

// 셀렉트조건에 맞는 게시물의 전체 카운트 구하기
//$tot=$db->fa1("select count(*) from member $where");
//echo $where;

//-------------------------
// 조건에 따라 검색
// 1. 카운트 구하고
$tot=$db->fa1("select count(*) from $tbl $where");


//$lpp=$_CFG[lpp];
$lpp=100;
if($lpp <10 || $lpp >100){
	$lpp=100; // 한페이지에 몇줄 뿌리게 되어있는지...
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
	$sortby='idx';
}
if(!$sortorder){
	$sortorder='desc';
}

if($sortby){
	$orderby=" order by $sortby $sortorder";
}

// 페이지링크를 만든다.
$prefix="$PHP_SELF?"."&sortorder=$sort_order&sortby=$sortby&$search_urlquery";
$pagelink=make_pagelink($tot,$page,$prefix,$lpp,$ppp);


// 2. 데이타 셀렉트
$r=$db->query("select * from $tbl $where $orderby limit $s,$lpp");


?>



<div class="content_title">
프로파일 관리
</div>

<script>



</script>


<form method=post id="list_form" name="list_form">
<input type=hidden name=a>
<table width=100% class="t_ex1" border=1 cellpadding=0 cellspacing=0>
<col />
<col />
<col />
<tr>
	<th class=c2 style="width:25px;"><input type='checkbox' id='all_check' name='all_check' onclick="check_all(this,'selected_idx[]');" style="cursor:pointer;" /></th>
	<th class=left>번호</th>
	<th class=left>이름</th>

	<th class=left>중간 Min</th>
	<th class=left>중간 Max</th>

	<th class=left>상위 Min</th>
	<th class=left>상위 Max</th>

	<th class=left>하위 advice</th>
	<th class=left>중간 advice</th>
	<th class=left>상위 advice</th>

	<th>-</th>
</tr>
<?
while($data=mysql_fetch_array($r)){
	?>
	<tr class=hover>
		<td class=c1>
			<input type=checkbox name="selected_idx[]" value="<?=$data[idx]?>" style="cursor:pointer;">
		</td>

		<td class=left><?=$data[idx]?></td>

		<td class=left><a href="#<?=$_SERVER[PHP_SELF]?>?idx=<?=$data[idx]?>&a=mod_form"><?=$data[SUBAREA_NAME]?></a></td>

		<td class=left>
			<input type=text name=middle_start[<?=$data[idx]?>] value="<?=$data[MIDDLE_START]?>" style="width:50px;">
		</td>

		<td class=left>
			<input type=text name=middle_end[<?=$data[idx]?>] value="<?=$data[MIDDLE_END]?>" style="width:50px;">
		</td>

		<td class=left>
			<input type=text name=top_start[<?=$data[idx]?>] value="<?=$data[TOP_START]?>" style="width:50px;">
		</td>

		<td class=left>
			<input type=text name=top_end[<?=$data[idx]?>] value="<?=$data[TOP_END]?>" style="width:50px;">
		</td>

		<td class=left>
			<textarea name=advice0[<?=$data[idx]?>] style="width:90%;height:100px;"><?=$data[advice0]?></textarea>
		</td>
		<td class=left>
			<textarea name=advice1[<?=$data[idx]?>] style="width:90%;height:100px;"><?=$data[advice1]?></textarea>
		</td>
		<td class=left>
			<textarea name=advice2[<?=$data[idx]?>] style="width:90%;height:100px;"><?=$data[advice2]?></textarea>
		</td>

		<td class=c2>

			<span class="button small"><a href="<?=$phpself?>?a=d&idx=<?=$data[idx]?>&then_go=<?=urlencode($_SERVER[REQUEST_URI])?>" onclick="return confirm('정말 삭제하시겠습니까?');"><span>삭제</span></a></span>

		</td>
	</tr>

	<?
}
?>
<tr>
	

	<td class=c1 colspan=11><span class="button blue"><a onclick="save_changes()">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;모든 변경사항 저장&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></span></td>

</tr>
</table>



<br>


<br>
</form>

<table width=800 class=t_paging style="border:0px solid black;">
	<tr><td align=center>
		<?=$pagelink?>
	</td></tr>
</table>






<form id="make_form" name="make_form" action="<?=$phpself?>" method=post>
<input type=hidden name=a value=add>
<table width=100% style="padding:5px;border:2px solid #97B1E1;">
	<tr>
		<td colspan=3>
			<table border=0 class=t_ex1 width=100%>
			<tr><td colspan=3>advice 등록</td></tr>
			<tr>
				<td>이름</td><td><input type=text name=advice_name></td>
				<td>&nbsp;</td>
			</tr>

			<tr>
				<td>중간점수</td>
				<td>

					<input name=MIDDLE_START style="width:50px;">-<input name=MIDDLE_END style="width:50px;">
					
				</td>
				<td width=150 valign=top>


				</td>
			</tr>
			<tr>
				<td>상위점수</td>
				<td>

					<input name=TOP_START style="width:50px;">-<input name=TOP_END style="width:50px;">
					
				</td>
				<td width=150 valign=top>


				</td>
			</tr>

			<tr>
				<td>하위 advice</td>
				<td>
					<textarea name=advice0 style="width:90%;height:200px;"></textarea>
				</td>
				<td width=150 valign=top>


				</td>
			</tr>
			<tr>
				<td>중간 advice</td>
				<td>
					<textarea name=advice1 style="width:90%;height:200px;"></textarea>
				</td>
				<td width=150 valign=top>


				</td>
			</tr>
			<tr>
				<td>상위 advice</td>
				<td>
					<textarea name=advice2 style="width:90%;height:200px;"></textarea>
				</td>
				<td width=150 valign=top>


				</td>
			</tr>

			</table>
			
		</td>
	</tr>
	<tr>
		<td width=100><span class="button blue"><a onclick="make_advice()"> 등록 </a></span></td>
		<td align=center></td>
		<td width=100></td>

	</tr>
</table>
</form>



<script>

function save_changes(){
	if(confirm('저장할까요?')){
		f=document.list_form;
		f.a.value="save_changes";
		document.getElementById('list_form').submit();
	}
}


// 대답 추가
function add_answer(){
	var tbl=$("#answer_table");
	var html='';
	html+='<tr>';
	html+='				<td><input type=text name=answer[]></td>';
	html+='				<td><input type=text name=answer_point[]></td>';
	html+='</tr>';

	tbl.append(html);


}

function make_advice(){
	document.getElementById('make_form').submit();
}



</script>






<?
// 푸터
include '../../footer.admin.php';
?>