<?php

namespace App\Repositories\Api\Auth;


use App\Entities\HttpCode;
use App\Entities\Status;
use App\Entities\UserRoles;
use App\Http\Resources\UserAuthResource;
use App\Jobs\SendSMSJob;
use App\Models\User;
use App\Models\VerificationCode;
use App\Repositories\General\UtilsRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthApiRepository
{

    // process user login
    public static function signup(array $data)
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'phonecode' => $data['phonecode'],
            'phone' => $data['phone'],
            'status' => Status::UNVERIFIED,
            'lang' => App::getLocale(),
            'device_type' => isset($data['device_type']) ? $data['device_type'] : null,
            'device_token' => isset($data['device_token']) ? $data['device_token'] : null,
            'city_id' => $data['city_id'],
            'address' => $data['address'],
            'latitude' => isset($data['latitude']) ? $data['longitude'] : null,
            'longitude' => isset($data['longitude']) ? $data['longitude'] : null,
        ];
        if ($data['user']) {
            $user = $data['user'];
            $user->update($userData);
        } else {
            $user = User::create($userData);
        }
        if ($user) {
            // 1- send verification sms
            self::sendVerificationCode($user);
            if (isset($data['web'])) {
                Auth::loginUsingId($user->id);
            }
            // return success response
            return [
                'message' => trans('api.create_account_success_message'),
                'code' => HttpCode::SUCCESS
            ];
        }
        return [
            'message' => trans('api.general_error_message'),
            'code' => HttpCode::ERROR
        ];
    }

    // get user data
    public static function getUserData($id, $token = false)
    {
        $user = User::where(['id' => $id])
            ->first(['id', 'name', 'email', 'phone', 'phonecode', 'status', 'lang', 'city_id']);
        if ($token) {
            $user->token = $user->createToken('damain')->accessToken;
        }
        $user->city_name = @$user->city->name;
        $country = @$user->city->counyry;
        $user->country_id = $country ? $country->id : null;
        $user->country_name = $country ? $country->name : null;
        $user->image = ($user->image !== null) ? url($user->image) : null;
        return $user;
    }

    // process user login
    public static function login(array $data)
    {
        $remember = (isset($data['remember']) && $data['remember']);
        $user = null;
        if (Auth::attempt(['phonecode' => $data['phonecode'], 'phone' => $data['phone'],
            'password' => $data['password']], $remember)) {
            $user = auth()->user();
            if (isset($data['device_token'])) {
                $user->update([
                    'device_type' => $data['device_type'],
                    'device_token' => $data['device_token'],
                ]);
            }
        } else {
            return Response()->json([
                'message' => trans('api.credentials_error_message')
            ], HttpCode::ERROR);
        }


        if ($user && $user->role === UserRoles::CUSTOMER) {
            if ($user && $user->isBlocked()) {
                return Response()->json([
                    'message' => trans('api.block_status_error_message')
                ], HttpCode::ERROR);
            } else if ($user && $user->isActiveUser()) {
                $user = UserAuthResource::make($user);
                if(isset($data['web'])){
                    Auth::loginUsingId($user->id);
                }
                return Response()->json([
                    'data' => $user,
                    'message' => trans('api.login_success_message'),
                ], HttpCode::SUCCESS);
            } else if ($user && $user->isNotPhoneVerified()) {
                // send verification sms
                self::sendVerificationCode($user);
                if(isset($data['web'])){
                    Auth::loginUsingId($user->id);
                }
                return Response()->json([
                    'data' => [
                        'verify' => 1
                    ],
                    'message' => trans('api.not_verified_error_message'),
                ], HttpCode::NOT_VERIFIED);
            }
        }
        return Response()->json([
            'message' => trans('api.credentials_error_message')
        ], HttpCode::ERROR);
    }

    // forget password
    public static function forgetPassword(array $data)
    {
        $user = User::where(['phonecode' => $data ['phonecode'], 'phone' => $data['phone']])
            ->first();
        if ($user) {
            $is_sent = self::sendVerificationCode($user);
            if ($is_sent) {
                $response = [
                    'message' => trans('api.forget_password_success_message'),
                    'code' => HttpCode::SUCCESS
                ];
                if (isset($data['web'])) {
                    $response['data'] = Crypt::encrypt($data['phone']);
                }
                return $response;
            } else {
                return [
                    'message' => trans('api.general_error_message'),
                    'code' => HttpCode::ERROR
                ];
            }
        }
        return [
            'message' => trans('api.not_found_error_message'),
            'code' => HttpCode::ERROR
        ];
    }

    // change password for forget password process
    public static function changeForgetPassword(array $data)
    {
        $user = User::where(['phonecode' => $data ['phonecode'], 'phone' => $data['phone']])
            ->first();
        if ($user) {
            $user->update([
                'password' => Hash::make($data['password']),
                'device_type' => isset($data['device_type']) ? $data['device_type'] : null,
                'device_token' => isset($data['device_token']) ? $data['device_token'] : null
            ]);
            $user = UserAuthResource::make($user);
            return [
                'data' => $user,
                'message' => trans('api.change_password_success_message'),
                'code' => HttpCode::SUCCESS
            ];
        }
        return [
            'message' => trans('api.not_found_error_message'),
            'code' => HttpCode::ERROR
        ];
    }


    // logout current user
    public static function logout()
    {
        $user = Auth::user();
        if ($user) {
            $user->update(['device_token' => null, 'device_type' => null]);
            if($user->token()) {
                $user->token()->revoke();
                $user->token()->delete();
            }
            Auth::logout();
        }
        return [
            'message' => 'success',
            'code' => HttpCode::SUCCESS
        ];
    }

    // get verification code
    public static function getVerificationCode(array $data)
    {
        $user = User::where(['phone' => $data['phone'], 'phonecode' => $data['phonecode']])
            ->orWhere(function ($query) use ($data) {
                $query->where(['edit_phone' => $data['phone'], 'edit_phonecode' => $data['phonecode']]);
            })
            ->first();
        if ($user) {
            $verificationCode = VerificationCode::where(['user_id' => $user->id])->first();
            if ($verificationCode) {
                return [
                    'data' => [
                        'code' => $verificationCode->code
                    ],
                    'message' => 'success',
                    'code' => HttpCode::SUCCESS
                ];
            }
        }
        return [
            'message' => 'error',
            'code' => HttpCode::ERROR
        ];
    }

    // resend verification code
    public static function resendVerificationCode(array $data)
    {

        $user = User::where(['phone' => $data['phone'], 'phonecode' => $data['phonecode']])
            ->orWhere(function ($query) use ($data) {
                $query->where(['edit_phone' => $data['phone'], 'edit_phonecode' => $data['phonecode']]);
            })
            ->first();
        if ($user) {
            $is_sent = self::sendVerificationCode($user);
            if ($is_sent) {
                return [
                    'message' => trans('api.resend_success_message'),
                    'code' => HttpCode::SUCCESS
                ];
            }
        }

        return [
            'message' => trans('api.general_error_message'),
            'code' => HttpCode::ERROR
        ];
    }

    // check verification code
    public static function checkVerificationCode(array $data)
    {
        $user = User::where(['phone' => $data['phone'], 'phonecode' => $data['phonecode']])
            ->orWhere(function ($query) use ($data) {
                $query->where(['edit_phone' => $data['phone'], 'edit_phonecode' => $data['phonecode']]);
            })
            ->first();
        if ($user) {
            $verificationCode = VerificationCode::where(['user_id' => $user->id,
                'code' => $data['code']])->first();
            if ($verificationCode) {
                $verificationCode->forceDelete();
                $verify = false;
                $response = [
                    'code' => HttpCode::SUCCESS
                ];
                if ($user->edit_phone !== null && $user->edit_phonecode !== null) {
                    $user->update([
                        'phone' => $user->edit_phone,
                        'phonecode' => $user->edit_phonecode,
                        'edit_phone' => null,
                        'edit_phonecode' => null
                    ]);
                    $verify = true;
                } else if ($user->status === Status::UNVERIFIED) {
                    $user->update([
                        'status' => Status::ACTIVE
                    ]);
                    $verify = true;
                }
                if ($verify) {
                    $user = UserAuthResource::make($user);
                    $response['data'] = $user;
                    $response['message'] = trans('api.verify_success_message');
                } else {
                    if (isset($data['web'])) {
                        $response['data'] = Crypt::encrypt($data['phone']);
                        Session::put('dm_chpss', $response['data']);
                    }
                    $response['message'] = trans('api.verify_code_success_message');
                }
                return $response;
            }
            return [
                'message' => trans('api.verify_error_message'),
                'code' => HttpCode::ERROR
            ];
        }
        return [
            'message' => trans('api.general_error_message'),
            'code' => HttpCode::ERROR
        ];
    }

    // send verification code sms to user
    public static function sendVerificationCode($user)
    {
        VerificationCode::where(['user_id' => $user->id])->forceDelete();
        $code = self::createUserVerificationCode($user);
        $verificationCode = VerificationCode::create([
            'user_id' => $user->id,
            'code' => $code
        ]);
        if ($verificationCode) {
            // send sms
            $phone = ($user->edit_phone !== null) ?
                $user->edit_phonecode . $user->edit_phone : $user->phonecode . $user->phone;
            $locale = App::getLocale();
            $message = str_replace('{code}', $code, trans('sms.your_code'));
            SendSMSJob::dispatch($phone, $message, $locale);
            return true;
        } else {
            return false;
        }
    }

    // create unique verification code
    public static function createUserVerificationCode($user)
    {
        $code = UtilsRepository::createVerificationCode($user->id, 4);
        if (VerificationCode::where(['code' => $code])->first()) {
            $code = self::createUserVerificationCode($user);
        }
//        return env('APP_ENV') === 'local' ? '0000' : $code;
        return '0000';
    }

}
