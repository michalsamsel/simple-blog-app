<?php

namespace Database\Seeders;

use DB;
use Str;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {    
        DB::table('users')->insert([
            'name' =>'Example Account',
            'email' => 'example@email.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        \App\Models\User::factory(10)->create();
        \App\Models\Post::factory(50)->create();
        \App\Models\Comment::factory(200)->create();
    }
}
