<?php
namespace App\Services\Dashboard\Auth;

use App\Repositories\Dashboard\Auth\AuthRepository;
use App\Repositories\General\UtilsRepository;
use App\Repositories\General\ValidationRepository;
use Illuminate\Support\Facades\Auth;

class AuthService
{

    public static function processLogin(array $arr)
    {
        $response = AuthRepository::processLogin($arr);
        $errorMessage = null;
        if (!is_bool($response)) {
            $errorMessage = $response;
            $response = false;
        }
        return UtilsRepository::response($response, null,
            null, $errorMessage , '');
    }

    public static function isAuthorized()
    {
        return AuthRepository::isAuthorized();
    }

    public static function updateProfile($data)
    {

        $rules = [
            'name' => 'required',
        ];

        if (isset($data['email']) && $data['user']->email !== $data['email']) {
            $rules['email'] = 'required|unique:users';
        }

        if (((isset($data['old_password']) && !empty($data['old_password']))
            || isset($data['password']) && !empty($data['password'])
            || isset($data['password_confirmation']) && !empty($data['password_confirmation']))) {
            if (!Auth::validate(['email' => $data['user']->email, 'password' => $data['old_password']])) {
                return UtilsRepository::response(false, null, null,
                    trans('admin.old_password_error_message'),
                    trans('admin.error_title'));
            }
            $rules['password'] = 'required|confirmed';
        }

        $messages = [
            'confirmed' => trans('admin.confirm_password_error_message'),
            'email.unique' => trans('admin.email_unique_message'),
        ];
        $validated = ValidationRepository::validateWebGeneral($data, $rules, $messages);
        if ($validated !== true) {
            return $validated;
        }

        $response = AuthRepository::updateProfile($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , trans('admin.success_title'));
    }

    public static function logout($url)
    {
        return AuthRepository::logout($url);
    }

    public static function processResetPassword(array $data)
    {
        return AuthRepository::processResetPassword($data);
    }

    public static function processChangePassword(array $data)
    {
        return AuthRepository::processChangePassword($data);
    }

}

?>
