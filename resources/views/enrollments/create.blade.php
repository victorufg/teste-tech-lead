<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Realizar Matrícula') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 text-red-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-red-700 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="p-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('enrollments.store') }}" class="space-y-6">
                        @csrf

                        <!-- Course -->
                        <div>
                            <x-input-label for="course_id" :value="__('Selecione o Curso')" class="text-lg font-medium" />
                            <div class="relative mt-2">
                                <select id="course_id" name="course_id" class="block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 shadow-sm py-3 px-4 appearance-none transition-colors">
                                    <option value="">Escolha um curso...</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ (old('course_id') == $course->id || (isset($selectedCourse) && $selectedCourse->id == $course->id)) ? 'selected' : '' }}>
                                            {{ $course->name }} — (Deadline: {{ \Carbon\Carbon::parse($course->enrollment_deadline)->format('d/m/Y') }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                </div>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Apenas cursos com prazo de matrícula válido são exibidos.</p>
                            <x-input-error :messages="$errors->get('course_id')" class="mt-2" />
                        </div>

                        <!-- Student -->
                        <div>
                            <x-input-label for="student_id" :value="__('Selecione o Aluno')" class="text-lg font-medium" />
                            <div class="relative mt-2">
                                <select id="student_id" name="student_id" class="block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 shadow-sm py-3 px-4 appearance-none transition-colors">
                                    <option value="">Escolha um aluno...</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                            {{ $student->name }} — ({{ $student->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end pt-4 border-t border-gray-100 dark:border-gray-700 mt-6">
                            <a href="{{ route('enrollments.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 mr-6 font-medium transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-primary-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md transform hover:scale-105">
                                {{ __('Confirmar Matrícula') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
