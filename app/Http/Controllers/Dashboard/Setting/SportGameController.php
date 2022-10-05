<?php

namespace App\Http\Controllers\Dashboard\Setting;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\Setting\SportGameService;
use Illuminate\Http\Request;

class SportGameController extends Controller
{
    //
    public function showSportGames()
    {
        $data['pageConfigs'] = [
            'pageHeader' => false,
            'defaultLanguage' => 'ar',
            'direction' => 'rtl'
        ];
        $data['title'] = trans('admin.sport_games_title');
        $data['debatable_names'] = array(trans('admin.name'), trans('admin.description'),
            trans('admin.actions'));
        return view('admin.settings.sport_games')->with($data);
    }

    public function getSportGamesData(Request $request)
    {
        $data = $request->all();
        return SportGameService::getSportGamesData($data);
    }

    public function addSportGame(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        return SportGameService::addSportGame($data);
    }

    public function deleteSportGame(Request $request)
    {
        $data = $request->all();
        return SportGameService::deleteSportGame($data);
    }

    public function restoreSportGame(Request $request)
    {
        $data = $request->all();
        return SportGameService::restoreSportGame($data);
    }

    public function getSportGameData(Request $request)
    {
        $data = $request->all();
        return SportGameService::getSportGameData($data);
    }

    public function editSportGame(Request $request)
    {
        $data = $request->all();
        $data['request'] = $request;
        return SportGameService::editSportGame($data);
    }

}
