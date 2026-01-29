<table class="w-full text-left border-collapse">
    <thead>
        <tr class="bg-white">
            <th class="pl-8 pr-4 py-5 w-12 border-b border-slate-100">
                <input type="checkbox" 
                    @change="toggleSelectAll(@js($students->pluck('id')->map(fn($id) => (string)$id)->values()), $el.checked)"
                    class="rounded border-slate-300 text-slate-900 shadow-sm focus:border-slate-300 focus:ring focus:ring-slate-200 focus:ring-opacity-50 cursor-pointer">
            </th>
            <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Estudante</th>
            <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Contato</th>
            <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Documento</th>
            <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Data Nasc.</th>
            <th class="px-8 py-5 text-right text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Ações</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-slate-50">
        @forelse ($students as $student)
            <tr class="group hover:bg-slate-50/50 transition-all duration-300 cursor-default">
                <td class="pl-8 pr-4 py-6">
                    <input type="checkbox" value="{{ $student->id }}" x-model="selected"
                        class="rounded border-slate-300 text-slate-900 shadow-sm focus:border-slate-300 focus:ring focus:ring-slate-200 focus:ring-opacity-50 cursor-pointer">
                </td>
                <td class="px-8 py-6">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shadow-sm group-hover:scale-110 transition-transform duration-500">
                            <span class="text-lg font-black">{{ substr($student->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <div class="text-sm font-black text-slate-900 tracking-tight">{{ $student->name }}</div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter mt-0.5">ID: #{{ str_pad($student->id, 4, '0', STR_PAD_LEFT) }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-8 py-6">
                    <div class="flex flex-col">
                        <span class="text-sm font-bold text-slate-700 tracking-tight">{{ $student->email }}</span>
                        <span class="text-[10px] font-bold text-slate-400 mt-0.5">E-mail Pessoal</span>
                    </div>
                </td>
                <td class="px-8 py-6">
                    <span class="inline-flex items-center px-3 py-1 bg-slate-100 border border-slate-200 rounded-full text-[10px] font-black text-slate-600 uppercase tracking-widest">
                        {{ $student->cpf }}
                    </span>
                </td>
                <td class="px-8 py-6">
                    <div class="text-sm font-bold text-slate-700 tracking-tight">
                        {{ \Carbon\Carbon::parse($student->birth_date)->format('d/m/Y') }}
                    </div>
                </td>
                <td class="px-8 py-6 text-right">
                    <div class="flex items-center justify-end gap-2 transition-all duration-300">
                        <button 
                            @click="editStudent({ 
                                id: {{ $student->id }}, 
                                name: '{{ addslashes($student->name) }}', 
                                email: '{{ $student->email }}', 
                                cpf: '{{ $student->cpf }}', 
                                birth_date: '{{ $student->birth_date }}' 
                            })"
                            class="p-2.5 bg-white text-slate-400 hover:text-slate-900 border border-slate-200 rounded-xl hover:shadow-premium transition-all active:scale-90"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"></path></svg>
                        </button>
                        <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline" onsubmit="return confirm('Excluir aluno?')">
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
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 tracking-tight">Nenhum aluno encontrado</h3>
                        <p class="mt-2 text-slate-400 font-bold text-xs uppercase tracking-widest leading-relaxed">Não encontramos nenhum registro correspondente.</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div id="pagination-container">
    @if($students->hasPages())
        <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
            <div class="hidden sm:block">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Página {{ $students->currentPage() }} de {{ $students->lastPage() }}</p>
            </div>
            <div class="flex-1 sm:flex-none">
                {{ $students->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</div>
