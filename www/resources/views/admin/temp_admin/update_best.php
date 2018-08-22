<?


include_once '../config.php';


//admin_login_check();
if($is_admin){
	$db->query("update board_{$cafe_id}_{$id} set is_notice='$new_val' where idx='$idx'");
	echo 1;
}
?>