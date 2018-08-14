<?



include '../config.php';

$_CFG[left_menu_file]="$www_dir/admin/left.bbs.php";

// 인증체크
admin_login_check();

//debugreq();

// 헤더
include '../header.admin.php';


$a=$_REQUEST['a'];
$new_val=$_REQUEST['new_val'];
$id=$_REQUEST['id'];
if($a){
	if($a=='d'){
		zoi_delete_bbs($cafe_id,$id);
	}

//	if($a=='change_heap_sts'){
//		// 생성
//		if($new_val){
//			bbs_create_heap($cafe_id,$id);
//		}
//		// 해제
//		else {
//		}
//	}
}

/*
$r=$db->query("select * from na_cat");
while($data=mysql_fetch_array($r)){
	$id=$data[ct_id];

	$data[ct_id][0]=' ';
	$data[ct_id]=str_replace(' ','',$data[ct_id]);
	//echo $data[ct_id];
	$tot=$db->fa1("select count(*) from lst_board_kms where id='$data[ct_id]'");
	if(!$tot){
		$db->query("delete from na_cat where ct_id='$id'");

	}
	//echo $tot;

}
exit;
*/



/*********************
* 페이징
*/
$tot=$db->fa1("select count(*) from lst_board_{$cafe_id}"); //게시물의 전체 카운트
$no=$tot;
$lpp=10; // 한페이지에 몇줄 뿌리게 되어있는지...
$ppp=10;  // 페이지링크를 몇개 뿌릴 것인지..
$page=$_GET[page];
//$cafe_id = $_GET[cafe_id];
$id = $_GET[id];
//페이지 변수가 없을땐 페이지는 1이다
if(!$page){
	$page=1;
}

$s=($page-1)*$lpp; // 시작번호

//
$prefix="$PHP_SELF?cafe_id=$cafe_id&id=$id";

// 페이지링크를 만든다.
$pagelink=make_pagelink($tot,$page,$prefix,$lpp,$ppp);

// 게시판 목록 셀렉트 하는 방법은
$query="select * from lst_board_{$cafe_id} order by idx desc limit $s,$lpp "; // s번 부터 차례대로 $lpp 만큼 가져와라..

$r=$db->query($query);


//print_r($r);



?>

<script type='text/javascript' language='javascript'>
function form_check(){
	var frm = document.bbs_form;

	if(frm.bbs_id.value==""){
		alert("게시판 id를 입력하세요");
		frm.bbs_id.focus();
	}
	else if(frm.bbs_name.value==""){
		alert("게시판 이름을 입력하세요");
		frm.bbs_name.focus();
	}
	else{
		frm.action='./add_bbs.html'; 
		frm.submit();
	}
}

function del_check(){
	var frm = document.bbs_form;
	//if()
		frm.action='./del_process.html'; 
		frm.submit();
}

function bbs_id_mod(new_value,idx){
	var frm = document.bbs_form;
		frm.new_bbs_id.value = new_value;	
		frm.a.value='bbs_id_mod';
		frm.idx.value= idx;
		frm.action = './process.html';
		frm.submit();

}

function bbs_name_mod(new_value,idx){
	var frm = document.bbs_form;
		frm.new_bbs_name.value = new_value;
		frm.a.value='bbs_name_mod';
		frm.idx.value = idx;
		frm.action = './process.html';
		frm.submit();
}


</script>

<div class="content_title">
게시판 목록 (<?=$tot?>)
</div>

<!-- style="text-align:center;"-->

<form method='POST' id='bbs_form' name='bbs_form'/>
<input type='hidden' id='cafe_id' name='cafe_id' value='<?=$cafe_id;?>'/>
<input type='hidden' id='a' name='a'/>
<input type='hidden' id='idx' name='idx'/>
<input type='hidden' id='new_bbs_id' name='new_bbs_id'/>
<input type='hidden' id='new_bbs_name' name='new_bbs_name'/>

<table border=1 cellpadding=0 cellspacing=0 width=100% class=t_ex1 style="border:1px solid black;">
	<tr>
		<th align=center><input type='checkbox' id='bbs_check_all' name='bbs_check_all' onclick="check_all(this,'bbs_check[]');" /></th>
		<!--<th align=center><font style="font-weight:bold">그룹</font></th>
		-->
		<th align=center><font style="font-weight:bold">type</font></th>
		<th align=center><font style="font-weight:bold">id</font></th>
		<th align=center><font style="font-weight:bold">name</font></th>
		<th align=center><font style="font-weight:bold">date</font></th>
		<th>&nbsp;</th>
	</tr>
<?

/*
if($xx){
	while($data=mysql_fetch_array($r)){
		
		*/?><!--
		$menu_arr['/admin_bbs.php?id=<?/*=$data[id]*/?>']=add_menu('<?/*=$data[bbs_name]*/?>');
		--><?/*
	}
	exit;

}*/

// 게시판 목록 출력
//$q="select * from lst_board_{$cafe_id}";
//$r=$db->query($q);
//while($data=mysql_fetch_array($r)){

foreach($r as $row_idx=>$data){
?>


 <tr>
   <td align="center"><input type='checkbox' id='bbs_check' name='bbs_check[]' value='<?=$data[idx]?>'/></td>
   <!--
   <td align="center"><?=($data[group_idx]?$db->fa1("select group_name from bbs_group where idx='$data[group_idx]'"):'-')?></td>
   -->

   <td align="center">
		<?
			echo $board_types[$data[board_type]];
			
		?>
   </td>
   
   <td align="center">
	   <?=$data[id]?>
		<!--
		<input type='text' id='bbs_id<?=$data[idx]?>' name='bbs_id<?=$data[idx]?>' value='<?=$data[id];?>' style="text-align:center;"/>
		<input type='button' value='변경' onclick="bbs_id_mod(document.getElementById('bbs_id<?=$data[idx]?>').value,'<?=$data[idx]?>');"/>
		-->
	</td>
   <td align="center">
	   <?=$data[bbs_name];?>
		<!--
		<input type='text' id='bbs_name<?=$data[idx]?>' name='bbs_name<?=$data[idx]?>'value='<?=$data[cafe_bbs_name];?>' style="text-align:center;"/>
		<input type='button' value='변경' onclick="bbs_name_mod(document.getElementById('bbs_name<?=$data[idx]?>').value,'<?=$data[idx]?>');"/>
		-->
	</td>
	<? /*?>
	<td align="center">
		<?
			if($data[have_heap]){
		?>
				<input type='button' value='재생성' onclick="location.href='<?=$phpself?>?a=change_heap_sts&page=<?=$page?>&id=<?=$data[id]?>&idx=<?=$data[idx]?>&new_val=1';" /><input type='button' value='해제하기' onclick="location.href='<?=$phpself?>?a=change_heap_sts&page=<?=$page?>&id=<?=$data[id]?>&idx=<?=$data[idx]?>&new_val=0';" />
	
		<?
			}
			else {
		?>
				<input type='button' value='사용하기' onclick="location.href='<?=$phpself?>?a=change_heap_sts&page=<?=$page?>&id=<?=$data[id]?>&idx=<?=$data[idx]?>&new_val=1';" />			

		<? } ?>

	   
	</td>
	<? */?>
   <td align="center"><?=date("Y-m-d H:i:s",$data[regtt]);?></td>
   <td align="center">
		<!--<span class="button small"><a href='/admin/cat/cat.php?cat_id=_<?=$data[id]?>'>카테고리</a></span>
		-->
		<span class="button small"><a href='/admin/cat/cat.php?cat_id=_<?=$data[id]?>'>category</a></span>
		<span>|</span>


		<span class="button small"><a href='admin_bbs.php?id=<?=$data[id]?>'>view</a></span>
		<span>|</span>
		<span class="button small"><a href='bbs_add.php?a=mod_form&id=<?=$data[id]?>'>modify</a></span>
		<span>|</span>
		<span class="button small"><a href='<?=$phpself?>?id=<?=$data[id];?>&a=d&cafe_id=<?=$data[cafe_id];?>&id=<?=$data[id];?>' onclick="return confirm('warning: are you really?');">delete</a></span>
   </td>
 </tr>
<?$no=$no-1;
	}?>

</table>



</form>


<table width=100% class=t_paging>
	<tr>
		<td width=100><span class="button blue"><a href=bbs_add.php> create a board </a></span></td>
		<td><?=$pagelink?></td>
		<td width=100></td>
	</tr>
</table>


<?
// 푸터
include '../footer.admin.php';
?>