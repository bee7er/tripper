<?php

/****************   Model binding into route **************************/
Route::model('trip', 'App\Trip');
Route::model('language', 'App\Language');
Route::model('template', 'App\Template');
Route::model('resource', 'App\Resource');
Route::model('notice', 'App\Notice');
Route::model('user', 'App\User');
Route::pattern('id', '[0-9]+');
Route::pattern('slug', '[0-9a-z-_]+');

/***************    Site routes  **********************************/
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');
// Others
Route::get('home', 'HomeController@index');
Route::get('about', 'PagesController@about');

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);
/***************    Admin routes  **********************************/
Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function() {

    # Admin Dashboard
    Route::get('dashboard', 'Admin\DashboardController@index');

    # Trip
    Route::get('trip/data', 'Admin\TripController@data');
    Route::get('trip/{trip}/show', 'Admin\TripController@show');
    Route::get('trip/{trip}/edit', 'Admin\TripController@edit');
    Route::get('trip/{trip}/delete', 'Admin\TripController@delete');
    Route::resource('trip', 'Admin\TripController');

    Route::get('actionDiagram/{trip}/editActionDiagram', 'Admin\ActionDiagramController@index');
    Route::get('actionDiagram/{instance}/updateinstance', 'Admin\ActionDiagramController@updateinstance');
    Route::resource('actionDiagram', 'Admin\ActionDiagramController');

    # Language
    Route::get('language/data', 'Admin\LanguageController@data');
    Route::get('language/{language}/show', 'Admin\LanguageController@show');
    Route::get('language/{language}/edit', 'Admin\LanguageController@edit');
    Route::get('language/{language}/delete', 'Admin\LanguageController@delete');
    Route::resource('language', 'Admin\LanguageController');
    # Template
    Route::get('template/data', 'Admin\TemplateController@data');
    Route::get('template/{template}/show', 'Admin\TemplateController@show');
    Route::get('template/{template}/edit', 'Admin\TemplateController@edit');
    Route::get('template/{template}/delete', 'Admin\TemplateController@delete');
    Route::resource('template', 'Admin\TemplateController');
    # Resource
    Route::get('resource/data', 'Admin\ResourceController@data');
    Route::get('resource/{resource}/show', 'Admin\ResourceController@show');
    Route::get('resource/{resource}/edit', 'Admin\ResourceController@edit');
    Route::get('resource/{resource}/delete', 'Admin\ResourceController@delete');
    Route::resource('resource', 'Admin\ResourceController');
    # Notice
    Route::get('notice/data', 'Admin\NoticeController@data');
    Route::get('notice/{notice}/show', 'Admin\NoticeController@show');
    Route::get('notice/{notice}/edit', 'Admin\NoticeController@edit');
    Route::get('notice/{notice}/delete', 'Admin\NoticeController@delete');
    Route::resource('notice', 'Admin\NoticeController');

    # Users
    Route::get('user/data', 'Admin\UserController@data');
    Route::get('user/{user}/show', 'Admin\UserController@show');
    Route::get('user/{user}/edit', 'Admin\UserController@edit');
    Route::get('user/{user}/delete', 'Admin\UserController@delete');
    Route::resource('user', 'Admin\UserController');

    Route::group(
        ['prefix' => 'api'],
        function () {
            Route::post(
                '/get-action-diagram',
                [
                    'as' => 'api-get-action-diagram-route',
                    'uses' => 'Admin\ActionDiagramController@getActionDiagram'
                ]
            );
            Route::post(
                '/save-instance',
                [
                    'as' => 'api-save-instance-route',
                    'uses' => 'Admin\InstanceController@saveInstance'
                ]
            );
            Route::post(
                '/get-instance-context-menu',
                [
                    'as' => 'api-et-instance-context-menu-route',
                    'uses' => 'Admin\InstanceController@getInstanceContextMenu'
                ]
            );
            Route::post(
                '/get-instance-form',
                [
                    'as' => 'api-get-instance-form-route',
                    'uses' => 'Admin\InstanceController@getInstanceForm'
                ]
            );
        }
    );

});
