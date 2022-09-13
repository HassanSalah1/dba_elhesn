<?php
namespace App\Repositories\Dashboard\News;


use App\Models\Category;
use Yajra\DataTables\Facades\DataTables;

class CategoryRepository
{

    // get Categories and create datatable data.
    public static function getCategoriesData(array $data)
    {
        $categories = Category::withTrashed()
            ->orderBy('id', 'DESC');
        return DataTables::of($categories)
            ->addColumn('actions', function ($category) {
                $ul = '';
                if ($category->deleted_at === null) {
                    $ul .= '<a data-toggle="tooltip" title="' . trans('admin.edit') . '" id="' . $category->id . '" onclick="editCategory(this);return false;" href="#" class="on-default edit-row btn btn-info"><i data-feather="edit"></i></a>
                   ';
                    $ul .= '<a data-toggle="tooltip" title="' . trans('admin.delete_action') . '" id="' . $category->id . '" onclick="deleteCategory(this);return false;" href="#" class="on-default remove-row btn btn-danger"><i data-feather="delete"></i></a>';
                } else {
                    $ul .= '<a data-toggle="tooltip" title="' . trans('admin.restore_action') . '" id="' . $category->id . '" onclick="restoreCategory(this);return false;" href="#" class="on-default remove-row btn btn-success"><i data-feather="refresh-cw"></i></a>';
                }
                return $ul;
            })->make(true);
    }


    public static function addCategory(array $data)
    {
        $categoryData = [
            'name_ar' => $data['name_ar'],
            'name_en' => $data['name_en'],
        ];

        $created = Category::create($categoryData);
        if ($created) {
            return true;
        }
        return false;
    }

    public static function deleteCategory(array $data)
    {
        $category = Category::where(['id' => $data['id']])->first();
        if ($category) {
            $category->delete();
            return true;
        }
        return false;
    }

    public static function restoreCategory(array $data)
    {
        $category = Category::withTrashed()->where(['id' => $data['id']])->first();
        if ($category) {
            $category->restore();
            return true;
        }
        return false;
    }

    public static function getCategoryData(array $data)
    {
        $category = Category::where(['id' => $data['id']])->first();
        if ($category) {
            return $category;
        }
        return false;
    }

    public static function editCategory(array $data)
    {
        $category = Category::where(['id' => $data['id']])->first();
        if ($category) {
            $categoryData = [
                'name_ar' => $data['name_ar'],
                'name_en' => $data['name_en'],
            ];
            $updated = $category->update($categoryData);
            if ($updated) {
                return true;
            }
        }
        return false;
    }

}

?>
