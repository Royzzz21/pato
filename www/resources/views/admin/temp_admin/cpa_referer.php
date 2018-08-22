<?


include_once '../config.php';
$_CFG[left_menu_file]="$www_dir/admin/left.stat.php";

admin_login_check();

if(!$search[startdate]){
	$search[startdate]=date('Y-m-d');
	$search[enddate]=date('Y-m-d');
}


include '../header.admin.php';
?>

<div id=ctl00_mvContentWidth class=subCtn>
<?

	$connect=$db->conex;


	//$connect=dbConn();

	$month = date('m');

	if($select_yyyy){
		$year=$select_yyyy;
	}
	else {

		if($selected_year){
			$year_mm=$selected_year;
			list($year,$month)=explode('-',$year_mm);
		}
		else {
			$year_mm  = date('Y-m');
			$year=date('Y');
		}

	}



?>


<br>
<table  width="100%" align="center"  border="0" cellspacing="0" cellpadding="0" >
<tr>
	<td colspan=2 align=left><img src="/adminmode_/images/icon2.gif" width="4" height="5" align="absmiddle"><span class="subtitle"><B> CPA분석</span></td>
	<td align=right></td>
</tr>
</table>
<table  cellSpacing="0" cellPadding="0" width=100%>
	<TBODY>
		<TR bgcolor="#ffffff">
		<TD bgColor="#49AAE1" height=2></TD>
		</TR>
	</TBODY>
</TABLE>
<br>





<!-- 일별 방문자 통계 -->
<table height="250" width=100%>
<tr>
	<td>
		<table width=100% height="250" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="center">
					<font color="#FF9900"><B>[ <?=$year?> 년 <?=$month?> 월 <?=$day?>일 CPA방문자 REFERER 확인 ]</font>
				</td>
			</tr>
			<tr>
				<td height=10></td>
			</tr>
			<tr>
				<td align="center">
					<table border=0 cellpadding=10 width=100% style="border-collapse:collapse; border: 0px solid #000000;">
							<tr>
								<td nowrap style="border: 1px solid #000000;">REFERER</td>
								<td nowrap style="border: 1px solid #000000;">카운트</td>
							</tr>
					<?


						if($select_pcode){
							$where_pcode=" and pcode='$select_pcode'";
						}

						// 해당 카운트 얻기

						$query = "SELECT count(*) as referer_cnt,referer from cpa_stat where logdate like '$year_mm-$day%' $where_pcode group by referer order by referer_cnt desc"; 

						//echo $query;

						$r=$db->query($query);


						//echo $last_date;

						while($data=mysql_fetch_array($r)){
							?>
							<tr>
								<td nowrap style="border: 1px solid #000000;"><?=$data[referer]?></td>
								<td nowrap style="border: 1px solid #000000;"><?=$data[referer_cnt]?></td>
							</tr>
							
							<?

						}

					
					?>
					
					</table>

				</td>
			</tr>
		</table>
	</td>
</tr>
</table>



</DIV>

<?
include '../footer.admin.php';
?>