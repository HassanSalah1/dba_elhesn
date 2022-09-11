<?php

namespace App\Http\Controllers\Dashboard\Setting;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\Setting\CommitteeService;
use Illuminate\Http\Request;

class CommitteeController extends Controller
{
    //
    public function showCommittees()
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.committees_title');
        $data['debatable_names'] = array(trans('admin.name'), trans('admin.description'),
            trans('admin.actions'));
        return view('admin.settings.committees')->with($data);
    }

    public function getCommitteesData(Request $request)
    {
        $data = $request->all();
        return CommitteeService::getCommitteesData($data);
    }

    public function addCommittee(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        return CommitteeService::addCommittee($data);
    }

    public function deleteCommittee(Request $request)
    {
        $data = $request->all();
        return CommitteeService::deleteCommittee($data);
    }

    public function restoreCommittee(Request $request)
    {
        $data = $request->all();
        return CommitteeService::restoreCommittee($data);
    }

    public function getCommitteeData(Request $request)
    {
        $data = $request->all();
        return CommitteeService::getCommitteeData($data);
    }

    public function editCommittee(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        return CommitteeService::editCommittee($data);
    }

}
