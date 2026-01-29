<x-app-layout>
    <div x-data="{ 
        editingStudent: { id: null, name: '', email: '', cpf: '', birth_date: '' },
        errors: {},
        isLoading: false,
        selected: [],
        toggleSelectAll(ids, checked) {
            // Force string conversion for strict matching with checkbox values
            this.selected = checked ? ids.map(id => String(id)) : [];
        },

        editStudent(student) {
            this.errors = {};
            this.editingStudent = { ...student };
            $dispatch('open-drawer-edit-student');
        },

        async performSearch() {
            const url = new URL(window.location.href);
            if (this.search) {
                url.searchParams.set('search', this.search);
            } else {
                url.searchParams.delete('search');
            }
            url.searchParams.delete('page'); 
            
            try {
                this.isLoading = true;
                const response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const html = await response.text();
                
                document.getElementById('table-container').innerHTML = html;
                
                // Re-initialize Alpine on the new content (Critical for Mass Delete)
                if (window.Alpine) {
                    window.Alpine.initTree(document.getElementById('table-container'));
                }

                window.history.pushState({}, '', url);
            } catch (e) {
                console.error(e);
            } finally {
                this.isLoading = false;
            }
        },

        search: '{{ request('search') }}',

        async submitForm(event) {
            this.errors = {};
            const form = event.target;
            const formData = new FormData(form);

            // Client-side validation for instant feedback
            let hasErrors = false;
            const fields = ['name', 'email', 'cpf', 'birth_date'];
            fields.forEach(field => {
                if (!formData.get(field)) {
                    this.errors[field] = ['Este campo é obrigatório.'];
                    hasErrors = true;
                }
            });

            if (hasErrors) return;

            this.isLoading = true;
            
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });

                if (response.ok) {
                    window.location.reload();
                } else {
                    const data = await response.json();
                    this.errors = { ...this.errors, ...(data.errors || {}) };
                }
            } catch (e) {
                console.error(e);
            } finally {
                this.isLoading = false;
            }
        }
    }" 
    class="space-y-8">
        <!-- Dashboard Heading -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Alunos</h1>
                <p class="mt-2 text-sm text-slate-500 font-medium">Gerencie a base de estudantes e suas informações cadastrais.</p>
            </div>
            <div class="flex items-center gap-3">
                <button 
                    @click="$dispatch('open-drawer-new-student')"
                    class="inline-flex items-center px-5 py-2.5 bg-slate-900 border border-transparent rounded-xl font-bold text-sm text-white shadow-premium hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition-all active:scale-95"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                    Novo Aluno
                </button>
            </div>
        </div>

        <!-- Side Drawer for New Student -->
        <x-side-drawer name="new-student" title="Cadastrar Novo Aluno">
            <form @submit.prevent="submitForm" action="{{ route('students.store') }}" method="POST" class="space-y-8" novalidate>
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2 px-1">Nome Completo</label>
                    <input type="text" id="name" name="name" autofocus 
                        class="block w-full px-5 py-4 border border-slate-200 rounded-2xl text-sm bg-slate-50/50 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-900/5 focus:border-slate-400 transition-all shadow-sm"
                        placeholder="Ex: João Silva">
                    
                    <template x-if="errors.name">
                        <ul class="mt-2 space-y-1 text-rose-500 text-[10px] font-bold uppercase tracking-widest">
                            <template x-for="error in errors.name">
                                <li x-text="error"></li>
                            </template>
                        </ul>
                    </template>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2 px-1">E-mail</label>
                    <input type="email" id="email" name="email" 
                        class="block w-full px-5 py-4 border border-slate-200 rounded-2xl text-sm bg-slate-50/50 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-900/5 focus:border-slate-400 transition-all shadow-sm"
                        placeholder="joao@exemplo.com">
                    
                    <template x-if="errors.email">
                        <ul class="mt-2 space-y-1 text-rose-500 text-[10px] font-bold uppercase tracking-widest">
                            <template x-for="error in errors.email">
                                <li x-text="error"></li>
                            </template>
                        </ul>
                    </template>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <!-- CPF -->
                    <div>
                        <label for="cpf" class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2 px-1">CPF</label>
                        <input type="text" id="cpf" name="cpf" 
                            class="block w-full px-5 py-4 border border-slate-200 rounded-2xl text-sm bg-slate-50/50 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-900/5 focus:border-slate-400 transition-all shadow-sm"
                            placeholder="000.000.000-00">
                        
                        <template x-if="errors.cpf">
                            <ul class="mt-2 space-y-1 text-rose-500 text-[10px] font-bold uppercase tracking-widest">
                                <template x-for="error in errors.cpf">
                                    <li x-text="error"></li>
                                </template>
                            </ul>
                        </template>
                        <x-input-error :messages="$errors->get('cpf')" class="mt-2" />
                    </div>

                    <!-- Birth Date -->
                    <div>
                        <label for="birth_date" class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2 px-1">Data de Nascimento</label>
                        <input type="date" id="birth_date" name="birth_date" 
                            class="block w-full px-5 py-4 border border-slate-200 rounded-2xl text-sm bg-slate-50/50 text-slate-900 focus:outline-none focus:ring-4 focus:ring-slate-900/5 focus:border-slate-400 transition-all shadow-sm">
                        
                        <template x-if="errors.birth_date">
                            <ul class="mt-2 space-y-1 text-rose-500 text-[10px] font-bold uppercase tracking-widest">
                                <template x-for="error in errors.birth_date">
                                    <li x-text="error"></li>
                                </template>
                            </ul>
                        </template>
                        <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" :disabled="isLoading" class="w-full flex items-center justify-center px-6 py-4 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase tracking-[0.2em] shadow-premium hover:bg-slate-800 transition-all active:scale-95 disabled:opacity-50">
                        <span x-show="!isLoading">Confirmar Cadastro</span>
                        <span x-show="isLoading" style="display: none">Processando...</span>
                    </button>
                </div>
            </form>
        </x-side-drawer>

        <!-- Side Drawer for Edit Student -->
        <x-side-drawer name="edit-student" title="Editar Aluno">
            <form @submit.prevent="submitForm" :action="`/students/${editingStudent.id}`" method="POST" class="space-y-8" novalidate>
                @csrf
                @method('PUT')
                <input type="hidden" name="id" x-model="editingStudent.id">

                <!-- Name -->
                <div>
                    <label for="edit_name" class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2 px-1">Nome Completo</label>
                    <input type="text" id="edit_name" name="name" x-model="editingStudent.name"  
                        class="block w-full px-5 py-4 border border-slate-200 rounded-2xl text-sm bg-slate-50/50 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-900/5 focus:border-slate-400 transition-all shadow-sm"
                        placeholder="Ex: João Silva">
                    
                    <template x-if="errors.name">
                        <ul class="mt-2 space-y-1 text-rose-500 text-[10px] font-bold uppercase tracking-widest">
                            <template x-for="error in errors.name">
                                <li x-text="error"></li>
                            </template>
                        </ul>
                    </template>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email -->
                <div>
                    <label for="edit_email" class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2 px-1">E-mail</label>
                    <input type="email" id="edit_email" name="email" x-model="editingStudent.email"  
                        class="block w-full px-5 py-4 border border-slate-200 rounded-2xl text-sm bg-slate-50/50 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-900/5 focus:border-slate-400 transition-all shadow-sm"
                        placeholder="joao@exemplo.com">
                    
                    <template x-if="errors.email">
                        <ul class="mt-2 space-y-1 text-rose-500 text-[10px] font-bold uppercase tracking-widest">
                            <template x-for="error in errors.email">
                                <li x-text="error"></li>
                            </template>
                        </ul>
                    </template>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <!-- CPF -->
                    <div>
                        <label for="edit_cpf" class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2 px-1">CPF</label>
                        <input type="text" id="edit_cpf" name="cpf" x-model="editingStudent.cpf" 
                            class="block w-full px-5 py-4 border border-slate-200 rounded-2xl text-sm bg-slate-50/50 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-900/5 focus:border-slate-400 transition-all shadow-sm"
                            placeholder="000.000.000-00">
                        
                        <template x-if="errors.cpf">
                            <ul class="mt-2 space-y-1 text-rose-500 text-[10px] font-bold uppercase tracking-widest">
                                <template x-for="error in errors.cpf">
                                    <li x-text="error"></li>
                                </template>
                            </ul>
                        </template>
                        <x-input-error :messages="$errors->get('cpf')" class="mt-2" />
                    </div>

                    <!-- Birth Date -->
                    <div>
                        <label for="edit_birth_date" class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2 px-1">Data de Nascimento</label>
                        <input type="date" id="edit_birth_date" name="birth_date" x-model="editingStudent.birth_date" 
                            class="block w-full px-5 py-4 border border-slate-200 rounded-2xl text-sm bg-slate-50/50 text-slate-900 focus:outline-none focus:ring-4 focus:ring-slate-900/5 focus:border-slate-400 transition-all shadow-sm">
                        
                        <template x-if="errors.birth_date">
                            <ul class="mt-2 space-y-1 text-rose-500 text-[10px] font-bold uppercase tracking-widest">
                                <template x-for="error in errors.birth_date">
                                    <li x-text="error"></li>
                                </template>
                            </ul>
                        </template>
                        <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" :disabled="isLoading" class="w-full flex items-center justify-center px-6 py-4 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase tracking-[0.2em] shadow-premium hover:bg-slate-800 transition-all active:scale-95 disabled:opacity-50">
                        <span x-show="!isLoading">Salvar Alterações</span>
                        <span x-show="isLoading" style="display: none">Salvando...</span>
                    </button>
                </div>
            </form>
        </x-side-drawer>


        <!-- Premium Datatable Card -->
        <div class="bg-white rounded-[2rem] shadow-premium border border-slate-200/50 overflow-hidden">
            
            <!-- Table Action Bar -->
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/30 flex flex-col lg:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-4 w-full lg:w-auto">
                    <form action="{{ route('students.index') }}" method="GET" class="relative group flex-1 lg:flex-none flex items-center gap-3">
                        <div class="relative flex-1">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-slate-900 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </span>
                            <input type="text" 
                                name="search"
                                value="{{ request('search') }}"
                                x-model="search"
                                placeholder="Buscar por nome, email ou CPF..." 
                                class="block w-full lg:w-80 pl-11 pr-4 py-3 border border-slate-200 rounded-2xl text-sm bg-white placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-900/5 focus:border-slate-400 transition-all shadow-sm">
                        </div>
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-900 text-xs font-black uppercase tracking-widest rounded-2xl transition-all active:scale-95 shadow-sm border border-slate-200">
                            Buscar
                        </button>
                    </form>
                </div>

                <div class="flex items-center gap-4 w-full lg:w-auto justify-end">
                    <select name="limit" onchange="window.location.href='{{ route('students.index') }}?limit=' + this.value" class="appearance-none bg-white border border-slate-200 text-slate-900 text-xs font-bold rounded-2xl pl-4 pr-10 py-3 focus:ring-4 focus:ring-slate-900/5 focus:border-slate-400 shadow-sm cursor-pointer hover:bg-slate-50 transition-all">
                        <option value="15" {{ request('limit') == 15 ? 'selected' : '' }}>15 por página</option>
                        <option value="25" {{ request('limit') == 25 ? 'selected' : '' }}>25 por página</option>
                        <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50 por página</option>
                        <option value="100" {{ request('limit') == 100 ? 'selected' : '' }}>100 por página</option>
                    </select>
                </div>
            </div>

            <!-- Table Implementation -->
            <div class="relative min-h-[400px]">
                <div x-show="isLoading" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute inset-0 bg-white/80 backdrop-blur-[2px] z-20 flex items-center justify-center rounded-b-[2rem]">
                    <div class="flex flex-col items-center">
                        <svg class="animate-spin mb-3 h-10 w-10 text-slate-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-xs font-black text-slate-900 uppercase tracking-widest animate-pulse">Buscando...</span>
                    </div>
                </div>

                <div id="table-container" class="overflow-x-auto overflow-y-hidden">
                    @include('students.partials.table')
                </div>
            </div>
        </div>

    <!-- Floating Action Bar for Mass Delete -->
    <div x-show="selected.length > 0" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-full"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-full"
            class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-slate-900 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-6 z-50 border border-slate-700/50">
        
        <div class="flex items-center gap-3">
            <span class="bg-white/10 text-white font-black text-xs px-2.5 py-1 rounded-lg" x-text="selected.length"></span>
            <span class="font-bold text-sm">itens selecionados</span>
        </div>

        <div class="h-6 w-px bg-white/20"></div>

        <form action="{{ route('students.massDestroy') }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir os itens selecionados?')">
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
