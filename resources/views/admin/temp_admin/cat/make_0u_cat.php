<?
include 'cat.lib.php';
include '../_config.php';
//include $admin_dir.'/admin_header.php'; 

//print_r(file('/www/zoi/cache/cat.html'));
//exit;

reg_global(array('id','a','aa','cat_id','cmd','pcat','cat','new_cat','cat_name','new_seq_data'));


$catobj=new category;
$catobj->auto_id=0;
$catobj->prefix='cat_';
$catobj->suffix='';
$catobj->set_db(&$db); // 사용중인 db obj 를 넘긴다. 우리는 무조건 $db 를 넘기면됨
$catobj->set_dbname('zoi'); // 현재 사용하는 디비명을 지정한다.

// 카테고리 테이블의 모든 데이타를 셀렉트한다.
$r=$db->query("select * from zoi.cat_0u");


$catd=array();

// 전체 카테고리데이타를 배열로만든다..
while($data=@mysql_fetch_array($r)){
	//$x++;
	$catd[$data[cat]][cat]=$data[cat];
	$catd[$data[cat]][name]=$data[name];
	$catd[$data[cat]][name_field_data]=$data[name]; // default
	$catd[$data[cat]][url]=$data[url];
	$catd[$data[cat]][url_field_data]=$url_prefix.$data[cat]; // default
	$catd[$data[cat]][catpath]=$data[catpath];
	$catd[$data[cat]][num]=$x;
}





$r='';
$top_menu='';
$cat_num=0;
$top_list=array(); // 최상위들의 id 를 배열로만들거야.
$parent_list=array(); // 서브들의 부모 id 를 배열로만들거야.
$gongu_names=array(); // 카테고리들의 이름을 배열로만들거야.

// 1단계 카테고리 얻기
$top_cats=$db->query("select * from zoi.cat_0u where pcat='/' order by seq asc");
while($data=mysql_fetch_array($top_cats)){
	$cat=$data[cat];
	$top_list[]=$cat;

//	make_divsubmenu('',0,$data[cat],0,$catd,$r1,$r2);
//}

//
//function make_divsubmenu($pcat,$item_idx,$cat,$cur_depth,&$catd,&$r1,&$r2){
//	global $db;

	//echo $cat."<br>";
	//echo "<br>select count(*) from cat_0u where pcat='$cat'";

	// 서브가 있으면 
	//if($db->fa1("select count(*) from cat_0u where pcat='$cat'")){

		// 1단계 디렉토리
		//if($cur_depth==0){
			
			if($cat_num){
			//	$top_menu.=" <font color=#bababa>|</font> ";
			}

//	if(!$top_menu){
//		$top_menu.="<a href=\"/file0u/?cat=$cat\" onmouseover=\"subdiv_close();show_layer('subdiv_$cat_num');\"><span class='cat_btn_selected'>$data[name]</span></a>";
//	}
//	else {
		$top_menu.="<div id='div_0u_$data[cat]' class=\"cat_btn\"><a href=\"/file0u/?search[cat0]=$cat\" onmouseover=\"subdiv_close();show_layer('subdiv_$cat_num');\"><span id='span_0u_$data[cat]'>".($data[cat]=='11111'?'<img src=/img/icon3/19icon2.gif border=0 />':'')."$data[name]</span></a></div>";
//	}

		//}

		// 1단계가 아니면..
		//else {
		//	break;
		//}

		$gongu_names[$cat]=$catd[$cat][name];

		// 서브카테고리 전부 모아서 div를 하나 생성
		$div="<div id=\"subdiv_{$cat_num}\" style='display:none;'>";
		$sub_num=0;
		$sub_cats=$db->query("select * from cat_0u where pcat='$cat' order by seq asc");
		while($data2=mysql_fetch_array($sub_cats)){
			$parent_list[$data2[cat]]=$data2[pcat];
			$gongu_names[$data2[cat]]=$catd[$data2[cat]][name];
			if($sub_num){
				$div.=' <font color=#bababa>|</font> ';
			}
			$div.="<a href=/file0u/?search[cat]=$data2[cat]><font class=small_cat>$data2[name]</font></a>";
			//make_divsubmenu($cat,$sub_num,$data[cat],$cur_depth+1,$catd,$tb,$r1,$r2);
			$sub_num++;
		}
		$div.='</div>';

		$r.=$div;


	/*}
	// 서브가 없으면
	else {

		// 1단계 디렉토리
		if($cur_depth==0){


		}
		// 1단계가 아니면..
		else {

		}
	}*/
	$cat_num++;

}

$sub_all=$r;

$sub_all='<div id="div_small_cat">'.$sub_all.'</div>';

$r='';
$r='<div style="clear:both;"></div>';
$r.=''.$top_menu.'';
$r.='<div style="clear:both;"></div>';
//$r.='<div style="background-color:#eeeeee;margin-top:5px;width:100%;height:1px;"></div>'; // 이것때문에 ie에서 이상한 현상 발생
//$r.=$sub_all;

$json_array='';
foreach($parent_list as $k=>$v){
	if($json_array){
		$json_array.=',';
	}
	$json_array.='"'.$k.'":"'.$v.'"';
}

$json_array2='';
foreach($gongu_names as $k=>$v){
	if($json_array2){
		$json_array2.=',';
	}
	$json_array2.='"'.$k.'":"'.$v.'"';
}

$html2=
	//'<script>'.
	'var top_0u_cat=new Array(\''.implode('\',\'',$top_list).'\');'.
	'var parent_0u_cat={'.$json_array.'};'.
	'var gongu_names={'.$json_array2.'};'.
	'var subdiv_tot='.$cat_num.';'.
	'';
	//'</script>';

$html2.=<<<EOF


	
	function show_curr_loca(c){

		var depth0_name=null;
		var depth1_name=null;
		var depth0_cat='';
		var depth1_cat='';

		if(parent_0u_cat[c]){
			var d=parent_0u_cat[c];
			var depth1_name=gongu_names[c];
			var depth1_cat=c;
			var depth0_name=gongu_names[d];
			var depth0_cat=d;
		}
		else {
			var depth0_name=gongu_names[c];
			var depth0_cat=c;
			
		}

		var str='';
		if(depth0_name){
			str+=' &gt; <a href="/file0u/?cat='+depth0_cat+'">'+depth0_name+'</a>';
		}

		if(depth1_name){
			str+=' &gt; '+depth1_name;
		}
		else {
			str+=' &gt; 전체';
		}

		obj_get('curr_loca_div').innerHTML=str;

	}

	function select_0u_cat(c){

		if(parent_0u_cat[c]){
			c=parent_0u_cat[c];
		}

		for(var k in top_0u_cat){
			//alert(top_0u_cat[k]+' and '+c);
			if(top_0u_cat[k]==c){
				//alert('!');

				subdiv_close();
				show_layer('subdiv_'+k);
				//alert(obj_get('span_0u_'+top_0u_cat[k]));
				//alert(obj_get('span_0u_'+top_0u_cat[k]).className);
				obj_get('div_0u_'+top_0u_cat[k]).className='cat_btn_selected';

				if(obj_get('c_'+top_0u_cat[k])){
					obj_get('c_'+top_0u_cat[k]).checked=true;
				}
				//alert(obj_get('div_0u_'+top_0u_cat[k]).className);
								

			}
		}
	}

	function hidden_layer(layer_name){
		if (layer_name == '') return;
		obj_get(layer_name).style.display='none';
	}
	 
	function show_layer(layer_name){
		if (layer_name == '') return;
		obj_get(layer_name).style.display='block';
	}

	function subdiv_close(){
		//alert(subdiv_tot);

		for(var i=0;i<subdiv_tot;i++){
			hidden_layer('subdiv_'+i);
		}
	}


EOF;

$script=<<<EOF

<style type="text/css">

/* cat_btn */
.cat_btn {
	float:left;
	white-space: nowrap;
}

.cat_btn a{display:block; background:url('/img/catbtn/catbtn_bg.gif') left 0; float:left; font:12px 굴림; color:#555; padding-left:6px; text-decoration:none; height:27px; cursor:pointer; margin-right:3px; overflow:hidden}
.cat_btn a span{display:block; float:left; background:url('/img/catbtn/catbtn_bg.gif') right 0; line-height:240%; padding-right:6px; height:27px; overflow:hidden;font-weight:bold;}
.cat_btn a:hover{background:url('/img/catbtn/catbtn_bg.gif') left -27px;}
.cat_btn a:hover span{background:url('/img/catbtn/catbtn_bg.gif') right -27px; color:black; font-weight:bold;}

/* cat_btn (selected) */
.cat_btn_selected {
	float:left;
	white-space: nowrap;
}

.cat_btn_selected a{display:block; background:url('/img/catbtn/catbtn_bg.gif') left -27px; float:left; font:12px 굴림; color:white; padding-left:6px; text-decoration:none; height:27px; cursor:pointer; margin-right:3px; overflow:hidden}
.cat_btn_selected a span{display:block; float:left; background:url('/img/catbtn/catbtn_bg.gif') right -27px; line-height:240%; padding-right:6px; height:27px; overflow:hidden;font-weight:bold;}
.cat_btn_selected a:hover{background:url('/img/catbtn/catbtn_bg.gif') left -27px;}
.cat_btn_selected a:hover span{background:url('/img/catbtn/catbtn_bg.gif') right -27px; color:white; font-weight:bold;}

</style>


EOF;


fw($zoi_dir.'/cache/cat.js','w',$html2);
fw($zoi_dir.'/cache/cat_main.html','w',$script.$r);
fw($zoi_dir.'/cache/cat_sub.html','w',$sub_all);
fw($zoi_dir.'/cache/cat.php','w','<? $cat_data=\''.serialize($catd).'\'; ?>');


// 대분류를 모두 찍는다.

echo "대분류 목록:";
foreach($top_list as $v){
	echo "<br>$v {$catd[$v][name]}";

}

//include $admin_dir.'/admin_footer.php'; 
?>