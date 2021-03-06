<?php

/****************   Model binding into route **************************/
Route::model('trip', 'App\Trip');
Route::model('question', 'App\Model\Question');
Route::model('clist', 'App\Model\Clist');
Route::model('language', 'App\Language');
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
    # Question
    Route::get('question/data', 'Admin\QuestionController@data');
    Route::get('question/{question}/show', 'Admin\QuestionController@show');
    Route::get('question/{question}/edit', 'Admin\QuestionController@edit');
    Route::get('question/{question}/delete', 'Admin\QuestionController@delete');
    Route::resource('question', 'Admin\QuestionController');
    # Clist
    Route::get('clist/data', 'Admin\ClistController@data');
    Route::get('clist/{clist}/show', 'Admin\ClistController@show');
    Route::get('clist/{clist}/edit', 'Admin\ClistController@edit');
    Route::get('clist/{clist}/delete', 'Admin\ClistController@delete');
    Route::resource('clist', 'Admin\ClistController');

    Route::get('actionDiagram/{trip}/editActionDiagram', 'Admin\ActionDiagramController@index');
    Route::get('actionDiagram/{instance}/updateinstance', 'Admin\ActionDiagramController@updateinstance');
    Route::resource('actionDiagram', 'Admin\ActionDiagramController');

    # Language
    Route::get('language/data', 'Admin\LanguageController@data');
    Route::get('language/{language}/show', 'Admin\LanguageController@show');
    Route::get('language/{language}/edit', 'Admin\LanguageController@edit');
    Route::get('language/{language}/delete', 'Admin\LanguageController@delete');
    Route::resource('language', 'Admin\LanguageController');

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
                '/delete-instance',
                [
                    'as' => 'api-delete-instance-route',
                    'uses' => 'Admin\InstanceController@deleteInstance'
                ]
            );
            Route::post(
                '/get-instance-context-menu',
                [
                    'as' => 'api-get-instance-context-menu-route',
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
            Route::post(
                '/send-action',
                [
                    'as' => 'api-send-action-route',
                    'uses' => 'Admin\InstanceController@sendAction'
                ]
            );
            Route::post(
                '/selected-question',
                [
                    'as' => 'api-selected-question-route',
                    'uses' => 'Admin\InstanceController@selectedQuestion'
                ]
            );
            Route::post(
                '/selected-snippet',
                [
                    'as' => 'api-selected-snippet-route',
                    'uses' => 'Admin\InstanceController@selectedSnippet'
                ]
            );
            Route::post(
                '/get-constant-form',
                [
                    'as' => 'api-get-constant-form-route',
                    'uses' => 'Admin\ClistController@getConstantForm'
                ]
            );
            Route::post(
                '/save-clist',
                [
                    'as' => 'api-save-clist-route',
                    'uses' => 'Admin\ClistController@saveAction'
                ]
            );
        }
    );

});
