<?php

require __DIR__.'/../www/app/BCXConfig.lib.php';


//echo getcwd();
//exit;

//require __DIR__.'/app/BCXDec.lib.php';
//require __DIR__.'/app/BCXOrder.lib.php';
//require __DIR__.'/app/BCXOrderbook.lib.php';
//require __DIR__.'/app/BCXPosition.lib.php';


define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader
| for our application. We just need to utilize it! We'll require it
| into the script here so that we do not have to worry about the
| loading of any our classes "manually". Feels great to relax.
|
*/

require __DIR__.'/../www/vendor/autoload.php';

$app = require_once __DIR__.'/../www/bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
|
*/


$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

//$response->send();

//use BCXLibs;

//echo 3;exit;


use BCXLibs\BCXDec;
use BCXLibs\BCXOrder;
use BCXLibs\BCXOrderbook;
use BCXLibs\BCXPosition;

/*
 * //////// bcmath test
//$trade_exec=new BCXDec();
$a=new BCXDec('0.00012322');
$b=new BCXDec('0.00012321');
//$a=100.10001;
//$a=$b;

$c=$a < $b;
var_dump($c);

*/


///////// 루프를 돌면서 orders 테이블 감시, 새로운 주문이 있다면 처리
$loop_cnt=0;
$bcxorder=new BCXOrder();

// 미체결시 주문취소는 안쓰는걸로 가정
$cancel_order_partial_filled=0;
while(1){

//    $amount_obj=new BCXDec('0.0001');
//    echo "\n$loop_cnt";
//    $loop_cnt++;
//    continue;

    $rows = DB::select("select * from trade_od where sts='0' order by idx asc limit 1");
    foreach($rows as $k=>$v){
        debugmsg("---------------------------------------------------------------------");
        usleep(1);

        $order_idx=$v->idx;

        // 거래를 처리한다.
        //$left_od_amount=process_order($order_idx);

        // 체결시도
        $order_result=$bcxorder->process_order($v);

        $left_od_amount=$order_result['left_od_amount']; // 남은 수량
        $tx_total_sum=$order_result['tx_total_sum']; // 처리된 금액합계

        // 오더북 등록
        // 미체결량이 있는경우에만 오더북에 등록
        if($left_od_amount>0){
            // 일부 체결시에는 orderbook에 등록하는게 아니고 캔슬
            if($cancel_order_partial_filled){
//                debugmsg("미체결이 남아있는 오더를 취소한다.");
//                // 미체결이 남아있는 오더를 취소한다.
//                if(!order_cancel($uid,$order_idx)){
//                    return -1;
//                }
            }
            else {
                debugmsg("call orderbook_add.");

                // 미체결수량 오더북에 등록
                $bcxorder->orderbook_add($v->user_id,$order_idx,$v->cp_code,$v->bs,$v->price,$left_od_amount,POSI_TYPE_MARGIN);
            }
        }

//
//        $amount_obj=new BCXDec($v->amount);
//        $price_obj=new BCXDec($v->price);
//        $left_od_amount_obj=new BCXDec($left_od_amount);
//
//        $tx_amount_obj=new BCXDec();
//        $tx_amount_obj=$amount_obj+$left_od_amount_obj;
//
//        debugmsg("tx_amount=$tx_amount_obj");
//
//        $tot_price_obj=new BCXDec();
//        $tot_price_obj=$tx_amount_obj*$price_obj;
//
//        debugmsg("requested price=$tot_price_obj");
//
//        // 실제 체결된 금액
//        debugmsg("executed price=$tx_total_sum");

        //// 처리완료로 만들어 재처리를 금지한다.
        DB::update("update trade_od set sts='1' where idx=?",[$v->idx]);

        // 결과를 저장한다. TODO: 가능하면 다른 프로세스로 분리할것. 조금이라도 빠르게 하기위해서
        res_add($v->user_id,$order_idx,'order_exec.ok');
//
//        echo 1;
        //exit;
//        usleep(1);

    }

//    usleep(5000);
//    echo "\n$loop_cnt";
    $loop_cnt++;
    flush();

}


//new_bc();

$kernel->terminate($request, $response);
?>