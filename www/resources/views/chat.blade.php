@extends('layouts.chatapp')

@section('content')
    <!-- Page Header -->


    <!-- Main Content -->

    <script src="https://use.fontawesome.com/45e03a14ce.js"></script>
    <div class="col-sm-100 main_section ">
        <div class="container" width="100%">
            <div class="chat_container">
                <div class="col-sm-50 message_section">
                    <div class="row">

                        <div class="chat_area">
                                <div class="panel panel-default" style="border-radius:0;">
                                    {{-- 사용자정보 --}}
                                    <div class="panel-heading" style="background:#a9bdce;border:0;border-radius:0;font-weight:bold;color:#4b4d66;">
                                        <p>Room name</p>
                                        <a href="#" id="closechatroom_">[x]</a>
                                        <?php
                                        $cuser_id = 0;
                                        if(Auth::check()){ // 로그인 유무 체크
                                            $cuser_id = Auth::id();
                                        }
                                        ?></div>

                                    <div class="panel-body" style="padding:0;">
                                        {{-- 대화내용 --}}
                                        <div id="chatRoomBoxCon"class="panel panel-default" style="position:relative;background:#b2c7d9;border:0;border-radius:0;margin-bottom:0;overflow:auto">
                                            <div id="chatRoomBox" class="panel-body" style="min-height:100px;height:500px;max-height:500px;overflow-y:scroll;overflow-x:hidden;padding:10px;">
                                                <div id="chatRoom" style="width:100%; color:#000;font-size:13px !important;">
                                                    <!-- // 차후 추가하기
                                                    <div class="row">
                                                        <div style="width:100%;text-align:center;">- 2018년 1월 20일 토요일 -</div>
                                                    </div>
                                                    -->
                                                </div>
                                            </div>
                                        </div>

                                        {{-- 대화입력 --}}
                                        <div>
                                            <table style="width:100%;">
                                                <tr>
                                                    <td style=""><textarea id="myMsg" value="" autofocus="autofocus" class="form-control" style="resize:none;border-radius:0;border:none;overflow: auto;outline: none;-webkit-box-shadow: none;-moz-box-shadow: none;box-shadow: none;display:inline-block;width:100%;background:#fff;overflow-y:hidden;"></textarea></td>
                                                    <td style="width:54px;text-align:right;"><button type="submit" id="sendMyMsg" class="btn btn-primary" style="display:inline-block; margin-right:10px;background:#ffec42;border-color:#e8d78f;color:#d6b03d;outline: none;">send</button></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>



                        </div><!--chat_area-->

            </div>
        </div>
    </div>


            <button type="button" id="room" style="display:none;opacity:1;">room</button>
            {{--<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>--}}
            <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
            <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
            <script src="http://<?php echo $_SERVER['SERVER_NAME']; ?>:8080/socket.io/socket.io.js"></script>
            <script>
            var room_num = '1';

            function joyScrollDown(){ // 내용 추가후 스크롤 다운
                $('#chatRoomBox').animate({scrollTop: $('#chatRoomBox').prop("scrollHeight")}, 500);
            }
            $(document).ready(function(){
                // open a socket connection
                var socket = io.connect('http://<?php echo $_SERVER['SERVER_NAME']; ?>:8080', {'sync disconnect on unload' : true});
                // var socket = new io.connect('http://192.168.2.32:8080', {
                    // 'reconnection': true,
                    // 'reconnectionDelay': 1000,
                    // 'reconnectionDelayMax' : 5000,
                    // 'reconnectionAttempts': 5
                // });

                // 채팅 초기셋 {
                    socket.on('initRoomSet', function(data){
                        for(i=0; i<data.length; i++){
                            // data[i].t = (data[i].t).replace('PM', '오후');
                            // data[i].t = (data[i].t).replace('AM', '오전');
                            if(data[i].i == '<?=$cuser_id?>'){
                                $('#chatRoom').prepend("<div class=\"me\" style=\"text-align:right;margin-top:7px;\"><div style=\"display:inline-block;min-width:60px;font-size:11px;line-height: 190%;text-align: right;padding-right: 7px;vertical-align: bottom;\">"+ data[i].t +"</div><div style=\"display:inline-block;max-width:77%;border-radius:3px;padding:7px; background:#ffeb33;text-align:left;\">" + data[i].c + "</div></div>");
                            }
                            else{
                                $('#chatRoom').prepend("<div class=\"you\"><div style=\"clear:both;font-weight:bold;margin-top:7px;\">" + data[i].n + "</div><div style=\"display:inline-block;max-width:77%; padding:7px; background:#fff; border-radius:3px;\">" + data[i].c + "</div><div style=\"display:inline-block;min-width:60px;font-size:11px;padding-left:7px;vertical-align: bottom;\">"+ data[i].t +"</div></div>");
                            }
                        }
                        // var objDiv = document.getElementById("chatRoomBox");
                        // objDiv.scrollTop = objDiv.scrollHeight;

                        $('#chatRoomBox').animate({scrollTop: $('#chatRoomBox').prop("scrollHeight")}, 0); // 스크롤 다운
                        $('#chatRoomBoxCon').animate({scrollTop: $('#chatRoomBox').prop("scrollHeight")}, 0); // 스크롤 다운


                        // timer = setInterval($('#chatRoomBox').animate({scrollTop: $('#chatRoomBox').prop("scrollHeight")}, 0), 3000);
                    });
                    $('#room').click(function(){
                        socket.emit('initRoomAsk', {'r' : room_num});
                    });
                    $('#room').trigger('click');
                // 채팅 초기셋 }

                // 채팅 내용 추가 {
                    // 소켓 :  : getMyMsg
                    socket.on('getMsg', function(data){
                        // 채팅내용 추가
                        if(data.i == '<?=$cuser_id?>'){
                            $('#chatRoom').append("<div class=\"me\" style=\"text-align:right;margin-top:7px;\"><div style=\"display:inline-block;min-width:60px;font-size:11px;line-height: 190%;text-align: right;padding-right: 7px;vertical-align: bottom;\">"+ data.t +"</div><div style=\"display:inline-block;max-width:77%;border-radius:3px;padding:7px; background:#ffeb33;text-align:left;\">" + data.m + "</div></div>");
                        }else{
                            $('#chatRoom').append("<div class=\"you\"><div style=\"clear:both;font-weight:bold;margin-top:7px;\">" + data.n + "</div><div style=\"display:inline-block;max-width:77%; padding:7px; background:#fff; border-radius:3px;\">" + data.m + "</div><div style=\"display:inline-block;min-width:60px;font-size:11px;padding-left:7px;vertical-align: bottom;\">"+ data.t +"</div></div>");
                        }
                        joyScrollDown(); // 스크롤 다운
                    });

                    // 전송버튼 클릭, 엔터키 입력
                    $('#sendMyMsg').click(function(){ sendMsg(); });
                    $('#myMsg').keypress(function (e){
                        var key = e.which;
                        if(key == 13 && !e.shiftKey){ sendMsg(); return false; } // enter only not enter+shift
                    });

                    // 소켓 : 내 메시지 전달 : sendMyMsg
                    function sendMsg(){
                        //alert(1);
                        if('<?=$cuser_id?>'==0){
                            alert('Please login first');
                            return;
                        }
                        var myMsg = $('#myMsg').val();	// 채팅내용
                        $('#myMsg').val('');			// 채팅입력후 입력창 빈칸으로 만든다.
                        $('#myMsg').focus();			// 채팅입력창으로 커서 이동

                        if(myMsg==''){return;} 			 // 채팅 글자가 없으면 추가 안한다.
                        socket.emit('sendMyMsg', {'r' : room_num,
                                                  't' : 'public',
                                                  'i' : '<?=$cuser_id?>',
                                                  'm' : myMsg}); // 채팅통신
                    }

                // 채팅 내용 추가 }

                /*
                // // when user connect, store the user id and name
                // socket.on('connect', function (user) {
                    // socket.emit('join', {id: "<?php Auth::id(); ?>", name: "<?php Auth::id(); ?>"});
                // });
                */
            });
            </script>
            {{-- 채팅 모듈 END --}}

        </div>
    </div>

@endsection
