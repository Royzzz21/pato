<?
$table_id = $_REQUEST[table_id];
$table_name = $_REQUEST[table_name];

if($table_id && $table_name){

	if($_REQUEST[mode]=='modify'){
		$q = "update cat set 
			id			=		'$table_id',
			name		=		'$table_name'
			where idx = '$idx'
		";
		$db->query($q);

	}
	// 새로 등록
	else {
		// 등록성공
		if(cat_create_table($table_id,$table_name)){

		}

	}
}


go2('index.php');

?>