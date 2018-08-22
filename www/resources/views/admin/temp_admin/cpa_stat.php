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

/*
	$query = "SELECT count( * ) as range_count, (
						CASE
							WHEN a.totmonth ='01' THEN '01'
							WHEN a.totmonth ='02' THEN '02'
							WHEN a.totmonth ='03' THEN '03'
							WHEN a.totmonth ='04' THEN '04'
							WHEN a.totmonth ='05' THEN '05'
							WHEN a.totmonth ='06' THEN '06'
							WHEN a.totmonth ='07' THEN '07'
							WHEN a.totmonth ='08' THEN '08'
							WHEN a.totmonth ='09' THEN '09'
							WHEN a.totmonth ='10' THEN '10'
							WHEN a.totmonth ='11' THEN '11'
							WHEN a.totmonth ='12' THEN '12'
							END ) AS age_range
					FROM (
						SELECT replace(left(logdate,7),'$year-','') as  totmonth FROM cpa_stat where logdate like '%$year%'
					)a GROUP BY age_range ORDER BY age_range asc "; 
	$sql	= mysql_query($query, $connect);

	*/


	//mysql_close($connect);



// 헤더

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

<?=make_selectbox('select_yyyy',$_CFG[select_yyyy],$year,'연도선택','',"location.href='$phpself?select_yyyy='+this.value;")?>

<select id=select_year name=select_year onchange="location.href='<?=$_SERVER[PHP_SELF]?>?selected_year='+this.value;">
<option value="">-----<?=$year?>년 달 선택-----</option>
<?
	$yyyy=date('Y');

	for($i=1;$i<=12;$i++){
		$mm=str_pad($i,2,'0',STR_PAD_LEFT);
		?>
		<option value="<?=$yyyy?>-<?=$mm?>" <?=("$yyyy-$mm"==$selected_year?' selected':'')?>><?=$mm?> 월</option>
		<?
	}
?>
</select>

<?=make_selectbox('select_pcode',$db->r2a($db->query("select distinct(pcode) from cpa_stat"),'pcode','pcode'),$select_pcode,'파트너코드 전체','',"location.href='$phpself?select_pcode='+this.value+'&select_year='+document.getElementById('select_year').value;")?>




<!-- 일별 방문자 통계 -->
<table height="250" width=100%>
<tr>
	<td>
		<table width=100% height="250" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="center">
					<font color="#FF9900"><B>[ <?=$year?> 년 <?=$month?> 월 일별 CPA방문자 ]</font>
				</td>
			</tr>
			<tr>
				<td height=10></td>
			</tr>
			<tr>
				<td align="center">
					<table border=0 cellpadding=10 width=100% style="border-collapse:collapse; border: 0px solid #000000;">
							<tr>
							
								<td nowrap style="border: 1px solid #000000;">날짜</td>
								<td nowrap style="border: 1px solid #000000;">방문자</td>
								<td nowrap style="border: 1px solid #000000;">신청자</td>
								<td nowrap style="border: 1px solid #000000;">구매전환율</td>
								
								
							</tr>
					<?


						if($select_pcode){
							$where_pcode="and pcode='$select_pcode'";
						}
						// 일별 카운트 얻기

						$query = "SELECT count(*) as range_count, a.totday AS age_range,sum(buy) as buy_count FROM (	SELECT replace(left(logdate,10),'$year_mm-','') as  totday,buy FROM cpa_stat where logdate like '$year_mm-%' $where_pcode)a GROUP BY age_range ORDER BY age_range asc "; 

						//echo $query;

						$r=$db->query($query);
						$day_data=$db->r2a($r,'age_range');

						//print_r($day_data);
						
						// 이달의 마지막날을 구한다.
						$last_date=1;
						// 이번달의 마지막 날을 $date에 저장한다.
						while (checkdate($month,$last_date,$year)){
							$last_date++;
						}
						$last_date-=1;

						//echo $last_date;

						for($i=1;$i<=$last_date;$i++){
							?>
							<tr>
							
								<td nowrap style="border: 1px solid #000000;"><a href="cpa_referer.php?select_year=<?=$year_mm?>&day=<?=$i?>&select_pcode=<?=$select_pcode?>"><?=$i?>일</a></td>
								<td nowrap style="border: 1px solid #000000;"><?=intval($day_data[$i][range_count])?>명</td>
								<td nowrap style="border: 1px solid #000000;"><?=intval($day_data[$i][buy_count])?>명</td>
								<td nowrap style="border: 1px solid #000000;"><?
									if($day_data[$i][range_count]){
										echo floor(($day_data[$i][buy_count]/$day_data[$i][range_count])*100);
									}
									else {
										echo 0;
									}
								?>%</td>
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