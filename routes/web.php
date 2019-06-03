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

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {

    Route::get('/', function () {
        return view('pages.home.index');
    });

    Route::resource('/users', 'UsersController');

    Route::get('/my-profile', 'UsersController@getProfile');

    Route::get('/my-profile/edit', 'UsersController@getEditProfile');

    Route::patch('/my-profile/edit', 'UsersController@postEditProfile');

    Route::resource('/permissions', 'PermissionsController');

    Route::resource('/roles', 'RolesController');

    Route::get('/users/role/{id}', 'UsersController@getRole');

    Route::put('/users/role/{id}', 'UsersController@updateRole');

    Route::resource('/documents', 'DocumentsController');

    Route::resource('/contacts', 'ContactsController');

    Route::get('/contacts/{id}/assign', 'ContactsController@getAssignContact');

    Route::put('/contacts/{id}/assign', 'ContactsController@postAssignContact');

    Route::get('/forbidden', function () {
        return view('pages.forbidden.forbidden_area');
    });
});

Route::get('/', function () {
   return redirect()->to('/admin');
});

Auth::routes();