<?php

namespace App\Http\Controllers\Dashboard\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Dashboard\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class UserController extends Controller
{
    //
    public function showUsers()
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.users_title');
        $data['debatable_names'] = [trans('admin.name'), trans('admin.phone'),
            trans('admin.email'), trans('admin.registration_date'), trans('admin.status'),
            trans('admin.actions')];
        return view('admin.user.users')->with($data);
    }

    public function getUsersData(Request $request)
    {
        $data = $request->all();
        $data['locale'] = App::getLocale();
        return UserService::getUsersData($data);
    }

    public function verifyUser(Request $request)
    {
        $data = $request->all();
        return UserService::verifyUser($data);
    }

    public function changeStatus(Request $request)
    {
        $data = $request->all();
        return UserService::changeStatus($data);
    }


    public function showUserDetails($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->to(url('/admin/users'));
        }
        $data['pageConfigs'] = [
            'pageHeader' => true,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.user_details_title') . ' - ' . $user->name;
        $data['breadcrumbs'] = [
            [
                'name' => trans('admin.users_title'),
                'link' => url('/admin/users')
            ],
            [
                'name' => $data['title'],
            ]
        ];
        $data['user'] = $user;
        return view('admin.user.user_details')->with($data);
    }

}
