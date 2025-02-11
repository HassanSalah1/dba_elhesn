<?php

namespace App\Http\Controllers\Api\Setting;

use App\Http\Controllers\Controller;
use App\Services\Api\Setting\SettingApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use PDO;
use PDOException;

class SettingController extends Controller
{

    public function testConnection(Request $request)
    {
        $serverName = 'dhsckarem.ddns.net';
        $uid = 'dhclubapp';
        $pwd = 'bNHW^3&q1mH5';
        $databaseName = 'FBall';

        $connectionInfo = [
            "UID" => $uid,
            "PWD" => $pwd,
            "Database" => $databaseName,
            "ColumnEncryption"=>"Enabled",
            "TrustServerCertificate" => true
        ];
        /* Connect using SQL Server Authentication. */
        $conn = \sqlsrv_connect($serverName, $connectionInfo);

        if( $conn ) {
            $sql = "SELECT RowID FROM dbo.MobileApp_Sports";
            if(($result = \sqlsrv_query($conn, $sql)) !== false){
                while( $object = sqlsrv_fetch_object( $result )) {
                    echo $object->RowID.'<br />';
                }
            }
        }else{
        }
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
