<?php
namespace App\Repositories\Dashboard\News;


use App\Entities\ImageType;
use App\Models\News;
use App\Models\Image;
use App\Repositories\General\UtilsRepository;
use Yajra\DataTables\Facades\DataTables;

class NewRepository
{

    // get News and create datatable data.
    public static function getNewsData(array $data)
    {
        $news = News::orderBy('id', 'DESC')->get();
        return DataTables::of($news)
            ->editColumn('image', function ($new) {
                if ($new->image()) {
                    return '<a href="' . url($new->image()->image) . '" data-popup="lightbox">
                    <img src="' . url($new->image()->image) . '" class="img-rounded img-preview"
                    style="max-height:50px;max-width:50px;"></a>';
                }
            })
            ->addColumn('actions', function ($new) {
                $ul = '';
                $ul .= '<a data-toggle="tooltip" title="' . trans('admin.edit') . '" id="' . $new->id . '" href="' . url('/admin/new/edit/' . $new->id) . '" class="on-default edit-row btn btn-info"><i data-feather="edit"></i></a>
                   ';
                $ul .= '<a data-toggle="tooltip" title="' . trans('admin.delete_action') . '" id="' . $new->id . '" onclick="deleteNew(this);return false;" href="#" class="on-default remove-row btn btn-danger"><i data-feather="delete"></i></a>';
                return $ul;
            })->make(true);
    }

    public static function addNew(array $data)
    {
        $newData = [
            'title_ar' => $data['title_ar'],
            'title_en' => $data['title_en'],
            'short_description_ar' => $data['short_description_ar'],
            'short_description_en' => $data['short_description_en'],
            'description_ar' => $data['description_ar'],
            'description_en' => $data['description_en'],
            'category_id' => $data['category_id'],
        ];

        $created = News::create($newData);
        if ($created) {
            $file_id = 'IMG_' . mt_rand(00000, 99999) . (time() + mt_rand(00000, 99999));
            $image_name = 'image';
            $image_path = 'uploads/news/';
            $image = UtilsRepository::createImage($data['request'], $image_name, $image_path, $file_id);
            if ($image !== false) {
                Image::create([
                    'item_id' => $created->id,
                    'item_type' => ImageType::NEWS,
                    'image' => $image,
                    'primary' => 1
                ]);
            }


            $images = $data['request']->file('images');
            if($images){
                foreach ($images as $image) {
                    $file_id = 'IMG_' . mt_rand(00000, 99999) . (time() + mt_rand(00000, 99999));
                    $image_name = $image;
                    $image = UtilsRepository::uploadImage($data['request'], $image_name, $image_path, $file_id);
                    if ($image !== false) {
                        Image::create([
                            'item_id' => $created->id,
                            'item_type' => ImageType::NEWS,
                            'image' => $image,
                            'primary' => 0
                        ]);
                    }
                }
            }


            return true;
        }
        return false;
    }

    public static function deleteNew(array $data)
    {
        $new = News::where(['id' => $data['id']])->first();
        if ($new) {
            if (file_exists($new->image)) {
                unlink($new->image);
            }
            $new->forceDelete();
            return true;
        }
        return false;
    }

    public static function removeImage(array $data)
    {
        $image = Image::where(['id' => $data['id'], 'item_type' => ImageType::NEWS])->first();
        if ($image) {
            if (file_exists($image->image)) {
                unlink($image->image);
            }
            $image->forceDelete();
            return true;
        }
        return false;
    }

    public static function getNewData(array $data)
    {
        $new = News::where(['id' => $data['id']])->first();
        if ($new) {
            $new->image = url($new->image);
            return $new;
        }
        return false;
    }

    public static function editNew(array $data)
    {
        $new = News::where(['id' => $data['id']])->first();
        if ($new) {
            $newData = [
                'title_ar' => $data['title_ar'],
                'title_en' => $data['title_en'],
                'short_description_ar' => $data['short_description_ar'],
                'short_description_en' => $data['short_description_en'],
                'description_ar' => $data['description_ar'],
                'description_en' => $data['description_en'],
                'category_id' => $data['category_id'],
            ];
            if ($data['request']->hasFile('image')) {
                $file_id = 'IMG_' . mt_rand(00000, 99999) . (time() + mt_rand(00000, 99999));
                $image_name = 'image';
                $image_path = 'uploads/news/';
                $image = UtilsRepository::createImage($data['request'], $image_name, $image_path, $file_id, 550, 330);
                if ($image !== false) {
                    if ($new->image() && file_exists($new->image()->image)) {
                        unlink($new->image()->image);
                    }
                    $imageObj = $new->image() ?: new Image();
                    $imageObj->item_id = $new->id;
                    $imageObj->item_type = ImageType::NEWS;
                    $imageObj->primary = 1;
                    $imageObj->image = $image;
                    $imageObj->save();
                }
            }

            $images = $data['request']->file('images');
            if ($images){
                foreach ($images as $image) {
                    $file_id = 'IMG_' . mt_rand(00000, 99999) . (time() + mt_rand(00000, 99999));
                    $image_path = 'uploads/news/';
                    $image_name = $image;
                    $image = UtilsRepository::uploadImage($data['request'], $image_name, $image_path, $file_id);
                    if ($image !== false) {
                        Image::create([
                            'item_id' => $new->id,
                            'item_type' => ImageType::NEWS,
                            'image' => $image,
                            'primary' => 0
                        ]);
                    }
                }
            }

            $updated = $new->update($newData);
            if ($updated) {
                return true;
            }
        }
        return false;
    }

}

?>
