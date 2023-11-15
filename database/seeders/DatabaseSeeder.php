<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]);

        $user1 = User::factory()->create([
            'name' => 'user1',
            'email' => 'user1@example.com',
            'password' => Hash::make('123456'),
        ]);

        $user2 = User::factory()->create([
            'name' => 'user2',
            'email' => 'user2@example.com',
            'password' => Hash::make('123456'),
        ]);

        Note::factory()
            ->count(6)
            ->state(new Sequence(
                ['user_id' => $user1->id],
                ['user_id' => $user2->id],
            ))
            ->create([
                'text' => fake()->text()
            ]);
    }

}
