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
            'name' => 'Super Admin',
            'email' => 'arif@mail.com',
            'password' => bcrypt('12345678'),
            'role_id' => 1,
            'warehouse_id' => 1,
            'photo_profile' => '-',
        ]);

        $superAdmin->assignRole('super admin');
    }
}
