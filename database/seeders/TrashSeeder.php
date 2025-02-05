<?php

namespace Database\Seeders;

use App\Models\Trash;
use Illuminate\Database\Seeder;

class TrashSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Trash::insert([
            [
                "name" => 'plastique',
                'image' => 'image 1',
                'points' => 10
            ],
            [
                'name' => 'Métaux',
                'image' => 'image 1',
                'points' => 10
            ],
            [
                'name' => 'Verre',
                'image' => 'image 1',
                'points' => 10
            ],
            [
                'name' => 'Papier / Carton',
                'image' => 'image 1',
                'points' => 10
            ],
            [
                'name' => 'Textile',
                'image' => 'image 1',
                'points' => 10
            ],
            [
                'name' => 'Matière organique',
                'image' => 'image 1',
                'points' => 10
            ],
            [
                'name' => 'Mégots',
                'image' => 'image 1',
                'points' => 10
            ]
        ],);
    }
}
