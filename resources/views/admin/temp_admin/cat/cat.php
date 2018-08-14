<?
/*---------------------------------------------------------
 * 카테고리 테이블 관리
*/

include '../config.php';
$curr_menu='/admin/bbs';



include '../../header.admin.php';


// 테이블 지정
$tbl_prefix=$_CFG[table_prefix];
$tbl="{$tbl_prefix}cat";



$cat=$_REQUEST['cat'];
$cat_id=$_REQUEST['cat_id'];
$cmd=$_REQUEST['cmd'];
$aa=$_REQUEST['aa'];

$pcat=$_REQUEST['pcat'];
$new_cat=$_REQUEST['new_cat'];
$cat_name=$_REQUEST['cat_name'];

$new_seq_data=$_REQUEST['new_seq_data'];

//reg_global(array('id','a','aa','cat_id','cmd','pcat','cat','new_cat','cat_name','new_seq_data'));


$catobj=new category;
$catobj->auto_id=1;
$catobj->prefix='cat_';
$catobj->suffix='';
$catobj->set_db($db); // 사용중인 db obj 를 넘긴다. 우리는 무조건 $db 를 넘기면됨
//	$catobj->set_dbname(''); // 현재 사용하는 디비명을 지정한다.


//$catobj->cat_init($_APP[id],$db);

//print_r($GLOBALS);
//exit;

if(!$cat_id){
	msgbox("select cat_id");
	exit;
//		$cat_id=$plugin_deps[cat];
}

//print_r($GLOBALS);exit;

$cats=array();
$ttable="{$db->tp}cat_{$cat_id}";
//	echo $cat_table;
//$ttable=$cat_table;


if($cmd){
//	print_r($_REQUEST);exit;
	switch($cmd){
		case 'add':

			//echo "add";exit;
			//echo "cat_add($ttable,$pcat,$cat,$cat_name)";
			//exit;

			if(!$catobj->cat_add($cat_id,$pcat,$new_cat,$cat_name)){
				msgbox("failed");
			}
			
			break;

		case 'mod':
			//echo "cat_mod($cat_id,$pcat,$cat,$new_cat,$cat_name);";
		//	exit;

			$catobj->cat_mod($cat_id,$pcat,$cat,$new_cat,$cat_name);
			break;

		case 'cat': // 상위 카테고리 재지정
			$catobj->cat_change_parent($cat_id,$pcat,$cat);
			break;

		case 'del':
			$catobj->cat_del($cat_id,$cat);
			break;

		case 'seq_save_changes':
			//echo ($new_seq_data);
			$child_cats=explode('|',$new_seq_data);
			for($i=0;$i<count($child_cats);$i++){
				//pcat='$cat'
				$db->query("update $ttable set seq='$i' where cat='$child_cats[$i]'");
			}
			
			break;
	}

	// 처리가 끝나면 이동.
	go2("{$_SERVER[PHP_SELF]}?cat_id=$cat_id");
}


// 모든 카테고리를 얻는다..
$cats=$db->r2a($db->query("select * from $ttable order by catpath asc,seq asc"));

if($cat){
	$cat_data=$db->fa("select * from $ttable where cat='$cat'");
	//print_r($cat_data);

}

// 배열로 만든다.
for($i=0;$i<count($cats);$i++){
	//echo $cat_data[pcat];exit;


	$cats[$i][dep]=substr_count($cats[$i][catpath],"/");
	if($cats[$i][dep]>$dep_max){
		$dep_max=$cats[$i][dep];
	}
	// 옵션생성
	$cats_option.="<option value=".$cats[$i][cat].($cat_data[pcat]==$cats[$i][cat]?' selected':'').">[".$cats[$i][cat]."] ".$cats[$i][catpath]." :: ".$cats[$i][name]."</option>";

}

// 카테고리 등록 폼
if($aa=='add_page'){

	//	for($i=0;$i<count($cats);$i++){
	//		$cats_option.="<option value=".$cats[$i][cat].">[".$cats[$i][cat]."] ".$cats[$i][name]."</option>";
	//	}

	?>

	<script>

	// 폼전송
	function cat_form_submit(){
		//alert($('#cat_id').val());

		//var val=$('#new_cat').val();
		//alert(val);

		// id체크
		if($('#new_cat').val()!='' && !is_validid($('#new_cat').val())){
			return false;
		}

		// 이름체크
		if($('#cat_name').val()==''){
			alert('이름을 입력하십시오');
			return false;
		}

		//$('#cat_form').submit();
		return true;
	}


	</script>

		<table border=0 cellpadding=5 cellspacing=1 bgcolor=#CCCCCC width=500 onsubmit="return(cat_form_submit())">
		<tr><td height=25 bgcolor=white><div class="content_title">Category <?if($cat)echo "change"; else echo "add";?></div></td></tr>
		<tr><td bgcolor=white>
			<form id="cat_form" autocomplete="off" method=post action=<?=$PHP_SELF?>>
			<input type=hidden name=id value=<?=$id?>>
			<input type=hidden name=a value=<?=$a?>>
			<!--input type=hidden name=cmd value='<? if($cat)echo 'mod'; else 'add'; ?>'-->
			<input type=hidden name=selected_menu value=<?=$selected_menu?>>
			<input type=hidden name=cat_id value=<?=$cat_id?>>

				<table border=0 cellpadding=0 cellspacing=0 align=center width=100%>
					<tr>
						<td>location</td>
						<td><select name=pcat size=10 style="width:100%;"><option value=0 <?=((!$cat_data[pcat] || $cat_data[pcat]=='/')?' selected':'')?>>top</option><? echo($cats_option); ?></select></td>
					</tr>

					<?

						if($cat){
							
							echo "<tr><td>ID</td><td><input type=hidden name=cmd value=mod><input type=hidden name=cat value='$cat' class=editbox><input type=text id=new_cat name=new_cat value='$cat' class=editbox></td></tr>";
						}
						else {
							echo "<tr><td>ID</td><td><input type=hidden name=cmd value=add><input type=text id=new_cat name=new_cat class=editbox></td></tr>";
						}

					?>


					<tr><td>name(*)</td><td>
						<input type=text id='cat_name' name=cat_name value='<?=$cat_data[name]?>' class=editbox >
						<?// echo mk_input('text','cat_name',$cat_data[name],20,100,1);	?>
					</td></tr>
					<? if($ext_menu)$add_menu(); ?>
					<!--tr><td>카테고리 이름(HTML)</td><td><textarea name=cat_hname><?=$cat_data[hname]?></textarea></td></tr-->
					<!--tr><td>수동 하이퍼 링크</td><td><input type=text name=cat_url value="<?=$cat_data[url]?>"></td></tr-->
					<tr><td colspan=2><input type=submit value=submit></td></tr>
				</table>
			</form>
		</td></tr></table>
<?
}
else if($aa=='seq_page'){
	?>
	<form name=f_cat_seq method=post action=<?=$PHP_SELF?>>
	<input type=hidden name=cat_id value=<?=$cat_id?>>
	<input type=hidden name=id value=<?=$id?>>
	<input type=hidden name=a value=<?=$a?>>
	<input type=hidden name=cmd value=seq_save_changes>
	<!--input type=hidden name=aa value=seq_save-->
	<input type=hidden name=selected_menu value=<?=$selected_menu?>>
	<input type=hidden name=cat value=<?=$cat?>>
	<input type=hidden name=new_seq_data value=''>

	<table width=500>
	<tr><td><select name=srcs size=20  style=width:100%></select></td></tr>
	<tr><td>
		<!--button onclick=addsrc('document.f.srcs'); class=button><font class=no>추가</button>&nbsp;
		<button onclick="javascript:change(document.f.srcs,document.f.srcs.selectedIndex)" class=button><font class=no>변경</button>&nbsp;
		<button onclick="javascript:sdel(document.f.srcs)" class=button><font class=no>삭제</button>&nbsp;
		-->

		<span class=button><a onclick="javascript:moveUp(document.f_cat_seq.srcs);return false;" class=button><font class=no>Back to top</a></span>&nbsp;
		<span class=button><a onclick="javascript:moveDown(document.f_cat_seq.srcs);return false;" class=button><font class=no>Go down</a></span>&nbsp;
		<span class=button><a onclick="if(before_submit()){document.f_cat_seq.submit();}else {return false;}" class=button><font class=no>Save Changes</a></span>
	</td></tr>
	</table>
	</form>

	<script language="javascript" src="combobox.js"></script>	
	<script>

	function before_submit(){
		var txt=combobox_list2text(document.f_cat_seq.srcs);
		document.f_cat_seq.new_seq_data.value=txt;
	//	alert(txt);
		return true;
	}

	function addsrcs(){
		for(var i=1;i<srcs.length;i++){
			addItem(document.f_cat_seq.srcs,srcs[i],srcs[i+1],'');
			i++;
		}
	}
	<?

		//echo "select * from $ttable where pcat='$cat' order by seq asc";
		//$cats=$db->r2a($db->query("select * from $ttable order by catpath asc"));
		$child_cat=$db->r2a($db->query("select * from $ttable where pcat='$cat' order by seq asc"));
		//print_r($child_cat);

		for($i=0;$i<count($child_cat);$i++){
			$r.= ",'{$child_cat[$i][cat]}','{$child_cat[$i][name]}'";
		}
	?>
	//var srcs=new Array('/_lib/_mws/mws.lib.php','/_lib/_hosting/util.lib.php','/_lib/server/sock.lib.php','/_lib/server/hosting/host.lib.php','/_lib/server/hosting/_host_client.php');
	var srcs=new Array(''<?=$r?>);
	addsrcs();
	//addItem(document.f_cat_seq.srcs,'/_lib/hosting/web.lib.php','/_lib/hosting/web.lib.php','');

	</script>


<?
}
else {
?>
	<form autocomplete="off" id="cat_form" method="post" action="category_seq_mod.html">
	<input type=hidden name=cmd value=cat>
<?
	echo "
	<input type=hidden name=id value=$id>
	<input type=hidden name=a value=$a>
	<input type=hidden name=aa value=$aa>
	<input type=hidden name=selected_menu value=$selected_menu>
	<input type=hidden name=cat_id value=$cat_id>
	<input type=hidden name=k>";
?>
	<table border=0 cellpadding=2 cellspacing=0 width=100% align=center bgcolor=white>
	<tr><td>

		<table cellpadding=0 cellspacing=0 border=0 width=100%><tr>
		<td>
			<span class="button blue"><a href="<?=$PHP_SELF?>?a=<?=$a?>&id=<?=$id?>&selected_menu=<?=$selected_menu?>&cat_id=<?=$cat_id?>&aa=add_page">add category</a></span>

		</td></tr></table>


	</td></tr>
	<tr><td>
<? 
	echo "<table class=t_ex2 border=0 cellpadding=0 cellspacing=0 bgcolor=#bebebe width=100% align=center>
		<tr>
			<th rowspan=2 align=center width=30 height=30><input type='checkbox' id='all_check' name='all_check' onclick=\"check_all(this,'cat[]');\" style=\"cursor:pointer;\" /></th>
			<th colspan=$dep_max align=center>Category </th>
			
			<th rowspan=2 align=center>Category name</th>
			<th rowspan=2 align=center>Category ID</th>
			<th rowspan=2 align=center>Category idx</th>
			<th rowspan=2 align=center width=25>sub</th>
			<th rowspan=2 align=center>Category path</th>

			<th rowspan=2 align=center width=150>Change</th>
			<th rowspan=2 align=center width=50>Delete</th>
		</tr>";

	$exp.=	"<tr>
			<!--td class=no_white align=center height=30>&nbsp;</td-->";

	for($x=0;$x<$dep_max;$x++){
		$exp.= "<th bgcolor=white align=center>".($x+1)."</th>";
	}

//			$exp.= "<td>&nbsp;</td><td>&nbsp;</td><td class=so_gray><b>선택사항</td><td class=so_gray><b>선택사항</td>
//				<td class=so_gray><b></td><td class=so_gray><b></td></tr>";

	echo $exp;
	echo '</tr>';

	//$tmp2=$db->query("select * from $ttable order by catpath asc");
	//while($data=mysql_fetch_array($tmp2)){
	for($i=0;$i<count($cats);$i++){
		echo "<tr bgcolor=white>
			<td align=center><input type=checkbox name=cat[] value=".$cats[$i][cat]."></td>";

		for($x=1;$x<$cats[$i][dep];$x++){
			echo "<td style=\"min-width:50px;\">&nbsp;</td>";
		}

		//echo "<td align=center>".$cats[$i][cat]."</td>";
		echo "<td align=center style=\"min-width:50px;\"><b>".$cats[$i][name]."</b></td>";

		for($x=0;$x<($dep_max - $cats[$i][dep]);$x++){
			echo "<td style=\"min-width:50px;\">&nbsp;</td>";
		}

		//if(!$cats[$i][lnk])echo "-";
		//else echo $cats[$i][lnk];

		echo "</td>
		
			<td class=c2><b>".$cats[$i][name]."</b></td>
			<td class=c2><b>".$cats[$i][cat]."</b></td>
			<td class=c2><b>".$cats[$i][idx]."</b></td>
			<td class=c1>".$cats[$i][child_cats]."</td>
			<td class=c2>".$cats[$i][catpath]."</td>
			
			<!--td>".$cats[$i][hname]."</td-->
			<!--td>".$cats[$i][url]."</td-->
			<td align=center>
				";

		echo "<span class=button><a href=/admin/admin_bbs.php?a=write&cafe_id=&id=".substr($cat_id,1)."&cat_id=".$cats[$i][cat].">제품등록</a></span>";

		echo "
				<span class=button><a href=$PHP_SELF?a=$a&id=$id&selected_menu=$selected_menu&cat_id=$cat_id&aa=add_page&cat=".$cats[$i][cat].">변경</a></span>
				<span class=button><a href=$PHP_SELF?a=$a&id=$id&selected_menu=$selected_menu&cat_id=$cat_id&aa=seq_page&cat=".$cats[$i][pcat].">노출관리</a></span>
			</td>
			<td align=center>
				<span class=button><a href=$PHP_SELF?a=$a&id=$id&selected_menu=$selected_menu&cat_id=$cat_id&cmd=del&cat=".$cats[$i][cat]." onclick=\"return confirm('정말 삭제할까요?');\">삭제</a></span>
			</td>
			</tr>";

		$catt[$doc_data[catpath]][]=$doc_data[cat];
	}

	//	echo $exp;
	echo "</table>";

?>
	</td></tr>
	<tr><td align=left>

			<table border=0 cellpadding=0 cellspacing=0>
				<tr>
					<td>
						<select name=pcat><option value="/">Top</option><? echo($cats_option); ?></select>
					</td>
					<td><span class="button blue"><a onclick="cat_move_category();">go</a></span></td>
				</tr>
				
			</form>
			</table>

	</td></tr>
	</table>

<?
}
?>
<script>
	// 카테고리 이동
	function cat_move_category(){
	//	alert('<?=$phpself?>');
		//$('#cat_form').attr('action','http://naver.com');
		$('#cat_form').attr('action','<?=$phpself?>');
		$('#cmd').val('cat');
		$('#cat_form').submit();

	}
</script>

<?

include '../../footer.admin.php';

?>