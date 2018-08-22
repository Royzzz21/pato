<?



include '../config.php';

// 메뉴지정
$curr_menu='/admin/test';

// 인증체크
na_pageauthcheck();

// 헤더
include '../../header.admin.php';

//debugreq();

if(!$pidx){
	exit;
}

// 테이블 지정
$tbl_prefix=$_CFG[table_prefix];
$tbl="test_q";

/*
// idx 변경
$r=$db->query("select * from test_q where pidx=3 order by idx asc");
$new_idx=234;
while($data=mysql_fetch_array($r)){

	// 234 ~ 282
	$db->query("update test_q set idx='$new_idx' where idx='$data[idx]'");
	$new_idx++;
}
exit;
*/


/*
// idx 변경
$r=$db->query("select * from test_q where pidx=4 order by idx asc");
$new_idx=1156;
while($data=mysql_fetch_array($r)){

	// 1156부터 1233 까지로 변경한다.
	$db->query("update test_q set idx='$new_idx' where idx='$data[idx]'");
	$new_idx++;
}
exit;
*/

/*
$r=$db->query("select * from test_q");
while($data=mysql_fetch_array($r)){
	if(preg_match("/[0-9.]+ /",$data[question],$match)){
		//print_r($match);exit;
		$step2=trim($match[0]);
		$db->query("update test_q set step2='$step2' where idx='$data[idx]'");
	}

}
exit;
*/


// 명령
if($a){

	// 배점을 자동처리
	if($a=='tool_update'){
		$step2=str_replace("\n","\t",$step2);
		$step2=str_replace(" ","\t",$step2);
		$l=explode("\t",$step2);
		foreach($l as $k=>$v){
			$v=trim($v);
			if($v){
				//echo $v.'<br>';
				//echo "update test_q set point_dist_type='$point' where step='$step' and step2='$v'";exit;

				$db->query("update test_q set point_dist_type='$point' where step='$step' and step2='$v'");
			}
		}

		exit;
	}

	// 배점을 자동으로 입력해주기 위한 툴입니다.
	if($a=='tool'){
		?>
		<form method=post>
		<input type=hidden name=a value="tool_update">
		pidx: <input type=text name=pidx value="<?=$pidx?>"><br>
		step: <input type=text name=step><br>
		step2: <textarea name=step2 style="width:500px;height:300px;"></textarea><br>
		point: <select name=point><? $r=$db->query("select * from test_point");
							while($data=mysql_fetch_array($r)){
								?>
								<option value="<?=$data[idx]?>"><?=$data[point_name]?></option>
								<?
							}
				?>
				</select><br>

		<input type=submit value=" ok ">
		</form>

		
		<?
			exit;

	}

	/*

	mysql> desc test_q;
	+-----------------+-----------+------+-----+---------+----------------+
	| Field           | Type      | Null | Key | Default | Extra          |
	+-----------------+-----------+------+-----+---------+----------------+
	| pidx            | int(11)   | NO   | MUL | NULL    |                | 
	| idx             | int(11)   | NO   | PRI | NULL    | auto_increment | 
	| question        | char(255) | NO   |     | NULL    |                | 
	| step            | int(11)   | NO   |     | NULL    |                | 
	| point_dist_type | int(11)   | NO   |     | NULL    |                | 
	| seq             | int(11)   | NO   | MUL | NULL    |                | 
	+-----------------+-----------+------+-----+---------+----------------+

	*/


// 답변배열과 포인트 배열을 받아서 -> serialize -> txt로 리턴
function serialize_answer($answer_arr,$point_arr){
	$answer_data=array();
	if(is_array($answer_arr))
	foreach($answer_arr as $k=>$v){
		if($v){
			$answer_data[]=array("answer"=>trim($v),"point"=>trim($point_arr[$k]));
		}
	}

//	print_r($answer_data);exit;
	if(count($answer_data)){
		$answer_txt=serialize($answer_data);
	}
	else {
		$answer_txt='';
	}

	return $answer_txt;

}

	if($a=='save_changes'){


		//print_r($_REQUEST);
		//var_dump($point_dist_type);

		//$question=explode("\n",$bulk_question);
		//echo $point_dist_type;exit;
		//if(is_array($point_dist_type))
		foreach($question as $k=>$v){
			//print_r($point_dist_type);exit;

			$v=trim($v);
			$answer_txt=serialize_answer($answer[$k],$answer_point[$k]);
			//echo $answer_txt;
//			if($v){
				//echo $v;exit;
				//if($answer_txt){
				//	$new_point_dist_type=0;
				//}
				//else {
					$new_point_dist_type=$point_dist_type[$k];
				//}

				$idx=$k;
				$answer_txt=mysql_real_escape_string($answer_txt);
				$q="update test_q set step2='$step2[$idx]',question='$question[$idx]',answer='$answer_txt',point_dist_type='$new_point_dist_type' where pidx='$pidx' and idx='$idx'";
				//echo $q;exit;
				
				// 문제를 업뎃
				$db->query($q);
//			}
		}

	}


	if($a=='bulk_add'){
		if($answer_txt){
			$new_point_dist_type=0;
		}
		else {
			$new_point_dist_type=$point_dist_type;
		}

		$question=explode("\n",$bulk_question);
		
		foreach($question as $k=>$v){

			$v=trim($v);
			$answer_txt=serialize_answer($answer[$k],$answer_point[$k]);

			
			
			if($v){
				// 문제를 입력한다.
				$answer_txt=mysql_real_escape_string($answer_txt);
				$db->query("insert into test_q set pidx='$pidx',question='$v',answer='$answer_txt',step='$step',point_dist_type='$new_point_dist_type'");
			}
		}

	}

	if($a=='add'){


		$answer_txt=serialize_answer($answer,$answer_point);

		if($answer_txt){
			$new_point_dist_type=0;
		}
		else {
			$new_point_dist_type=$point_dist_type;
		}

		foreach($question as $k=>$v){

			$v=trim($v);			
			if($v){
				// 문제를 입력한다.
				$answer_txt=mysql_real_escape_string($answer_txt);
				$db->query("insert into test_q set pidx='$pidx',question='$v',step='$step',answer='$answer_txt',point_dist_type='$new_point_dist_type'");
			}
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
$where="pidx='$pidx'"; // ac_type=2 학생
if($search_step){
	$where.=" and step='$search_step'";

}

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

$lpp=200;

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
	//$orderby=" order by $sortby $sortorder";
	$orderby=" order by step asc,seq asc,idx asc";
}

// 페이지링크를 만든다.
$prefix="$PHP_SELF?"."&pidx=$pidx&sortorder=$sort_order&sortby=$sortby&$search_urlquery";
$pagelink=make_pagelink($tot,$page,$prefix,$lpp,$ppp);


// 2. 데이타 셀렉트
$r=$db->query("select * from $tbl $where $orderby limit $s,$lpp");


?>

<!--
<a href=<?=$phpself?>>학생회원 목록</a> | 타입선택: 
<?
foreach($_CFG[acc_type] as $k=>$v){
	if($k>1){
		echo " / ";
	}
	echo "<a href=$phpself?search[ac_type]=$k>$v</a>";

}
?>
 | <a href=<?=$phpself?>?search[ac_approved]=0>미승인 목록</a>
<br><br>

-->

<div class="content_title">
질문 관리
</div>

<script>



</script>


<form method=post id='list_form' name="list_form">
<input type=hidden name=a>
<input type=hidden name=pidx value="<?=$pidx?>">
<table width=100% class="t_ex1" border=1 cellpadding=0 cellspacing=0>
<col width=100 />
<col width=50 />
<col width=100 />
<col width=100 />
<col />
<col width=150 />
<col width=100 />
<tr>
	<th class=c2 style="width:25px;"><input type='checkbox' id='all_check' name='all_check' onclick="check_all(this,'selected_idx[]');" style="cursor:pointer;" /></th>
<!--	<th>idx</th>
-->
	<th>idx</th>
	<th>단계</th>
	<th>세부단계</th>
	<th>질문</th>
	<th>답변 묶음</th>
	
	<th>-</th>
</tr>
<?

$r2=$db->query("select * from test_point");
$point_group=$db->r2a($r2);
//print_r($point_group);exit;


while($data=mysql_fetch_array($r)){
	?>
	<tr class=hover>
		<td class=c1>
			<input type=checkbox name="selected_idx[]" value="<?=$data[idx]?>" style="cursor:pointer;">
		</td>
		
		<td class=c1><b><?=$data[idx]?></b></td>
		<td class=c1><?=$data[step]?></td>
		<td class=c1><input type=text name="step2[<?=$data[idx]?>]" value="<?=$data[step2]?>" style="width:80px;"></td>
		<td class=left>
			<input type=text name="question[<?=$data[idx]?>]" value="<?=$data[question]?>" style="width:95%">
			
			<?
			if($data[point_dist_type]=='0'){
				?>
				<div id="custom_answer_div<?=$data[idx]?>" style="border:1px solid #bebebe;padding:10px;">
				<span class="button blue"><a onclick="add_answer('<?=$data[idx]?>')"> 답변 추가 </a></span><br>
				<input type=text name="answer_col" style="width:80%;border:0;" value="답변">&nbsp;<input type=text name="answer_col" style="width:10%;border:0;" value="점수">
				<?
				$answ=unserialize($data[answer]);
				if(is_array($answ)){
					foreach($answ as $k=>$v){
						?>
						<input type=text name="answer[<?=$data[idx]?>][]" style="width:80%;" value="<?=$v[answer]?>">&nbsp;<input type=text name="answer_point[<?=$data[idx]?>][]" style="width:10%;" value="<?=$v[point]?>">
						<?
					}
				}

				?>
				</div>
				<?
			}
			?>
		</td>
		<td class=c1>

			<select name="point_dist_type[<?=$data[idx]?>]">
				<option value="0">사용안함</option>
				<?
					
					//while($data=mysql_fetch_array($r)){
					foreach($point_group as $k=>$v){
						?>
						<option value="<?=$v[idx]?>" <?=(intval($v[idx])==intval($data[point_dist_type])?' selected ':'')?>><?=$v[point_name]?></option>
						<?
					}
				?>
			</select>
			<?//$data[point_dist_type]?>
			
		</td>
		<td class=c2>

			<!--
			<span class="button small"><a href="key_detail.php?pidx=<?=$data[idx]?>&a=mod_form&then_go=<?=urlencode($_SERVER[REQUEST_URI])?>">자세히</a></span>
			-->
			 

			 <?if($data[center_idx]){ ?><span class="button small"><a href="<?=$phpself?>?a=recover&idx=<?=$data[idx]?>&then_go=<?=urlencode($_SERVER[REQUEST_URI])?>" onclick="return confirm('정말 회수하시겠습니까?');"><span>회수</span></a></span><? }
			 else { ?>
			 <span class="button small bp"><a href="<?=$phpself?>?a=d&pidx=<?=$pidx?>&idx=<?=$data[idx]?>&then_go=<?=urlencode($_SERVER[REQUEST_URI])?>" onclick="return confirm('정말 삭제하시겠습니까?');"><span>삭제</span></a></span>
			 <? } ?>
		</td>
	</tr>

	<?
}
?>

<!--tr>
	<td class=c1 colspan=6><?//$pagelink?></td>
</tr-->

<tr>
	<td class=c1></td>
	<!--
	<td class=c1></td>
	<td class=c1></td>
	<td class=c1></td>
	-->
	<td class=c1 colspan=5><span class="button bp"><a onclick="save_changes()">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;모든 변경사항 저장&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></span></td>
	<td class=c1></td>
</tr>

</table>
<table width=800><tr><td align=left>
	<? if($search[ac_type]=='2' || $search[ac_type]=='3'){ ?><span class=button><a onclick="document.getElementById('account_form').action='link_manager.php?ac_type=<?=$search[ac_type]?>&then_go=<?=urlencode($_SERVER[REQUEST_URI])?>';document.getElementById('account_form').submit();" style="cursor:pointer;"><span>매니저에 연결</span></a></span><? } ?>

</td></tr></table>

<br>

<!--
<div style="float:left;">
선택한 인증키를 </div>
<div style="float:left;">
<select name=selected_center_idx>
<?
$r=$db->query("select * from na_center");
while($center_data=mysql_fetch_array($r)){
	?><option value="<?=$center_data[idx]?>"><?=$center_data[center_name]?></option>
	<?
}
?>
</select>
</div>

<div style="float:left;">
<span class="button blue"><a onclick="feed_key()"> 센터에 지급 </a></span>
</div>
-->
<br>
</form>

<table width=800 class=t_paging style="border:0px solid black;">
	<tr><td align=center>
		<?=$pagelink?>
	</td></tr>
</table>



<form id="make_form" name="make_form" action="<?=$phpself?>" method=post>
<input type=hidden name=a value=add>
<input type=hidden name=pidx value="<?=$pidx?>">
<table width=100% style="padding:5px;border:2px solid #97B1E1;">
	<tr>
		<td colspan=3>
			<table border=0 class=t_ex1 width=100%>
			<tr><td colspan=3>질문 등록</td></tr>
			<!--tr><td colspan=2>
				
			</td></tr-->
			<tr>
				<td>단계</td><td><input type=text name=step>(숫자만가능)</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>질문</td>
				<td>
					<div id="question_div"><input type=text name=question[] style="width:90%;"></div>
					<div id="question_div2" style="display:none;"><textarea name=bulk_question style="width:90%;height:200px;"></textarea><br />한줄에 한개씩 입력하세요.</div>
				</td>
				<td width=150 valign=top>
					<div id="btngroup1" style="padding-top:0px;padding-bottom:10px; float:left;">
						<span class="button blue"><a onclick="bulk_mode()"> 대량 등록 </a></span>
						<span class="button blue"><a onclick="add_question()"> 질문 추가 </a></span>
					</div>

					<div id="btngroup2" style="padding-top:0px;padding-bottom:10px; float:right;">
						<!--
						<span class="button blue"><a onclick="bulk_mode()"> 일반 등록 </a></span>
						-->
						
					</div>
				</td>
			</tr>
			<tr>
				<td>답변</td>
				<td>
					<div id="point_group_div">
					<select name=point_dist_type>
					<option value="">선택안함</option>
						<?
							$r=$db->query("select * from test_point");
							while($data=mysql_fetch_array($r)){
								?>
								<option value="<?=$data[idx]?>"><?=$data[point_name]?></option>
								<?
							}
						?>
					</select>
					
					(<a href=test_point.php>관리</a>)
					</div>

					<div id="custom_answer_div" style="display:none;">
					<input type=text name="answer_col" style="width:80%;border:0;" value="답변">&nbsp;<input type=text name="answer_col" style="width:10%;border:0;" value="점수">
					<input type=text name="answer[]" style="width:80%;" value="">&nbsp;<input type=text name="answer_point[]" style="width:10%;" value="">
					</div>
				</td>
				<td width=150 valign=top>
					<div id="btngroup3" style="padding-top:0px;padding-bottom:10px; float:left;">
						<span id="custom_answer_btn_div" class="button blue"><a onclick="custom_answer()"> 수동 구성 </a></span>
						<span id="custom_answer_add_btn_div" class="button blue"><a onclick="add_answer()"> 답변 추가 </a></span>
					</div>

				</td>
			</tr>

			</table>
			
		</td>
	</tr>
	<tr>
		<td width=100><span class="button blue"><a onclick="make_test()"> 생성 </a></span></td>
		<td align=center></td>
		<td width=100></td>

	</tr>
</table>
</form>

<script>

// 수동으로 답변을 입력하는 경우. (문제마다 선택가능한 답변이 다른경우.)
function custom_answer(){
	// 답변 그룹을 숨긴다.
	$("#point_group_div").hide();

	// 수동 답변 구성.
	$("#custom_answer_div").show();
	$("#custom_answer_btn_div").hide();
	$("#custom_answer_add_btn_div").show();


}

// 대답 추가
function add_answer(idx){

	var tbl;
	var html='';
	html+='<br/>';

	if(idx){
		tbl=$("#custom_answer_div"+idx);
		html+='<input type=text name="answer['+idx+'][]" style="width:80%;" value="">&nbsp;<input type=text name="answer_point['+idx+'][]" style="width:10%;" value="">';
	}
	else {
		tbl=$("#custom_answer_div");
		html+='<input type=text name="answer[]" style="width:80%;" value="">&nbsp;<input type=text name="answer_point[]" style="width:10%;" value="">';
	}


	
	for(i=0;i<1;i++){
		tbl.append(html);
	}
}

function save_changes(){
	if(confirm('저장할까요?')){
		f=document.list_form;
		f.a.value="save_changes";
		document.getElementById('list_form').submit();
	}
}

function make_test(){
//	alert('a');
	f=document.make_form;
	//if(f.test_name.value==""){
	//	alert('진단명을 입력하세요');
	//	return;
	//}

	document.getElementById('make_form').submit();
}

function feed_key(){
	var obj=document.getElementById('key_form');
	obj.a.value='feed_key';
	obj.submit();
}

// 대답 추가
function add_question(){
	var tbl=$("#question_div");
	var html='';
	html+='<br/>';
	html+='<input type=text name=question[] style="width:90%;">';
	
	for(i=0;i<5;i++){
		tbl.append(html);
	}
}


// 대량등록모드
function bulk_mode(){
	f=document.make_form;
	f.a.value="bulk_add";

	$("#btngroup1").hide();
	$("#btngroup2").show();

	$("#question_div").hide();
	$("#question_div2").show();

	
}

</script>



<?
// 푸터
include '../../footer.admin.php';
?>