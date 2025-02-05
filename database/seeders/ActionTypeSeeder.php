<?php

namespace Database\Seeders;

use App\Models\ActionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ActionType::insert([
            [
                'name' => 'déchet'
            ],
            [
                'name' => 'déplacement'
            ]
        ]);
    }
}
