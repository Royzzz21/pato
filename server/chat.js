var app     = require('express')(),
    http    = require('http').Server(app),
    io      = require('socket.io')(http);

    // Redis   = require('ioredis'),
    // redis   = new Redis();

var port = 8080,
    users = nicknames = {};

http.listen(port, function() {
    console.log('Listening on *:' + port);
});

// DB Data
var db_config = {
    host : 'local.pato.net',
    port : 3306,
    user : 'pato',
    password: 'pato123',
    database : 'lsapp'
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



/////////////////////////////////////////////////////////////////
var date = require('date-and-time');
date.setLocales('en', {
    A: ['AM',  'PM']
});
/////////////////////////////////////////////////////////////////
var q='';
/////////////////////////////////////////////////////////////////
io.sockets.on('connection', function (socket) {
	var now      = new Date();
	var thisTime = date.format(now, 'YYYY/MM/DD HH:mm:ss');
	
	// 처음 페이지 로딩시 오더북 데이타 테이블에 추가
	socket.on('initRoomAsk', function(data){	
		// 채팅 DB 조회
		q  = 'select t1.rnum as r, t1.user_id as i, t1.chat as c, DATE_FORMAT(t1.chat_time, \'%p %H:%i\') as t, t2.name as n, t2.email as e ';
		q += 'from chat_room t1 ';
		q += 'inner join users t2 ';
		q += 'on t1.user_id = t2.id ';
		q += 'where t1.rnum = \'' + data.r + '\' ';
		q += 'order by t1.chat_time desc ';
		q += 'limit 500';
		
		client.query(q, function(error,result,fields){
			if(error){ console.log('error : ' + error); }
			else{
				// console.log(result);
				// socket.emit 단일
				// io.sockets.emit 다수
				socket.emit('initRoomSet',result);
			}
		});
	});
	
	// 내가 보내는 메시지
	socket.on('sendMyMsg', function(data){
		/*
			r : 방번호
			t : 방타입
			i : 유저아이디
			m : 메시지
		*/
		
		// 손님은 글을 쓸수 없다. DB저장X
		if(data.i==0){return;}
		
		
		var blank_pattern = /^\s+|\s+$/g;
		if( data.m.replace( blank_pattern, '' ) == "" ){return;}
		
		// 채팅 DB 저장
		q  = 'insert into ';
		q += 'chat_room(rnum, rtype, user_id, chat,chat_time) ';
		q += 'values(\'' + data.r + '\', \'' + data.t + '\', \'' + data.i + '\', \'' + data.m + '\', now())';
		
		client.query(q, function(error,result,fields){
			if(error){ console.log('error : ' + error); }
			else{
				// 채팅 DB 조회
				q  = 'select t1.rnum, t1.user_id, t1.chat, t1.chat_time, t2.name, t2.email ';
				q += 'from chat_room t1 ';
				q += 'inner join users t2 ';
				q += 'on t1.user_id = t2.id ';
				q += 'where t1.rnum = \'' + data.r + '\' ';
				q += 'and t1.user_id = \''+ data.i + '\' ';
				q += 'order by t1.chat_time desc ';
				q += 'limit 1';
				
				client.query(q , function(error,result,fields){
					if(error){ console.log('error : ' + error); }
					else{
						// 시간 포맷 변경
						result[0].chat_time = date.format(result[0].chat_time, 'A HH:mm');
						
						// 통신
						// socket.emit을 하면 다른 브라우저에는 전송이 안된다. 본인 브라우저만 된다.
						io.emit('getMsg' , {i : result[0].user_id, n : result[0].name, m : result[0].chat, t : result[0].chat_time});
					}
				});
			}
		});
		
		
		
		
		// console.log(data.msg2);
		// io.sockets.emit('getMyMsg',data);
		// io.emit('init',result); 
	});
	

	
    // socket.on('join', function (user) {

        // console.info('New client connected (id=' + user.id + ' (' + user.name + ') => socket=' + socket.id + ').');

        // // save socket to emit later on a specific one
        // socket.userId   = user.id;
        // socket.nickname = user.name;

        // users[user.id] = socket;

        // // store connected nicknames
        // nicknames[user.id] = {
            // 'nickname': user.name,
            // 'socketId': socket.id,
        // };


        // function updateNicknames() {
            // // send connected users to all sockets to display in nickname list
            // io.sockets.emit('chat.users', nicknames);
        // }

        // updateNicknames();


        // // subscribe connected user to a specific channel, later he can receive message directly from our ChatController
        // redis.subscribe(['chat.message', 'chat.private'], function (err, count) {

        // });

        // // get messages send by ChatController
        // redis.on("message", function (channel, message) {
            // console.log('Receive message %s from system in channel %s', message, channel);

            // socket.emit(channel, message);
        // });


        // // get user sent message and broadcast to all connected users
        // socket.on('chat.send.message', function (message) {
            // console.log('Receive message ' + message.msg + ' from user in channel chat.message');

            // io.sockets.emit('chat.message', JSON.stringify(message));
        // });


        // socket.on('disconnect', function() {
            // if( ! socket.nickname) return;

            // delete users[user.id];
            // delete nicknames[user.id];

            // updateNicknames();

            // console.info('Client gone (id=' + user.id+ ' => socket=' + socket.id + ').');
        // });

    // });
});