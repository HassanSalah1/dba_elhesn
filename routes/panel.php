<?php

use App\Http\Controllers\Dashboard\Auth\AuthenticationController;
use App\Http\Controllers\Dashboard\Contact\ContactController;
use App\Http\Controllers\Dashboard\HomeController;
use App\Http\Controllers\Dashboard\Notification\NotificationController;
use App\Http\Controllers\Dashboard\Order\BankTransferController;
use App\Http\Controllers\Dashboard\Order\OrderController;
use App\Http\Controllers\Dashboard\Order\Setting\BankAccountController;
use App\Http\Controllers\Dashboard\Order\Setting\PaymentMethodController;
use App\Http\Controllers\Dashboard\Order\Setting\ShipmentController;
use App\Http\Controllers\Dashboard\Product\CategoryController;
use App\Http\Controllers\Dashboard\Product\ProductController;
use App\Http\Controllers\Dashboard\Setting\CityController;
use App\Http\Controllers\Dashboard\Setting\CommitteeController;
use App\Http\Controllers\Dashboard\Setting\ContactTypeController;
use App\Http\Controllers\Dashboard\Setting\CountryController;
use App\Http\Controllers\Dashboard\Setting\FaqController;
use App\Http\Controllers\Dashboard\Setting\GalleryController;
use App\Http\Controllers\Dashboard\Setting\IntroController;
use App\Http\Controllers\Dashboard\Setting\NegotiationPercentController;
use App\Http\Controllers\Dashboard\Setting\NegotiationPeriodController;
use App\Http\Controllers\Dashboard\Setting\TeamController;
use App\Http\Controllers\Dashboard\Setting\UserGuideController;
use App\Http\Controllers\Dashboard\User\UserController;
use App\Http\Controllers\Dashboard\Withdraw\WithdrawRequestController;

Route::group(['prefix' => 'admin'], function () {


    Route::group(['prefix' => 'auth'], function () {
        Route::get('login', [AuthenticationController::class, 'showLogin']);
        Route::post('login', [AuthenticationController::class, 'processLogin']);
    });

    Route::middleware(["admin", "web"])->group(function () {
        Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout'); // logout current user

        Route::get('/home', [HomeController::class, 'showHome'])->name('dashboard-home'); // show home page
        ////////////////////////////////////////////////////////////
        Route::get('/intros', [IntroController::class, 'showIntros'])->name('dashboard-intros'); // show Index page that control all Intros
        Route::get('/intros/data', [IntroController::class, 'getIntrosData']); // get all Intros data for DataTable
        Route::post('/intro/add', [IntroController::class, 'addIntro']); // add Intro
        Route::post('/intro/data', [IntroController::class, 'getIntroData']); // get Intro data
        Route::post('/intro/edit', [IntroController::class, 'editIntro']); // edit Intro
        Route::post('/intro/delete', [IntroController::class, 'deleteIntro']); // delete Intro
        ////////////////////////////////
        Route::get('/terms', [HomeController::class, 'showTerms'])->name('dashboard-terms'); // about page
        Route::post('/terms/save', [HomeController::class, 'saveTerms']);
        ////////////////////////////////
        Route::post('/upload/image', [HomeController::class, 'uploadEditorImages']); // upload editor images inside text
        //////////////////////////////////
        Route::get('/setting', [HomeController::class, 'showSetting'])->name('dashboard-setting'); // about page
        Route::post('/setting/save', [HomeController::class, 'saveSetting']);
        ////////////////////////////////
        Route::get('/contacts', [ContactController::class, 'showContacts'])->name('dashboard-contacts'); // show Index page that control all Contacts
        Route::get('/contacts/data', [ContactController::class, 'getContactsData']); // get all Contacts data for DataTable
        Route::post('/contact/replay', [ContactController::class, 'replayContact']); // replay Contact

        ////////////////////////////////
        Route::get('/users', [UserController::class, 'showUsers'])->name('dashboard-users'); // show Index page that control all users
        Route::get('/users/data', [UserController::class, 'getUsersData']); // get all users data for DataTable

        Route::get('/user/details/{id}', [UserController::class, 'showUserDetails']); // show user details
        Route::post('/user/verify', [UserController::class, 'verifyUser']); // verify user
        Route::post('/user/change', [UserController::class, 'changeStatus']); // change user Status


        ////////////////////////////////
        Route::get('/teams', [TeamController::class, 'showTeams'])->name('dashboard-testimonials'); // show Index page that control all Teams
        Route::get('/teams/data', [TeamController::class, 'getTeamsData']); // get all Teams data for DataTable
        Route::post('/team/add', [TeamController::class, 'addTeam']); // add Team
        Route::post('/team/data', [TeamController::class, 'getTeamData']); // get Team data
        Route::post('/team/edit', [TeamController::class, 'editTeam']); // edit Team
        Route::post('/team/delete', [TeamController::class, 'deleteTeam']); // delete Team

        ////////////////////////////////
        Route::get('/galleries', [GalleryController::class, 'showGalleries'])->name('dashboard-galleries'); // show Index page that control all Galleries
        Route::get('/galleries/data', [GalleryController::class, 'getGalleriesData']); // get all Galleries data for DataTable
        Route::post('/gallery/add', [GalleryController::class, 'addGallery']); // add Gallery
        Route::post('/gallery/data', [GalleryController::class, 'getGalleryData']); // get Gallery data
        Route::post('/gallery/edit', [GalleryController::class, 'editGallery']); // edit Gallery
        Route::post('/gallery/delete', [GalleryController::class, 'deleteGallery']); // delete Gallery

        ////////////////////////////////
        Route::get('/committees', [CommitteeController::class, 'showCommittees'])->name('dashboard-committee'); // show Index page that control all Committees
        Route::get('/committees/data', [CommitteeController::class, 'getCommitteesData']); // get all Committees data for DataTable
        Route::post('/committee/add', [CommitteeController::class, 'addCommittee']); // add Committee
        Route::post('/committee/data', [CommitteeController::class, 'getCommitteeData']); // get Committee data
        Route::post('/committee/edit', [CommitteeController::class, 'editCommittee']); // edit Committee
        Route::post('/committee/delete', [CommitteeController::class, 'deleteCommittee']); // delete Committee

        //////////////////////////////////
        Route::get('/history', [HomeController::class, 'showHistory'])->name('dashboard-history'); // history page
        Route::post('/history/save', [HomeController::class, 'saveHistory']);
        //////////////////////////////////
        Route::get('/about', [HomeController::class, 'showAbout'])->name('dashboard-about'); // about page
        Route::post('/about/save', [HomeController::class, 'saveAbout']);


        ////////////////////////////////

        Route::get('/privacy', [HomeController::class, 'showPrivacy'])->name('dashboard-privacy'); // about page
        Route::post('/privacy/save', [HomeController::class, 'savePrivacy']);
        ////////////////////////////////
        Route::get('/faqs', [FaqController::class, 'showFaqs'])->name('dashboard-faqs'); // show Index page that control all Faqs
        Route::get('/faqs/data', [FaqController::class, 'getFaqsData']); // get all Faqs data for DataTable
        Route::post('/faq/add', [FaqController::class, 'addFaq']); // add Faq
        Route::post('/faq/data', [FaqController::class, 'getFaqData']); // get Faq data
        Route::post('/faq/edit', [FaqController::class, 'editFaq']); // edit Faq
        Route::post('/faq/delete', [FaqController::class, 'deleteFaq']); // delete Faq
        ////////////////////////////////
        Route::get('/contact/types', [ContactTypeController::class, 'showContactTypes'])->name('dashboard-contactTypes'); // show Index page that control all ContactTypes
        Route::get('/contact/types/data', [ContactTypeController::class, 'getContactTypesData']); // get all ContactTypes data for DataTable
        Route::post('/contact/types/add', [ContactTypeController::class, 'addContactType']); // add ContactType
        Route::post('/contact/types/data', [ContactTypeController::class, 'getContactTypeData']); // get ContactType data
        Route::post('/contact/types/edit', [ContactTypeController::class, 'editContactType']); // edit ContactType
        Route::post('/contact/types/delete', [ContactTypeController::class, 'deleteContactType']); // delete ContactType
        Route::post('/contact/types/restore', [ContactTypeController::class, 'restoreContactType']); // delete ContactType
        ////////////////////////////////

        Route::get('/countries', [CountryController::class, 'showCountries'])->name('dashboard-countries'); // show Index page that control all Countries
        Route::get('/countries/data', [CountryController::class, 'getCountriesData']); // get all Countries data for DataTable
        Route::post('/country/change', [CountryController::class, 'changeCountry']); // change Country
        ////////////////////////////////
        Route::get('/cities', [CityController::class, 'showCities'])->name('dashboard-cities'); // show Index page that control all Citys
        Route::get('/cities/data', [CityController::class, 'getCitiesData']); // get all Cities data for DataTable
        Route::post('/city/delete', [CityController::class, 'deleteCity']); // add City
        Route::post('/city/restore', [CityController::class, 'restoreCity']); // restore City


        ////////////////////////////////
        Route::get('/categories', [CategoryController::class, 'showCategories'])->name('dashboard-categories'); // show Index page that control all Categories
        Route::get('/categories/data', [CategoryController::class, 'getCategoriesData']); // get all Categories data for DataTable
        Route::post('/category/add', [CategoryController::class, 'addCategory']); // add Category
        Route::post('/category/data', [CategoryController::class, 'getCategoryData']); // get Category data
        Route::post('/category/edit', [CategoryController::class, 'editCategory']); // edit Category
        Route::post('/category/delete', [CategoryController::class, 'deleteCategory']); // delete Category
        Route::post('/category/restore', [CategoryController::class, 'restoreCategory']); // delete Category

        Route::get('/categories/sub/{id}', [CategoryController::class, 'showSubCategories'])->name('dashboard-categories'); // show Index page that control all Categories
        Route::get('/categories/get/sub/data/{id}', [CategoryController::class, 'getSubCategoriesData']); // get all Categories data for DataTable
        Route::get('/categories/sub2/{id}', [CategoryController::class, 'showSubCategoriesLevel3'])->name('dashboard-categories'); // show Index page that control all Categories
        ////////////////////////////////
        Route::get('/negotiation/periods', [NegotiationPeriodController::class, 'showNegotiationPeriods'])->name('dashboard-negotiation_periods'); // show Index page that control all NegotiationPeriods
        Route::get('/negotiation/periods/data', [NegotiationPeriodController::class, 'getNegotiationPeriodsData']); // get all NegotiationPeriods data for DataTable
        Route::post('/negotiation/period/add', [NegotiationPeriodController::class, 'addNegotiationPeriod']); // add NegotiationPeriod
        Route::post('/negotiation/period/data', [NegotiationPeriodController::class, 'getNegotiationPeriodData']); // get NegotiationPeriod data
        Route::post('/negotiation/period/edit', [NegotiationPeriodController::class, 'editNegotiationPeriod']); // edit NegotiationPeriod
        Route::post('/negotiation/period/delete', [NegotiationPeriodController::class, 'deleteNegotiationPeriod']); // delete NegotiationPeriod
        ////////////////////////////////
        Route::get('/negotiation/percents', [NegotiationPercentController::class, 'showNegotiationPercents'])->name('dashboard-negotiation_percents'); // show Index page that control all NegotiationPercents
        Route::get('/negotiation/percents/data', [NegotiationPercentController::class, 'getNegotiationPercentsData']); // get all NegotiationPercents data for DataTable
        Route::post('/negotiation/percent/add', [NegotiationPercentController::class, 'addNegotiationPercent']); // add NegotiationPercent
        Route::post('/negotiation/percent/data', [NegotiationPercentController::class, 'getNegotiationPercentData']); // get NegotiationPercent data
        Route::post('/negotiation/percent/edit', [NegotiationPercentController::class, 'editNegotiationPercent']); // edit NegotiationPercent
        Route::post('/negotiation/percent/delete', [NegotiationPercentController::class, 'deleteNegotiationPercent']); // delete NegotiationPercent

        ////////////////////////////////
        Route::get('/products', [ProductController::class, 'showProducts'])->name('dashboard-products'); // show Index page that control all products
        Route::get('/products/data', [ProductController::class, 'getProductsData']); // get all products data for DataTable
        Route::get('/product/details/{id}', [ProductController::class, 'getProductsDetails']); // get  product details

        ////////////////////////////////
        Route::get('/shipments', [ShipmentController::class, 'showShipments'])->name('dashboard-shipments'); // show Index page that control all Shipments
        Route::get('/shipments/data', [ShipmentController::class, 'getShipmentsData']); // get all Shipments data for DataTable
        Route::post('/shipment/add', [ShipmentController::class, 'addShipment']); // add Shipment
        Route::post('/shipment/data', [ShipmentController::class, 'getShipmentData']); // get Shipment data
        Route::post('/shipment/edit', [ShipmentController::class, 'editShipment']); // edit Shipment
        Route::post('/shipment/delete', [ShipmentController::class, 'deleteShipment']); // delete Shipment
        Route::post('/shipment/restore', [ShipmentController::class, 'restoreShipment']); // restore Shipment

        ////////////////////////////////
        Route::get('/payment_methods', [PaymentMethodController::class, 'showPaymentMethods'])->name('dashboard-payment_methods'); // show Index page that control all PaymentMethods
        Route::get('/payment_methods/data', [PaymentMethodController::class, 'getPaymentMethodsData']); // get all PaymentMethods data for DataTable
        Route::post('/payment_method/add', [PaymentMethodController::class, 'addPaymentMethod']); // add PaymentMethod
        Route::post('/payment_method/data', [PaymentMethodController::class, 'getPaymentMethodData']); // get PaymentMethod data
        Route::post('/payment_method/edit', [PaymentMethodController::class, 'editPaymentMethod']); // edit PaymentMethod
        Route::post('/payment_method/delete', [PaymentMethodController::class, 'deletePaymentMethod']); // delete PaymentMethod
        Route::post('/payment_method/restore', [PaymentMethodController::class, 'restorePaymentMethod']); // restore PaymentMethod

        ////////////////////////////////
        Route::get('/bank_accounts', [BankAccountController::class, 'showBankAccounts'])->name('dashboard-bank_accounts'); // show Index page that control all BankAccounts
        Route::get('/bank_accounts/data', [BankAccountController::class, 'getBankAccountsData']); // get all BankAccounts data for DataTable
        Route::post('/bank_account/add', [BankAccountController::class, 'addBankAccount']); // add BankAccount
        Route::post('/bank_account/data', [BankAccountController::class, 'getBankAccountData']); // get BankAccount data
        Route::post('/bank_account/edit', [BankAccountController::class, 'editBankAccount']); // edit BankAccount
        Route::post('/bank_account/delete', [BankAccountController::class, 'deleteBankAccount']); // delete BankAccount
        Route::post('/bank_account/restore', [BankAccountController::class, 'restoreBankAccount']); // restore BankAccount

        ////////////////////////////////
        Route::get('/orders/new', [OrderController::class, 'showNewOrders'])->name('dashboard-new_orders'); // show Index page that control all new  orders
        Route::get('/orders/progress', [OrderController::class, 'showProgressOrders'])->name('dashboard-progress_orders'); // show Index page that control all progress  orders
        Route::get('/orders/completed', [OrderController::class, 'showCompletedOrders'])->name('dashboard-completed_orders'); // show Index page that control all completed  orders
        Route::get('/orders/canceled', [OrderController::class, 'showCanceledOrders'])->name('dashboard-canceled_orders'); // show Index page that control all canceled  orders
        Route::get('/orders/refused', [OrderController::class, 'showRefusedOrders'])->name('dashboard-refused_orders'); // show Index page that control all refused  orders
        Route::get('/get/orders/data', [OrderController::class, 'getOrdersData']); // get all  orders data for DataTable
        Route::post('/orders/accept', [OrderController::class, 'approveOrderRefuseRequest']); // approve  Order Refuse Request
        Route::post('/orders/refuse', [OrderController::class, 'refuseOrderRefuseRequest']); // refuse  Order Refuse Request
        Route::get('/order/details/{id}', [OrderController::class, 'getOrderDetails']); // get  orders details

        ////////////////////////////////
        Route::get('/new_bank_transfers', [BankTransferController::class, 'showNewBankTransfers'])->name('dashboard-new_bank_transfers'); // show Index page that control all new Bank Transfers
        Route::get('/approved_bank_transfers', [BankTransferController::class, 'showApprovedBankTransfers'])->name('dashboard-approved_bank_transfers'); // show Index page that control all approved Bank Transfers
        Route::get('/refused_bank_transfers', [BankTransferController::class, 'showRefusedBankTransfers'])->name('dashboard-refused_bank_transfers'); // show Index page that control all refused Bank Transfers
        Route::get('/bank_transfers/data', [BankTransferController::class, 'getBankTransfersData']); // get all BankTransfers data for DataTable
        Route::post('/bank_transfer/change', [BankTransferController::class, 'changeBankTransferStatus']); // add BankTransfer status

        ////////////////////////////////
        Route::get('/new_withdraw_requests', [WithdrawRequestController::class, 'showNewWithdrawRequests'])->name('dashboard-new_withdraw_requests'); // show Index page that control all new withdraw requests
        Route::get('/approved_withdraw_requests', [WithdrawRequestController::class, 'showApprovedWithdrawRequests'])->name('dashboard-approved_withdraw_requests'); // show Index page that control all approved withdraw requests
        Route::get('/refused_withdraw_requests', [WithdrawRequestController::class, 'showRefusedWithdrawRequests'])->name('dashboard-refused_withdraw_requests'); // show Index page that control all refused withdraw requests
        Route::get('/withdraw_requests/data', [WithdrawRequestController::class, 'getWithdrawRequestsData']); // get all WithdrawRequests data for DataTable
        Route::post('/withdraw_request/change', [WithdrawRequestController::class, 'changeWithdrawRequestStatus']); // add WithdrawRequest status


        ////////////////////////////////
        Route::get('/notification', [NotificationController::class, 'showSendNotification'])->name('dashboard-show_send_notification'); // show send notification page
        Route::post('/notification/send', [NotificationController::class, 'sendNotification']); // send Notification

    });
});

?>
