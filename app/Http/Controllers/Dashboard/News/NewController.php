<?php

namespace App\Http\Controllers\Dashboard\News;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\Dashboard\News\NewService;
use App\Models\News;
use Illuminate\Http\Request;

class NewController extends Controller
{
    //
    public function showNews()
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.news_title');
        $data['debatable_names'] = array(trans('admin.title'), trans('admin.image'),
            trans('admin.actions'));
        return view('admin.news.index')->with($data);
    }

    public function getNewsData(Request $request)
    {
        $data = $request->all();
        return NewService::getNewsData($data);
    }

    public function showAddNew()
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.add_new');
        $data['categories'] = Category::all();
        return view('admin.news.add_new')->with($data);
    }

    public function addNew(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        return NewService::addNew($data);
    }

    public function deleteNew(Request $request)
    {
        $data = $request->all();
        return NewService::deleteNew($data);
    }


    public function showEditNew($id)
    {
        $new = News::where(['id' => $id])->first();
        if (!$new) {
            return redirect()->to('/admin/news');
        }
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $new->image = url($new->image()->image);
        $data['new'] = $new;
        $data['title'] = trans('admin.edit_new');
        $data['categories'] = Category::all();
        return view('admin.news.add_new')->with($data);
    }

    public function editNew(Request $request, $id)
    {
        $data = $request->all();
        $data['request'] = $request;
        $data['id'] = $id;
        return NewService::editNew($data);
    }

    public function removeImage(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;
        return NewService::removeImage($data);
    }

}
