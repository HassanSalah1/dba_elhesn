<?php

namespace App\Http\Controllers\Dashboard\Product;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\Dashboard\Product\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class CategoryController extends Controller
{
    //
    public function showCategories()
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.categories_title');
        $data['debatable_names'] = array(trans('admin.name_ar'), trans('admin.name_en'),
            trans('admin.sub_categories_count'), trans('admin.actions'));
        return view('admin.product.category.category')->with($data);
    }

    public function getCategoriesData(Request $request)
    {
        $data = $request->all();
        $data['locale'] = App::getLocale();
        return CategoryService::getCategoriesData($data);
    }

    public function addCategory(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        return CategoryService::addCategory($data);
    }

    public function deleteCategory(Request $request)
    {
        $data = $request->all();
        return CategoryService::deleteCategory($data);
    }

    public function getCategoryData(Request $request)
    {
        $data = $request->all();
        return CategoryService::getCategoryData($data);
    }


    public function editCategory(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        return CategoryService::editCategory($data);
    }

    public function restoreCategory(Request $request)
    {
        $data = $request->all();
        return CategoryService::restoreCategory($data);
    }


    public function showSubCategories($id)
    {
        $category = Category::find($id);
        if (!$category || $category->category_id !== null) {
            return redirect()->to(url('/admin/categories'));
        }
        $data['pageConfigs'] = [
            'pageHeader' => true,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.subcategories') . ' - (' . $category->name . ')';
        $data['breadcrumbs'] = [
            [
                'name' => trans('admin.categories_title'),
                'link' => url('/admin/categories')
            ],
            [
                'name' => $data['title'],
            ]
        ];
        $data['id'] = $id;
        $data['debatable_names'] = array(trans('admin.name_ar'), trans('admin.name_en'),
            trans('admin.sub_categories_count'), trans('admin.actions'));
        return view('admin.product.category.subcategory')->with($data);
    }

    public function getSubCategoriesData($id, Request $request)
    {
        $data = $request->all();
        $data['id'] = $id;
        return CategoryService::getSubCategoriesData($data);
    }


    public function showSubCategoriesLevel3($id)
    {
        $category = Category::find($id);
        if (!$category || !$category->category || $category->category->category_id !== null) {
            return redirect()->to(url('/admin/categories'));
        }
        $data['pageConfigs'] = [
            'pageHeader' => true,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.subcategories') . ' - (' . $category->name . ')';
        $data['breadcrumbs'] = [
            [
                'name' => trans('admin.categories_title'),
                'link' => url('/admin/categories')
            ],
            [
                'name' => trans('admin.subcategories') . ' - (' . $category->category->name . ')',
                'link' => url('/admin/categories/sub/'.$category->category->id)
            ],
            [
                'name' => $data['title'],
            ]
        ];
        $data['id'] = $id;
        $data['debatable_names'] = array(trans('admin.name_ar'), trans('admin.name_en'),
            trans('admin.actions'));
        return view('admin.product.category.sub_subcategory')->with($data);
    }

}
