<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
Route::get('/hello', function () {
    return '<h1>Hello World</h1>';
});
Route::get('/users/{id}/{name}', function ($id,$name) {
    return 'This is user '.$name.' with an id of '.$id;
});
*/
Route::get('/', 'PagesController@index');
Route::get('/about', 'PagesController@about');
Route::get('/services', 'PagesController@services');

Route::resource('posts', 'PostsController');

Auth::routes();

Route::get('/dashboard', 'DashboardController@index');

Route::get('pagenotfound' ,['as' => 'notfound' , 'uses' => 'PostsController@pagenotfound']);

Route::get('admin/member', 'AdminMemberController@index')->name('admin.member.index');
Route::resource('admin/member', 'AdminMemberController');

Route::get('/chat', 'ChatController@index');

Route::get('admin/board', 'AdminBoardController@index')->name('admin.board.index');
Route::resource('admin/board', 'AdminBoardController');
//////////////////////
// 관리자
Route::get('/admin', 'AdminCon@index')->name('admin.index');

// 관리자 -> 카페 관리메뉴
Route::get('/admin/cafe/{cmd}', 'AdminCon@cafe_run')->name('admin.cafe.get');
Route::post('/admin/cafe/{cmd}', 'AdminCon@cafe_run')->name('admin.cafe.post');

// 관리자 -> 게시판 관리메뉴
Route::get('/admin/bbs/{cmd}', 'AdminCon@bbs_run')->name('admin.bbs.get');
Route::post('/admin/bbs/{cmd}', 'AdminCon@bbs_run')->name('admin.bbs.post');
