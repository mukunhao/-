<?php
use Illuminate\Http\Request;



Route::group(['middleware' => ['admin']], function() {


});
//Route::get('/index','Admin\IndexController@index');
//Route::get('/search','Admin\IndexController@search');
Route::POST('/login','Admin\LoginController@login');
//Route::get('/logout','Admin\LoginController@logout');
//Route::get('/shangchuan','Admin\LoginController@shangchuan');
//Route::POST('/sc','Admin\LoginController@sc');
//批量导出
Route::get('/export','Admin\HuiController@export');
//全部导出
Route::get('/exportData','Admin\HuiController@exportData');
//Route::get('/export','Admin\LoginController@export');
Route::get('/dc','Admin\LoginController@dc');

//Route::POST('/auth','Admin\UserController@auth');

//美术饭学校信息
Route::post('/getSchool','Admin\MsfIndexController@getSchool');

//美术饭列表页
Route::post('/getIndex','Admin\MsfIndexController@index');
Route::post('/Msfhuishou','Admin\MsfIndexController@huishou');
Route::get('/Msfexport','Admin\MsfIndexController@export');
Route::get('/Msfexportdata','Admin\MsfIndexController@exportData');
//海绵推送
Route::post('/HmPush','Admin\HaimianTsController@HaimianPush');
//海绵推送列表页
Route::post('/getHaimian','Admin\HaimianTsController@push');
//海绵批量导出
Route::get('/exportHaimian','Admin\HaimianTsController@export');
//海绵全部导出
Route::get('/exportHaimianData','Admin\HaimianTsController@exportData');
//海绵未推送列表页
Route::post('/getNopush','Admin\HaimianTsController@noPush');
//海绵已回收列表页
Route::post('/huishou','Admin\HaimianTsController@huishou');

//海绵推送学校信息
Route::post('/getHmSchool','Admin\HaimianTsController@getHmSchool');

//回收数据页城市信息
Route::post('/getcity','Admin\HuiController@getCity');

//获取回收学生的信息
Route::post('/getstudent','Admin\HuiController@getStudentHs');

//推送数据页搜索
Route::post('/getsearch','Admin\TsController@searchCity');

//推送数据
Route::post('/getts','Admin\TsController@getTs');

//推送页文件上传
Route::POST('/upload/{cityid}/{schoolid}','Admin\TsController@upload');

//推送页设置预计推送数量
Route::POST('/updateplan','Admin\TsController@updatePlanCount');
//获取报名学生
Route::get('/getStudentNo','Admin\MsfIndexController@getStudentNo');
//删除
Route::post('/HmDelete','Admin\HaimianTsController@HmDelete');


