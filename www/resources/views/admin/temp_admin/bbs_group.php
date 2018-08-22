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

// 명령
if($a){
	if($a=='d'){
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



<div class="content_title">
Group Management Board(<?=$tot?>)
</div>

<script>



</script>

<!--
<form id='mem_search_form' name='mem_search_form' action='<?$PHP_SELF;?>' method='POST'>

<?=make_selectbox('search[ac_type]',$_CFG[acc_type],$search[ac_type],'모든타입')?>
<?=make_selectbox('search[sel_search_type]',array('id'=>'아이디','name'=>'이름'),$search[sel_search_type])?>

<input type=text id=search_word name=search[search_word] size=20 /><input type=submit value=" 검색 " />

</form>
-->


<!-- 이름	아이디	센터명	학교	가입일	상태 -->

<form method=post id='account_form'>
<table width=100% class="t_ex1" border=1 cellpadding=0 cellspacing=0>
<col />
<col />
<col />
<tr>
	<th class=c2 style="width:25px;"><input type='checkbox' id='all_check' name='all_check' onclick="check_all(this,'selected_idx[]');" style="cursor:pointer;" /></th>
	<th>Group name</th>
	<th>Publishes</th>
	<th>Number of Board</th>
	<th>explication</th>
	<th>&nbsp;</th>
</tr>
<?
while($data=mysql_fetch_array($r)){
	?>
	<tr>
		<td class=c2><input type=checkbox name="selected_idx[]" value="<?=$data[idx]?>" style="cursor:pointer;"></td>
		<td class=c2><a href=group_add.php?idx=<?=$data[idx]?>&a=mod_form&then_go=<?=urlencode($_SERVER[REQUEST_URI])?>><?=$data[group_name]?></a></td>
		<td class=c2><?=($data[is_open]?'Public':'Private')?></td>
		<td class=c2>
			<?
				//$data[tot_board]
				echo $db->fa1("select count(*) from lst_board_{$cafe_id} where group_idx='$data[idx]'");
			?>
		</td>
		<td class=c2><?=$data[group_desc]?></td>
		<td class=c2>
			<!--
			<span class="button small"><a href="std_course_add.php?ac_id=<?=$data[ac_id]?>&then_go=<?=urlencode($_SERVER[REQUEST_URI])?>">편집</a></span>
			-->
			<span class="button small"><a href="<?=$phpself?>?a=d&idx=<?=$data[idx]?>&then_go=<?=urlencode($_SERVER[REQUEST_URI])?>" onclick="return confirm('Really sure you want to delete?');"><span>delete</span></a></span>
		</td>
	</tr>

	<?
}
?>

</table>
<table width=800><tr><td align=left>
	<? if($search[ac_type]=='2' || $search[ac_type]=='3'){ ?><span class=button><a onclick="document.getElementById('account_form').action='link_manager.php?ac_type=<?=$search[ac_type]?>&then_go=<?=urlencode($_SERVER[REQUEST_URI])?>';document.getElementById('account_form').submit();" style="cursor:pointer;"><span>매니저에 연결</span></a></span><? } ?>

</td></tr></table>
<table width=800 class=t_paging>
	<tr>
		<td width=100><span class="button blue"><a href=group_add.php> Register </a></span></td>
		<td align=center><?=$pagelink?></td>
		<td width=100></td>
	</tr>
</table>

<?
// 푸터
include '../footer.admin.php';
?>