<?php

namespace App\Services\Api\Product;


use App\Repositories\Api\Product\CategoryApiRepository;
use App\Repositories\General\UtilsRepository;

class CategoryApiService
{

    public static function getCategories(array $data)
    {
        $response = CategoryApiRepository::getCategories($data);
        return UtilsRepository::handleResponseApi($response);
    }
}
?>
