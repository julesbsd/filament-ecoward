<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::insert([
            [
                "name" => 'company 1',
                "email" => 'contact@company.fr',
                "logo" => 'logo1',
                "website" => 'www.google.com',
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => 'company 2',
                "email" => 'contact2@company.fr',
                "logo" => 'logo2',
                "website" => 'www.google.com',
                "created_at" => now(),
                "updated_at" => now()
            ],
        ],);
    }
}