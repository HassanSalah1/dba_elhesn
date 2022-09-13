<?php
namespace App\Services\Dashboard\News;


use App\Repositories\Dashboard\News\ProductRepository;

class ProductService
{

    public static function getProductsData(array $data)
    {
        return ProductRepository::getProductsData($data);
    }

}

?>
