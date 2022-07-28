<?php

namespace App\Services\Api\Auth;

use App\Entities\HttpCode;
use App\Entities\UserRoles;
use App\Models\City;
use App\Models\User;
use App\Repositories\Api\Auth\AuthApiRepository;
use App\Repositories\General\UtilsRepository;
use App\Repositories\General\ValidationRepository;

class AuthApiService
{

    public static function signup(array $data)
    {
        $data['role'] = UserRoles::CUSTOMER;
        $country = null;
        if(isset($data['city_id'])){
            $city = City::withoutTrashed()->where(['id' => $data['city_id']])->first();
            if($city && $city->deleted_at === null){
                $country = $city->country;
                if(!$country || $country->status === 0){
                    return UtilsRepository::handleResponseApi([
                        'message' => trans('api.general_error_message'),
                        'code' => HttpCode::ERROR
                    ]);
                }
            }else{
                return UtilsRepository::handleResponseApi([
                    'message' => trans('api.general_error_message'),
                    'code' => HttpCode::ERROR
                ]);
            }
        }

        $user = User::where([
            'role' => UserRoles::REGISTER,
            'phonecode' => isset($data['phonecode']) ? $data['phonecode'] : null,
            'phone' => isset($data['phone']) ? $data['phone'] : null,
        ])->first();

        $keys = [
            'name' => 'required',
            'email' => 'required|unique:users',
            'phonecode' => 'required',
            'phone' => 'required'.( (!$user) ? '|unique:users' : ''). ( ($country) ? '|phone:'.strtoupper($country->code).',mobile' : ''),
            'city_id' => 'required',
            'password' => 'required',
            'address' => 'required',
            'role' => 'required|in:' . implode(',', UserRoles::getUserKeys()),
//            'device_type' => 'required',
//            'device_token' => 'required',
        ];
        $messages = [
            'required' => trans('api.required_error_message'),
            'email.unique' => trans('api.email_unique_error_message'),
            'phone.unique' => trans('api.phone_unique_error_message'),
            'role.in' => trans('api.role_error_message'),
            'phone' => trans('api.valid_phone_error_message'),
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys, $messages);
        if ($validated !== true) {
            return $validated;
        }
        $data['user'] = $user;
        $response = AuthApiRepository::signup($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function login(array $data)
    {
        $keys = [
            'phone' => 'required',
            'phonecode' => 'required',
            'password' => 'required',
//            'device_type' => 'required',
//            'device_token' => 'required',
        ];
        $messages = [
            'required' => trans('api.required_error_message'),
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys, $messages);
        if ($validated !== true) {
            return $validated;
        }
        return AuthApiRepository::login($data);
    }

    public static function getVerificationCode(array $data)
    {
        $keys = [
            'phone' => 'required',
            'phonecode' => 'required',
        ];
        $messages = [
            'required' => trans('api.required_error_message'),
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys, $messages);
        if ($validated !== true) {
            return $validated;
        }
        $response = AuthApiRepository::getVerificationCode($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function resendVerificationCode(array $data)
    {
        $keys = [
            'phone' => 'required',
            'phonecode' => 'required',
        ];
        $messages = [
            'required' => trans('api.required_error_message'),
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys, $messages);
        if ($validated !== true) {
            return $validated;
        }
        $response = AuthApiRepository::resendVerificationCode($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function checkVerificationCode(array $data)
    {
        $keys = [
            'phonecode' => 'required',
            'phone' => 'required',
            'code' => 'required',
        ];
        $messages = [
            'required' => trans('api.required_error_message'),
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys, $messages);
        if ($validated !== true) {
            return $validated;
        }
        $response = AuthApiRepository::checkVerificationCode($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function forgetPassword(array $data)
    {
        $keys = [
            'phone' => 'required',
            'phonecode' => 'required',
        ];
        $messages = [
            'required' => trans('api.required_error_message'),
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys, $messages);
        if ($validated !== true) {
            return $validated;
        }
        $response = AuthApiRepository::forgetPassword($data);
        return UtilsRepository::handleResponseApi($response);
    }

    public static function changeForgetPassword(array $data)
    {
        $keys = [
            'phone' => 'required',
            'phonecode' => 'required',
            'password' => 'required',
//            'device_type' => 'required',
//            'device_token'  => 'required',
        ];
        $messages = [
            'required' => trans('api.required_error_message'),
        ];
        $validated = ValidationRepository::validateAPIGeneral($data, $keys, $messages);
        if ($validated !== true) {
            return $validated;
        }
        $response = AuthApiRepository::changeForgetPassword($data);
        return UtilsRepository::handleResponseApi($response);
    }


    public static function logout()
    {
        $response = AuthApiRepository::logout();
        return UtilsRepository::handleResponseApi($response);
    }


}

?>
