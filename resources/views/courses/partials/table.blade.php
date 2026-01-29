<table class="w-full text-left border-collapse">
    <thead>
        <tr class="bg-white">
            <th class="pl-8 pr-4 py-5 w-12 border-b border-slate-100">
                <input type="checkbox" 
                    @change="toggleSelectAll(@js($courses->pluck('id')->map(fn($id) => (string)$id)->values()), $el.checked)"
                    class="rounded border-slate-300 text-slate-900 shadow-sm focus:border-slate-300 focus:ring focus:ring-slate-200 focus:ring-opacity-50 cursor-pointer">
            </th>
            <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Curso</th>
            <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Tipo</th>
            <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Matrículas</th>
            <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Data Limite</th>
            <th class="px-8 py-5 text-right text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Ações</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-slate-50">
        @forelse ($courses as $course)
            <tr class="group hover:bg-slate-50/50 transition-all duration-300 cursor-default">
                <td class="pl-8 pr-4 py-6">
                    <input type="checkbox" value="{{ $course->id }}" x-model="selected"
                        class="rounded border-slate-300 text-slate-900 shadow-sm focus:border-slate-300 focus:ring focus:ring-slate-200 focus:ring-opacity-50 cursor-pointer">
                </td>
                <td class="px-8 py-6">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-2xl bg-slate-900 flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform duration-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <div>
                            <div class="text-sm font-black text-slate-900 tracking-tight">{{ $course->name }}</div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter mt-0.5">ID: #{{ str_pad($course->id, 4, '0', STR_PAD_LEFT) }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-8 py-6">
                    <span @class([
                        'inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider',
                        'bg-emerald-50 text-emerald-600 border border-emerald-100' => $course->type === 'online',
                        'bg-slate-100 text-slate-600 border border-slate-200' => $course->type === 'presencial',
                    ])>
                        {{ $course->type }}
                    </span>
                </td>
                <td class="px-8 py-6">
                    <div class="flex flex-col gap-1.5 min-w-[120px]">
                        <div class="flex items-center justify-between text-[11px] font-bold text-slate-900 uppercase">
                            <span>{{ $course->students_count }} Alunos</span>
                            <span class="text-slate-400">{{ $course->max_students ?? '∞' }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                            <div class="bg-slate-900 h-full rounded-full transition-all duration-700 ease-out shadow-[0_0_8px_rgba(15,23,42,0.3)]" 
                                    style="width: {{ $course->max_students ? min(100, ($course->students_count / $course->max_students) * 100) : 100 }}%"></div>
                        </div>
                    </div>
                </td>
                <td class="px-8 py-6">
                    <div class="flex flex-col">
                        <span class="text-sm font-bold text-slate-700 tracking-tight">
                            {{ \Carbon\Carbon::parse($course->enrollment_deadline)->translatedFormat('d M, Y') }}
                        </span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase mt-0.5">Prazo Final</span>
                    </div>
                </td>
                <td class="px-8 py-6 text-right">
                    <div class="flex items-center justify-end gap-2 transition-all duration-300">
                        <button 
                            @click="editCourse({ 
                                id: {{ $course->id }}, 
                                name: '{{ addslashes($course->name) }}', 
                                type: '{{ $course->type }}', 
                                max_students: '{{ $course->max_students }}', 
                                enrollment_deadline: '{{ $course->enrollment_deadline }}' 
                            })"
                            class="p-2.5 bg-white text-slate-400 hover:text-slate-900 border border-slate-200 rounded-xl hover:shadow-premium transition-all active:scale-90"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"></path></svg>
                        </button>
                        <form action="{{ route('courses.destroy', $course) }}" method="POST" class="inline" onsubmit="return confirm('Excluir curso?')">
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
                <td colspan="5" class="px-8 py-32 text-center bg-white rounded-b-[2rem]">
                    <div class="flex flex-col items-center max-w-sm mx-auto">
                        <div class="h-20 w-20 bg-slate-50 border border-dashed border-slate-300 rounded-[2rem] flex items-center justify-center text-slate-300 mb-6 animate-pulse">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"></path></svg>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 tracking-tight">Nenhum curso encontrado</h3>
                        <p class="mt-2 text-slate-400 font-bold text-xs uppercase tracking-widest leading-relaxed">Não encontramos nenhum registro correspondente.</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div id="pagination-container">
    @if($courses->hasPages())
        <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
            <div class="hidden sm:block">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Página {{ $courses->currentPage() }} de {{ $courses->lastPage() }}</p>
            </div>
            <div class="flex-1 sm:flex-none">
                {{ $courses->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</div>
