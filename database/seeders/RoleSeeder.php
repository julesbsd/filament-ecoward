<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert([
            [
                'name' => 'user'
            ],
            [
                'name' => 'admin'
            ],
            [
                'name' => 'organisateur'
            ]
        ]);
    }
}
