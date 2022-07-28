<?php

namespace App\Http\Controllers\Site;

use App\Entities\HttpCode;
use App\Entities\NegotiationPeriodType;
use App\Entities\OrderStatus;
use App\Entities\OrderUserType;
use App\Entities\ProductType;
use App\Http\Controllers\Controller;
use App\Models\NegotiationPeriod;
use App\Models\Order;
use App\Models\Product;
use App\Repositories\Api\Order\DamainOrderApiRepository;
use App\Repositories\Api\Order\OrderApiRepository;
use App\Repositories\Api\Order\OrderSettingApiRepository;
use App\Repositories\Api\Product\CategoryApiRepository;
use App\Repositories\Api\Product\ProductApiRepository;
use App\Repositories\Api\Setting\SettingApiRepository;
use App\Repositories\Api\User\UserApiRepository;
use App\Services\Api\Order\DamainOrderApiService;
use App\Services\Api\Order\OrderActionsApiService;
use App\Services\Api\Order\OrderApiService;
use App\Services\Api\Order\OrderOfferApiService;
use App\Services\Api\Product\ProductApiService;
use App\Services\Api\User\UserApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class OrderController extends Controller
{

    public function showAddDamainOrder(Request $request)
    {
        $data['title'] = trans('site.damain');
        $data['locale'] = App::getLocale();
        $user = auth()->user();
        $data['user'] = $user && $user->isActiveCustomerAuth() ? $user : null;
        $data['social'] = SettingApiRepository::loadSocial();
        $data['notifications'] = [];
        if ($data['user']) {
            $filter['web'] = 1;
            $data['notifications'] = (UserApiRepository::getMyNotifications($filter)['data'])->toArray($request);
        }
        $data['type'] = $request->type ?: OrderUserType::SELLER;
        $data['categories'] = CategoryApiRepository::getCategories([])['data'];
        return view('site.order.add_damain')->with($data);
    }

    public function addDamainOrder(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        $data['web'] = 1;
        return DamainOrderApiService::addDamainOrder($data);
    }

    public function showOrders(Request $request)
    {
        $data['title'] = trans('site.orders_title');
        $data['locale'] = App::getLocale();
        $user = auth()->user();
        $data['user'] = $user && $user->isActiveCustomerAuth() ? $user : null;
        $data['social'] = SettingApiRepository::loadSocial();
        $data['notifications'] = [];
        if ($data['user']) {
            $filter['web'] = 1;
            $data['notifications'] = (UserApiRepository::getMyNotifications($filter)['data'])->toArray($request);
        }

        $data['status'] = $request->status ?: 'new';

        $filter = $request->all();
        $filter['web'] = 1;
        $filter['request'] = $request;
        $data['orders'] = OrderApiRepository::getMyOrders($filter)['data'];
        $data['activeClass'] = 'orders';
        return view('site.order.orders')->with($data);
    }

    public function showOrderDetails(Request $request, $id)
    {

        $order = OrderApiRepository::getOrderDetails(['id' => $id, 'web' => 1, 'request' => $request]);
        if ($order['code'] === HttpCode::ERROR) {
            return redirect()->to(url('/orders'));
        }
        $order = $order['data'];
        $data['title'] = trans('site.order_details_title') . ' #' . $order->id;
        $data['locale'] = App::getLocale();
        $user = auth()->user();
        $data['user'] = $user && $user->isActiveCustomerAuth() ? $user : null;
        $data['social'] = SettingApiRepository::loadSocial();
        $data['notifications'] = [];
        if ($data['user']) {
            $filter['web'] = 1;
            $data['notifications'] = (UserApiRepository::getMyNotifications($filter)['data'])->toArray($request);
        }
        $order = $order->toArray($request);
        $data['order'] = $order;
        if (isset($order['actions']['can_payment']) && $order['actions']['can_payment']) {
            $data['paymentMethods'] = (OrderSettingApiRepository::getPaymentMethods([])['data'])->toArray($request);
            $data['bankAccounts'] = (OrderSettingApiRepository::getBankAccounts([])['data'])->toArray($request);
        }
        if (isset($order['actions']['can_shipment']) && $order['actions']['can_shipment']) {
            $data['shipments'] = (OrderSettingApiRepository::getShipments([])['data'])->toArray($request);
        }

        return view('site.order.order_details')->with($data);
    }

    public function showDamainOrderDetails(Request $request, $id)
    {
        $order = DamainOrderApiRepository::getDamainOrderDetails(['id' => $id]);
        if ($order['code'] === HttpCode::ERROR) {
            return redirect()->to(url('/orders'));
        }
        $order = $order['data'];
        $data['title'] = trans('site.order_details_title') . ' #' . $order->id;
        $data['locale'] = App::getLocale();
        $user = auth()->user();
        $data['user'] = $user && $user->isActiveCustomerAuth() ? $user : null;
        $data['social'] = SettingApiRepository::loadSocial();
        $data['notifications'] = [];
        if ($data['user']) {
            $filter['web'] = 1;
            $data['notifications'] = (UserApiRepository::getMyNotifications($filter)['data'])->toArray($request);
        }
        $order = $order->toArray($request);
        $data['order'] = $order;
        if (isset($order['actions']['can_payment']) && $order['actions']['can_payment']) {
            $data['paymentMethods'] = (OrderSettingApiRepository::getPaymentMethods([])['data'])->toArray($request);
            $data['bankAccounts'] = (OrderSettingApiRepository::getBankAccounts([])['data'])->toArray($request);
        }
        if (isset($order['actions']['can_shipment']) && $order['actions']['can_shipment']) {
            $data['shipments'] = (OrderSettingApiRepository::getShipments([])['data'])->toArray($request);
        }

        return view('site.order.order_details')->with($data);
    }

    public function acceptOrder(Request $request)
    {
        $data = $request->all();
        return OrderActionsApiService::acceptOrder($data);
    }

    public function refuseOrder(Request $request)
    {
        $data = $request->all();
        return OrderActionsApiService::refuseOrder($data);
    }

    public function cancelOrder(Request $request)
    {
        $data = $request->all();
        return OrderActionsApiService::cancelOrder($data);
    }


    public function showAddProductToDamainOrder(Request $request, $id)
    {

        $order = Order::find($id);
        if (!$order || !$order->actions()['add_product']) {
            return redirect()->to(url('/orders'));
        }
        $data['title'] = trans('site.add_product');
        $data['locale'] = App::getLocale();
        $user = auth()->user();
        $data['user'] = $user && $user->isActiveCustomerAuth() ? $user : null;
        $data['social'] = SettingApiRepository::loadSocial();
        $data['notifications'] = [];
        if ($data['user']) {
            $filter['web'] = 1;
            $data['notifications'] = (UserApiRepository::getMyNotifications($filter)['data'])->toArray($request);
        }
        $data['order'] = $order;
        $data['categories'] = CategoryApiRepository::getCategories([])['data'];

        return view('site.order.add_product')->with($data);
    }

    public function addProductToDamainOrder(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        $data['web'] = 1;
        return DamainOrderApiService::addProductToDamainOrder($data);
    }

//
    public function showEditProductToDamainOrder(Request $request, $id)
    {

        $order = Order::find($id);
        if (!$order || !$order->actions()['can_edit']) {
            return redirect()->to(url('/orders'));
        }
        $data['title'] = trans('site.can_edit');
        $data['locale'] = App::getLocale();
        $user = auth()->user();
        $data['user'] = $user && $user->isActiveCustomerAuth() ? $user : null;
        $data['social'] = SettingApiRepository::loadSocial();
        $data['notifications'] = [];
        if ($data['user']) {
            $filter['web'] = 1;
            $data['notifications'] = (UserApiRepository::getMyNotifications($filter)['data'])->toArray($request);
        }
        $data['order'] = $order;
        $data['categories'] = CategoryApiRepository::getCategories([])['data'];
        $data['sub_categories'] = CategoryApiRepository::getCategories(['category_id' => @$order->damainOrder->category_id])['data'];
        $data['sub_sub_categories'] = CategoryApiRepository::getCategories(['category_id' => @$order->damainOrder->sub_category_id])['data'];


        return view('site.order.edit_product')->with($data);
    }

    public function removeImage(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;
        return SettingApiRepository::removeGeneralImage($data);
    }

    public function editDamainOrderProduct(Request $request)
    {
        $data = $request->all();
        return DamainOrderApiService::editDamainOrderProduct($data);
    }

    public function payOrder(Request $request)
    {
        $data = $request->all();
        $data['platform'] = 'web';
        return OrderActionsApiService::payOrder($data);
    }

    public function makeOrderShipped(Request $request)
    {
        $data = $request->all();
        $data['platform'] = 'web';
        return OrderActionsApiService::makeOrderShipped($data);
    }

    public function acceptOrderDelivery(Request $request)
    {
        $data = $request->all();
        return OrderActionsApiService::acceptOrderDelivery($data);
    }

    public function refuseOrderDelivery(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        $data['web'] = 1;
        return OrderActionsApiService::refuseOrderDelivery($data);
    }


    public function addDirectOrder(Request $request)
    {
        $data = $request->all();
        return OrderApiService::addDirectOrder($data);
    }

    public function addProductNegotiationOffer(Request $request)
    {
        $data = $request->all();
        return OrderOfferApiService::addProductNegotiationOffer($data);
    }

    public function acceptNegotiationOffer(Request $request)
    {
        $data = $request->all();
        return OrderOfferApiService::acceptNegotiationOffer($data);
    }

    public function refuseNegotiationOffer(Request $request)
    {
        $data = $request->all();
        return OrderOfferApiService::refuseNegotiationOffer($data);
    }

    public function addProductBidOffer(Request $request)
    {
        $data = $request->all();
        return OrderOfferApiService::addProductBidOffer($data);
    }

    public function acceptBidOffer(Request $request)
    {
        $data = $request->all();
        return OrderOfferApiService::acceptBidOffer($data);
    }

    public function refuseBidOffer(Request $request)
    {
        $data = $request->all();
        return OrderOfferApiService::refuseBidOffer($data);
    }

    public function showBids(Request $request)
    {
        $data['title'] = trans('site.bids_title');
        $data['locale'] = App::getLocale();
        $user = auth()->user();
        $data['user'] = $user && $user->isActiveCustomerAuth() ? $user : null;
        $data['social'] = SettingApiRepository::loadSocial();
        $data['notifications'] = [];
        if ($data['user']) {
            $filter['web'] = 1;
            $data['notifications'] = (UserApiRepository::getMyNotifications($filter)['data'])->toArray($request);
        }

        $data['status'] = $request->status ?: 'new';

        $filter = $request->all();
        $filter['web'] = 1;
        $filter['request'] = $request;
        $data['orders'] = OrderApiRepository::getMyBidOrders($filter)['data'];
        $data['activeClass'] = 'bids';
        return view('site.order.bids')->with($data);
    }


}
