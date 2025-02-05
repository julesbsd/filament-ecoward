<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChallengeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('challenges')->insert([
            [
                'name' => 'Ramasser des mégots',
                'description' => '',
                'trash_id' => 1,
                'is_active' => true,
                'base_goal' => 5, // Objectif initial
                'increment_goal' => 5, // Incrément par niveau
                'type_id' => 1,
                'points' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ramasser des canettes',
                'base_goal' => 7,
                'description' => '',
                'trash_id' => 1,
                'is_active' => true,
                'increment_goal' => 3,
                'type_id' => 1,
                'points' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Recycler des bouteilles en plastique',
                'base_goal' => 10,
                'description' => '',
                'trash_id' => 1,
                'is_active' => true,
                'increment_goal' => 5,
                'type_id' => 1,
                'points' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ramasser des papiers',
                'base_goal' => 15,
                'description' => '',
                'trash_id' => 1,
                'is_active' => true,
                'increment_goal' => 10,
                'type_id' => 1,
                'points' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Planter des arbres',
                'base_goal' => 1,
                'description' => '',
                'trash_id' => 1,
                'is_active' => true,
                'increment_goal' => 1,
                'type_id' => 1,
                'points' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
