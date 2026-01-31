<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'=>'Admin',
            'email'=>'admin@perpus.com',
            'password'=>bcrypt('admin123'),
            'role'=>'admin'
        ]);

        User::create([
            'name'=>'Siswa',
            'email'=>'siswa@perpus.com',
            'password'=>bcrypt('siswa123'),
            'role'=>'siswa'
        ]);
    }
}
