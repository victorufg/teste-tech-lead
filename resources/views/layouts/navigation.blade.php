<nav x-data="{ open: false }" class="bg-white border-r border-slate-200/60 w-64 min-w-[16rem] hidden md:flex flex-col h-screen sticky top-0 z-10 transition-all duration-300">
    <!-- Desktop Logo -->
    <div class="flex items-center px-6 py-8">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
            <div class="bg-slate-900 p-2 rounded-xl shadow-sm">
                <x-application-logo class="h-6 w-6 text-white" />
            </div>
            <span class="text-2xl font-black tracking-tighter text-slate-900">EduSys</span>
        </a>
    </div>

        <!-- Navigation Menu -->
        <div class="flex-1 px-4 py-2 space-y-1 overflow-y-auto">
            <div class="px-3 py-3">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Painel Principal</span>
            </div>
            
            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="dashboard">
                Dashboard
            </x-sidebar-link>

            <div class="px-3 py-3 mt-4">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Gestão Educacional</span>
            </div>

            <x-sidebar-link :href="route('courses.index')" :active="request()->routeIs('courses.*')" icon="courses">
                Cursos
            </x-sidebar-link>

            <x-sidebar-link :href="route('students.index')" :active="request()->routeIs('students.*')" icon="students">
                Alunos
            </x-sidebar-link>

            <x-sidebar-link :href="route('enrollments.index')" :active="request()->routeIs('enrollments.*')" icon="enrollments">
                Matrículas
            </x-sidebar-link>
        </div>

        <!-- User Section Footer -->
        <div class="p-4 border-t border-slate-100 bg-slate-50/30">
            <x-dropdown align="top" width="48">
                <x-slot name="trigger">
                    <button class="flex items-center w-full p-2.5 rounded-2xl hover:bg-white hover:shadow-premium transition-all duration-300 group">
                        <div class="h-10 w-10 bg-slate-900 rounded-xl flex items-center justify-center text-white text-sm font-black shadow-sm group-hover:scale-105 transition-transform">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="ml-3 text-left flex-1 min-w-0">
                            <p class="text-sm font-bold text-slate-900 leading-none truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[11px] text-slate-400 mt-1 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <svg class="h-4 w-4 text-slate-400 group-hover:text-slate-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4 4 4-4"></path>
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <div class="px-4 py-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-50">Conta</div>
                    <x-dropdown-link :href="route('profile.edit')" class="text-sm font-medium py-2.5">
                        {{ __('Ver Perfil') }}
                    </x-dropdown-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="text-sm font-medium text-rose-500 hover:bg-rose-50 py-2.5">
                            {{ __('Sair') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
</nav>
