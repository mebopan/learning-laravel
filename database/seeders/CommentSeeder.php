<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $companies = Company::all();

        for ($i = 0; $i < 4000; $i++) {
            $user = $users->random();
            $company = $companies->random();

            Comment::factory()->create([
                'user_id' => $user->id,
                'company_id' => $company->id,
            ]);
        }
    }
}
