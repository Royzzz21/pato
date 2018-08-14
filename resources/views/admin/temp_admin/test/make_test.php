<?



include '../config.php';

// 메뉴지정
$curr_menu='/admin/test';

// 인증체크
na_pageauthcheck();

// 헤더
include '../../header.admin.php';

//debugreq();


// 테이블 지정
$tbl_prefix=$_CFG[table_prefix];
$tbl="test_name";

// 명령
if($a){
	/*
	mysql> desc test_name;
	+-----------+-----------+------+-----+---------+----------------+
	| Field     | Type      | Null | Key | Default | Extra          |
	+-----------+-----------+------+-----+---------+----------------+
	| idx       | int(11)   | NO   | PRI | NULL    | auto_increment | 
	| test_name | char(255) | YES  |     | NULL    |                | 
	+-----------+-----------+------+-----+---------+----------------+

	*/

	if($a=='add'){
		// 테스트를 생성한다.
		$db->query("insert into test_name set test_name='$test_name'");
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
진단 생성
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
	<th>진단명</th>
	<th>-</th>
</tr>
<?
while($data=mysql_fetch_array($r)){
	?>
	<tr class=hover>
		<td class=c1>
			<input type=checkbox name="selected_idx[]" value="<?=$data[idx]?>" style="cursor:pointer;">
		</td>
		

		<td class=c1><a href=test_question.php?pidx=<?=$data[idx]?>><?=$data[test_name]?></a></td>
	


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
<table width=500 style="padding:5px;border:2px solid #97B1E1;">
	<tr>
		<td colspan=3>
			<table border=0>
			<tr><td colspan=2>진단 생성</td></tr>
			<tr><td>진단명</td><td><input type=text name=test_name></td></tr>
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
function make_test(){
//	alert('a');
	f=document.make_form;
	if(f.test_name.value==""){
		alert('진단명을 입력하세요');
		return;
	}

	document.getElementById('make_form').submit();
}

function feed_key(){
	var obj=document.getElementById('key_form');
	obj.a.value='feed_key';
	obj.submit();

}
</script>


<?
// 푸터
include '../../footer.admin.php';
?>