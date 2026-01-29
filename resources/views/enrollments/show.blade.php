<x-app-layout>
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Detalhes da Matrícula</h1>
                <p class="mt-2 text-sm text-slate-500 font-medium">Informações completas sobre o vínculo.</p>
            </div>
            <a href="{{ route('enrollments.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-xl font-bold text-xs text-slate-600 uppercase tracking-widest hover:border-slate-300 hover:text-slate-800 transition-all active:scale-95 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Voltar
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Student Card -->
            <div class="bg-white rounded-[2rem] shadow-premium border border-slate-200/50 p-8">
                <div class="flex items-center gap-4 mb-6">
                    <div class="h-12 w-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">Aluno</h2>
                        <p class="text-sm text-slate-400">Dados do estudante</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Nome</span>
                        <p class="text-base font-semibold text-slate-900">{{ $enrollment->student->name }}</p>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Email</span>
                        <p class="text-base font-medium text-slate-600">{{ $enrollment->student->email }}</p>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">CPF</span>
                        <p class="text-base font-medium text-slate-600">{{ $enrollment->student->cpf }}</p>
                    </div>
                </div>
            </div>

            <!-- Course Card -->
            <div class="bg-white rounded-[2rem] shadow-premium border border-slate-200/50 p-8">
                <div class="flex items-center gap-4 mb-6">
                    <div class="h-12 w-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">Curso</h2>
                        <p class="text-sm text-slate-400">Curso matriculado</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Nome do Curso</span>
                        <p class="text-base font-semibold text-slate-900">{{ $enrollment->course->name }}</p>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Tipo</span>
                        <span class="inline-flex px-2 py-1 rounded-lg text-xs font-bold uppercase tracking-wide
                            {{ $enrollment->course->type === 'online' ? 'bg-sky-100 text-sky-700' : 'bg-emerald-100 text-emerald-700' }}">
                            {{ $enrollment->course->type === 'online' ? 'Online' : 'Presencial' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Data da Matrícula</span>
                        <p class="text-base font-medium text-slate-600">{{ $enrollment->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
