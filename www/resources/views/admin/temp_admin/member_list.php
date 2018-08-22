<?



include '../config.php';

$_CFG[left_menu_file]="$www_dir/admin/left.member.php";

// 인증체크
admin_login_check();

//debugreq();



// 상태 변경
if($a=='change_sts' && $user_idx){

	if(intval($curr_value)==0){
		$new_val='1';
	}
	else {
		$new_val='0';

	}

	$db->query("update member set ac_approved='$new_val' where ac_idx='$user_idx'");

}





//print_r($regions);
//exit;

// 테이블 지정
$tbl_prefix=$_CFG[table_prefix];
$tbl="member";


// 조건 검색
//$where="ac_type='100'";
$where="";



//---------------------------------------------------------------
// 검색조건은 페이지 이동시에 계속 넘겨야 하므로 미리 만들어둔다.
//$search_urlquery='';
$search_urlquery=make_array2urlquery($search);

// 검색 조건이 있으면 조건을 처리한다.
$search=$_REQUEST[search];
if($search){


	// 상태 검색
	if($search['ac_status']){
		$where.=($where?' and ':'');
		$where.="ac_status='$search[ac_status]'";
	}

	// 센터 검색
	if($search['center_idx']){
		$where.=($where?' and ':'where ');
		$where.="CENTER_SEQ='$search[center_idx]'";
	}

	// 계정타입이 지정되었으면..
	if($search['ac_type']){
		$where.=($where?' and ':'');
		$where.="ac_type='$search[ac_type]'";
	}

	// 승인여부 검색
	if($search['ac_approved']!=''){
		$where.=($where?' and ':'');
		$where.="ac_approved='$search[ac_approved]'";
	}

	// 검색어가 있으면..
	if($search[search_word]){
		
		// 검색어를 검색할 필드
		if($search[sel_search_type]=='id'){
			$where.=($where?' and ':'');
			$where.="ac_id like '%$search[search_word]%'";
		}
		else if($search[sel_search_type]=='name'){
			$where.=($where?' and ':'');
			$where.="ac_name like '%$search[search_word]%'";
		}
		else if($search[sel_search_type]=='gm_name'){
			$where.=($where?' and ':'');
			$where.="g.gm_name2 like '%$search[search_word]%'";
		}
	}
}

if($where){
	$where=' where '.$where;
}

//echo $where;

// 명령
if($a){
	if($a=='d'){
		$q="delete from na_student where ac_id='$ac_id'";
		//echo $q;exit;
		$db->query($q);
	}
	// 상태 변경
	if($a=='changeapprstatus' && $ac_idx){
		if($ac_approved=='0'){
			$new_val='1';
		}
		else {
			$new_val='0';
		}

		$db->query("update $tbl set ac_approved='$new_val' where idx='$ac_idx'");

	}
	/*
	else if($a=='change_sts'){
		//print_r($selected_idx);exit;

		foreach($selected_idx as $v){
			$db->query("update $tbl set user_status_cd='$sel_update_status' where ac_idx='$v'");
		}
	}
	*/


	if($a=='excel'){
		//exit; // 회원 엑셀 파일 내용체크할것
			
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set properties
		// Excel 문서 속성을 지정해주는 부분이다. 적당히 수정하면 된다.
		$objPHPExcel->getProperties()->setCreator("게시판")
									 ->setLastModifiedBy("")
									 ->setTitle("게시판 리스트 다운로드")
									 ->setSubject("게시판 데이타")
									 ->setDescription("")
									 ->setKeywords("")
									 ->setCategory("");


		


		// Add some data
		// Excel 파일의 각 셀의 타이틀을 정해준다.
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue("A1", "번호")
					->setCellValue("B1", "협의회")
					->setCellValue("C1", "센터/가맹점")
					->setCellValue("D1", "이름")
					->setCellValue("E1", "아이디")
					->setCellValue("F1", "암호")

					->setCellValue("G1", "성별")
					->setCellValue("H1", "학교")
					->setCellValue("I1", "학년")

					->setCellValue("J1", "주소")
					->setCellValue("K1", "우편번호")
					->setCellValue("L1", "연락처")
					->setCellValue("M1", "이메일")

					->setCellValue("N1", "부모님")
					->setCellValue("O1", "부모님연락처")
					->setCellValue("P1", "부모님이메일")
					->setCellValue("Q1", "부모님직업")

					;

		$sheet = $objPHPExcel->getActiveSheet();
		$sheet->getColumnDimension('A')->setWidth(5);
		$sheet->getColumnDimension('B')->setWidth(30);
		$sheet->getColumnDimension('C')->setWidth(30);
		$sheet->getColumnDimension('D')->setWidth(10);
		$sheet->getColumnDimension('E')->setWidth(25);
		$sheet->getColumnDimension('F')->setWidth(15);

		$sheet->getColumnDimension('G')->setWidth(5); //성별
		$sheet->getColumnDimension('H')->setWidth(20); //학교
		$sheet->getColumnDimension('I')->setWidth(5); // 학년

		$sheet->getColumnDimension('J')->setWidth(50); // 주소
		$sheet->getColumnDimension('K')->setWidth(15); // 우편
		$sheet->getColumnDimension('L')->setWidth(25); // 연락
		$sheet->getColumnDimension('M')->setWidth(25); // 이멜

		$sheet->getColumnDimension('N')->setWidth(10); // 부모님이름
		$sheet->getColumnDimension('O')->setWidth(25); // 연락처
		$sheet->getColumnDimension('P')->setWidth(25); // 이멜
		$sheet->getColumnDimension('Q')->setWidth(20); // 직업


		// for 문을 이용해 DB에서 가져온 데이터를 순차적으로 입력한다.
		// 변수 i의 값은 2부터 시작하도록 해야한다.
		/*
		$no=0;
		for ($i=2; $row=mysql_fetch_array($result); $i++){    
			$no++;

			// Add some data
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("A$i", "$no")
						->setCellValue("B$i", "$row[bbs_subject]")
						->setCellValue("C$i", "$row[bbs_writer]")
						->setCellValue("D$i", date('Y-m-d',$row[bbs_regtt]))


				;
		}*/


		$gm_list=$db->r2a($db->query("select * from na_student where ac_type='10' order by gm_name asc"),'gm_code','gm_name');


		$r=$db->query("select * from $tbl $where");
		if($sel_type=='tmd_member' || $sel_type=='gm'){

			//while($member_data=mysql_fetch_array($r)){
			$no=0;
			for ($i=2; $member_data=mysql_fetch_array($r); $i++){   
				$no++;

				
				// Add some data
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue("A$i", "$no")
							->setCellValue("B$i",'')
							->setCellValue("C$i", ($member_data[gm_code]?($gm_list[$member_data[gm_code]]):'-'))
							->setCellValue("D$i", $member_data['ac_name'])
							->setCellValue("E$i", $member_data['ac_id'])
							//->setCellValue("E$i", date('Y-m-d',$member_data[reg_time]))
							->setCellValue("F$i", $member_data[plain_pw])

							->setCellValue("G$i", $member_data[sex]) // 성별

							->setCellValue("H$i", $member_data[school_name].($member_data[school_type]?'['.$_CFG[school_type][$member_data[school_type]].']':''))
							->setCellValue("I$i", $member_data[school_grade])
							->setCellValue("J$i", $member_data[member_addr1].' '.$member_data[member_addr2])
							->setCellValue("K$i", $member_data[member_zip1].'-'.$member_data[member_zip2])
							->setCellValue("L$i", $member_data[phone])
							->setCellValue("M$i", $member_data[email])

							->setCellValue("N$i", $member_data[parent_name].($member_data[parent_name]?'('.$member_data[p_sex].')':''))
							->setCellValue("O$i", $member_data[parent_mobile])
							->setCellValue("P$i", $member_data[p_email])
							->setCellValue("Q$i", $_CFG[p_job][$member_data[p_job]])



				;

			}

		}
		else {
			//while($member_data=mysql_fetch_array($r)){
			$no=0;
			for ($i=2; $member_data=mysql_fetch_array($r); $i++){   
				$no++;


				if($member_data[center_idx]){
					$region_name=$regions[$centers[$member_data[center_idx]][region_idx]];
				}
				else {
					$region_name='';
				}

				// Add some data
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue("A$i", "$no")
							->setCellValue("B$i",$region_name)
							->setCellValue("C$i", ($member_data[center_idx]?arrayvalue(get_centerdata($member_data['center_idx']),'PLACE_NAME'):'미지정'))
							->setCellValue("D$i", $member_data['ac_name'])
							->setCellValue("E$i", $member_data['ac_id'])
							->setCellValue("F$i", date('Y-m-d',$member_data[reg_time]))
							->setCellValue("G$i", $member_data[school_name].($member_data[school_type]?'['.$_CFG[school_type][$member_data[school_type]].']':''))
							->setCellValue("H$i", $member_data[school_grade])
							->setCellValue("I$i", $member_data[phone])
							->setCellValue("J$i", $member_data[email])
							->setCellValue("K$i", $member_data[parent_name])
							->setCellValue("L$i", $member_data[parent_mobile])


				;

			}
		}

		// Rename sheet
		$objPHPExcel->getActiveSheet()->setTitle("게시판 데이타");

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);

		// 파일의 저장형식이 utf-8일 경우 한글파일 이름은 깨지므로 euc-kr로 변환해준다.
		//$filename = iconv("UTF-8", "EUC-KR", "excel");
		$filename='excel';

		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;


	}

}


// 헤더
include '../header.admin.php';


//if($where){
//	$where=' where '.$where;
//}

// 셀렉트조건에 맞는 게시물의 전체 카운트 구하기
//$tot=$db->fa1("select count(*) from member $where");
//echo $where;

//-------------------------
// 조건에 따라 검색
// 1. 카운트 구하고
$tot=$db->fa1("select count(*) from $tbl $where");

//echo "select count(*) from $tbl $where";

$lpp=$_CFG[lpp];
if($lpp <10 || $lpp >100){
	$lpp=10; // 한페이지에 몇줄 뿌리게 되어있는지...
}

//$lpp=100;

$ppp=$_CFG[ppp]; // 페이지링크를 몇개 뿌릴 것인지..
$page=$_REQUEST[page];

//페이지 변수가 없을땐 페이지는 1이다
if(!$page){
	$page=1;
}

$s=($page-1)*$lpp; // 시작번호
$no=$tot-$s; // 가상 번호

// 정렬키
$sortby=$_REQUEST[sortby];
// 정렬 방향
$sortorder=$_REQUEST[sortorder];

if(!$sortby){
	$sortby='idx';
}
if(!$sortorder){
	$sortorder='desc';
}

if($sortby){
	$orderby=" order by $sortby $sortorder";
}

// 페이지링크를 만든다.
$prefix="$PHP_SELF?"."&sel_type=$sel_type&sortorder=$sort_order&sortby=$sortby&$search_urlquery";
$pagelink=make_pagelink($tot,$page,$prefix,$lpp,$ppp);


// 2. 데이타 셀렉트
$q="select * from $tbl $where $orderby limit $s,$lpp";
//select * from na_student m LEFT JOIN gm g ON m.gm_code=g.gm_code where ac_type='10' and is_tmdedu='1' and gm_id='ZOO075';
//echo $q;
$r=$db->query($q);


?>





<div class="content_title">
<?=$sel_typename?> 리스트(<?=$tot?>)
</div>



<form method=post id='account_form' name='account_form'>
<input type=hidden name=a>
<table width=100% class="t_ex1" border=1 cellpadding=0 cellspacing=0 style="border:1px solid black;">
<col />
<col />
<col />
<tr>
	<th class=c ><input type='checkbox' id='all_check' name='all_check' onclick="check_all(this,'selected_idx[]');" style="cursor:pointer;" /></th>
	<th>아이디</th>
	<th>이름</th>
	<th>회원상태</th>
	<th>승인여부</th>
</tr>
<?
foreach($r as $row_idx=>$row_data){
	?>
	<tr>
		<td class=c style="text-align: center"><input type=checkbox name="selected_idx[]" value="<?=$row_data[idx]?>" style="cursor:pointer;"></td>
		<td class=c style="text-align: center">
			<a href="admin_editmem.php?&id=<?=($row_data[ac_id])?>"><?=($row_data[ac_id])?></a>
			<?$doc_data[ac_id]?>

		</td>
		<td class=c style="text-align: center">
			<?=($row_data[name])?>
		</td>

		<!-- 회원상태-->
		<td class="c stscolor<?=$row_data[ac_status]?>" style="text-align: center">
			<?=$_CFG[ac_status][$row_data[ac_status]]?>
			<?=($row_data[ac_status])?>
		</td>


		<!-- 승인 -->
		<td style="text-align:center;">
			<span class="button small white"><a href="javascript:change_sts('<?=$row_data[idx]?>','<?=$row_data[ac_approved]?>')"><?=(intval($row_data[ac_approved])==0?'<font color=red>미승인</font>':'승인')?></a></span>
		</td>



	<!--
		<td class=c>
			<span class="button small"><a href="member_add.php?sel_type=<?=$sel_type?>&a=mod_form&ac_id=<?=$row_data[ac_id]?>&then_go=<?=urlencode($_SERVER[REQUEST_URI])?>">편집</a></span>
			 <span class="button small"><a href="<?=$phpself?>?a=d&ac_id=<?=$row_data[ac_id]?>&then_go=<?=urlencode($_SERVER[REQUEST_URI])?>" onclick="return confirm('정말 삭제하시겠습니까?');"><span>삭제</span></a></span>

		</td>
		-->
	</tr>

	<?
}


?>


</table>

<div style="">
	<?=$pagelink?>
</div>

<script>
	// 상태변경
	function change_sts(answer_seq,curr_value){
		var ret;
		/*if(curr_value=='0'){
			ret=confirm('승인 하시겠습니까?');
		}
		else {
			ret=confirm('승인을 취소하시겠습니까?');
		}*/
		ret=true;

		if(curr_value=='1'){
			ret=confirm('승인을 취소하시겠습니까? 승인을 취소하면 로그인이 안됩니다.');
		}
		

		if(ret){
			location.href='<?=$phpself?>?a=change_sts&user_idx='+answer_seq+'&curr_value='+curr_value;
		}
	}
</script>


<!--
<table width=100% cellpadding=0 cellspacing=0>
	<tr>
		<td width=200>
			<span><?=make_selectbox('sel_update_status',$_CFG[user_status_cd],'')?></span>
			<span class="button blue"><a href="javascript:update_all_sts();">상태변경</a></span>
		</td>
		<td align=center><?=$pagelink?></td>
		<td align=right width=100><span class="button blue"><a href=member_add.php?sel_type=<?=$sel_type?>> 등록 </a></span></td>
	</tr>
</table>
<table width=800 class=t_paging style="border:0px solid black;">
	<tr><td align=left>
		
	</td></tr>
</table>
-->

</form>

<script>

// 선택항목 상태변경
function update_all_sts(){
	document.account_form.a.value='change_sts';
	if(confirm('상태를 변경할까요?')){
		document.account_form.submit();
	}
}



</script>


<?
// 푸터
include '../footer.admin.php';
?>