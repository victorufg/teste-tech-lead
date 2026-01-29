<x-app-layout>
    <div x-data="{
        viewingEnrollment: null,
        isLoading: false,
        selected: [],
        toggleAll(checked) {
            this.selected = checked ? [{{ $enrollments->pluck('id')->map(fn($id) => "'$id'")->join(',') }}] : [];
        },

        async openViewDrawer(id) {
            this.viewingEnrollment = null; // Reset to show loader
            $dispatch('open-drawer-view-enrollment'); // Open immediately
            
            this.isLoading = true;
            try {
                const response = await fetch(`/enrollments/${id}`, {
                    headers: { 'Accept': 'application/json' }
                });
                this.viewingEnrollment = await response.json();
            } catch (e) {
                console.error(e);
            } finally {
                this.isLoading = false;
            }
        }
    }" class="space-y-8">
        <!-- Dashboard Heading -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Matrículas</h1>
                <p class="mt-2 text-sm text-slate-500 font-medium">Gestão centralizada de inscrições e vínculos alunos-cursos.</p>
            </div>
            <div class="flex items-center gap-3">
                <button 
                    @click="$dispatch('open-drawer-new-enrollment')"
                    class="inline-flex items-center px-5 py-2.5 bg-slate-900 border border-transparent rounded-xl font-bold text-sm text-white shadow-premium hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition-all active:scale-95"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                    Nova Matrícula
                </button>
            </div>
        </div>

        <!-- Side Drawer for New Enrollment -->
        <x-side-drawer name="new-enrollment" title="Vincular Aluno ao Curso">
            <form method="POST" action="{{ route('enrollments.store') }}" class="space-y-8">
                @csrf

                <!-- Student Selection -->
                <div>
                    <label for="student_id" class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2 px-1">Selecionar Aluno</label>
                    <select id="student_id" name="student_id" required
                        class="block w-full px-5 py-4 border border-slate-200 rounded-2xl text-sm bg-slate-50/50 text-slate-900 focus:outline-none focus:ring-4 focus:ring-slate-900/5 focus:border-slate-400 transition-all shadow-sm appearance-none">
                        <option value="">Selecione um aluno...</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                </div>

                <!-- Course Selection -->
                <div>
                    <label for="course_id" class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2 px-1">Selecionar Curso</label>
                    <select id="course_id" name="course_id" required
                        class="block w-full px-5 py-4 border border-slate-200 rounded-2xl text-sm bg-slate-50/50 text-slate-900 focus:outline-none focus:ring-4 focus:ring-slate-900/5 focus:border-slate-400 transition-all shadow-sm appearance-none">
                        <option value="">Selecione um curso...</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('course_id')" class="mt-2" />
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full flex items-center justify-center px-6 py-4 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase tracking-[0.2em] shadow-premium hover:bg-slate-800 transition-all active:scale-95">
                        Efetivar Matrícula
                    </button>
                </div>
            </form>
            </form>
        </x-side-drawer>

        <!-- Side Drawer for View Enrollment -->
        <x-side-drawer name="view-enrollment" title="Detalhes da Matrícula">
            <template x-if="viewingEnrollment">
                <div class="space-y-8">
                    <!-- Student Info -->
                    <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Aluno</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Nome</span>
                                <p class="text-sm font-bold text-slate-900" x-text="viewingEnrollment.student.name"></p>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Email</span>
                                <p class="text-sm font-medium text-slate-600" x-text="viewingEnrollment.student.email"></p>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase">CPF</span>
                                <p class="text-sm font-medium text-slate-600" x-text="viewingEnrollment.student.cpf"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Course Info -->
                    <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Curso</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Curso</span>
                                <p class="text-sm font-bold text-slate-900" x-text="viewingEnrollment.course.name"></p>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Tipo</span>
                                <p class="text-sm font-medium text-slate-600">
                                    <span x-show="viewingEnrollment.course.type === 'online'" class="inline-flex px-2 py-1 rounded bg-sky-100 text-sky-700 text-xs font-bold uppercase">Online</span>
                                    <span x-show="viewingEnrollment.course.type !== 'online'" class="inline-flex px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-xs font-bold uppercase">Presencial</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template x-if="!viewingEnrollment">
                <div class="flex items-center justify-center py-12">
                    <svg class="animate-spin h-8 w-8 text-slate-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </template>
        </x-side-drawer>

        <!-- Premium Datatable Card -->
        <div class="bg-white rounded-[2rem] shadow-premium border border-slate-200/50 overflow-hidden">
            
            <!-- Table Action Bar -->
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/30 flex flex-col lg:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-4 w-full lg:w-auto">
                    <div class="relative group flex-1 lg:flex-none">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-slate-900 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                        <input type="text" placeholder="Buscar por aluno ou curso..." class="block w-full lg:w-80 pl-11 pr-4 py-3 border border-slate-200 rounded-2xl text-sm bg-white placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-900/5 focus:border-slate-400 transition-all shadow-sm">
                    </div>
                </div>

                <div class="flex items-center gap-4 w-full lg:w-auto justify-end">
                    <select name="limit" onchange="window.location.href='{{ route('enrollments.index') }}?limit=' + this.value" class="appearance-none bg-white border border-slate-200 text-slate-900 text-xs font-bold rounded-2xl pl-4 pr-10 py-3 focus:ring-4 focus:ring-slate-900/5 focus:border-slate-400 shadow-sm cursor-pointer hover:bg-slate-50 transition-all">
                        <option value="15" {{ request('limit') == 15 ? 'selected' : '' }}>15 por página</option>
                        <option value="25" {{ request('limit') == 25 ? 'selected' : '' }}>25 por página</option>
                        <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50 por página</option>
                        <option value="100" {{ request('limit') == 100 ? 'selected' : '' }}>100 por página</option>
                    </select>
                </div>
            </div>

            <!-- Table Implementation -->
            <div class="overflow-x-auto overflow-y-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white">
                            <th class="pl-8 pr-4 py-5 w-12 border-b border-slate-100">
                                <input type="checkbox" 
                                    @change="toggleAll($el.checked)"
                                    class="rounded border-slate-300 text-slate-900 shadow-sm focus:border-slate-300 focus:ring focus:ring-slate-200 focus:ring-opacity-50 cursor-pointer">
                            </th>
                            <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Curso</th>
                            <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Estudante</th>
                            <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Data da Matrícula</th>
                            <th class="px-8 py-5 text-right text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($enrollments as $enrollment)
                            <tr class="group hover:bg-slate-50/50 transition-all duration-300 cursor-default">
                                <td class="pl-8 pr-4 py-6">
                                    <input type="checkbox" value="{{ $enrollment->id }}" x-model="selected"
                                        class="rounded border-slate-300 text-slate-900 shadow-sm focus:border-slate-300 focus:ring focus:ring-slate-200 focus:ring-opacity-50 cursor-pointer">
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="h-10 w-10 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-sm">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                        </div>
                                        <div class="text-sm font-black text-slate-900 tracking-tight">{{ $enrollment->course->name }}</div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-700 tracking-tight">{{ $enrollment->student->name }}</span>
                                        <span class="text-[10px] font-bold text-slate-400 mt-0.5">{{ $enrollment->student->email }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-700 tracking-tight">
                                            {{ $enrollment->created_at->format('d/m/Y') }}
                                        </span>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase mt-0.5">Vínculo Criado</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-2 transition-all duration-300">
                                        <button @click="openViewDrawer({{ $enrollment->id }})" class="p-2.5 bg-white text-slate-400 hover:text-slate-900 border border-slate-200 rounded-xl hover:shadow-premium transition-all active:scale-90">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        </button>
                                        <form action="{{ route('enrollments.destroy', $enrollment) }}" method="POST" class="inline" onsubmit="return confirm('Excluir matrícula?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2.5 bg-white text-slate-400 hover:text-rose-600 border border-slate-200 hover:border-rose-100 rounded-xl hover:shadow-premium transition-all active:scale-90">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-32 text-center bg-white rounded-b-[2rem]">
                                    <div class="flex flex-col items-center max-w-sm mx-auto">
                                        <div class="h-20 w-20 bg-slate-50 border border-dashed border-slate-300 rounded-[2rem] flex items-center justify-center text-slate-300 mb-6 animate-pulse">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <h3 class="text-xl font-black text-slate-900 tracking-tight">Nenhuma matrícula encontrada</h3>
                                        <p class="mt-2 text-slate-400 font-bold text-xs uppercase tracking-widest leading-relaxed">Não há vínculos registrados no sistema no momento.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Modern Pagination -->
            @if($enrollments->hasPages())
                <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
                    <div class="hidden sm:block">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Página {{ $enrollments->currentPage() }} de {{ $enrollments->lastPage() }}</p>
                    </div>
                    <div class="flex-1 sm:flex-none">
                        {{ $enrollments->appends(['limit' => request('limit')])->links() }}
                    </div>
                </div>
            @endif
        </div>
    <!-- Floating Action Bar for Mass Delete -->
    <div x-show="selected.length > 0" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-full"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-full"
            class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-slate-900 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-6 z-50 border border-slate-700/50"
            style="display: none;">
        
        <div class="flex items-center gap-3">
            <span class="bg-white/10 text-white font-black text-xs px-2.5 py-1 rounded-lg" x-text="selected.length"></span>
            <span class="font-bold text-sm">itens selecionados</span>
        </div>

        <div class="h-6 w-px bg-white/20"></div>

        <form action="{{ route('enrollments.massDestroy') }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir os itens selecionados?')">
            @csrf
            @method('DELETE')
            <template x-for="id in selected">
                <input type="hidden" name="ids[]" :value="id">
            </template>
            <button type="submit" class="text-rose-400 hover:text-rose-200 font-bold text-sm uppercase tracking-wider transition-colors">
                Excluir Selecionados
            </button>
        </form>

        <button @click="selected = []" class="text-slate-400 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    </div>
</x-app-layout>
