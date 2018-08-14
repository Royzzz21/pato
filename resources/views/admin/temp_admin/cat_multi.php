<?


include '../config.php';

$_CFG[left_menu_file]="$www_dir/admin/left.cat.php";



admin_login_check();


include '../header.admin.php';



$tbl="cat_multi";



// 사업부 등록
function kw_add($a){
	global $_CFG;
	global $db;

	// query 생성
	$s=make_set($a);
	$q="insert into subcat set $s";

	// 실행
	return $db->query($q);
}


// 명령
if($a){
	
	if($a=='d'){
		//print_r($_REQUEST);exit;
		$db->query("delete from $tbl where idx='$idx'");


	}
	/*
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


// 조건 검색
$where='';



//$where=" WHERE ";





// 검색조건은 페이지 이동시에 계속 넘겨야 하므로 미리 만들어둔다.
//$search_urlquery='';
$search_urlquery=make_array2urlquery($search);

// 검색 조건이 있으면 조건을 처리한다.
$search=$_REQUEST[search];
if($search){

	// 카테고리 검색이면

	if($search[cat]){
		if($where){
			$where.=' AND ';
		}
		$where.="cat_id='$search[cat]'";
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

//print_r($r);
$cat_data=$db->r2a($db->query("select * from cat_house"),'idx','cat_name');

//print_r($cat_data);

?>

<div style="padding-top:10px;padding-bottom:10px;">
	<!--
	<span class="awesome blue" onclick="location.href='<?=$phpself?>';">전체</span>
	-->

	<?

	foreach($cat_data as $k=>$v){
		?>
		<span class="awesome black" onclick="location.href='<?=$phpself?>?search[cat]=<?=$k?>';"><?=$v?></span>
		<?
	}


	?>

</div>

<form method=post id='account_form'>
</form>

<?
if($search[cat]){
	?>
	<table width=100% class="t_ex1" border=1 cellpadding=0 cellspacing=0>
	<col />
	<col />
	<col />
	<tr>
		<th class=c2 style="width:25px;"><input type='checkbox' id='all_check' name='all_check' onclick="check_all(this,'selected_idx[]');" style="cursor:pointer;" /></th>
		<th>카테고리</th>
		<th>스튜디오</th>
		<th>&nbsp;</th>
	</tr>
	<?

		while($data=mysql_fetch_array($r)){
			?>
			<tr>
				<td align=center><input type=checkbox name="selected_idx[]" value="<?=$data[ac_idx]?>" style="cursor:pointer;"></td>
		<!--		<td class=c align=center>
					<?=$no--?>
				</td>
		-->
				<td class=c2>
					<?=$cat_data[$data[cat_id]]?>
				</td>
				<td class=c2>
				<?
					$doc_idx=$data[doc_idx];
					$studio_data=$db->fa("select * from board_{$cafe_id}_{$id} where idx='$doc_idx'");
					echo $studio_data[bbs_subject];
				?>
				</td>


				<td class=c2>
					<!--
					<span class="button small bp"><a href="company_add.php?idx=<?=$data[idx]?>&a=mod_form&then_go=<?=urlencode($_SERVER[REQUEST_URI])?>">편집</a></span>
					-->
					<span class="button small bp"><a href="<?=$phpself?>?a=d&idx=<?=$data[idx]?>&then_go=<?=urlencode($_SERVER[REQUEST_URI])?>" onclick="return confirm('정말 삭제할까요?');">삭제</a></span>

				</td>
			</tr>

			<?
		}

	?>


	</table>
<? } ?>


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




	<table width=100% class=t_paging>
		<tr>
			<td align=center><?=$pagelink?></td>
		</tr>
	</table>




<table width=800 class=t_paging style="border:0px solid black;">
	<tr><td align=left>
		
	</td></tr>
</table>



<?
include '../footer.admin.php';
?>