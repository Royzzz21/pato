<?
/*---------------------------------------------------------
 * 카테고리 테이블 관리
*/

include '../config.php';
$curr_menu='/admin/bbs';

// 인증체크
na_pageauthcheck();

include '../header.php';


// 테이블 지정
$tbl_prefix=$_CFG[table_prefix];
$tbl="{$tbl_prefix}cat";


// 명령
if($a){
	/*
	debugreq(1);
	Array
	(
		[a] => a
		[ct_idx] => 
		[curr_status] => 
		[ct_id] => 1
		[ct_name] => 2
		[ct_comment] => 3
		[user_id] => 1
		[user_name] => 1
		[user_info] => YTo0OntzOjM6ImlkeCI7czoyOiIxNCI7czo0OiJuYW1lIjtzOjE6IjEiO3M6NDoibmljayI7TjtzOjQ6InR5cGUiO3M6MToiMSI7fQ==
		[user_lt] => 1321601771
		[user_hash] => 942a1d61a6b4ccc267264cb3485d2ea9
	)
	*/

	// 카테고리 추가
	if($a=='a'){
		// 새로 등록
		if(cat_create_table($ct_id,$ct_name,$ct_comment)){
			// 등록성공



		}
	}
	else if($a=='d'){
		/*
		debugreq(1);
		Array
		(
			[a] => d
			[ct_idx] => 1
			[user_id] => 1
			[user_name] => 1
			[user_info] => YTo0OntzOjM6ImlkeCI7czoyOiIxNCI7czo0OiJuYW1lIjtzOjE6IjEiO3M6NDoibmljayI7TjtzOjQ6InR5cGUiO3M6MToiMSI7fQ==
			[user_lt] => 1321601771
			[user_hash] => 942a1d61a6b4ccc267264cb3485d2ea9
		)
		*/
		//$db->query("delete from $tbl where id='$ct_id'");
		cat_delete_table($ct_id);

		
	}
	else if($a=='update'){

		//cat_update_table($ct_idx,$ct_id,$ct_name,$ct_comment);

		// 아이디변경못하게 변경
		cat_update_table($ct_idx,$ct_origid,$ct_name,$ct_comment);

	}

	go2("/admin/cat/list.php");
	exit;

	
}

$orderby='';
// 조건 검색
$where='';

// 셀렉트조건에 맞는 게시물의 전체 카운트 구하기
//$tot=$db->fa1("select count(*) from member $where");


//-------------------------
// 조건에 따라 검색
// 1. 카운트 구하고
$tot=$db->fa1("select count(*) from $tbl $where");

//$orderby=" order by ct_width asc,ct_height asc";

// 2. 데이타 셀렉트
//$r=$db->query("select * from $tbl $where $orderby limit $s,$lpp");
$r=$db->query("select * from $tbl $where $orderby");


?>


<style type="text/css">
/*<![CDATA[*/
/*table {border-collapse: collapse}*/

/*]]>*/
</style>

<div class="content_title">카테고리 테이블 관리</div> 
<!--
이 페이지는 허가된 사용자 외에 접근이 금지되어있습니다.
-->

<!--
<form id='bh_search_form' name='bh_search_form' action='<?=$PHP_SELF;?>' method='POST'>

<label style="cursor:pointer;"><input type=radio name=search[sel_search_type] value='idx' checked>idx</label>
<label style="cursor:pointer;"><input type=radio name=search[sel_search_type] value='name'>name</label> <input type=text id=search_word name=search[search_word] size=20 /><input type=submit value=" 검색 " />
-->

</form>

<?
//print_r($_CFG);

?>

<form autocomplete="off" id='ct_form' name='ct_form' action='<?=$phpself;?>' method='POST'>
<input type=hidden id=a name=a value=a>
<input type=hidden id=ct_idx name=ct_idx value=''>
<input type=hidden id=curr_status name=curr_status value=''>
<table width=1000 class="t_ex1" border=0 cellpadding=0 cellspacing=0>
<tr>
	<th width=25>idx</th>
	<th>id</th>
	<th>name</th>
	<th>설명</th>
	<th>상태</th>
	<th>&nbsp;</th>
</tr>
<?
while($data=mysql_fetch_array($r)){
	?>
	<tr>
		<td style=""><?=$data[ct_idx]?></td>
		
		<td class="c1 hand hover" <?=make_onclicklink("/admin/cat/cat.php?cat_id={$data[ct_id]}")?>><?=$data[ct_id]?></td>
		<td class="c2"><?=$data[ct_name]?></td>
		<td class=c2><?=$data[ct_comment]?></td>
		<td class=c2>
			<!--
			<span class=button><a href="javascript:ct_changestatus('<?=$data[ct_idx]?>','<?=$data[ct_status]?>');" onclick="return confirm('상태를 변경할까요?');"><?=$_CFG[status_type][$data[ct_status]]?></a></span>
			-->
			</td>
		
		<td class=c2>
			<span class=button><a onclick="ct_copy2form('<?=$data[ct_idx]?>','<?=$data[ct_id]?>','<?=$data[ct_name]?>','<?=$data[ct_comment]?>');">변경</a></span>
			<span class=button><a href="<?=$phpself?>?a=d&ct_id=<?=$data[ct_id]?>" onclick="return confirm('정말 삭제하시겠습니까?');">삭제</a></span>

		<!--
		<a href="/adv/edit_campaign.php?cp_idx=<?=$data[cp_idx]?>&then_go=<?=urlencode($_SERVER[REQUEST_URI])?>"><span>편집</span></a>
		| <a href="/adv/del_campaign.php?a=d&cp_idx=<?=$data[cp_idx]?>&then_go=<?=urlencode($_SERVER[REQUEST_URI])?>" onclick="return confirm('정말 삭제하시겠습니까?');"><span>삭제</span></a>
		-->
		</td>
	</tr>

	<?
}
?>

<tr>
	<td></td>
	
	<td><input type=hidden id=ct_origid name="ct_origid" style="width:150px;"><input type=text id=ct_id name="ct_id" style="width:150px;"></td>

	<td><div id=textinput1><input type=text id=ct_name name="ct_name" style="width:150px;"></div></td>
	<td><input type=text id=ct_comment name="ct_comment" style="width:100%;"></td>
	<td></td>

	<td class=c2>
		<span id=btn_add class=button><a href="javascript:document.getElementById('ct_form').submit();">추가</a></span>
		<span id=btn_reset class=button><a href="javascript:document.getElementById('ct_form').reset();">리셋</a></span>

		<span id=btn_update class=button style="display:none;"><a href="javascript:ct_update();">저장</a></span>
		<span id=btn_updatecancel class=button style="display:none;"><a href="javascript:ct_updatecancel();">취소</a></span>
	</td>

</tr>
</table>
<br>
</form>

<!--
<table width=800><tr><td align=center><?=$pagelink?></td></tr></table>
-->

<script>

// 변경을 위해 폼으로 복사
function ct_copy2form(idx,id,name,comment){
	// ct_idx 지정
	$('#ct_idx').val(idx);
	$('#ct_origid').val(id);
	$('#ct_id').val(id);

	$('#ct_name').val(name);
	$('#ct_comment').val(comment);

	$('#btn_add').hide();
	$('#btn_reset').hide();

	$('#btn_update').show();
	$('#btn_updatecancel').show();

//	onchange_bttype();

}

// 상태변경
function ct_changestatus(idx,curr){
	// ct_idx 지정
	$('#ct_idx').val(idx);
	// 현재상태
	$('#curr_status').val(curr);

	// 상태변경
	$('#a').val('changestatus');
	

	$('#ct_form').submit();

}

// 추가
function ct_add(){
	$('#a').val('a');
	$('#ct_form').submit();
}

// 변경
function ct_update(){
	//document.getElementById('ct_form').a.value='';

	$('#a').val('update');

	//alert($('#a').val());
	$('#ct_form').submit();
	//document.getElementById('ct_form').submit();
}

// 변경 취소
function ct_updatecancel(){
	//alert('a');
	$('#btn_add').show();
	$('#btn_reset').show();

	$('#btn_update').hide();
	$('#btn_updatecancel').hide();
}

//onchange_bttype();

</script>



<?
/*
?>
<form action='<?=$_SERVER['PHP_SELF']?>' id='cat_mana' name='cat_mana' method='POST'>
<input type=hidden name=a value=''>
<table border='1' width='100%' cellpadding=5 cellspacing=0>
	<tr>
		<td colspan='8' align='right'>
			<input type='button' value='등록' onclick="javascript:location.href='<?=$_SERVER[PHP_SELF]?>?a=insert'">
			<input type='button' value='삭제' onclick="cat_delete();">
		</td>

	</tr>
	<tr>
		<td align='center'><input type='checkbox' id='all_check' name='all_check' onclick="check_all(this,'all_check[]');"></td>
		<td align='center'>아이디</td>
		<td align='center'>이름</td>
		
		<td>&nbsp;</td>
	</tr>
<?

$result = $db->query("select * from $tbl");
while($row=mysql_fetch_array($result)){?>
<tr>
	<td align='center'><input type='checkbox' id='all_check[]' name='all_check[]' value='<?=$row[id];?>'></td>
	<td align='center'><a href=cat.php?cat_id=<?=$row[id];?>><?=$row[id];?></a></td>
	<td align='center'><?=$row[name];?></td>
	
	<td align='center'><a href=cat.php?cat_id=<?=$row[id];?>>수정</a> | <a href="<?=$phpself?>?a=delete&all_check[]=<?=$row[id];?>" onclick="if(confirm('정말??')){}else {return false;}">삭제</a></td>
</tr>
<?}?>
</table>
</form>
<? */ ?>


<script>
	function form_submit(){
		var frm = document.cat_mana;
		frm.submit();
	}
	function cat_delete(){
		document.cat_mana.a.value='delete';
		form_submit();
	}
</script>



<?
include '../footer.php';
?>