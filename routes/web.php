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

// 前台
Route::prefix('/')->group(function () {
    // 首页
    Route::get('', 'Home\IndexController@index');

    // 文章详情
    Route::get('detail/{id}', 'Home\IndexController@show');

    // 文章专栏
    Route::get('article', 'Home\IndexController@article');

    // 关于
    Route::get('about', 'Home\IndexController@about');

    // 发送评论
    Route::post('comments', 'Home\IndexController@comments');
    // 获取评论
    Route::get('getComments/{id}', 'Home\IndexController@getComments');
});


// 后台登录
Route::post('admin/login', function () {
    return 333;
});
Route::any('/admin/login', 'Admin\IndexController@login');

// ajax 检测权限
Route::post('/admin/permission', 'Admin\IndexController@permission');

// 后台
Route::prefix('/admin')->middleware(['checkLogin', 'checkPermission'])->group(function () {
// Route::prefix('/admin')->group(function () {
// Route::prefix('/admin')->group(function () {
    // 登出
    Route::get('logout', 'Admin\IndexController@logout');

    // 首页
    Route::any('', 'Admin\IndexController@index');
    Route::any('welcome', function () {
        return view('admin.welcome');
    });

    Route::any('getMenu', 'Admin\IndexController@getMenu');

    // 文章列表相关
    Route::get('article/allList', 'Admin\ArticleController@allList');
    Route::post('article/photo', 'Admin\ArticleController@photo');

    Route::resource('article', 'Admin\ArticleController');


    // 分类相关
    Route::get('category/add', function () {
        return view('admin.article.add');
    });
    Route::get('category/treeForSelect', 'Admin\CategoryController@treeForSelect');
    Route::post('category/status', 'Admin\CategoryController@status');
    Route::patch('category', 'Admin\CategoryController@update');
    Route::resource('category', 'Admin\CategoryController');

    // 标签管理
    Route::resource('tag', 'Admin\TagController');

    // 友链管理
    Route::get('/links/list', 'Admin\LinksController@list');
    Route::resource('links', 'Admin\LinksController');

    // 管理员权限相关
    // 管理员列表
    Route::get('admin/list', 'Admin\AdminController@list');

    // 更改管理员的权限状态
    Route::post('admin/status', 'Admin\AdminController@status');

    Route::resource('admin', 'Admin\AdminController');


    // 角色相关的操作
    // 获取角色列表
    Route::get('role/list', 'Admin\RoleController@list');

    Route::resource('role', 'Admin\RoleController');


    // 菜单相关
    // 将菜单数据整合成树形数据
    Route::post('menu/treeForSelect', 'Admin\MenuController@treeForSelect');
    Route::get('menu/list', 'Admin\MenuController@list');
    Route::get('menu/treeForRole', 'Admin\MenuController@treeForRole');
    Route::resource('menu', 'Admin\MenuController');
});



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
