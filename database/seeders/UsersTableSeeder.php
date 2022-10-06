<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->create([
            "email" => "testuser1@email.com", 
            "password" => Hash::make('1234')
        ]);

        $this->create([
            "email" => "testuser2@email.com", 
            "password" => Hash::make('1234')
        ]);
    }

    private function create(array $data)
    {
        return User::create($data);
    }
}
