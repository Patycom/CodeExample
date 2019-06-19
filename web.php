<?php


Route::resource('/', 'IndexController',['only' => ['index'], 'names' => ['index' => 'main'] ])/*->middleware('statuscheck:Ban')*/;

Route::resource('news', 'BlogController')->parameters(['news' => 'alias']);

Route::get('news/category/{cat_alias?}', ['uses' => 'BlogController@index', 'as' => 'newsCat']);

Route::resource('comment', 'CommentController')->middleware('throttle:3,1',  'auth', 'verified');

Route::post('comment/check',['uses' => 'CommentController@check', 'as'=>'captcha']);

Route::get('/ban',['uses' => 'BanController@index', 'as'=>'ban']);

Route::get('/page/{page?}',['uses' => 'PageController@index', 'as'=>'custom_page']);

Route::get('/profile/{id?}',['uses' => 'ProfileController@show', 'as'=>'profile']);

Route::post('/profile/update',['uses' => 'ProfileController@update', 'as'=>'profile.update']);

Route::post('/profile/upload',['uses' => 'ProfileController@uploadPhoto', 'as'=>'avatar']);



// Auth

    Auth::routes(['verify' => true]);

    Route::get('/home', 'HomeController@index')->name('home');


//ADMIN

    Route::group(['prefix'=>'admin','as'=>'admin.', 'middleware' => 'auth'],function (){

        Route::get('/', ['uses' => 'Admin\IndexController@index', 'as' => 'adminIndex', 'middleware' => 'accesscheck:VIEW_ADMIN']);

        Route::resource('news', 'Admin\NewsController')->middleware('accesscheck:EDIT_NEWS');

        Route::resource('comments', 'Admin\CommentsController')->middleware('accesscheck:EDIT_COMMENTS');

        Route::resource('users', 'Admin\UsersController')->middleware('accesscheck:EDIT_USERS');

        Route::resource('menu', 'Admin\MenuController')->middleware('accesscheck:EDIT_MENUS');

        Route::resource('permissions', 'Admin\PermissionController')->middleware('accesscheck:EDIT_PERMS');

        Route::resource('pages', 'Admin\PagesController')->middleware('accesscheck:ADD_PAGES');

        Route::post('menu/ajax',['uses' => 'Admin\MenuController@saveMenuPosition', 'as'=>'saveMenuPosition']);

        Route::post('menu/select',['uses' => 'Admin\MenuController@onchangeSelect', 'as'=>'onchangeSelect']);

        Route::post ('sidebar/deleteItem', ['uses' => 'Admin\SidebarController@deleteCustomItems', 'as' => 'deleteItem']);

        Route::resource('sidebar', 'Admin\SidebarsController');

        Route::resource('custom_sidebar', 'Admin\CustomSidebarController');

        Route::post ('sidebar/position', ['uses' => 'Admin\CustomSidebarController@saveSidebarPosition', 'as' => 'saveSidebarPosition']);

        Route::post ('sidebar/save', ['uses' => 'Admin\SidebarsController@saveSidebar', 'as' => 'saveSidebar']);

        Route::post ('sidebar/update', ['uses' => 'Admin\SidebarsController@saveSidebar', 'as' => 'updateSidebar']);


    });//admin





