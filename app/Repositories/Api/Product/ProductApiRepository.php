<?php

namespace App\Repositories\Api\Product;

use App\Entities\HttpCode;
use App\Entities\ProductType;
use App\Http\Resources\CommentResource;
use App\Http\Resources\NegotiationPercentageResource;
use App\Http\Resources\NegotiationPeriodResource;
use App\Http\Resources\ProductDetailsResource;
use App\Http\Resources\ProductImageResource;
use App\Models\NegotiationPercentage;
use App\Models\NegotiationPeriod;
use App\Models\Product;
use App\Models\ProductComment;
use App\Models\ProductImage;
use App\Repositories\General\UtilsRepository;
use Illuminate\Support\Facades\DB;

class ProductApiRepository
{

    public static function getNegotiationPercent(array $data)
    {
        $negotiationPercent = NegotiationPercentage::orderBy('id', 'DESC')->get();
        return [
            'data' => NegotiationPercentageResource::collection($negotiationPercent),
            'message' => 'success',
            'code' => HttpCode::SUCCESS
        ];
    }

    public static function getNegotiationPeriod(array $data)
    {
        $negotiationPeriod = NegotiationPeriod::orderBy('id', 'DESC')->get();
        return [
            'data' => NegotiationPeriodResource::collection($negotiationPeriod),
            'message' => 'success',
            'code' => HttpCode::SUCCESS
        ];
    }

    public static function uploadProductImage(array $data)
    {
        $file_id = 'IMG_' . mt_rand(00000, 99999) . (time() + mt_rand(00000, 99999));
        $image_name = 'image';
        $image_path = 'uploads/products/';
        $image = UtilsRepository::createImage($data['request'], $image_name, $image_path, $file_id);
        if ($image !== false) {
            $productImage = ProductImage::create([
                'image' => $image,
            ]);
            return [
                'data' => [
                    'product_image' => ProductImageResource::make($productImage)
                ],
                'message' => trans('api.done_successfully'),
                'code' => HttpCode::SUCCESS
            ];
        }
        return [
            'message' => trans('api.general_error_message'),
            'code' => HttpCode::ERROR
        ];
    }

    public static function removeProductImage(array $data)
    {
        $productImage = ProductImage::find($data['id']);
        if ($productImage) {
            $productImage->forceDelete();
            return [
                'message' => trans('api.done_successfully'),
                'code' => HttpCode::SUCCESS
            ];
        }
        return [
            'message' => trans('api.general_error_message'),
            'code' => HttpCode::ERROR
        ];
    }

    public static function addProduct(array $data)
    {
        $product = null;
        DB::beginTransaction();
        try {
            $negotiationPeriod = null;
            if ((isset($data['period']) && $data['type'] === ProductType::BID)) {
                $negotiationPeriod = NegotiationPeriod::where(['id' => $data['period']])->first();
                if (!$negotiationPeriod) {
                    return [
                        'message' => trans('api.general_error_message'),
                        'code' => HttpCode::ERROR
                    ];
                }
            }
            $productData = [
                'name' => $data['name'],
                'description' => $data['description'],
                'category_id' => $data['category_id'],
                'price' => $data['price'],
                'type' => $data['type'],
                'sub_category_id' => (isset($data['sub_category_id'])) ? $data['sub_category_id'] : null,
                'sub_sub_category_id' => (isset($data['sub_sub_category_id'])) ? $data['sub_sub_category_id'] : null,
                'max_price' => (isset($data['max_price']) && $data['type'] === ProductType::BID) ?
                    $data['max_price'] : null,
                'show_user' => (isset($data['show_user']) && $data['type'] === ProductType::DIRECT) ?
                    $data['show_user'] : null,
                'negotiation' => (isset($data['negotiation']) && $data['type'] === ProductType::DIRECT) ?
                    $data['negotiation'] : null,
                'percent' => (isset($data['percent']) && $data['type'] === ProductType::DIRECT) ?
                    $data['percent'] : null,
                'period' => (isset($data['period']) && $data['type'] === ProductType::BID && $negotiationPeriod) ?
                    $negotiationPeriod->period : null,
                'period_type' => (isset($data['period']) && $data['type'] === ProductType::BID && $negotiationPeriod) ?
                    $negotiationPeriod->type : null,
                'user_id' => auth()->user()->id,
                'fields' => null,
            ];
            if (isset($data['fields'])) {
                $productData['fields'] = json_encode($data['fields']);
            }
            $product = Product::create($productData);
            if (isset($data['web']) && isset($data['request']) && $data['request']->hasFile('images')) {
                $files = $data['request']->file('images');
                foreach ($files as $file) {
                    $file_id = 'IMG_' . mt_rand(00000, 99999) . (time() + mt_rand(00000, 99999));
                    $image_path = 'uploads/products/';
                    $image = UtilsRepository::uploadImage($data['request'], $file, $image_path, $file_id);
                    if($image){
                        ProductImage::create([
                            'image' => $image,
                            'product_id' => $product->id
                        ]);
                    }
                }
            } else if (isset($data['images']) && !empty($data['images'])) {
                $data['images'] = explode(',', $data['images']);
                foreach ($data['images'] as $imageId) {
                    $productImage = ProductImage::find($imageId);
                    if ($productImage) {
                        $productImage->update([
                            'product_id' => $product->id,
                        ]);
                    }
                }
            }
            DB::commit();    // Commiting  ==> There is no problem whatsoever
        } catch (\Exception $e) {
            DB::rollback();   // rollbacking  ==> Something went wrong
            return [
                'message' => trans('api.general_error_message'),
                'code' => HttpCode::ERROR
            ];
        }
        if ($product) {
            return [
                'data' => [
                    'product' => ProductDetailsResource::make($product),
                ],
                'message' => trans('api.done_successfully'),
                'code' => HttpCode::SUCCESS
            ];
        }
        return [
            'message' => trans('api.general_error_message'),
            'code' => HttpCode::ERROR
        ];
    }

    public static function editProduct(array $data)
    {
        DB::beginTransaction();
        try {
            $product = Product::withoutTrashed()
                ->where(['id' => $data['id'], 'user_id' => auth()->user()->id])->first();
            if ($product) {
                $negotiationPeriod = null;
                if ((isset($data['period']) && $data['type'] === ProductType::BID)) {
                    $negotiationPeriod = NegotiationPeriod::where(['id' => $data['period']])->first();
                    if (!$negotiationPeriod) {
                        return [
                            'message' => trans('api.general_error_message'),
                            'code' => HttpCode::ERROR
                        ];
                    }
                }

                $productData = [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'price' => $data['price'],
                    'type' => $data['type'],
                    'max_price' => (isset($data['max_price']) && $data['type'] === ProductType::BID) ?
                        $data['max_price'] : $product->max_price,
                    'show_user' => (isset($data['show_user']) && $data['type'] === ProductType::DIRECT) ?
                        $data['show_user'] : $product->show_user,
                    'negotiation' => (isset($data['negotiation']) && $data['type'] === ProductType::DIRECT) ?
                        $data['negotiation'] : $product->negotiation,
                    'percent' => (isset($data['percent']) && $data['type'] === ProductType::DIRECT) ?
                        $data['percent'] : $product->percent,
                    'period' => (isset($data['period']) && $data['type'] === ProductType::BID && $negotiationPeriod) ?
                        $negotiationPeriod->period : $product->period,
                    'period_type' => (isset($data['period']) && $data['type'] === ProductType::BID && $negotiationPeriod) ?
                        $negotiationPeriod->type : $product->period_type,
                    'user_id' => auth()->user()->id,
                ];
                if (isset($data['fields'])) {
                    $productData['fields'] = json_encode($data['fields']);
                }
                $product->update($productData);
                if (isset($data['images']) && !empty($data['images'])) {
                    $data['images'] = explode(',', $data['images']);
                    foreach ($data['images'] as $imageId) {
                        $productImage = ProductImage::find($imageId);
                        if ($productImage) {
                            $productImage->update([
                                'product_id' => $product->id,
                            ]);
                        }
                    }
                }
            }
            DB::commit();    // Commiting  ==> There is no problem whatsoever
            if ($product) {
                return [
                    'data' => [
                        'product' => ProductDetailsResource::make($product),
                    ],
                    'message' => trans('api.done_successfully'),
                    'code' => HttpCode::SUCCESS
                ];
            }
        } catch (\Exception $e) {
            DB::rollback();   // rollbacking  ==> Something went wrong
        }
        return [
            'message' => trans('api.general_error_message'),
            'code' => HttpCode::ERROR
        ];
    }

    public static function deleteProduct(array $data)
    {
        $product = Product::withoutTrashed()
            ->where([
                'id' => $data['id'],
                'user_id' => auth()->id(),
            ])->first();
        if ($product) {
            $product->delete();
            return [
                'message' => trans('api.done_successfully'),
                'code' => HttpCode::SUCCESS
            ];
        }
        return [
            'message' => trans('api.general_error_message'),
            'code' => HttpCode::ERROR
        ];
    }

    public static function addProductComment(array $data)
    {
        $commentData = [
            'product_id' => $data['product_id'],
            'comment' => $data['comment'],
            'user_id' => auth()->id()
        ];
        $created = ProductComment::create($commentData);
        if ($created) {
            return [
                'data' => [
                    'comment' => CommentResource::make($created)
                ],
                'message' => trans('api.done_successfully'),
                'code' => HttpCode::SUCCESS
            ];
        }
        return [
            'message' => trans('api.general_error_message'),
            'code' => HttpCode::ERROR
        ];
    }

    public static function getProductDetails(array $data)
    {
        $web = isset($data['web']) ? 1 : 0;
        $product = Product::withoutTrashed()
            ->where([
                'id' => $data['id'],
            ])->first();
        if ($product) {
            $resource = ProductDetailsResource::make($product);
            if ($web) {
                $resource = $resource->toArray($data['request']);
            }
            return [
                'data' => $resource,
                'message' => trans('api.done_successfully'),
                'code' => HttpCode::SUCCESS
            ];
        }
        return [
            'message' => trans('api.general_error_message'),
            'code' => HttpCode::ERROR
        ];
    }


}

?>
