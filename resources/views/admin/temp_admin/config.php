<?
define('ADMIN_MENU',1);
include dirname(__FILE__).'/../config.php';

// 인증체크
na_pageauthcheck();

// 관리자 여부 체크
admin_login_check();

?>