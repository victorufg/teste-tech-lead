<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Resources\CourseResource;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 15);
        $search = $request->input('search');

        $courses = Course::select('id', 'name', 'type', 'max_students', 'enrollment_deadline', 'created_at')
            ->withCount('students')
            ->when($search, function ($query, $search) {
                if (is_numeric($search)) {
                    return $query->where('id', $search)
                                 ->orWhere('name', 'like', "%{$search}%");
                }
                return $query->where('name', 'like', "%{$search}%");
            })
            ->when($request->input('type'), function ($query, $type) {
                if ($type !== 'all') {
                    return $query->where('type', $type);
                }
                return $query;
            })
            ->when($request->input('deadline'), function ($query, $date) {
                return $query->whereDate('enrollment_deadline', $date);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        if ($request->wantsJson() || $request->is('api/*')) {
            return CourseResource::collection($courses);
        }

        if ($request->ajax()) {
            return view('courses.partials.table', compact('courses'));
        }

        return view('courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('courses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request)
    {
        $course = Course::create($request->validated());

        if ($request->wantsJson() || $request->is('api/*')) {
            return new CourseResource($course);
        }

        return redirect()->route('courses.index')
            ->with('success', 'Curso criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        if (request()->wantsJson() || request()->is('api/*')) {
            return new CourseResource($course->loadCount('students'));
        }
        return view('courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        return view('courses.edit', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        $course->update($request->validated());

        if ($request->wantsJson() || $request->is('api/*')) {
            return new CourseResource($course);
        }

        return redirect()->route('courses.index')
            ->with('success', 'Curso atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $course->delete();

        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json(null, 204);
        }

        return redirect()->route('courses.index')
            ->with('success', 'Curso excluído com sucesso.');
    }

    public function massDestroy(Request $request)
    {
        $ids = $request->input('ids');
        if ($ids) {
            Course::whereIn('id', $ids)->delete();
            return redirect()->route('courses.index')
                ->with('success', 'Cursos selecionados foram excluídos.');
        }
        return redirect()->route('courses.index')
            ->with('error', 'Nenhum curso selecionado.');
    }
}
