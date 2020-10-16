<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});
Route::redirect('home', 'admin/dashboard');
Route::redirect('/', 'login');
Auth::routes();
Route::post('custom/login', 'Auth\LoginController@customLogin');

/**
 * Admin Auth Routes
 */
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['check.token']], function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // users
    Route::resource('users', 'User\UserController');
    Route::get('users/import/bulk', 'User\UserController@bulkImportModal')->name('users.bulk-import.modal');
    Route::get('users/report/basic', 'User\UserController@reportBasic')->name('users.report.basic');
    Route::get('users/project-preview/{id}/modal', 'User\UserController@projectPreviewModal')->name('users.project-preview.modal');

    // lms-settings
    Route::resource('lms-settings', 'LmsSetting\LmsSettingController');

    // activity items
    Route::resource('activity-items', 'Activity\ActivityItemController');

    // activity types
    Route::resource('activity-types', 'Activity\ActivityTypeController');

    // projects
    Route::get('projects/indexing-queue', 'Project\ProjectController@indexingQueue');
    Route::get('projects/user-projects', 'Project\ProjectController@userProjects');
    Route::resource('projects', 'Project\ProjectController');

    // organization types
    Route::get('organization-types', 'Organization\OrganizationTypesController@index');
    Route::get('organization-types/create', 'Organization\OrganizationTypesController@create');
    Route::get('organization-types/{id}/edit', 'Organization\OrganizationTypesController@edit');
    Route::get('organization-types/{id}/delete', 'Organization\OrganizationTypesController@delete');
    Route::get('organization-types/{id}/order/{direction}', 'Organization\OrganizationTypesController@change_order');
    Route::post('organization-types/save', 'Organization\OrganizationTypesController@save');
});
