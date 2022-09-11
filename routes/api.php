<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Home\HomeController;
use App\Http\Controllers\Api\Order\DamainOrderController;
use App\Http\Controllers\Api\Order\OrderActionsController;
use App\Http\Controllers\Api\Order\OrderController;
use App\Http\Controllers\Api\Order\OrderOfferController;
use App\Http\Controllers\Api\Order\OrderSettingController;
use App\Http\Controllers\Api\Product\CategoryController;
use App\Http\Controllers\Api\Product\ProductController;
use App\Http\Controllers\Api\Setting\SettingController;
use App\Http\Controllers\Api\User\CreditController;
use App\Http\Controllers\Api\User\UserController;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => 'lang'], function () {
    Route::group(['prefix' => 'v1'], function () {

        Route::get('/intros', [SettingController::class, 'getIntros']); // get intros

        Route::get('/terms', [SettingController::class, 'getTerms']); // get terms
        Route::get('/contact', [SettingController::class, 'getContactDetails']); // get contact details

        Route::post('/contact', [SettingController::class, 'addContact']); // contact us

        Route::get('/teams', [SettingController::class, 'getTeams']); // get teams
        Route::get('/gallery', [SettingController::class, 'getGallery']); // get gallery

        Route::get('/history', [SettingController::class, 'getHistory']); // history

        Route::get('/news', [SettingController::class, 'getNews']); // get news
        Route::get('/new/details/{id}', [SettingController::class, 'getNewDetails']); // get new details

        Route::get('/actions', [SettingController::class, 'getActions']); // get actions
        Route::get('/action/details/{id}', [SettingController::class, 'getActionDetails']); // get action details

        Route::get('/about-dba', [SettingController::class, 'getAbout']); // get about
        Route::get('/committees', [SettingController::class, 'getCommittees']); // get committees


        Route::post('/signup', [AuthController::class, 'register']); // register new user
        Route::get('/get/verification/code', [AuthController::class, 'getVerificationCode']); // get verification code'Auth\@getVerificationCode'); // get verification code
        Route::post('/verify/check', [AuthController::class, 'checkVerificationCode']); // get verification code'Auth\@checkVerificationCode'); // check verification code
        Route::post('/verify/resend', [AuthController::class, 'resendVerificationCode']); // resend verification code
        Route::post('/login', [AuthController::class, 'login']); // login user
        Route::post('/password/forget', [AuthController::class, 'forgetPassword']); // forget password
        Route::post('/password/forget/change', [AuthController::class, 'changeForgetPassword']); // change forget password


//        Route::get('/get/home', [HomeController::class, 'getHome']); // get Home data

        Route::group(['middleware' => ['auth:api', 'authApi']], function () {

            Route::post('/logout', [AuthController::class, 'logout']); // logout

            Route::get('/profile/get', [UserController::class, 'getProfile']); // get profile
            Route::post('/profile/edit', [UserController::class, 'editProfile']); // edit profile


        });
    });
});
