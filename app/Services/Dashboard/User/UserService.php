<?php
namespace App\Services\Dashboard\User;

use App\Repositories\Dashboard\User\UserRepository;
use App\Repositories\General\UtilsRepository;

class UserService
{


    public static function getUsersData(array $data)
    {
        return UserRepository::getUsersData($data);
    }

    public static function changeStatus(array $data)
    {
        $response = UserRepository::changeStatus($data);
        if (!is_bool($response)) {
            return UtilsRepository::response(false,
                trans('admin.process_success_message'),
                trans('admin.success_title'), $response, trans('admin.error_title'));
        }
        return UtilsRepository::response($response,
            trans('admin.process_success_message'), trans('admin.success_title'));
    }

    public static function verifyUser(array $data)
    {
        $response = UserRepository::verifyUser($data);
        return UtilsRepository::response($response,
            trans('admin.verify_success_message'), trans('admin.success_title'));
    }
}

?>
