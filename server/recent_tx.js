{
    // DB Data
    var db_config = {
        host : 'db_server',
        port : 3306,
        user : 'pato',
        password: 'pato123',
        database : 'coindev'
    };
    var mysql = require('mysql');
    // var client = mysql.createConnection(db_config);



    var client=null;

    var db_connecting_ts=0;
    function handleDisconnect() {

        if(client!=null){
            client.end();
            client=null;
        }

        db_connecting_ts = new Date().getTime();
        console.log('handleDisconnect()');

        client = mysql.createConnection(db_config); // Recreate the connection, since
        // the old one cannot be reused.

        client.connect(function(err) {              // The server is either down
            if(err) {                                     // or restarting (takes a while sometimes).
                console.log('error when connecting to db:', err);
                setTimeout(handleDisconnect, 2000); // We introduce a delay before attempting to reconnect,
            }                                     // to avoid a hot loop, and to allow our node script to
            else {
                db_status=true;

            }
        });                                     // process asynchronous requests in the meantime.
                                                // If you're also serving http, display a 503 error.
        client.on('error', function(err) {
                db_status = false;
                console.log('db error', err);
                if (!err.fatal) {
                    console.log('db fatal error', err);
                    return;
                }

                //if(err.code === 'PROTOCOL_CONNECTION_LOST') { // Connection to the MySQL server is usually
                //    handleDisconnect();                         // lost due to either server restart, or a
                //} else {                                      // connnection idle timeout (the wait_timeout
                //    console.log('db unknown error', err);
                //    //throw err;                                  // server variable configures this)
                //}

                // 재접속 시도
                handleDisconnect();
            }
        );
    }
    handleDisconnect();



////////////////////////////////////////////////////////////////

    var request = require("request");

// 익스프레스
    var app     = require('express')();
    var express = require('express');
    var path    = require('path');
    var server  = require('http').Server(app);

// 소캣
    var io = require('socket.io')(server);

// 웹소캣
    var WebSocketServer = require('websocket').server;
    var WebSocketClient = require('websocket').client;
    var WebSocketFrame  = require('websocket').frame;
    var WebSocketRouter = require('websocket').router;
    var W3CWebSocket    = require('websocket').w3cwebsocket;

    server.listen(8880);

/////////////////////////////////////////////////////////////////
    var E = Number('1e' + 1);

/////////////////////////////////////////////////////////////////
// date
    var date = require('date-and-time');
    date.setLocales('en', {
        A: ['오전', '오후']
    });

    var now      = '';
    var now      = new Date();
    var thisTime = date.format(now, 'YYYY/MM/DD HH:mm:ss');

// 시간 포맷 변경
// result[0].chat_time = date.format(result[0].chat_time, 'A HH:mm');
// date.format(now, 'YYYY/MM/DD HH:mm:ss');

/////////////////////////////////////////////////////////////////
// 부동소수점 오류해결
    var add = require('sinful-math').add;

/////////////////////////////////////////////////////////////////
// 변수모음
    var t          = '';
    var q          = '';
    var krw        = 0;
    var tKrw       = 0;
    var wcoin      = '';
    var tot_amount = 0; // 수수료를 뺀 코인수
    var coin_num   = 0; // 주문총액
    var coin_fee   = 0; // 수수료

// 거래내역 상태 3가지
    var ready    = 'ready';
    var cancel   = 'cancel';
    var complete = 'complete';

    var tSum     = 0;
    var tDate    = '';
    var tBs      = '';
}

//// 요청 등록
//function insert_req(user_id,cmd,data){
//
//    var q  = "insert into `trade_req` (user_id, cmd, data, sts, created_at)";
//    q += " values(?, ?, ?, ?, now())";
//    var sts=0;
//    console.log('insert_req: q='+q);
//    client.query(q,[user_id, cmd, data, sts], function(error,result,fields) {
//        if (error){
//            //throw error;
//
//        }
//
//    });
//
//}

global.tx_lastIdx=0;

function get_recentTx(){

    console.log('check recent tx');
    // 0. 최근 거래 내역을 얻는다.
    var q  = 'select * from trade_tx order by idx desc limit 10';

    client.query(q ,[], function(error,result,fields) {
        if (error) {
            console.log('error : ' + error);
        }
        else {
            console.log('get trade_tx query ok.');

            if(result.length > 0) {
                global.tx_lastIdx = result[0].idx;
            }
            else {
                console.log('result.length=' + result.length);
            }
        }
    });

}





/////////////////////////////////////////////////////////////////
// socket.emit 단일
// io.sockets.emit 다수
var async = require('async');
io.on('connection', function (socket) {

    console.log('-------------------------\nclient connected.');
    //
    //// 가격정보
    //socket.on('res.init', function(data){
    //
    //
    //    var blank_pattern = /^\s+|\s+$/g;
    //    if( (data.i).replace( blank_pattern, '' ) == "" ){return;}
    //
    //    // 비로그인
    //    if(data.i == '' || data.i == 0){
    //        //console.log('not logged.');
    //        //return ;
    //    }
    //    // 로그인 되어있다면
    //    else {
    //        console.log('connected user_id='+data.i);
    //        socket.join('room-'+data.i);
    //    }
    //
    //    get_tradingInfo(socket,data); // 순서를 지정했다고 해서 리턴이 빠르다는 보장이 없다.
    //
    //    return ;
    //});
    //

});

var cnt=0;
var loop_lock=false; //  루프타는중


get_recentTx();
console.log('lastIdx: '+global.tx_lastIdx);

// 체결 테이블을 감시하고 새로운 체결이 있으면 모든 접속자에게 통지한다.
//
function check_loop() {
    //console.log('loop: '+(cnt++));

    var now_ts = new Date().getTime();
    //if(db_status==true && db_connecting_ts+10000 < now_ts && loop_lock==false){
    if(db_status==true && loop_lock==false){
        if (global.tx_lastIdx > 0) {
            // 락
            loop_lock=true;

            //console.log('lastIdx: ' + global.tx_lastIdx);
            //
            //console.log('get trade_tx');

            // 미통보 결과 셀렉트
            var q = 'select * from trade_tx where idx>? order by idx asc';
            client.query(q, [global.tx_lastIdx], function (error, result, fields) {
                if (error) {
                    loop_lock=false;
                    handleDisconnect();

                }
                else {

                    //// 결과물 처리
                    //for(var i=0;i<result.length;i++) {
                    //    var targ_user_id=result[i].user_id;
                    //    var res_idx=result[i].idx;
                    //
                    //    //// 보낼 데이타 정리
                    //    var res_data = {
                    //        order_idx: result[i].order_idx,
                    //        res_cmd: result[i].res_cmd,
                    //        data: result[i].data
                    //
                    //    };
                    //    console.log(res_data);
                    //
                    //    // 유저에게 통지
                    //    console.log("res.msg.ok send");
                    //
                    //
                    //
                    //}
                    if (result.length > 0) {
                        global.tx_lastIdx = result[result.length - 1].idx;
                        var data = {"data": result};
                        io.of('/').emit('tx.new_tx', data);
                    }

                    loop_lock=false;

                }
            });
        }
    }
    //var usersInRoom = io.of('/').in('room-1').clients;
    //console.log('sockets='+usersInRoom);


}
setInterval(check_loop, 500);