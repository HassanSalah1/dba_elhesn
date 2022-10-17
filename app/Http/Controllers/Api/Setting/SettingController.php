<?php

namespace App\Http\Controllers\Api\Setting;

use App\Http\Controllers\Controller;
use App\Services\Api\Setting\SettingApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;
use PDOException;

class SettingController extends Controller
{

    public function testConnection(Request $request)
    {
        $serverName = env('DB_SQL_HOST');
        $uid = env('DB_SQL_USERNAME');
        $pwd = env('DB_SQL_PASSWORD');
        $databaseName = env('DB_SQL_DATABASE');
        try {
            $conn = new PDO("sqlsrv:Server=$serverName;Database=$databaseName;ConnectionPooling=0", $uid, $pwd);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            $e->getMessage();
        }
        die();

//        echo phpinfo();
//        die();

        $connectionInfo = [
            "UID" => $uid,
            "PWD" => $pwd,
            "Database" => $databaseName
        ];
        /* Connect using SQL Server Authentication. */
        $conn = \sqlsrv_connect($serverName, $connectionInfo);
        var_dump($conn);
        die();

        $tsql = "SELECT id, RowID FROM dbo.MobMobileApp_Sports";
        /* Execute the query. */
        $stmt = \sqlsrv_query($conn, $tsql);

//        $users = DB::connection('sqlsrv')->table('MobileApp_Sports')->select('*')->get();

        return response()->json([$stmt]);
    }

    public function getSiteNews(Request $request)
    {
        $data = $request->all();
        return SettingApiService::getSiteNews($data);
    }

    public function getIntros(Request $request)
    {
        $data = $request->all();
        return SettingApiService::getIntros($data);
    }

    public function getAbout(Request $request)
    {
        $data = $request->all();
        return SettingApiService::getAbout($data);
    }


    public function getTerms(Request $request)
    {
        $data = $request->all();
        return SettingApiService::getTerms($data);
    }


    public function getHistory(Request $request)
    {
        $data = $request->all();
        return SettingApiService::getHistory($data);
    }

    public function getContactDetails(Request $request)
    {
        $data = $request->all();
        return SettingApiService::getContactDetails($data);
    }

    public function addContact(Request $request)
    {
        $data = $request->all();
        return SettingApiService::addContact($data);
    }

    public function getCategories(Request $request)
    {
        $data = $request->all();
        return SettingApiService::getCategories($data);
    }

    public function getTeams(Request $request)
    {
        $data = $request->all();
        return SettingApiService::getTeams($data);
    }

    public function getGallery(Request $request)
    {
        $data = $request->all();
        return SettingApiService::getGallery($data);
    }

    public function getSportGames(Request $request)
    {
        $data = $request->all();
        return SettingApiService::getSportGames($data);
    }

    public function getSportGamesGallery(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;
        return SettingApiService::getSportGamesGallery($data);
    }

    public function getCommittees(Request $request)
    {
        $data = $request->all();
        return SettingApiService::getCommittees($data);
    }

    public function getHome(Request $request)
    {
        $data = $request->all();
        return SettingApiService::getHome($data);
    }

    public function getNews(Request $request)
    {
        $data = $request->all();
        return SettingApiService::getNews($data);
    }

    public function getNewDetails(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;
        return SettingApiService::getNewDetails($data);
    }

    public function getActions(Request $request)
    {
        $data = $request->all();
        return SettingApiService::getActions($data);
    }

    public function getActionDetails(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;
        return SettingApiService::getActionDetails($data);
    }

}

?>
