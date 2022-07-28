<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Services\Api\Order\OrderOfferApiService;
use Illuminate\Http\Request;

class OrderOfferController extends Controller
{

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

}
