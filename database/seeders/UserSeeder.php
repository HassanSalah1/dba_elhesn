<?php

namespace Database\Seeders;

use App\Entities\Key;
use App\Entities\Status;
use App\Entities\UserRoles;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::firstOrCreate([
            'name' => 'damain admin',
            'email' => 'admin@damain.com',
            'password' => Hash::make('12#adm$in@456'),
            'role' => UserRoles::ADMIN,
            'status' => Status::ACTIVE
        ]);

        Setting::firstOrCreate([
            'key' => Key::MAX_TIME_TO_PAY,
            'value' => 48
        ]);
    }
}
