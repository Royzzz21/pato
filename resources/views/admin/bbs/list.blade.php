@extends('layouts.coin.app')
@section('content')

<section class="content">  
  <div class="clearfix">
     {{--<div class="aboutus-top">
            <h2 class="font-30">정보2</h2>
     </div>--}}
     <div>

     </div>
      <div>

          <div id='content_bbs' style="">



              <script>
                  function form_chk(){
                      var frm = document.search_form;
                      if(frm.search_box.value==""){
                          alert('검색어를 입력하세요');
                          return false;
                      }
                      else{
                          return true;
                      }

                  }
              </script>


              <style>
                  .col_header {
                      height:24px;
                      color:black;
                      font-weight:bold;
                      font-size:11px;
                      font-family:dotum;
                      background-color:white;
                      border-bottom:1px solid #dddddd;
                  }
              </style>


              <?php

                  $no=0;

    /*              $tot=$db->fa1("select count(*) from board_{$cafe_id}_{$bbs_id}"); //게시물의 전체 카운트



              $lpp=3; // 한페이지에 몇줄 뿌리게 되어있는지...
              $ppp=10;  // 페이지링크를 몇개 뿌릴 것인지..
              $page=$page;

              //$cafe_id = $_GET[cafe_id];
              //$bbs_id = $_GET[id];

              //페이지 변수가 없을땐 페이지는 1이다
              if(!$page){
                  $page=1;
              }

              $s=($page-1)*$lpp; // 시작번호
              $no=$tot-$s; // 가상 번호
              //
              $prefix="$req_path2?".($_REQUEST[cafe_id]?"cafe_id=$cafe_id":'')."&id=$bbs_id";

              // 페이지링크를 만든다.
              $pagelink=make_pagelink($tot,$page,$prefix,$lpp,$ppp);

              $query="select * from board_{$cafe_id}_{$bbs_id} order by idx desc limit $s,$lpp "; // s번 부터 차례대로 $lpp 만큼 가져와라..
              $r=$db->query($query);

              if($_REQUEST[cafe_id]){
                  $a_cafeid="cafe_id=$cafe_id";
              }
*/
              //while($rows = mysql_fetch_array($r)){
              ?>


              <table id='bbs_table' class="boardList cBlue" border=0 cellspacing='0' cellpadding='7' width="100%" style="border:1px solid #dddddd;margin-bottom:10px;">
                  <tr>
                      <td>

                          <table border=0 cellspacing='0' cellpadding='0' width=100% style="table-layout:fixed;border-top:1px solid #EFEFEF;">
                              <tr>
                                  <td>bbs 이름</td>
                                  <td>bbs aid</td>
                                  <td>cafe_id</td>
                                  <td>Date</td>
                              </tr>

                          <?
                          foreach($list_info as $row){
                             $reg_time=$row->ts; // 등록시각

                              ?>


                                  <tr>
                                      <td style="font-size:13px;background-color:#FFFFFF;padding:10px;font-weight:bold;"><a href="#<?=$req_path2?>?a=view&idx=<?=$row->id?>&page=<?=$page?>" style="color:#17002f"><?=$row->bbs_name?></a></td>
                                      <td style="font-size:12px;background-color:#FFFFFF;padding:10px;font-weight:bold;border-top:1px solid white;"><?=$row->bbs_aid;?></td>
                                      <td style="font-size:12px;background-color:#FFFFFF;padding:10px;font-weight:bold;border-top:1px solid white;"><?=$row->cafe_id;?></td>



                                      <td style="font-size:12px;background-color:#FFFFFF;padding:10px;font-weight:bold;border-top:1px solid white;"><?=date($_CFG['default_date_format'],$row->ts)?></td>
                                      <td>
                                          <? if($is_admin){ ?>
                                          <div  class="buttons" style="text-align:right;background-color:white;white-space: nowrap;">
                                              <a onclick="location.href='<?=$req_path2;?>?cafe_id=<?=$row->id?>/create&mode=modify&page=<?=$page?>'" title="수정하기" class="normal-btn small1" style=""><em>수정하기</em></a>
                                              <a onclick="if(confirm('정말 삭제할까요?')){location.href='<?=$req_path2;?>/delete?cafe_id=<?=$row->id?>&a=del_process&page=<?=$page?>';}" class="normal-btn small1" style=""><em>삭제하기</em></a>
                                          </div>
                                          <? } ?>
                                      </td>



                                  </tr>


                                  <!-- 첨부된 파일

                                  <tr>
                                      <td colspan="4" style="font-size:12px;background-color:#FFFFFF;padding:10px;font-weight:bold;border-top:1px solid white;">

                                          <?
    /*
                                          $imghtml='';

                                          $r=$db->query("select * from file_{$cafe_id}_{$bbs_id} where doc_idx='$row->idx'");
                                          foreach($r as $file_data){

                                              // 확장자가 이미지파일이라면
                                              if(is_image_ext(get_ext($file_data[orig_name]))){
                                                  // 이미지 출력용
                                                  $imghtml.="<div style=\"padding:5px;\"><img src=\"/bbs/download.php?id=$bbs_id&idx=$file_data[idx]\" /></div>";
                                              }
                                          }
    */
                                          ?>

                                      </td>
                                  </tr>
     -->



                              <?
                              $no=$no-1;
                          }
                          ?>

                          </table>
                      </td>
                  </tr>
              </table>




              <style>
                  @media (max-width: 2000px) {.divcont img {display:inline-block; width:auto\9 !important; /* ie8 */ width:auto !important; max-width:100%; min-width:100%; height:auto !important;}}

              </style>


              <table border=0 cellspacing='0' cellpadding='7' width=100% height=25 style="padding-top:20px;">

                  <tr>
                      <td width=100>
                          <!--<span class="button black small">-->
                          <a href='<?=$req_path2;?>/list?page=<?=$page?>' class="normal-btn small1" style="float:;"><em>목록</em></a>
                      </td>
                      <td colspan='3' align='center'>
                          <?
                          //echo $pagelink;
                          //echo $pg->getPage();
                          ?>
                      </td>
                      <td align='right' width=100>
                          <? if($is_admin){ ?>
                          <a href="<?= $req_path2 ?>/create" class="normal-btn small1" style="float:;"><em>새로 등록하기</em></a>
                          <? } ?>


                      </td>
                  </tr>
              </table>







                      <!-- 검색 -->
              <div id='search' align='center'>
                  <form id='search_form' name='search_form' method='GET' action=<?=$req_path2;?> onsubmit="return form_chk();">
                  <input type='hidden' id='page' name='page' value='<?=$page?>'/>
                  <input type='hidden' id='search_mode' name='search_mode' value='1'/>
                  <input type='text' id='search_box' name='search_box' style="height:24px;"/>

                  <a onclick="document.getElementById('search_form').submit();" class="normal-btn small1" style="float:;"><em>Search</em></a>
                  </form>
              </div>






                      <!-- 불펌방지 코드 시작 -->
              <script type="text/javascript">
                  var omitformtags=["input", "textarea", "select"]
                  omitformtags=omitformtags.join("|")
                  function disableselect(e){
                      if (omitformtags.indexOf(e.target.tagName.toLowerCase())==-1)
                          return false
                  }
                  function reEnable(){
                      return true
                  }
                  if (typeof document.onselectstart!="undefined")
                      document.onselectstart=new Function ("return false")
                  else{
                      document.onmousedown=disableselect
                      document.onmouseup=reEnable
                  }
              </script>
              <!-- 불펌방지 코드 종료 -->


          </div>




      </div>
  </div>  
</section>
@endsection