<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function authenticated_user_can_view_courses_list()
    {
        Course::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get(route('courses.index'));

        $response->assertStatus(200);
        $response->assertViewHas('courses');
    }

    /** @test */
    public function guest_cannot_view_courses_list()
    {
        $response = $this->get(route('courses.index'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function user_can_create_a_course()
    {
        $courseData = [
            'name' => 'Laravel Advanced',
            'type' => 'online',
            'max_students' => 30,
            'enrollment_deadline' => now()->addDays(10)->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->user)->post(route('courses.store'), $courseData);

        $response->assertRedirect(route('courses.index'));
        $this->assertDatabaseHas('courses', ['name' => 'Laravel Advanced']);
    }

    /** @test */
    public function user_can_update_a_course()
    {
        $course = Course::factory()->create();
        $updatedData = [
            'name' => 'Updated Course Name',
            'type' => 'presencial',
            'max_students' => 20,
            'enrollment_deadline' => now()->addDays(5)->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->user)->put(route('courses.update', $course), $updatedData);

        $response->assertRedirect(route('courses.index'));
        $this->assertDatabaseHas('courses', ['id' => $course->id, 'name' => 'Updated Course Name']);
    }

    /** @test */
    public function user_can_delete_a_course()
    {
        $course = Course::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('courses.destroy', $course));

        $response->assertRedirect(route('courses.index'));
        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }

    /** @test */
    public function user_can_search_courses_via_ajax()
    {
        Course::factory()->create(['name' => 'Specific Course']);
        Course::factory()->create(['name' => 'Other Course']);

        $response = $this->actingAs($this->user)->get(route('courses.index', ['search' => 'Specific']), [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $response->assertStatus(200);
        $response->assertSee('Specific Course');
        $response->assertDontSee('Other Course');
    }

    /** @test */
    public function user_can_mass_delete_courses()
    {
        $courses = Course::factory()->count(3)->create();
        $ids = $courses->pluck('id')->toArray();

        $response = $this->actingAs($this->user)->delete(route('courses.massDestroy'), [
            'ids' => $ids
        ]);

        $response->assertRedirect(route('courses.index'));
        $this->assertDatabaseCount('courses', 0);
    }
}
