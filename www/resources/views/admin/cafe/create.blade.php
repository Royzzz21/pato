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

                  function fwrite_check(is_onsubmit){
                      //alert('a');
                      var f=document.fwrite;

                      // 전송을 해도 되나 체크
                      // 값이 있으면 전송
                      if(f.cafe_name.value==''){
                          alert('카페명을 입력해주세요.');
                          //editor_wr_ok();
                          return false;
                      }

                      if(f.cafe_aid.value==''){
                          alert('카페ID를 입력해주세요.');
                          //editor_wr_ok();
                          return false;
                      }

                      //f.wr_content.value = myeditor.outputBodyHTML();
                      //alert(f.wr_content.value);

                      //alert(f.wr_content.value);
                      //f.wr_content.value=geditor.get_content();

                      oEditors.getById["wr_content"].exec("UPDATE_CONTENTS_FIELD", []); // 에디터의 내용이 textarea에 적용됩니다.

                      if (is_onsubmit) {
                          return true;
                      }

                      f.submit();

                  }


              </script>

              <form action="<?=$req_path2?>/create_ok" id="fwrite" name="fwrite" onsubmit="return fwrite_check(1)" enctype="multipart/form-data" method="POST">
                  {{ csrf_field() }}
                  <input type='hidden' id='mode' name='mode'  value='<? if(isset($mode)){echo $mode;} ?>' />
                  <input type='hidden' id='idx' name='idx'  value='<? if(isset($idx)){echo $idx;} ?>' />

                  <div>
                      <h2>
                          카페 생성하기
                      </h2>
                  </div>
                  <table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="037bd9" bgcolor="037bd9" style="border:1px solid #dddddd;">
                      <tr>
                          <td><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">


                                  <?
                                          // qna 게시판이라면 비공개 기능을 사용한다
                                  if(0){
                                  ?>
                                          <!-- 암호 -->
                                  <tr>
                                      <td>

                                          <!-- 상태/타입/제목 -->
                                          <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#a5c9dc" bgcolor="#C3C3C3">
                                              <tr>
                                                  <td>
                                                      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
                                                          <tr>
                                                              <td width="100" height="35" align="center" bgcolor="#EFEFEF"><strong>공개여부</strong>
                                                              </td>
                                                              <td height="35" colspan="" style="padding-left:5px;">
                                                                  <div style="float:left;"><input type='checkbox' id='' name='is_secret' value='1' <? if(isset($rows)){echo ($rows->is_secret?'checked':'');} ?>>비공개</div>
                                                              </td>
                                                          </tr>
                                                      </table>
                                                  </td>
                                              </tr>
                                          </table>

                                      </td>
                                  </tr>
                                  <?
                                  }
                                  ?>


                                  <input type=hidden name=selected_cat value="<?

                                  if(isset($cat_id)){
                                      echo $cat_id;
                                  }
                                  else {
                                    if(isset($rows)){
                                        echo $rows->cat;
                                    }
                                  }

                                  ?>">

                                  <tr>
                                      <td>

                                          <!-- 상태/타입/제목 -->
                                          <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#a5c9dc"
                                                 bgcolor="#C3C3C3">
                                              <tr>
                                                  <td>

                                                      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"
                                                             bgcolor="#FFFFFF">

                                                          <tr>
                                                              <td width="100" height="35" align="center" bgcolor="#EFEFEF" style="font-size:12px;">
                                                                  <strong>카페이름</strong>
                                                              </td>
                                                              <td height="35" style="padding-left:5px;">
                                                                  <input type='text' id='cafe_name'
                                                                         name='cafe_name'
                                                                         value='<? if(isset($rows)){echo $rows->bbs_subject; } ?>'
                                                                         onkeydown="if(event.keycode==9)alert('tab');"
                                                                         style="width:80%;height:25px;line-height:25px;border:1px solid #bebebe;outline-style:none;padding:0;margin:0"
                                                                         AUTOCOMPLETE="OFF"/>
                                                              </td>
                                                          </tr>



                                                      </table>
                                                  </td>
                                              </tr>
                                          </table>

                                      </td>
                                  </tr>


                                  <tr>
                                      <td>

                                          <!-- 상태/타입/제목 -->
                                          <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#a5c9dc"
                                                 bgcolor="#C3C3C3">
                                              <tr>
                                                  <td>

                                                      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"
                                                             bgcolor="#FFFFFF">

                                                          <tr>
                                                              <td width="100" height="35" align="center" bgcolor="#EFEFEF" style="font-size:12px;">
                                                                  <strong>카페 ID</strong>
                                                              </td>
                                                              <td height="35" style="padding-left:5px;">
                                                                  <input type='text' id='cafe_aid'
                                                                         name='cafe_aid'
                                                                         value='<? if(isset($rows)){echo $rows->bbs_subject; } ?>'
                                                                         onkeydown="if(event.keycode==9)alert('tab');"
                                                                         style="width:80%;height:25px;line-height:25px;border:1px solid #bebebe;outline-style:none;padding:0;margin:0"
                                                                         AUTOCOMPLETE="OFF"/>
                                                              </td>
                                                          </tr>



                                                      </table>
                                                  </td>
                                              </tr>
                                          </table>

                                      </td>
                                  </tr>

                                  <tr>
                                      <td height="77">

                                          <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#a5c9dc" bgcolor="#a5c9dc">
                                              <tr>
                                                  <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">

                                                          <tr bgcolor="effaff">
                                                              <td width="100%" align="center" bgcolor="#FFFFFF">


							<textarea id="wr_content" name="wr_content" class="gray_txt"
                                      style="FONT-SIZE: 12px;background-color:#FFFFFF;min-width:260px;height:300px;width:100%;"
                                      rows=15 itemname="내용"
                                      required><? if(isset($rows)){echo $rows->bbs_content;} ?></textarea>


                                                                  <script type="text/javascript" src="/nse/js/HuskyEZCreator.js"
                                                                          charset="utf-8"></script>

                                                                  <script type="text/javascript">
                                                                      var oEditors = [];
                                                                      nhn.husky.EZCreator.createInIFrame({
                                                                          oAppRef: oEditors,
                                                                          elPlaceHolder: "wr_content",
                                                                          sSkinURI: "/nse/SmartEditor2Skin.html",
                                                                          fCreator: "createSEditor2"
                                                                      });
                                                                      function submitContents(elClickedObj) {
                                                                          //oEditors.getById["wr_content"].exec("UPDATE_CONTENTS_FIELD", []); // 에디터의 내용이 textarea에 적용됩니다.
                                                                          // 에디터의 내용에 대한 값 검증은 이곳에서 document.getElementById("ir1").value를 이용해서 처리하면 됩니다.

                                                                          try {
                                                                              //elClickedObj.form.submit();
                                                                          } catch (e) {
                                                                          }
                                                                      }
                                                                  </script>


                                                              </td>
                                                          </tr>
                                                      </table></td>
                                              </tr>
                                          </table>
                                      </td>
                                  </tr>


                                  <!-- 파일추가 -->
                                  <tr>
                                      <td>

                                          <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#a5c9dc" bgcolor="#C3C3C3">

                                              <?
                                              if($edit_mode){
                                              ?>
                                              <tr>
                                                  <td>
                                                      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
                                                          <tr>
                                                              <td width="100" height="35" align="center" bgcolor="effaff"><strong>업로드된 파일</strong></td>
                                                              <td  height="35" style="padding-left:5px;">
                                                                  <?

                                                                      /*
                                                                  $r = $db->query("select * from file_{$cafe_id}_{$id} where doc_idx='$rows[idx]'");
                                                                  //while($file_data = mysql_fetch_array($r)){

                                                                  foreach($r as $arr_idx => $file_data){
                                                                  ?>
                                                                  <div>
                                                                      <a href="/upload/<?= $file_data[f_name] ?>"
                                                                         target="_blank"><?= $file_data[orig_name] ?></a>&nbsp;
                                                                      <input type=checkbox name="deletefile[]"
                                                                             value="<?= $file_data[idx] ?>"
                                                                             id="uploaded_<?= $arr_idx ?>"><label
                                                                              for="uploaded_<?= $arr_idx ?>">삭제</label>
                                                                  </div>
                                                                  <?
                                                                  }


*/

                                                                  ?>
                                                              </td>
                                                          </tr>

                                                      </table>
                                                  </td>
                                              </tr>
                                              <? } ?>

                                              <tr>
                                                  <td>

                                                      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
                                                          <tr>
                                                              <td width="100" height="35" align="center" valign=top bgcolor="#EFEFEF">
                                                                  <div style="padding:10px;font-size:12px;">
                                                                      <strong>file <span class="button small"><a onclick="more_file();">[add]</a></span></strong>
                                                                  </div>
                                                              </td>
                                                              <td height="35" style="padding-left:5px;">
                                                                  <div id='upload_div'>
                                                                      <input type=file name="uploadfile[]">
                                                                  </div>
                                                              </td>
                                                          </tr>
                                                      </table>

                                                      <script>
                                                          $(document).ready(function(){

                                                              more_file();
                                                              more_file();
                                                              more_file();
                                                              more_file();
                                                              more_file();

                                                          });

                                                      </script>

                                                  </td>
                                              </tr>
                                          </table>

                                      </td>
                                  </tr>

                                  <!-- 버튼-->

                                  <tr>
                                      <td style="padding-top:15px;">

                                          <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                                              <tr>
                                                  <td align="center">


                                                      <a href="javascript:void(fwrite_check());" class="normal-btn small1" style=""><em>생성하기</em></a>

                                                      &nbsp;&nbsp;

                                                      <a href="javascript:location.href='<?=$req_path2?>/list?page=<?=$page?>';" class="normal-btn small1" style=""><em>취소하기</em></a>



                                                  </td>
                                              </tr>
                                          </table></td>
                                  </tr>
                              </table></td>
                      </tr>
                  </table>

              </form>

              <script>
                  // 파일추가
                  function more_file(){
                      document.getElementById('upload_div').innerHTML+='<br><input type=file name="uploadfile[]">';
                  }
              </script>



              <div style="clear:both;"></div>
              <br />


          </div>




      </div>
  </div>  
</section>
@endsection