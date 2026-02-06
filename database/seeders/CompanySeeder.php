<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            ['name' => 'Apple Inc.'],
            ['name' => 'Google LLC'],
            ['name' => 'Meta Platforms (Facebook)'],
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }
    }
}
