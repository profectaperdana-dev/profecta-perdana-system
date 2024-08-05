<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdmin = User::create([
            'employee_id' => '1',
            'username' => 'programmer',
            'password' => bcrypt('12345678'),
            'role_id' => 1,
            'job_id' => 3,
            'phone_number' => '08123456789',
            'warehouse_id' => 1,
            'photo_profile' => '-',
        ]);
    }
}
