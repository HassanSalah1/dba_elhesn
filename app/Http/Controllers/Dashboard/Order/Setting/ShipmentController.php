<?php

namespace App\Http\Controllers\Dashboard\Order\Setting;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\Order\Setting\ShipmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ShipmentController extends Controller
{
    //
    public function showShipments()
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.shipments_title');
        $data['debatable_names'] = array(trans('admin.name_ar'), trans('admin.name_en'),
            trans('admin.image'), trans('admin.price'), trans('admin.actions'));
        return view('admin.order.settings.shipment')->with($data);
    }

    public function getShipmentsData(Request $request)
    {
        $data = $request->all();
        $data['locale'] = App::getLocale();
        return ShipmentService::getShipmentsData($data);
    }

    public function addShipment(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        return ShipmentService::addShipment($data);
    }

    public function deleteShipment(Request $request)
    {
        $data = $request->all();
        return ShipmentService::deleteShipment($data);
    }

    public function restoreShipment(Request $request)
    {
        $data = $request->all();
        return ShipmentService::restoreShipment($data);
    }


    public function getShipmentData(Request $request)
    {
        $data = $request->all();
        return ShipmentService::getShipmentData($data);
    }

    public function editShipment(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        return ShipmentService::editShipment($data);
    }

}
