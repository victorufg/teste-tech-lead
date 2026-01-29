<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Novo Aluno') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="p-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('students.store') }}" class="space-y-6">
                        @csrf

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Nome do Aluno')" class="text-lg font-medium" />
                            <x-text-input id="name" class="block mt-2 w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 transition-colors py-3" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div>
                            <x-input-label for="email" :value="__('Email')" class="text-lg font-medium" />
                            <x-text-input id="email" class="block mt-2 w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 transition-colors py-3" type="email" name="email" :value="old('email')" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Birth Date -->
                        <div>
                            <x-input-label for="birth_date" :value="__('Data de Nascimento')" class="text-lg font-medium" />
                            <x-text-input id="birth_date" class="block mt-2 w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 transition-colors py-3" type="date" name="birth_date" :value="old('birth_date')" required />
                            <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end pt-4 border-t border-gray-100 dark:border-gray-700 mt-6">
                            <a href="{{ route('students.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 mr-6 font-medium transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-primary-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md transform hover:scale-105">
                                {{ __('Salvar Aluno') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
