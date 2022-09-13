<?php

namespace App\Http\Controllers\Dashboard\News;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\Dashboard\News\CategoryService;
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
            trans('admin.actions'));
        return view('admin.news.category')->with($data);
    }

    public function getCategoriesData(Request $request)
    {
        $data = $request->all();
        return CategoryService::getCategoriesData($data);
    }

    public function addCategory(Request $request)
    {
        $data = $request->all();
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
        return CategoryService::editCategory($data);
    }

    public function restoreCategory(Request $request)
    {
        $data = $request->all();
        return CategoryService::restoreCategory($data);
    }

}
