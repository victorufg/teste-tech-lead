<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create a default test user
        User::factory()->create([
            'name' => 'Admin Teste',
            'email' => 'admin@teste.com',
            'password' => bcrypt('password'),
        ]);

        // 2. Create Students
        $students = \App\Models\Student::factory(50)->create();

        // 3. Create Courses
        $courses = \App\Models\Course::factory(10)->create();

        // 4. Enroll students in random courses
        foreach ($students as $student) {
            // Each student enrolls in 1 to 3 random courses
            $randomCourses = $courses->random(rand(1, 3));
            $student->courses()->attach($randomCourses);
        }
    }
}
