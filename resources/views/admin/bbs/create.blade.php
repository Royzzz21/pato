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
                          게시판 생성하기
                      </h2>
                  </div>
                  <table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="037bd9" bgcolor="037bd9" style="border:1px solid #dddddd;">
                      <tr>
                          <td><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">



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
                                                                  <strong>게시판 이름</strong>
                                                              </td>
                                                              <td height="35" style="padding-left:5px;">
                                                                  <input type='text' id='bbs_name'
                                                                         name='bbs_name'
                                                                         value='<? if(isset($rows)){echo $rows->bbs_name; } ?>'
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
                                                                  <strong>게시판 ID</strong>
                                                              </td>
                                                              <td height="35" style="padding-left:5px;">
                                                                  <input type='text' id='cafe_aid'
                                                                         name='bbs_aid'
                                                                         value='<? if(isset($rows)){echo $rows->bbs_aid; } ?>'
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