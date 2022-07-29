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

        Route::get('/terms', [SettingController::class, 'getTerms']); // get terms
        Route::get('/contact', [SettingController::class, 'getContactDetails']); // get contact details

        Route::post('/contact', [SettingController::class, 'addContact']); // contact us
        Route::get('/teams', [SettingController::class, 'getTeams']); // get teams
        Route::get('/gallery', [SettingController::class, 'getGallery']); // get gallery

        Route::get('/news', [SettingController::class, 'getNews']); // get news
        Route::get('/actions', [SettingController::class, 'getActions']); // get actions


        Route::post('/signup', [AuthController::class, 'register']); // register new user
        Route::get('/get/verification/code', [AuthController::class, 'getVerificationCode']); // get verification code'Auth\@getVerificationCode'); // get verification code
        Route::post('/verify/check', [AuthController::class, 'checkVerificationCode']); // get verification code'Auth\@checkVerificationCode'); // check verification code
        Route::post('/verify/resend', [AuthController::class, 'resendVerificationCode']); // resend verification code
        Route::post('/login', [AuthController::class, 'login']); // login user
        Route::post('/password/forget', [AuthController::class, 'forgetPassword']); // forget password
        Route::post('/password/forget/change', [AuthController::class, 'changeForgetPassword']); // change forget password

        Route::get('/get/categories', [CategoryController::class, 'getCategories']); // get main or sub categories

        Route::get('/get/home', [HomeController::class, 'getHome']); // get Home data


        Route::group(['middleware' => ['auth:api', 'authApi']], function () {

            Route::post('/logout', [AuthController::class, 'logout']); // logout

            Route::get('/profile/get', [UserController::class, 'getProfile']); // get profile
            Route::post('/profile/edit', [UserController::class, 'editProfile']); // edit profile

            Route::post('/product/upload/image', [ProductController::class, 'uploadProductImage']); // upload Product Image
            Route::post('/product/remove/image/{id}', [ProductController::class, 'removeProductImage']); // remove Product Image
            Route::post('/product/add', [ProductController::class, 'addProduct']); // add product


            Route::get('/my/products', [UserController::class, 'getMyProducts']); // get my products
            Route::post('/product/favorite/toggle/{id}', [UserController::class, 'toggleFavouriteProduct']); // toggle Favourite Product
            Route::get('/my/favourites', [UserController::class, 'getMyFavouriteProducts']); // get my favourite products

            Route::post('/product/edit/{id}', [ProductController::class, 'editProduct']);
            Route::post('/product/delete/{id}', [ProductController::class, 'deleteProduct']);
            Route::post('/product/comment/add', [ProductController::class, 'addProductComment']); // add product comment

            Route::get('/get/shipments', [OrderSettingController::class, 'getShipments']); // add product
            Route::get('/get/payment_methods', [OrderSettingController::class, 'getPaymentMethods']); // add Payment Methods
            Route::get('/get/bank_accounts', [OrderSettingController::class, 'getBankAccounts']); // add bank accounts

            Route::post('/upload/general/image', [SettingController::class, 'uploadGeneralImage']); // upload General Image
            Route::post('/remove/general/image/{id}', [SettingController::class, 'removeGeneralImage']); // remove General Image

            // get my orders
            Route::get('/my/orders', [OrderController::class, 'getMyOrders']); // get my orders

            ////// damain order
            Route::get('/order/damain/details/{id}', [DamainOrderController::class, 'getDamainOrderDetails']); // get damain order details

            Route::post('/order/add/damain', [DamainOrderController::class, 'addDamainOrder']); // add damain order
            Route::post('/order/actions/add_product', [DamainOrderController::class, 'addProductToDamainOrder']); // add product details to damain order
            Route::post('/order/actions/edit_product', [DamainOrderController::class, 'editDamainOrderProduct']); // edit product details damain order

            Route::post('/order/add/direct', [OrderController::class, 'addDirectOrder']); // add direct order

            Route::post('/order/actions/accept', [OrderActionsController::class, 'acceptOrder']); // accept  order
            Route::post('/order/actions/refuse', [OrderActionsController::class, 'refuseOrder']); // refuse  order
            Route::post('/order/actions/cancel', [OrderActionsController::class, 'cancelOrder']); // cancel  order
            Route::post('/order/actions/pay', [OrderActionsController::class, 'payOrder']); // pay  order
            Route::post('/order/actions/shipment', [OrderActionsController::class, 'makeOrderShipped']); // make  order shipped
            Route::post('/order/actions/delivery/accept', [OrderActionsController::class, 'acceptOrderDelivery']); // accept  Order Delivery
            Route::post('/order/actions/delivery/refuse', [OrderActionsController::class, 'refuseOrderDelivery']); // accept  Order Delivery

            Route::get('/order/details/{id}', [OrderController::class, 'getOrderDetails']); // get order details

            Route::post('/product/negotiation/offer/add', [OrderOfferController::class, 'addProductNegotiationOffer']); // add Product Negotiation Offer
            Route::post('/product/offer/accept', [OrderOfferController::class, 'acceptNegotiationOffer']); // accept Negotiation Offer
            Route::post('/product/offer/refuse', [OrderOfferController::class, 'refuseNegotiationOffer']); // refuse Negotiation Offer

            Route::post('/product/bid/add', [OrderOfferController::class, 'addProductBidOffer']); // add Product Bid Offer
            Route::post('/product/bid/accept', [OrderOfferController::class, 'acceptBidOffer']); // accept Bid Offer
            Route::post('/product/bid/refuse', [OrderOfferController::class, 'refuseBidOffer']); // refuse Bid Offer

            // get my bid orders
            Route::get('/my/bid/orders', [OrderController::class, 'getMyBidOrders']); // get my bid orders

            // wallet
            Route::get('/get/wallet', [CreditController::class, 'getMyWallet']); // get my wallet
            Route::post('/wallet/charge', [CreditController::class, 'chargeMyWallet']); // charge My Wallet
            Route::post('/withdraw/request', [CreditController::class, 'requestWithdraw']); // request withdraw

            // get my notifications
            Route::get('/my/notifications', [UserController::class, 'getMyNotifications']); // get my notifications

            // get my chats
            Route::get('/my/chats', [UserController::class, 'getMyChats']); // get my chats
            Route::get('/chat/details/{id}', [UserController::class, 'getChatDetails']); // get my chats

        });

        Route::post('/order/payment-done', [CreditController::class, 'payDone']); // pay  order
        Route::get('/payment-error', [CreditController::class, 'payError']); // pay  order

    });
});
