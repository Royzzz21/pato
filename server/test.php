<?php

require __DIR__.'/../www/app/BCXConfig.lib.php';

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


$orderObj=new BCXOrder();
$posiObj=new BCXPosition();

///////// 루프를 돌면서 req 테이블 감시, 새로운 요청이 있다면 처리
$loop_cnt=0;
//
//DB::getPdo()->








$time_int_arr=[60,180,300,600,900,1800,3600,7200,14400,28800,86400];


///////////////////////////
// 차트 테이블 수정
if(0)
{
    function fix_chart_table($time_int){
        $pdo = DB::getPdo();
        $stmt = $pdo->query("alter table chart_btcusd_$time_int add column vol double");
    }

    // 단위별 마지막 가격과 시간을 얻는다.
    foreach($time_int_arr as $k => $v){
        fix_chart_table($v);
    }

    exit;


}

///////////////////////////
// 차트 데이타 날리기
if(0)
{
    function delete_chart_data($time_int){
        $pdo = DB::getPdo();
        $stmt = $pdo->query("delete from chart_btcusd_$time_int");
    }

    // 단위별 마지막 가격과 시간을 얻는다.
    foreach($time_int_arr as $k => $v){
        delete_chart_data($v);
    }

    exit;

}


/*
 *
 * 라라벨 날쿼리 날리는 방법
 *
$pdo = DB::getPdo();
//$pdo->setAttribute( \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
$stmt = $pdo->query("
CREATE TABLE `chart_btcusd_111160` (
	`tx_time` INT(11) NOT NULL,
	`lo` DOUBLE NOT NULL,
	`hi` DOUBLE NOT NULL,
	`op` DOUBLE NOT NULL,
	`cl` DOUBLE NOT NULL,
	PRIMARY KEY (`tx_time`)
)
COMMENT='chart 60'
COLLATE='utf8_unicode_ci'
ENGINE=InnoDB
ROW_FORMAT=DYNAMIC
;
");
*/




$pdo = DB::getPdo();

// req 외에 거래관련 모두 지움
$pdo->query("delete from trade_tx");
$pdo->query("delete from trade_posi");
$pdo->query("delete from trade_od");
$pdo->query("delete from trade_ob");
// 요청을 다시 실행하도록 한다.
$pdo->query("update trade_req set sts=0");


//
//DB::delete("delete from trade_tx");
//DB::delete("delete from trade_posi");
//DB::delete("delete from trade_od");
//DB::delete("delete from trade_ob");
//
//DB::update("update trade_req set sts=0");




//new_bc();

$kernel->terminate($request, $response);


