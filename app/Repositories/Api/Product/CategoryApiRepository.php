<?php

namespace App\Repositories\Api\Product;

use App\Entities\HttpCode;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryApiRepository
{

    public static function getCategories(array $data)
    {
        $search = [];
        if(isset($data['category_id'])){
            $search['category_id'] = $data['category_id'];
        }else{
            $search['category_id'] = null;
        }
        $categories = Category::withoutTrashed()
            ->where($search)
//            ->where(function ($query) use ($search) {
//                if($search['category_id'] == null){
//                    $query->whereHas('categories');
//                }
//            })
            ->get();
        return [
            'data' => CategoryResource::collection($categories),
            'message' => 'success',
            'code' => HttpCode::SUCCESS
        ];
    }
}

?>
