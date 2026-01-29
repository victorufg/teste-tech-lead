<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Resources\StudentResource;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 15);
        $search = $request->input('search');

        $students = Student::when($search, function ($query, $search) {
                if (is_numeric($search)) {
                    return $query->where('id', $search)
                                 ->orWhere('name', 'like', "%{$search}%")
                                 ->orWhere('email', 'like', "%{$search}%")
                                 ->orWhere('cpf', 'like', "%{$search}%");
                }
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%")
                             ->orWhere('cpf', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        if ($request->wantsJson() || $request->is('api/*')) {
            return StudentResource::collection($students);
        }

        if ($request->ajax()) {
            return view('students.partials.table', compact('students'));
        }

        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        \Illuminate\Support\Facades\Log::info('Start Student Store');
        
        $data = $request->validated();
        \Illuminate\Support\Facades\Log::info('Validation Passed');

        $student = Student::create($data);
        \Illuminate\Support\Facades\Log::info('Student Created in DB');

        if ($request->wantsJson() || $request->is('api/*')) {
            return new StudentResource($student);
        }

        return redirect()->route('students.index')
            ->with('success', 'Aluno cadastrado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        if (request()->wantsJson() || request()->is('api/*')) {
            return new StudentResource($student);
        }
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        $student->update($request->validated());

        if ($request->wantsJson() || $request->is('api/*')) {
            return new StudentResource($student);
        }

        return redirect()->route('students.index')
            ->with('success', 'Aluno atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();

        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json(null, 204);
        }

        return redirect()->route('students.index')
            ->with('success', 'Aluno excluído com sucesso.');
    }

    public function massDestroy(Request $request)
    {
        $ids = $request->input('ids');
        if ($ids) {
            Student::whereIn('id', $ids)->delete();
            return redirect()->route('students.index')
                ->with('success', 'Alunos selecionados foram excluídos.');
        }
        return redirect()->route('students.index')
            ->with('error', 'Nenhum aluno selecionado.');
    }
}
