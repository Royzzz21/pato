<?


include '../config.php';

if(!$search[page_name]){
	$search[page_name]='event';
}

// 내꺼만 나오게
//$search['user_idx']=$_USER[idx];

$_CFG[left_menu_file]="$www_dir/admin/left.stat.php";

// 인증체크
admin_login_check();

//debugreq();



// 상태 변경
if($a=='change_sts' && $user_idx){

	if($curr_value=='N'){
		$new_val='Y';
	}
	else {
		$new_val='N';

	}

	$db->query("update happy_member set diag_auth_yn='$new_val' where ac_idx='$user_idx'");

}




// 헤더
include '../header.admin.php';




?>

<!--
<a href=<?=$phpself?>><?=$sel_typename?> 회원 목록</a> | 타입선택: 


<?
foreach($_CFG[acc_type] as $k=>$v){
	if($k>1){
		echo " / ";
	}
	echo "<a href=$phpself?search[ac_type]=$k>$v</a>";

}
?>
| <a href=<?=$phpself?>?search[ac_approved]=0>미승인 목록</a>
-->



<div class="content_title">
<?=$sel_typename?> 리스트(<?=$tot?>)
</div>


<?

// unixtime 을 구한다.
$ut_today=time(); // 기준일
$ut_1d_ago=$ut_today-(86400*1); // 1일전
$ut_3d_ago=$ut_today-(86400*3); // 3일전
$ut_5d_ago=$ut_today-(86400*5); // 5일전
$ut_10d_ago=$ut_today-(86400*10); // 10일전
$ut_30d_ago=$ut_today-(86400*30); // 30일전

?>


<?
/*
$rrr=$db->query("select * from happy_member");
while($data=mysql_fetch_array($rrr)){
	$p_pw=strtolower(get_rndstr(5));
	$db->query("update happy_member set plain_pw='$p_pw' where ac_idx='$data[ac_idx]'");

}
exit;
*/

?>



<!-- 검색폼 -->


<form id='search_form' name='search_form' action='<?$PHP_SELF;?>' method='get'>

<table style="border:1px solid #cccccc;">
<tr>
	<td>

		<div>
			<table border=0>
			<tr>

				<td>
					
					<div style="float:left;">

						<input id="search_startdate" name="search[startdate]" value="<?=$search[startdate]?>" style="width:80px;" />
						<a onclick="$('#search_startdate').DatePickerShow();" style="cursor:pointer;"><img src=/img/icon-calendar.gif border=0></a>
					</div>

				</td>
				<td> ~ </td>
				<td>
					<div style="float:left;">
						<input id="search_enddate" name="search[enddate]" value="<?=$search[enddate]?>" style="width:80px;" />
						<a onclick="$('#search_enddate').DatePickerShow();" style="cursor:pointer;"><img src=/img/icon-calendar.gif border=0></a>
					</div>
				</td>

				<td>
					<span class="button"><a onclick="<?=set_date_range($ut_1d_ago,$ut_today)?>">1일</a></span>
					<span class="button"><a onclick="<?=set_date_range($ut_3d_ago,$ut_today)?>">3일</a></span>
					<span class="button"><a onclick="<?=set_date_range($ut_5d_ago,$ut_today)?>">5일</a></span>
					<span class="button"><a onclick="<?=set_date_range($ut_10d_ago,$ut_today)?>">10일</a></span>
					<span class="button"><a onclick="<?=set_date_range($ut_30d_ago,$ut_today)?>">30일</a></span>
				</td>
				<td><span class="button"><a onclick="$('#search_form').submit();">검색</a></span></td>

			</tr>
			</table>
		</div>


	</td>
	<td valign=top>
		<span>
			
		</span>
	</td>
</tr>
</table>

</form>



<?
$id='event_counsel';
include $www_dir.'/bbs/bbs.php';

?>





<script>
	// 상태변경
	function change_sts(answer_seq,curr_value){
		var ret;
		if(curr_value=='N'){
			ret=confirm('Y로 변경할까요?');
		}
		else {
			ret=confirm('N으로 변경할까요?');
		}

		if(ret){
			location.href='<?=$phpself?>?a=change_sts&user_idx='+answer_seq+'&curr_value='+curr_value;
		}
	}
</script>






<script>

// 선택항목 상태변경
function update_all_sts(){
	document.account_form.a.value='change_sts';
	if(confirm('상태를 변경할까요?')){
		document.account_form.submit();
	}
}

	// 이익율 업데이트
	function update_margin(ac_idx,new_margin){
		if(new_margin.length>0){
		//alert(new_margin);
			$.ajax({
			   type: "POST",
			   url: "update_margin.php",
			   data: "ac_idx="+ac_idx+"&new_margin="+new_margin,
			   success: function(msg){
				 //alert( $('#margin_div'+ac_idx).val());
				 $('#margin_div'+ac_idx).text(new_margin+'%');
				 $('#margin_div'+ac_idx).show();
				 $('#margin_edit_div'+ac_idx).hide();


				 //alert( "업데이트 하였습니다: " + msg );

			   }
			});
		}
	}
	




</script>




<script>
// 팝업 달력 1
$('#search_startdate').DatePicker({
 format:'Y-m-d',
 date: $('#search_startdate').val(),
 current: $('#search_startdate').val(),
 starts: 1,
 position: 'right', // element에 대한 상대적인 date picker 위치, 'top', 'left', 'right', 'bottom'
 onBeforeShow: function(){ // <- date picker가 보여지기 전에 호출되는 callback 함수
	var curr_yyyymmdd=$('#search_startdate').val();
	if(!curr_yyyymmdd){
		curr_yyyymmdd=get_yyyymmdd();
	}
	$('#search_startdate').DatePickerSetDate(curr_yyyymmdd, true); // <- 날짜 설정, true일 경우, 설정된 날짜로 이동한다. 
	 
 },
 onChange: function(formated, dates){
  $('#search_startdate').val(formated);
  //if ($('#closeOnSelect input').attr('checked')) { // <- check박스가 check되어 있으면,
  $('#search_startdate').DatePickerHide(); // <- date picker를 숨긴다. 노출하려면 DatePickerShow();
  //}
 }
});

// 팝업 달력 2
$('#search_enddate').DatePicker({
 format:'Y-m-d',
 date: $('#search_enddate').val(),
 current: $('#search_enddate').val(),
 starts: 1,
 position: 'right', // element에 대한 상대적인 date picker 위치, 'top', 'left', 'right', 'bottom'
 onBeforeShow: function(){ // <- date picker가 보여지기 전에 호출되는 callback 함수
	var curr_yyyymmdd=$('#search_enddate').val();
	if(!curr_yyyymmdd){
		curr_yyyymmdd=get_yyyymmdd();
	}
	$('#search_enddate').DatePickerSetDate(curr_yyyymmdd, true); // <- 날짜 설정, true일 경우, 설정된 날짜로 이동한다. 
 },
 onChange: function(formated, dates){
  $('#search_enddate').val(formated);
  //if ($('#closeOnSelect input').attr('checked')) { // <- check박스가 check되어 있으면,
  $('#search_enddate').DatePickerHide(); // <- date picker를 숨긴다. 노출하려면 DatePickerShow();
  //}
 }
});

</script>


<?
// 푸터
include '../footer.admin.php';
?>