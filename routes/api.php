<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserAddressController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use function Ramsey\Uuid\v1;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



// register new user apis
// Register new user APIs
Route::group(['prefix' => 'v1'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::resource('roles', 'RoleController');
    Route::resource('permissions', 'PermissionController');
});

Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::resource('products', 'ProductController');
    Route::resource('categories', 'CategoryController'); 
    Route::resource('user-address', 'UserAddressController');
    Route::resource('order', 'OrderController');
    Route::get('category-reports', 'ReportController@categoryWiseReports');
    Route::get('reports-payments', 'ReportController@fetchPaymentReports');
    Route::post('users/{userId}/assign-role', 'UserController@assignRole');
    Route::post('roles/{roleId}/assign-permission', 'UserController@assignPermission');


});


