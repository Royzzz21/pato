<?
include '../config.php';


/*cat_idx:3
house_idx:1
checked_sts:true*/

$cat_idx = $_REQUEST[cat_idx];
$house_idx = $_REQUEST[house_idx];
$checked_sts = $_REQUEST[checked_sts];

// ���� ī�װ� �׷��� row �� ��´�.
$cat_group_data=$db->fa("select * from cat where ct_id='_show'");

if($cat_group_data){
	$cat_table_idx=$cat_group_data['ct_idx'];
	// ���
	if($checked_sts == 'true'){

		$db->query("insert into cat_display set cat_table_idx='$cat_table_idx',cat_idx='$cat_idx',doc_idx='$house_idx'");
	} // ����
	else{
		$db->query("delete from cat_display where cat_table_idx='$cat_table_idx' and cat_idx='$cat_idx' and doc_idx='$house_idx'");
	}
}

?>