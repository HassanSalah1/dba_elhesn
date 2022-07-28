<?php

namespace App\Http\Controllers\Dashboard\Contact;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\Contact\ContactService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ContactController extends Controller
{
    //
    public function showContacts()
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.contacts_title');
        $data['debatable_names'] = [trans('admin.user'), trans('admin.contact_type'),
            trans('admin.message'), trans('admin.actions')];
        return view('admin.contact.contact')->with($data);
    }

    public function getContactsData(Request $request)
    {
        $data = $request->all();
        $data['locale'] = App::getLocale();
        return ContactService::getContactsData($data);
    }

    public function replayContact(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        return ContactService::replayContact($data);
    }

}
