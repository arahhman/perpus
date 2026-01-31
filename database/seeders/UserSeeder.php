<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MasterMahasiswa;
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
        $admin = User::create([
            'name'=>'Admin',
            'email'=>'admin@perpus.com',
            'password'=>bcrypt('admin123'),
            'role'=>'admin'
        ]);

        $siswa = User::create([
            'name'=>'Siswa',
            'email'=>'siswa@perpus.com',
            'password'=>bcrypt('siswa123'),
            'role'=>'siswa'
        ]);

        MasterMahasiswa::create([
            'id_user' => $siswa->id,
            'nim' => '12345678',
            'nama' => 'Siswa A',
            'alamat' => 'Jl. Contoh 1',
            'jenis_kelamin' => 'L',
            'ttl' => '2000-01-01',
            'program_studi' => 'Informatika',
        ]);
    }
}
