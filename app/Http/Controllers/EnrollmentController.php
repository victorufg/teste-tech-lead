<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Course;
use App\Models\Student;
use App\Http\Requests\StoreEnrollmentRequest;
use Illuminate\Http\Request;
use App\Http\Resources\EnrollmentResource;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 15);
        $enrollments = Enrollment::with(['course', 'student'])
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        if ($request->wantsJson() || $request->is('api/*')) {
            return EnrollmentResource::collection($enrollments);
        }
        
        // Also need these for the creation drawer in the index view
        $courses = Course::orderBy('name')->get();
        $students = Student::orderBy('name')->get();

        return view('enrollments.index', compact('enrollments', 'courses', 'students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::orderBy('name')->get();
        $students = Student::orderBy('name')->get();
        return view('enrollments.create', compact('courses', 'students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEnrollmentRequest $request)
    {
        $validated = $request->validated();
        $course = Course::findOrFail($validated['course_id']);

        // Check enrollment deadline
        // Check enrollment deadline
        if ($course->enrollment_deadline < now()->format('Y-m-d')) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'O prazo de matrícula para este curso já expirou.'], 422);
            }
            return redirect()->back()->with('error', 'O prazo de matrícula para este curso já expirou.');
        }

        // Check for duplicate enrollment
        $exists = Enrollment::where('course_id', $validated['course_id'])
            ->where('student_id', $validated['student_id'])
            ->exists();

        if ($exists) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Este aluno já está matriculado neste curso.'], 422);
            }
            return redirect()->back()->with('error', 'Este aluno já está matriculado neste curso.');
        }

        // Check for course capacity
        if ($course->max_students !== null) {
            $currentEnrollments = Enrollment::where('course_id', $course->id)->count();
            if ($currentEnrollments >= $course->max_students) {
                if ($request->wantsJson() || $request->is('api/*')) {
                    return response()->json(['message' => 'Este curso já atingiu o limite máximo de vagas.'], 422);
                }
                return redirect()->back()->with('error', 'Este curso já atingiu o limite máximo de vagas.');
            }
        }

        $enrollment = Enrollment::create($validated);

        if ($request->wantsJson() || $request->is('api/*')) {
            return new EnrollmentResource($enrollment);
        }

        return redirect()->route('enrollments.index')
            ->with('success', 'Matrícula realizada com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Enrollment $enrollment)
    {
        if (request()->wantsJson()) {
            $data = $enrollment->load(['student', 'course']);
            return response()->json($data);
        }
        return view('enrollments.show', compact('enrollment'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();

        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json(null, 204);
        }

        return redirect()->route('enrollments.index')
            ->with('success', 'Matrícula removida com sucesso.');
    }

    public function massDestroy(Request $request)
    {
        $ids = $request->input('ids');
        if ($ids) {
            Enrollment::whereIn('id', $ids)->delete();

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(null, 204);
            }

            return redirect()->route('enrollments.index')
                ->with('success', 'Matrículas selecionadas foram removidas.');
        }

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Nenhuma matrícula selecionada.'], 422);
        }

        return redirect()->route('enrollments.index')
            ->with('error', 'Nenhuma matrícula selecionada.');
    }
}
