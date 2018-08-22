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
						SELECT replace(left(logdate,7),'$year-','') as  totmonth FROM t_count_anomy where logdate like '%$year%'
					)a GROUP BY age_range ORDER BY age_range asc "; 
	$sql	= mysql_query($query, $connect);

	$query2 = "	SELECT count(*) as su, url2  
					FROM t_count_preurl
					WHERE logdate like '$year_mm%'
					group by url2 order by su desc "; 
	$sql2	= mysql_query($query2, $connect);

	$cquery = "	select count(*) as urlsu , url from t_count_preurl
					where logdate like '$year_mm%' and url !=''
					group by  url order by urlsu desc";
	$csql	= mysql_query($cquery, $connect);
	
	//mysql_close($connect);



// 헤더

?>


<br>
<table  width="100%" align="center"  border="0" cellspacing="0" cellpadding="0" >
<tr>
	<td colspan=2 align=left><img src="/adminmode_/images/icon2.gif" width="4" height="5" align="absmiddle"><span class="subtitle"><B> 접속현황</span></td>
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


<select name=select_year onchange="location.href='<?=$_SERVER[PHP_SELF]?>?selected_year='+this.value;">
<option value="">-----<?=$year?>년 월별 통계 보기-----</option>
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



<table height="250" width=100%>
	<td>
		<table width=100% height="250" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="center">
					<font color="#FF9900"><B>[ <?=$year?>년 월방문 ]</font>
				</td>
			</tr>
			<tr>
				<td height=10></td>
			</tr>
			<tr>
				<td align="center">
					<table width="100%"  height="200" border="0" cellspacing="3" cellpadding="3" bgcolor="#CCCCCC">
						<Td>
							<table width="100%"  height="200" border="0" cellspacing="2" cellpadding="2" bgcolor="#FFFFFF">
								<tr>
									<? 
										$k =1;

										//for($i=0;$i<$rs=mysql_fetch_array($sql);$i++) {
										//	
										//}
										$monthly_tot=$db->r2a($sql,'age_range','range_count'); // 월별 합계

										for($i=1;$i<=12;$i++) {
											$mm=str_pad($i,2,'0',STR_PAD_LEFT);
											//echo $mm;
											if(!$monthly_tot[$mm]){
												$monthly_tot[$mm]=0;
											}

											//print_r($rs);
									?>
									<td width="50"  align="center" valign="bottom">
										<table width="7" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td background="/adminmode_/images/detail_bar_bg1.gif" 
													<? if($monthly_tot[$mm]) {?>
													height="<?=$monthly_tot[$mm]/100?>" 
													<? } else {?> 
													height="0" 
													<? }?>
												></td><?=$monthly_tot[$mm]?>
											</tr>
											<tr>
												<td><img src="/adminmode_/images/detail_bar_gra.gif" height="12" width="7" />
												</td>
											</tr>
										</table>
									</td>
									<? $k++;}?>
								</tr>
								<tr>	
									<? for($i=1;$i<=12;$i++) {?>
									<td width="50"  align="center"><span><?=$i?>월</span><br/>
									</td>
									<? }?>
								</tr>
							</table>
						</td>
					</table>
				</td>
			</tr>
		</table>
	</td>	
</table>
<br>

<!-- 일별 방문자 통계 -->
<table height="250" width=100%>
<tr>
	<td>
		<table width=100% height="250" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="center">
					<font color="#FF9900"><B>[ <?=$year?> 년 <?=$month?> 월 일별 방문자 ]</font>
				</td>
			</tr>
			<tr>
				<td height=10></td>
			</tr>
			<tr>
				<td align="center">
					<table border=0 width=100%><tr>
					<?


						
						// 일별 카운트 얻기
						$query = "SELECT count(*) as range_count, a.totday AS age_range FROM (	SELECT replace(left(logdate,10),'$year_mm-','') as  totday FROM t_count_anomy where logdate like '$year_mm-%')a GROUP BY age_range ORDER BY age_range asc "; 

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
							<td>
								<table border=0 cellpadding=0 cellspacing=0>
								<tr><td nowrap><?=$i?>일</td></tr>
								<tr><td nowrap><?=intval($day_data[$i][range_count])?>명</td></tr>
								
								</table>
							</td>
							
							<?

						}

					
					?>
					
					</tr></table>

				</td>
			</tr>
		</table>
	</td>
</tr>
</table>



<!-- 키워드 통계 -->
<table width=100% >
	<tr>
		<td valign="top">
			<table  border="0" cellspacing="0" cellpadding="0"  height="300" width=100%>
				<tr>
					<td align="center">
						<font color="#FF9900"><B>[ <?=$month?>월 검색어 ]</font>
					</td>
				</tr>
				<tr>
					<td height=10></td>
				</tr>
				<tr>
					<td align="center">
						<table width="100%"  height="380" border="0" cellspacing="3" cellpadding="3" bgcolor="#CCCCCC">
							<td>
								<table   height="380"  border="0" cellspacing="2" cellpadding="2" bgcolor="#FFFFFF" align="left">
									<tr>
										<? 
											for($i=0;$i<$rs2=mysql_fetch_array($sql2);$i++) {
										?>
										<td width="50" height="300" align="center" valign="bottom">
											<table width="7" border="0" cellspacing="0" cellpadding="0">
												<tr>
													<td background="/adminmode_/images/detail_bar_bg1.gif" height="<?=$rs2[0]/5?>" 
													><?=$rs2[0]?></td><?=$rs2[1]?>
												</tr>
												<tr>
													<td><img src="/adminmode_/images/detail_bar_gra.gif" height="12" width="7" />
													</td>
												</tr>
											</table>
										</td>
										<? } ?>
									</tr>
								</table>
							</td>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>

</table>


<table  border="0" cellspacing="0" cellpadding="0"  width=100% height="380">
	<tr>
		<td align="center">
			<font color="#FF9900"><B>[ <?=$month?>월 방문전 사이트 ]</font>
		</td>
	</tr>
	<tr>
		<td height=10></td>
	</tr>
	<tr>
		<td valign="top">
			<table width="100%" height="380" border="0" cellspacing="3" cellpadding="3" bgcolor="#CCCCCC">
				<td valign="top">
					<table width="300"   border="0" cellspacing="2" cellpadding="2" bgcolor="#FFFFFF" >
						<? 
							for($i=0;$i<$rs3=mysql_fetch_array($csql);$i++) {
						?>
						<tr>
							<td><?=$rs3[1]?></td><td align='right'><?=$rs3[0]?></td>
						</tr>
						<? } ?>
					</table>
				</td>
			</table>
		</td>
	</tr>
</table>


</div>

<?
include '../footer.admin.php';
?>