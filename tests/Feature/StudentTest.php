<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function authenticated_user_can_view_students_list()
    {
        Student::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get(route('students.index'));

        $response->assertStatus(200);
        $response->assertViewHas('students');
    }

    /** @test */
    public function user_can_create_a_student()
    {
        $studentData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'cpf' => '12345678901',
            'birth_date' => '2000-01-01',
        ];

        $response = $this->actingAs($this->user)->post(route('students.store'), $studentData);

        $response->assertRedirect(route('students.index'));
        $this->assertDatabaseHas('students', ['email' => 'john@example.com']);
    }

    /** @test */
    public function user_can_update_a_student()
    {
        $student = Student::factory()->create();
        $updatedData = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'cpf' => '98765432109',
            'birth_date' => '1995-12-31',
        ];

        $response = $this->actingAs($this->user)->put(route('students.update', $student), $updatedData);

        $response->assertRedirect(route('students.index'));
        $this->assertDatabaseHas('students', ['id' => $student->id, 'name' => 'Jane Doe']);
    }

    /** @test */
    public function user_can_delete_a_student()
    {
        $student = Student::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('students.destroy', $student));

        $response->assertRedirect(route('students.index'));
        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }

    /** @test */
    public function user_can_search_students_via_ajax()
    {
        Student::factory()->create(['name' => 'Target Student']);
        Student::factory()->create(['name' => 'Other Student']);

        $response = $this->actingAs($this->user)->get(route('students.index', ['search' => 'Target']), [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $response->assertStatus(200);
        $response->assertSee('Target Student');
        $response->assertDontSee('Other Student');
    }

    /** @test */
    public function user_can_mass_delete_students()
    {
        $students = Student::factory()->count(3)->create();
        $ids = $students->pluck('id')->toArray();

        $response = $this->actingAs($this->user)->delete(route('students.massDestroy'), [
            'ids' => $ids
        ]);

        $response->assertRedirect(route('students.index'));
        $this->assertDatabaseCount('students', 0);
    }
}
