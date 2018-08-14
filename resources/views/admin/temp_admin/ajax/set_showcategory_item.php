<?
include '../config.php';


/*cat_idx:3
house_idx:1
checked_sts:true*/

$cat_idx = $_REQUEST[cat_idx];
$house_idx = $_REQUEST[house_idx];
$checked_sts = $_REQUEST[checked_sts];

// 노출 카테고리 그룹의 row 를 얻는다.
$cat_group_data=$db->fa("select * from cat where ct_id='_show'");

if($cat_group_data){
	$cat_table_idx=$cat_group_data['ct_idx'];
	// 등록
	if($checked_sts == 'true'){

		$db->query("insert into cat_display set cat_table_idx='$cat_table_idx',cat_idx='$cat_idx',doc_idx='$house_idx'");
	} // 삭제
	else{
		$db->query("delete from cat_display where cat_table_idx='$cat_table_idx' and cat_idx='$cat_idx' and doc_idx='$house_idx'");
	}
}

?>