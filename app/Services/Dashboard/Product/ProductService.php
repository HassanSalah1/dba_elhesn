<?php
namespace App\Services\Dashboard\Product;


use App\Repositories\Dashboard\Product\ProductRepository;

class ProductService
{

    public static function getProductsData(array $data)
    {
        return ProductRepository::getProductsData($data);
    }

}

?>
