<?php

use App\Http\Controllers\Site\AuthController;
use App\Http\Controllers\Site\HomeSiteController;
use App\Http\Controllers\Site\OrderController;
use App\Http\Controllers\Site\PageSiteController;
use App\Http\Controllers\Site\ProductController;
use App\Http\Controllers\Site\ProfileController;
use App\Http\Controllers\Site\UserController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group(['prefix' => LaravelLocalization::setLocale()
    , 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {

    Route::get('', [HomeSiteController::class, 'showHome']);
    Route::get('/questions', [PageSiteController::class, 'showQuestions']);
    Route::get('/about', [PageSiteController::class, 'showAbout']);
    Route::get('/faq', [PageSiteController::class, 'showFaq']);
    Route::get('/policy', [PageSiteController::class, 'showPolicy']);
    Route::get('/terms', [PageSiteController::class, 'showTerms']);
    Route::get('/guide', [PageSiteController::class, 'showGuide']);

    Route::get('/category/{id}', [HomeSiteController::class, 'showCategoryProducts']);

    Route::get('/product/details/{id}', [HomeSiteController::class, 'showProductDetails']);

    // login
    Route::get('/login', [AuthController::class, 'showLogin'])->middleware('guest-site');
    Route::post('/process/auth/login', [AuthController::class, 'processAuthLogin']);
    //////////////////////////////
    // register
    Route::get('/register', [AuthController::class, 'showRegister'])->middleware('guest-site');
    Route::post('/process/auth/register', [AuthController::class, 'processAuthRegister']);
    //////////////////////////////
    // verify
    Route::get('/verify', [AuthController::class, 'showVerify'])->middleware('unverified-site');
    Route::post('/process/auth/verify', [AuthController::class, 'processAuthVerify']);
    //////////////////////////////
    // forget password
    Route::get('/forget/password', [AuthController::class, 'showForgetPassword'])->middleware('guest-site');
    Route::post('/process/forget/password', [AuthController::class, 'processForgetPassword']);
    // verify after forget password
    Route::get('/forget/verify/{id}', [AuthController::class, 'showForgetVerify'])->middleware('guest-site');
    Route::post('/process/forget/verify', [AuthController::class, 'processForgetVerify']);
    // change password
    Route::get('/change/password/{id}', [AuthController::class, 'showChangePassword'])->middleware('guest-site');
    Route::post('/process/change/password', [AuthController::class, 'processChangePassword']);

    /////////////////////////////
    Route::group(['middleware' => 'auth-site'], function () {

        Route::get('/logout', [UserController::class, 'logout']);

        Route::get('/contact', [PageSiteController::class, 'showContact']);
        Route::post('/process/contact', [PageSiteController::class, 'processContact']);

        Route::get('/profile', [ProfileController::class, 'showProfile']);
        Route::post('/profile', [ProfileController::class, 'editProfile']);


        Route::get('/my/wallet', [UserController::class, 'showMyWallet']);
        Route::post('/wallet/charge', [UserController::class, 'chargeMyWallet']); // charge My Wallet
        Route::post('/withdraw/request', [UserController::class, 'requestWithdraw']); // request withdraw

        Route::get('/my/products', [UserController::class, 'showMyProducts']);
        Route::get('/my/favourites', [UserController::class, 'showMyFavourites']);

        Route::get('/product/add', [ProductController::class, 'showAddProduct']);
        Route::post('/product/add', [ProductController::class, 'saveAddProduct']);

        Route::post('/product/favourite', [ProductController::class, 'favouriteProduct']);

        Route::get('/product/edit/{id}', [ProductController::class, 'showEditProduct']);
        Route::post('/product/edit/{id}', [ProductController::class, 'saveEditProduct']);
        Route::post('/product/remove/image/{id}', [ProductController::class, 'removeProductImage']); // remove Product Image

        Route::get('/damain/create', [OrderController::class, 'showAddDamainOrder']);
        Route::post('/damain/create', [OrderController::class, 'addDamainOrder']);

        Route::get('/orders', [OrderController::class, 'showOrders']);
        Route::get('/order/details/{id}', [OrderController::class, 'showOrderDetails']);
        Route::get('/order/damain/details/{id}', [OrderController::class, 'showDamainOrderDetails']);

        Route::get('/order/damain/details/{id}', [OrderController::class, 'showDamainOrderDetails']);


        Route::post('/order/add/direct', [OrderController::class, 'addDirectOrder']); // add direct order
        Route::post('/product/negotiation/offer/add', [OrderController::class, 'addProductNegotiationOffer']); // add Product Negotiation Offer

        Route::post('/product/offer/accept', [OrderController::class, 'acceptNegotiationOffer']); // accept Negotiation Offer
        Route::post('/product/offer/refuse', [OrderController::class, 'refuseNegotiationOffer']); // refuse Negotiation Offer

        Route::post('/product/bid/add', [OrderController::class, 'addProductBidOffer']); // add Product Bid Offer
        Route::post('/product/bid/accept', [OrderController::class, 'acceptBidOffer']); // accept Bid Offer
        Route::post('/product/bid/refuse', [OrderController::class, 'refuseBidOffer']); // refuse Bid Offer


        Route::post('/order/actions/accept', [OrderController::class, 'acceptOrder']);
        Route::post('/order/actions/refuse', [OrderController::class, 'refuseOrder']);
        Route::post('/order/actions/cancel', [OrderController::class, 'cancelOrder']);
        Route::post('/order/actions/pay', [OrderController::class, 'payOrder']); // pay  order
        Route::post('/order/actions/shipment', [OrderController::class, 'makeOrderShipped']); // make  order shipped
        Route::post('/order/actions/delivery/accept', [OrderController::class, 'acceptOrderDelivery']); // accept  Order Delivery
        Route::post('/order/actions/delivery/refuse', [OrderController::class, 'refuseOrderDelivery']); // accept  Order Delivery

        Route::get('/order/product/add/{id}', [OrderController::class, 'showAddProductToDamainOrder']);
        Route::post('/order/actions/add_product', [OrderController::class, 'addProductToDamainOrder']); // add product details to damain order
        Route::get('/order/product/edit/{id}', [OrderController::class, 'showEditProductToDamainOrder']);
        Route::post('/order/actions/edit_product', [OrderController::class, 'editDamainOrderProduct']); // edit product details damain order
        Route::post('/general/image/remove/{id}', [OrderController::class, 'removeImage']); // remove Product Image

        Route::get('/bids', [OrderController::class, 'showBids']);

    });

});

