<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Curso') }}: {{ $course->name }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="p-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('courses.update', $course) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Nome do Curso')" class="text-lg font-medium" />
                            <x-text-input id="name" class="block mt-2 w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 transition-colors py-3" type="text" name="name" :value="old('name', $course->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Type -->
                        <div>
                            <x-input-label for="type" :value="__('Tipo')" class="text-lg font-medium" />
                            <select id="type" name="type" class="block mt-2 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 shadow-sm py-3 transition-colors">
                                <option value="presential" {{ old('type', $course->type) == 'presential' ? 'selected' : '' }}>Presencial</option>
                                <option value="online" {{ old('type', $course->type) == 'online' ? 'selected' : '' }}>Online</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Max Students -->
                            <div>
                                <x-input-label for="max_students" :value="__('Máx. Alunos')" class="text-lg font-medium" />
                                <x-text-input id="max_students" class="block mt-2 w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 transition-colors py-3" type="number" name="max_students" :value="old('max_students', $course->max_students)" placeholder="Opcional" />
                                <x-input-error :messages="$errors->get('max_students')" class="mt-2" />
                            </div>

                            <!-- Deadline -->
                            <div>
                                <x-input-label for="enrollment_deadline" :value="__('Data Limite de Matrícula')" class="text-lg font-medium" />
                                <x-text-input id="enrollment_deadline" class="block mt-2 w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 transition-colors py-3" type="date" name="enrollment_deadline" :value="old('enrollment_deadline', $course->enrollment_deadline)" required />
                                <x-input-error :messages="$errors->get('enrollment_deadline')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end pt-4 border-t border-gray-100 dark:border-gray-700 mt-6">
                            <a href="{{ route('courses.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 mr-6 font-medium transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-primary-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md transform hover:scale-105">
                                {{ __('Atualizar Curso') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
