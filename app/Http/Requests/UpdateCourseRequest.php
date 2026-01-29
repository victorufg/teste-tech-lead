<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'type' => ['required', 'in:online,presencial'],
            'max_students' => ['nullable', 'integer', 'min:1', 'max:10000'],
            'enrollment_deadline' => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Este campo é obrigatório.',
            'name.min' => 'O nome do curso deve ter pelo menos 3 caracteres.',
            'type.required' => 'Este campo é obrigatório.',
            'type.in' => 'O tipo selecionado é inválido.',
            'max_students.integer' => 'O limite de vagas deve ser um número inteiro.',
            'max_students.min' => 'O limite de vagas deve ser pelo menos 1.',
            'enrollment_deadline.required' => 'Este campo é obrigatório.',
            'enrollment_deadline.date' => 'Informe uma data válida.',
            'enrollment_deadline.after_or_equal' => 'O prazo não pode ser uma data retroativa.',
        ];
    }
}
