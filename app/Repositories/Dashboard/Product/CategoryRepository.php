<?php
namespace App\Repositories\Dashboard\Product;


use App\Models\Category;
use Yajra\DataTables\Facades\DataTables;

class CategoryRepository
{

    // get Categories and create datatable data.
    public static function getCategoriesData(array $data)
    {
        $categories = Category::withTrashed()
            ->where(['category_id' => null])
            ->orderBy('id', 'DESC');
        return DataTables::of($categories)
            ->addColumn('subcategories_count', function ($category) {
                $count = Category::withTrashed()->where('category_id', '=', $category->id)->count();
                return '<a href="' . url('/admin/categories/sub/' . $category->id)
                    . '"  style="text-decoration: underline;">' . $count . '</a>';
            })
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


    public static function getSubCategoriesData(array $data)
    {
        $categories = Category::withTrashed()->where('category_id', '=', $data['id'])->get();
        return DataTables::of($categories)
            ->addColumn('subcategories_count', function ($category) {
                $count = Category::withTrashed()->where('category_id', '=', $category->id)->count();
                return '<a href="' . url('/admin/categories/sub2/' . $category->id)
                    . '"  style="text-decoration: underline;">' . $count . '</a>';
            })
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
            'category_id' => (isset($data['category_id'])) ? $data['category_id'] : null,
            'fields' => null,
        ];
        if (isset($data['field_name'])) {
            $categoryData['fields'] = json_encode(array_map(function ($field) {
                return [
                    'key' => $field
                ];
            }, $data['field_name']));
        }

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
                'fields' => null,
            ];
            if (isset($data['field_name'])) {
                $categoryData['fields'] = json_encode(array_map(function ($field) {
                    return [
                        'key' => $field
                    ];
                }, $data['field_name']));
            }
            $updated = $category->update($categoryData);
            if ($updated) {
                return true;
            }
        }
        return false;
    }

}

?>
