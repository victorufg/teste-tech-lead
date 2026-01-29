@props(['name', 'title'])

<div
    x-data="{ 
        show: false,
        open() { this.show = true; document.body.classList.add('overflow-hidden') },
        close() { this.show = false; document.body.classList.remove('overflow-hidden') }
    }"
    x-on:open-drawer-{{ $name }}.window="open()"
    x-on:keydown.escape.window="close()"
>
    <template x-teleport="body">
        <div 
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            style="display: none"
            class="fixed inset-0 top-0 left-0 w-full h-full z-50 overflow-hidden"
            role="dialog"
            aria-modal="true"
        >
    <!-- Background overlay -->
    <div 
        x-show="show"
        x-transition:enter="ease-in-out duration-500"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in-out duration-500"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="close()"
        class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
    ></div>

    <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex">
        <div 
            x-show="show"
            x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="w-screen max-w-md"
        >
            <div class="h-full flex flex-col bg-white shadow-premium border-l border-slate-200/60">
                <!-- Header -->
                <div class="px-6 py-8 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">{{ $title }}</h2>
                    <button @click="close()" class="p-2 rounded-xl text-slate-400 hover:text-slate-900 hover:bg-slate-100 transition-all">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Content -->
                <div class="relative flex-1 py-8 px-6 overflow-y-auto">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </template>
</div>
