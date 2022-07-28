<?php

namespace App\Http\Controllers\Dashboard\Setting;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\Setting\ContactTypeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ContactTypeController extends Controller
{
    //
    public function showContactTypes()
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.contactTypes_title');
        $data['debatable_names'] = array(trans('admin.name_ar'), trans('admin.name_en'),
            trans('admin.actions'));
        return view('admin.settings.contact_type')->with($data);
    }

    public function getContactTypesData(Request $request)
    {
        $data = $request->all();
        $data['locale'] = App::getLocale();
        return ContactTypeService::getContactTypesData($data);
    }

    public function addContactType(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        return ContactTypeService::addContactType($data);
    }

    public function deleteContactType(Request $request)
    {
        $data = $request->all();
        return ContactTypeService::deleteContactType($data);
    }

    public function restoreContactType(Request $request)
    {
        $data = $request->all();
        return ContactTypeService::restoreContactType($data);
    }


    public function getContactTypeData(Request $request)
    {
        $data = $request->all();
        return ContactTypeService::getContactTypeData($data);
    }

    public function editContactType(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        return ContactTypeService::editContactType($data);
    }

}
