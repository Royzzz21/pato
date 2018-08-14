<?


include '../config.php';

$_CFG[left_menu_file]="$www_dir/admin/left.cat.php";




include '../header.admin.php';



$tbl="cat";
$idx=$_REQUEST['idx'];


$a=$_REQUEST['a'];

// 명령
if($a){

	//print_r($_REQUEST);
	//exit;

	/*
		Array ( [company_idx] => 5 [a] => loca_reg [then_go] => /admin/company/company.php [new_loca_name] => ㅁㅁㅁ [user_id] => dev@goweb.kr [user_info] => YTo1OntzOjM6ImlkeCI7czoyOiI1NiI7czo0OiJuYW1lIjtzOjA6IiI7czo0OiJuaWNrIjtzOjA6IiI7czo0OiJ0eXBlIjtzOjM6IjEwMCI7czo0OiJhcHByIjtzOjE6IjAiO30= [user_lv] => 9999 [user_lt] => 1372584304 [user_hash] => 0c8c47e2717ce442b13bbccd3c5eace2 )
	*/



	// 카테고리 그룹 등록하기
	if($a=='loca_reg'){
		$new_cat_id=$_REQUEST['new_cat_id'];
		$new_loca_name=$_REQUEST['new_loca_name'];
		$new_parent_cat=$_REQUEST['new_parent_cat'];

		$new_data=array();
		//$new_data[cat_id]=$new_cat_id;
		//$new_data[cat_name]=$new_loca_name;
		//$new_data[parent_cat]=$new_parent_cat;

		// 생성.
		//$ret=kw_add($new_data);

		$ret=cat_create_table($new_cat_id,$new_loca_name,'자동생성');


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
	// 삭제하기
	else if($a=='d'){
		//print_r($_REQUEST);exit;

		//$db->query("delete from $tbl where idx='$idx'");

		$data=$db->fa("select * from $tbl where ct_idx='$idx'");
		//print_R($data);
		//exit;
		cat_delete_table($data['ct_id']);

		//$db->query("drop table $tbl");

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
	$sortby='ct_idx';
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


<!--	<div >-->
<!--		<a href="/admin/cat_multi.php?id=--><?//=$id?><!--">노출관리</a> | <a href="/admin/cat_multi_sort.php?id=--><?//=$id?><!--">순서관리</a>-->
<!--	</div>-->

	<form method=post id='account_form'>
	</form>
	<table width=100% class="t_ex1" border=1 cellpadding=0 cellspacing=0>
		<col />
		<col />
		<col />
		<tr>
			<th class=c2 style="width:25px;">
<!--				<input type='checkbox' id='all_check' name='all_check' onclick="check_all(this,'selected_idx[]');" style="cursor:pointer;" />-->
			</th>
			<th>카테고리 그룹</th>
			<th>카테고리 설명</th>
			<th>타입</th>
			<th>삭제</th>
		</tr>
		<?

		//while($data=mysql_fetch_array($r)){
		foreach($r as $data){

			?>
			<tr>
				<td align=center>
<!--					<input type=checkbox name="selected_idx[]" value="--><?//=$data[ac_idx]?><!--" style="cursor:pointer;">-->
				</td>
				<td align=center>
					<a href="/admin/cat/cat.php?cat_id=<?=$data['ct_id']?>"><?=$data['ct_id']?></a>

				</td>
				<td align=center>
					<?
					//$_CFG[cat][$data[parent_cat]]
					echo $data['ct_name'];
					?>
				</td>
				<td align=center>
					<?=$data['ct_type']?>
				</td>

				<td align=center>
					<span class="button small bp"><a href="<?=$phpself?>?a=d&idx=<?=$data[ct_idx]?>&then_go=<?=urlencode($_SERVER[REQUEST_URI])?>" onclick="return confirm('정말 삭제할까요?');">삭제</a></span>
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




	<table width=100% class=t_paging>
		<tr>
			<td align=center><?=$pagelink?></td>
		</tr>
	</table>

	<div style="padding:10px;border:1px solid #bebebe;">
		<form method=post name=loca_form>
			<input type=hidden name=a value=loca_reg>
			<table width=800 class=t_paging>
				<tr>
					<td width=300>

						<table>
							<!--<tr>
								<td>상위 카테고리</td>
								<td><?/*=make_selectbox('new_parent_cat',$_CFG[cat],'')*/?></td>
							</tr>-->

							<tr>
								<td>카테고리ID</td>
								<td><input type=text class=awesome-text-box name=new_cat_id></td>
							</tr>

							<tr>
								<td>카테고리 그룹명</td>
								<td><input type=text class=awesome-text-box name=new_loca_name></td>
							</tr>


						</table>


					</td>
					<td><span class="button bp "><a href="javascript:loca_reg();"> 추가 </a></span></td>

					<td width=100></td>
				</tr>
			</table>
		</form>

	</div>


	<table width=800 class=t_paging style="border:0px solid black;">
		<tr><td align=left>

			</td></tr>
	</table>



<?
include '../footer.admin.php';
?>