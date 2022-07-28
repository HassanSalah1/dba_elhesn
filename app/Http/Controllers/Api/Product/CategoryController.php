<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Services\Api\Product\CategoryApiService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //

    public function getCategories(Request $request)
    {
        $data = $request->all();
        return CategoryApiService::getCategories($data);
    }

}

?>
