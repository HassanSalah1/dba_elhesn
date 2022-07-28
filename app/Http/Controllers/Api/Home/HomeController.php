<?php

namespace App\Http\Controllers\Api\Home;

use App\Http\Controllers\Controller;
use App\Services\Api\Home\HomeApiService;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function getHome(Request $request)
    {
        $data = $request->all();
        return HomeApiService::getHome($data);
    }


}

?>
