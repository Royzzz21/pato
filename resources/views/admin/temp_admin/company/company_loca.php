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
$tbl="company_loca";



// 사업부 등록
function company_loca_add($a){
	global $_CFG;
	global $db;

	// query 생성
	$s=make_set($a);
	$q="insert into company_loca set $s";

	// 실행
	return $db->query($q);
}


// 명령
if($a){

	//print_r($_REQUEST);
	//exit;

/*
	Array ( [company_idx] => 5 [a] => loca_reg [then_go] => /admin/company/company.php [new_loca_name] => ㅁㅁㅁ [user_id] => dev@goweb.kr [user_info] => YTo1OntzOjM6ImlkeCI7czoyOiI1NiI7czo0OiJuYW1lIjtzOjA6IiI7czo0OiJuaWNrIjtzOjA6IiI7czo0OiJ0eXBlIjtzOjM6IjEwMCI7czo0OiJhcHByIjtzOjE6IjAiO30= [user_lv] => 9999 [user_lt] => 1372584304 [user_hash] => 0c8c47e2717ce442b13bbccd3c5eace2 )
*/

	

	// 사업부 추가
	if($a=='loca_reg'){
		$new_data=array();
		$new_data[loca_name]=$new_loca_name;

		// 생성.
		$ret=company_loca_add($new_data);

		//echo $ret;
		if($ret){
			msgbox('등록이 완료되었습니다.');
			//go2('company_.php');
			//exit;
		}
		else {
			msgbox('등록이 실패되었습니다.');
			//go_back();
			//exit;
		}
	}
	else if($a=='d'){
		//print_r($_REQUEST);exit;
		$db->query("delete from $tbl where idx='$idx'");


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
$where='';
//$where="ac_idx='$_USER[idx]'"; // ac_type=2 학생

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
	$where=" where $where";
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

사업부관리
<form method=post id='account_form'>
</form>
<table width=100% class="t_ex1" border=1 cellpadding=0 cellspacing=0>
<col />
<col />
<col />
<tr>
	<th class=c2 style="width:25px;"><input type='checkbox' id='all_check' name='all_check' onclick="check_all(this,'selected_idx[]');" style="cursor:pointer;" /></th>
	<th>No.</th>
	<th>사업부명</th>
	<th>&nbsp;</th>
</tr>
<?
while($data=mysql_fetch_array($r)){
	?>
	<tr>
		<td class=c2><input type=checkbox name="selected_idx[]" value="<?=$data[ac_idx]?>" style="cursor:pointer;"></td>
		<td class=c2>
			<?=$no--?>
		</td>
		<td class=c2>
			<?=$data[loca_name]?>
		</td>
		
		<td class=c2>
			<!--
			<span class="button small bp"><a href="company_add.php?idx=<?=$data[idx]?>&a=mod_form&then_go=<?=urlencode($_SERVER[REQUEST_URI])?>">편집</a></span>
			-->
			<span class="button small bp"><a href="<?=$phpself?>?a=d&idx=<?=$data[idx]?>&then_go=<?=urlencode($_SERVER[REQUEST_URI])?>" onclick="return confirm('정말 삭제하시겠습니까?');">삭제</a></span>

		</td>
	</tr>

	<?
}
?>


</table>

<script>
function loca_reg(){
	//alert(1);
	var obj=document.loca_form;

	//alert(obj.new_loca_name.value);
	if(obj.new_loca_name.value==''){
		
	}
	else {
		obj.submit();
	}
}
</script>
<table width=800><tr><td align=left>
	<? if($search[ac_type]=='2' || $search[ac_type]=='3'){ ?><span class=button><a onclick="document.getElementById('account_form').action='link_manager.php?ac_type=<?=$search[ac_type]?>&then_go=<?=urlencode($_SERVER[REQUEST_URI])?>';document.getElementById('account_form').submit();" style="cursor:pointer;"><span>매니저에 연결</span></a></span><? } ?>

</td></tr></table>

<form method=post name=loca_form>
<input type=hidden name=a value=loca_reg>
<table width=800 class=t_paging>
	<tr>
		<td width=100>
			<input type=text name=new_loca_name>
		</td>
		<td><span class="button bp"><a href="javascript:loca_reg();"> 등록 </a></span></td>
		<td align=center><?=$pagelink?></td>
		<td width=100></td>
	</tr>
</table>
</form>

<table width=800 class=t_paging style="border:0px solid black;">
	<tr><td align=left>
		
	</td></tr>
</table>


<?
// 푸터
include '../../footer.admin.php';
?>