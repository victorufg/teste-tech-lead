<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Course;
use App\Models\Student;
use App\Models\Enrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_course_can_have_many_students()
    {
        $course = Course::factory()->create();
        $student = Student::factory()->create();

        $course->students()->attach($student);

        $this->assertCount(1, $course->students);
        $this->assertTrue($course->students->contains($student));
    }

    /** @test */
    public function a_student_can_have_many_courses()
    {
        $student = Student::factory()->create();
        $course = Course::factory()->create();

        $student->courses()->attach($course);

        $this->assertCount(1, $student->courses);
        $this->assertTrue($student->courses->contains($course));
    }

    /** @test */
    public function enrollment_belongs_to_course_and_student()
    {
        $course = Course::factory()->create();
        $student = Student::factory()->create();
        
        $enrollment = Enrollment::create([
            'course_id' => $course->id,
            'student_id' => $student->id,
        ]);

        $this->assertInstanceOf(Course::class, $enrollment->course);
        $this->assertInstanceOf(Student::class, $enrollment->student);
        $this->assertEquals($course->id, $enrollment->course->id);
        $this->assertEquals($student->id, $enrollment->student->id);
    }
}
