<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Course;
use App\Models\Student;
use App\Models\Enrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrollmentTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function authenticated_user_can_view_enrollments_list()
    {
        $course = Course::factory()->create();
        $student = Student::factory()->create();
        $course->students()->attach($student);

        $response = $this->actingAs($this->user)->get(route('enrollments.index'));

        $response->assertStatus(200);
        $response->assertSee($course->name);
        $response->assertSee($student->name);
    }

    /** @test */
    public function user_can_enroll_a_student_in_a_course()
    {
        $course = Course::factory()->create();
        $student = Student::factory()->create();

        $response = $this->actingAs($this->user)->post(route('enrollments.store'), [
            'course_id' => $course->id,
            'student_id' => $student->id,
        ]);

        $response->assertRedirect(route('enrollments.index'));
        $this->assertDatabaseHas('course_student', [
            'course_id' => $course->id,
            'student_id' => $student->id,
        ]);
    }

    /** @test */
    public function user_can_delete_an_enrollment()
    {
        $course = Course::factory()->create();
        $student = Student::factory()->create();
        $course->students()->attach($student);
        $enrollment = Enrollment::where('course_id', $course->id)->where('student_id', $student->id)->first();

        // Check the route definition for enrollment deletion. It usually uses the composite ID or similar.
        // In web.php it was Route::delete('/enrollments', [EnrollmentController::class, 'destroy'])->name('enrollments.destroy');
        // Wait, looking at web.php again: Route::delete('/enrollments', [EnrollmentController::class, 'destroy'])
        // Usually it should take a parameter. Let me check the controller.
        
        $response = $this->actingAs($this->user)->delete(route('enrollments.destroy', $enrollment));

        $response->assertRedirect(route('enrollments.index'));
        $this->assertDatabaseMissing('course_student', [
            'course_id' => $course->id,
            'student_id' => $student->id,
        ]);
    }

    /** @test */
    public function user_can_mass_delete_enrollments()
    {
        $course = Course::factory()->create();
        $students = Student::factory()->count(3)->create();
        $course->students()->attach($students);
        
        $enrollmentIds = Enrollment::all()->pluck('id')->toArray();

        $response = $this->actingAs($this->user)->delete(route('enrollments.massDestroy'), [
            'ids' => $enrollmentIds
        ]);

        $response->assertRedirect(route('enrollments.index'));
        $this->assertDatabaseCount('course_student', 0);
    }
}
