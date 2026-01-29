<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
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
        $studentId = $this->route('student')->id ?? $this->student->id ?? $this->student;
        
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:students,email,' . $studentId],
            'cpf' => ['required', 'string', 'regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$|^\d{11}$/', 'unique:students,cpf,' . $studentId],
            'birth_date' => ['required', 'date', 'before:today'],
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
            'name.required' => 'O nome do aluno é obrigatório.',
            'name.min' => 'O nome deve ter pelo menos 3 caracteres.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Informe um e-mail válido.',
            'email.unique' => 'Este e-mail já está cadastrado para outro aluno.',
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.regex' => 'O formato do CPF é inválido.',
            'cpf.unique' => 'Este CPF já está cadastrado para outro aluno.',
            'birth_date.required' => 'A data de nascimento é obrigatória.',
            'birth_date.date' => 'Informe uma data válida.',
            'birth_date.before' => 'A data de nascimento não pode ser futura.',
        ];
    }
}
