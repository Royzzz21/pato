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

	//
	if($a=='d'){
		//print_r($_REQUEST);exit;
		$db->query("delete from $tbl where idx='$idx'");
	}
	//
	else if($a=='save_order'){
		$selected_cat=$search[cat];
		$new_order=explode(',',$new_order_txt);
		$new_seq=0;
		
		foreach($new_order as $k=>$v){
			$q="update $tbl set seq='$new_seq' where idx='$v'";
			//echo '<br>'.$q;
			$db->query($q);
			$new_seq++;
		}
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
	$sortby='seq';
}
if(!$sortorder){
	$sortorder='asc';
}

if($sortby){
	$orderby=" order by $sortby $sortorder";
}

// 페이지링크를 만든다.
$prefix="$PHP_SELF?"."&sortorder=$sort_order&sortby=$sortby&$search_urlquery";
$pagelink=make_pagelink($tot,$page,$prefix,$lpp,$ppp);


// 2. 데이타 셀렉트
$r=$db->query("select * from $tbl $where $orderby");
//echo "select * from $tbl $where $orderby";

$cat_data=$db->r2a($db->query("select * from subcat"),'idx','cat_name');

//print_r($cat_data);

?>
<style>
/*
	Stylesheet for examples by DevHeart.
	http://devheart.org/

	Article title:	jQuery: Customizable layout using drag n drop
	Article URI:	http://devheart.org/articles/jquery-customizable-layout-using-drag-and-drop/

	Example title:	1. Getting started with sortable lists
	Example URI:	http://devheart.org/examples/jquery-customizable-layout-using-drag-and-drop/1-getting-started-with-sortable-lists/index.html
*/

/*
	Alignment
------------------------------------------------------------------- */

/* Floats */

.left {float: left;}
.right {float: right;}

.clear,.clearer {clear: both;}
.clearer {
	display: block;
	font-size: 0;
	height: 0;
	line-height: 0;
}


/*
	Example specifics
------------------------------------------------------------------- */

/* Layout */

#center-wrapper {
	margin: 0 auto;
	width: 920px;
}


/* Columns */

.column {
	margin-left: 2%;
	/*width: 32%;*/

}
.column.first {margin-left: 0;}


/* Sortable items */

.sortable-list {
	background-color: white;
	list-style: none;
	margin: 0;
	min-height: 30px;
	padding: 0px;
}
.sortable-item {
	background-color: #FFF;
	border: 2px solid #70747a;
	cursor: move;
	display: block;
	font-weight: bold;
	margin-bottom: 5px;
	padding: 10px 10px;
	text-align: left;
}

/* Containment area */

#containment {
	background-color: #FFA;
	height: 230px;
}


/* Item placeholder (visual helper) */

.placeholder {
	background-color: #70747a;
	border: 3px dashed #666;
	height: 100px;
	margin-bottom: 5px;
}

</style>

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

<!--
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
-->

<?
if($search[cat]){
	?>

	<div id="studio_list">
		<div class="column left">
			<ul class="sortable-list ui-sortable">
			<?
				while($data=mysql_fetch_array($r)){
					
					$doc_idx=$data[doc_idx];
					$studio_data=$db->fa("select * from board_{$cafe_id}_{$id} where idx='$doc_idx'");
					
					?>
						<li id="<?=$data[idx]?>" class="sortable-item" style="">
							<span>
								<? echo get_studioListImg($doc_idx,100,70); ?>
							</span>
							<span>
								<? echo $studio_data[bbs_subject]; ?>
						
							</span>

						</li>
					<?
				}
			?>
			</ul>
		

			<div style="clear:both;text-align:right;">
				<span class="awesome black" onclick="save_changes();">변경사항 저장</span>
			</div>

		</div>


	</div>

	
	<?
}
?>
<!--
</table>

<div class="clearer">&nbsp;</div>
-->


<script type="text/javascript">

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

function save_changes(){
	var new_order_txt=getItems('#studio_list');
	location.href="<?=$phpself?>?a=save_order&search[cat]=<?=$search[cat]?>&new_order_txt="+escape(new_order_txt);
	//alert(data);
}

// Get all items from a container


function getItems(container)


{


    var columns = [];


 

    $(container+ ' ul.sortable-list').each(function(){


        columns.push($(this).sortable('toArray').join(','));


    });


 

    return columns.join('|');


}


 

$(document).ready(function(){

	// Example 1.3: Sortable and connectable lists with visual helper
	$('#studio_list .sortable-list').sortable({
		connectWith: '#studio_list .sortable-list',
		placeholder: 'placeholder',
	});

});
</script>






<table width=800 class=t_paging style="border:0px solid black;">
	<tr><td align=left>
		
	</td></tr>
</table>








<?
include '../footer.admin.php';
?>