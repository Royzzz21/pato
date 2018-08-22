<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Redis;
// use Request;
//use LRedis;

class ChatController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
        // $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		// redis setter
		// Redis::set('name', 'Taylor');
		// $values = Redis::lrange('names', 5, 10);
		// $values = Redis::command('lrange', ['name', 5, 10]);
        return view('chat');
		// return view('home');
    }

//
//	public function systemMessage()
//	{
//        $redis = L5Redis::connection();
//		// $redis = new Predis\Client('tcp:192.168.2.32:6379?read_write_timeout=0');
//
//        $redis->publish('chat.message', json_encode([
//            'msg'      => 'System message',
//            'nickname' => 'System',
//            'system'   => true,
//        ]));
//	}
//

	public function publicIndex(){
		$redis = LRedis::connection();
		$data = ['message' => Request::input('message'), 'user' => Request::input('user')];
		$redis->publish('message', json_encode($data));
		return response()->json([]);
		
		/*
			타이틀바
			대화명, 대화내용, 시간
			
		
			그룹방이냐?
				y-> 
		*/
		// 방 번호
		// 내 아이디
		// 상대방 아이디
		$id = 1;
		
		
		// $redis = new Predis\Client('tcp:192.168.2.32:6379?read_write_timeout=0');
        // $redis->publish('chat.message', json_encode([
            // 'msg'      => 'System message',
            // 'nickname' => 'System',
            // 'system'   => true,
        // ]));
		
        // return view('chat', [ 'user'  => $id 
							// , 'user2' => 'b' ]);
	}
}
