<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Company;
use App\Models\Coupon;
use App\Models\Reward;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Trash;
use App\Models\TrashCategory;
use App\Models\User;
use App\Models\Action;
use App\Models\Challenge;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            ChallengeTypesSeeder::class,
            ActionTypeSeeder::class,
            TrashSeeder::class, // Assurez-vous que TrashSeeder est appelé avant de créer les actions
            ChallengeSeeder::class,
        ]);

        // User::factory(50)->create();

        Company::factory(20)->create()->each(function ($company) {
            // Créer un organisateur pour chaque entreprise
            $organizer = User::factory()->create([
                'company_id' => $company->id,
                'role_id' => 3, // Role ID for organizer
            ]);

            // Créer des utilisateurs pour chaque entreprise
            $users = User::factory(rand(20, 100))->create([
                'company_id' => $company->id,
            ]);

            // Créer des actions pour chaque utilisateur
            $users->each(function ($user) {
                $trash = Trash::inRandomOrder()->first();
                if ($trash) {
                    Action::factory(rand(5, 20))->create([
                        'user_id' => $user->id,
                        'challenge_id' => rand(1, 4),
                        'trash_id' => $trash->id,
                        'image_action' => 'images/bouteille.jpg',
                    ]);
                }
            });
        });

        $this->call([
            UserSeeder::class,
            ChallengeSeeder::class
        ]);

        DB::unprepared(
            "UPDATE users
                SET points = (
                    SELECT COALESCE(SUM(t.points * a.quantity), 0)
                    FROM actions a
                    JOIN trashes t ON a.trash_id = t.id
                    WHERE a.user_id = users.id AND a.status = 'accepted'
                )
                WHERE role_id = 1;
        ");
    }
}
