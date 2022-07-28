<?php

namespace App\Http\Controllers\Dashboard\Order;

use App\Entities\OrderType;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Dashboard\Order\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class OrderController extends Controller
{
    //
    public function showNewOrders()
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.new_orders_title');
        $data['debatable_names'] = array('#', trans('admin.buyer_details'),
            trans('admin.seller_details'), trans('admin.order_type'),
            trans('admin.status'), trans('admin.actions'));
        $data['status'] = 'new';
        return view('admin.order.order')->with($data);
    }

    public function getOrdersData(Request $request)
    {
        $data = $request->all();
        $data['locale'] = App::getLocale();
        return OrderService::getOrdersData($data);
    }


    public function showProgressOrders()
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.progress_orders_title');
        $data['debatable_names'] = array('#', trans('admin.buyer_details'),
            trans('admin.seller_details'), trans('admin.order_type'),
            trans('admin.status'), trans('admin.actions'));
        $data['status'] = 'progress';
        return view('admin.order.order')->with($data);
    }

    public function showCompletedOrders()
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.completed_orders_title');
        $data['debatable_names'] = array('#', trans('admin.buyer_details'),
            trans('admin.seller_details'), trans('admin.order_type'),
            trans('admin.status'), trans('admin.actions'));
        $data['status'] = 'completed';
        return view('admin.order.order')->with($data);
    }

    public function showCanceledOrders()
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.canceled_orders_title');
        $data['debatable_names'] = array('#', trans('admin.buyer_details'),
            trans('admin.seller_details'), trans('admin.order_type'),
            trans('admin.status'), trans('admin.actions'));
        $data['status'] = 'canceled';
        return view('admin.order.order')->with($data);
    }

    public function getOrderDetails($id)
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $order = Order::find($id);
        if (!$order) {
            return abort(404);
        }
        $data['title'] = trans('admin.order_details') . ' #' . $order->id;
        $data['order'] = $order;
        if ($order->normalOrder) {
            if($order->normalOrder->product) {
                $order->image = $order->normalOrder->product->image();
            }else{
                $order->image = $order->offers[0]->product->image();
            }
        }else{
            $order->images = $order->images();
        }
        $template_name = '';
        if ($order->type === OrderType::DIRECT) {
            $template_name = 'order_direct_details';
        }else if ($order->type === OrderType::DAMAIN) {
            $template_name = 'order_damain_details';
        }else if ($order->type === OrderType::BID) {
            $template_name = 'order_bid_details';

        }else if ($order->type === OrderType::NEGOTIATION) {
            $template_name = 'order_negotiation_details';
        }
        return view('admin.order.' . $template_name)->with($data);
    }

    public function showRefusedOrders()
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.refused_orders_title');
        $data['debatable_names'] = array('#', trans('admin.buyer_details'),
            trans('admin.seller_details'), trans('admin.order_type'),
            trans('admin.reason'), trans('admin.actions'));
        $data['status'] = 'refused';
        return view('admin.order.order')->with($data);
    }


    public function approveOrderRefuseRequest(Request $request)
    {
        $data = $request->all();
        return OrderService::approveOrderRefuseRequest($data);
    }

    public function refuseOrderRefuseRequest(Request $request)
    {
        $data = $request->all();
        return OrderService::refuseOrderRefuseRequest($data);
    }
}
