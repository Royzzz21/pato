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
$tbl="test_point";

// 명령
if($a){
	/*

	mysql> desc test_point;
	+------------+-----------+------+-----+---------+----------------+
	| Field      | Type      | Null | Key | Default | Extra          |
	+------------+-----------+------+-----+---------+----------------+
	| idx        | int(11)   | NO   | PRI | NULL    | auto_increment | 
	| point_name | char(255) | NO   |     | NULL    |                | 
	| point_val  | text      | NO   |     | NULL    |                | 
	+------------+-----------+------+-----+---------+----------------+
	*/

	$point_val='';

	// 새로입력 or 업데이트
	if($a=='add' || $a=='update'){
		
		$new_answ=array();
		foreach($answer as $k=>$v){
			$new_answ[]=array('name'=>$answer[$k],'point'=>$answer_point[$k]);
		}

		// point_val 을 생성
		$point_val=serialize($new_answ);

		if($a=='add'){
			//print_r($_REQUEST);exit;
			//Array ( [a] => add [answer_name] => asd [answer] => Array ( [0] => sada ) [answer_point] => Array ( [0] => dad ) [user_id] => admin [user_name] => 관리자 [user_info] => YTo0OntzOjM6ImlkeCI7czoyOiI0OSI7czo0OiJuYW1lIjtzOjk6Iuq0gOumrOyekCI7czo0OiJuaWNrIjtzOjA6IiI7czo0OiJ0eXBlIjtzOjQ6Ijk5OTkiO30= [user_lt] => 1334302005 [user_hash] => 0e6f60c6c78aa80129a58233f9fedd89 )

			// 문제를 입력한다.
			//$db->query("insert into test_q set pidx='$pidx',question='$question',step='$step',point_disp_type='$point_disp_type'");

			$db->query("insert into $tbl set point_name='$answer_name',point_val='$point_val'");

		}
		else if($a=='update' && $idx){
			$point_val=mysql_real_escape_string($point_val);
			//$point_val=addslashes($point_val);
//			echo $point_val;exit;

			$db->query("update $tbl set point_name='$answer_name',point_val='$point_val' where idx='$idx'");
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
선택가능한 항목과 점수를 관리
</div>

<script>



</script>


<form method=post id='key_form'>
<input type=hidden name=a>
<table width=100% class="t_ex1" border=1 cellpadding=0 cellspacing=0>
<col />
<col />
<col />
<tr>
	<th class=c2 style="width:25px;"><input type='checkbox' id='all_check' name='all_check' onclick="check_all(this,'selected_idx[]');" style="cursor:pointer;" /></th>
<!--	<th>idx</th>
-->
	<th class=left>이름</th>
	<th class=left>데이타</th>
	<th>-</th>
</tr>
<?
while($data=mysql_fetch_array($r)){
	?>
	<tr class=hover>
		<td class=c1>
			<input type=checkbox name="selected_idx[]" value="<?=$data[idx]?>" style="cursor:pointer;">
		</td>
		<td class=left><a href="<?=$_SERVER[PHP_SELF]?>?idx=<?=$data[idx]?>&a=mod_form"><?=$data[point_name]?></a></td>
		<td class=left>
			
			<table id="answer_table22" class=t_ex1 style="">
				<tr>
					<td>대답</td>
					<td>점수</td>
				</tr>
			<?

			if($data[point_val]){
				$answ=unserialize($data[point_val]);
			
				if(is_array($answ)){
					foreach($answ as $k=>$v){
						?>
						<tr>
							<td><input type=text name=answer[] value="<?=$v[name]?>" style="border:0;"></td>
							<td><input type=text name=answer_point[] value="<?=$v[point]?>" style="border:0;"></td>
						</tr>
						<?
					}
				}
			}
			?>
			</table>
		</td>
		<td class=c2>
			<!--
			<span class="button small"><a href="key_detail.php?pidx=<?=$data[idx]?>&a=mod_form&then_go=<?=urlencode($_SERVER[REQUEST_URI])?>">자세히</a></span>
			-->

			<?if($data[center_idx]){ ?><span class="button small"><a href="<?=$phpself?>?a=recover&idx=<?=$data[idx]?>&then_go=<?=urlencode($_SERVER[REQUEST_URI])?>" onclick="return confirm('정말 회수하시겠습니까?');"><span>회수</span></a></span><? }
			else { ?>
			<span class="button small"><a href="<?=$phpself?>?a=d&idx=<?=$data[idx]?>&then_go=<?=urlencode($_SERVER[REQUEST_URI])?>" onclick="return confirm('정말 삭제하시겠습니까?');"><span>삭제</span></a></span>
			<? } ?>
		</td>
	</tr>

	<?
}
?>

<!--tr>
	<td class=c1 colspan=6><?//$pagelink?></td>
</tr-->
</table>
<table width=800><tr><td align=left>
	<? if($search[ac_type]=='2' || $search[ac_type]=='3'){ ?><span class=button><a onclick="document.getElementById('account_form').action='link_manager.php?ac_type=<?=$search[ac_type]?>&then_go=<?=urlencode($_SERVER[REQUEST_URI])?>';document.getElementById('account_form').submit();" style="cursor:pointer;"><span>매니저에 연결</span></a></span><? } ?>

</td></tr></table>

<br>


<br>
</form>

<table width=800 class=t_paging style="border:0px solid black;">
	<tr><td align=center>
		<?=$pagelink?>
	</td></tr>
</table>




<script>

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

function make_point_type(){
//	alert('a');
//	f=document.make_form;
//	if(f.test_name.value==""){
///		alert('진단명을 입력하세요');
//		return;
//	}

	document.getElementById('make_form').submit();
}

function feed_key(){
	var obj=document.getElementById('key_form');
	obj.a.value='feed_key';
	obj.submit();

}
</script>




<form id="make_form" name="make_form" action="<?=$phpself?>" method=post>
<?
$data=array();
$answ=array();
if($a=='mod_form'){
	$data=$db->fa("select * from $tbl where idx='$idx'");
	
?>


<input type=hidden name=a value=update>
<input type=hidden name=idx value="<?=$idx?>">
<? } else { ?>
<input type=hidden name=a value=add>
<? } ?>
<table width=500 style="padding:5px;border:2px solid #97B1E1;">
	<tr>
		<td colspan=3>
			<table border=0 class=t_ex1>
			<tr><td colspan=2><u>대답 등록</u></td></tr>

			<tr>
				<td>그룹명</td>
				<td><input type=text name=answer_name value="<?=$data[point_name]?>" style="width:90%;"></td>
			</tr>

			<tr><td colspan=2>
				

				<table id="answer_table" class=t_ex1>
				<tr>
					<td>대답</td>
					<td>점수</td>
				</tr>
<?

if($data[point_val]){
	//echo $data[point_val];exit;

	$answ=unserialize($data[point_val]);
}


if(is_array($answ)){
//	print_r($answ);

	foreach($answ as $k=>$v){
		?>
					<tr>
						<td><input type=text name=answer[] value="<?=$v[name]?>"></td>
						<td><input type=text name=answer_point[] value="<?=$v[point]?>"></td>
					</tr>
		<?
	}
}
else {
?>
	<script>
		add_answer();
	</script>

<? } ?>
				</table>

				<div style="padding-top:10px;padding-bottom:10px; float:right;">
					<span class="button blue"><a onclick="add_answer()"> 항목 추가 </a></span>
				</div>


			</td></tr>

			</table>
			
		</td>
	</tr>
	<tr>
		<td width=100>
			
			<span class="button blue"><a onclick="make_point_type()">
			<? if($a=='mod_form'){ ?>
			업데이트
			<? } else {?>
			생성
			<? } ?>
			</a></span>
		</td>
		<td align=center></td>
		<td width=100></td>
	</tr>
</table>
</form>



<?
// 푸터
include '../../footer.admin.php';
?>