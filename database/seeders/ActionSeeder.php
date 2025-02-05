<?php

namespace Database\Seeders;

use App\Models\Action;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Action::insert([
            [
                'action_type_id' => 1,
                // 'trash_id' => 1,
                'user_id' => 1,
                'challenge_id' => 1,
                'description' => "description de l'action",
                'image_action' => 'images/bouteille.jpg',
                'status' => 'pending',
                'location' => 'latitude: 48.8566, longitude: 2.3522',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'action_type_id' => 1,
                // 'trash_id' => 2,
                'user_id' => 11,
                'challenge_id' => 1,
                'description' => "description de l'action",
                'image_action' => 'image 1',
                'status' => 'accepted',
                'location' => 'latitude: 48.8566, longitude: 2.3522',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'action_type_id' => 1,
                // 'trash_id' => 3,
                'user_id' => 11,
                'status' => 'refused',
                'challenge_id' => 1,
                'description' => "description de l'action",
                'image_action' => 'image 1',
                'location' => 'latitude: 48.8566, longitude: 2.3522',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            
        ]);
    }
}
