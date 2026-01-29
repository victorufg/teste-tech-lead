<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Student;
use App\Models\Enrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_get_all_courses_via_api()
    {
        Course::factory()->count(2)->create();

        $response = $this->getJson('/api/courses');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'type', 'max_students', 'enrollment_deadline']
            ]
        ]);
    }

    /** @test */
    public function can_create_course_via_api()
    {
        $data = [
            'name' => 'API Course',
            'type' => 'online',
            'max_students' => 10,
            'enrollment_deadline' => now()->addDays(5)->toDateString(),
        ];

        $response = $this->postJson('/api/courses', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('courses', ['name' => 'API Course']);
    }

    /** @test */
    public function can_get_all_students_via_api()
    {
        Student::factory()->count(2)->create();

        $response = $this->getJson('/api/students');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
    }

    /** @test */
    public function can_create_student_via_api()
    {
        $data = [
            'name' => 'API Student',
            'email' => 'api@example.com',
            'cpf' => '11122233344',
            'birth_date' => '1990-01-01',
        ];

        $response = $this->postJson('/api/students', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('students', ['email' => 'api@example.com']);
    }

    /** @test */
    public function can_get_all_enrollments_via_api()
    {
        $course = Course::factory()->create();
        $student = Student::factory()->create();
        $course->students()->attach($student);

        $response = $this->getJson('/api/enrollments');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
    }

    /** @test */
    public function can_create_enrollment_via_api()
    {
        $course = Course::factory()->create();
        $student = Student::factory()->create();

        $response = $this->postJson('/api/enrollments', [
            'course_id' => $course->id,
            'student_id' => $student->id,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('course_student', [
            'course_id' => $course->id,
            'student_id' => $student->id,
        ]);
    }

    /** @test */
    public function can_delete_enrollment_via_api()
    {
        $course = Course::factory()->create();
        $student = Student::factory()->create();
        $course->students()->attach($student);
        $enrollment = Enrollment::first();

        $response = $this->deleteJson("/api/enrollments/{$enrollment->id}");

        $response->assertStatus(204);
        $this->assertDatabaseEmpty('course_student');
    }
}
