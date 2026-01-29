<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body id="app-body" class="font-sans antialiased bg-slate-50/50 text-slate-900 m-0 p-0 overflow-x-hidden">
        <!-- Dashboard Wrapper -->
        <div class="flex min-h-screen w-full">
            
            <!-- Desktop Sidebar -->
            @include('layouts.navigation')

            <!-- Mobile Navbar (Top) -->
            <div class="md:hidden flex h-16 bg-white border-b border-slate-200/60 px-6 items-center justify-between z-40">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                    <div class="bg-slate-900 p-1.5 rounded-lg">
                        <x-application-logo class="h-5 w-5 text-white" />
                    </div>
                    <span class="text-lg font-bold tracking-tight text-slate-900">EduSys</span>
                </a>
            </div>

            <!-- Main Content Container -->
            <div class="flex-1 flex flex-col min-w-0">
                <!-- Top Header (Optional) -->
                @isset($header)
                    <header class="bg-white/80 backdrop-blur-md border-b border-slate-200/60 z-10">
                        <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto overflow-x-hidden p-6">
                    <div class="max-w-7xl mx-auto">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        <!-- Teleport Target for Modals/Drawers -->
        <div id="modal-root" class="relative z-[99999]"></div>
    </body>
</html>
