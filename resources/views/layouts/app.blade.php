<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Smart AI Hosting Solutions')</title>
    <meta name="description" content="Plataforma omnicanal de atención al cliente con IA integrada">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Pusher y Laravel Echo -->
    <script src="https://js.pusher.com/8.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
    <script>
        window.Pusher = Pusher;
        window.Echo = new window.echo({
            broadcaster: 'reverb',
            key: '{{ config("broadcasting.connections.reverb.key") }}',
            wsHost: window.location.hostname,
            wsPort: {{ config("broadcasting.connections.reverb.options.port", 8080) }},
            wssPort: {{ config("broadcasting.connections.reverb.options.port", 8080) }},
            forceTLS: false,
            enabledTransports: ['ws', 'wss'],
        });
    </script>
</head>
<body class="antialiased bg-[#F8FAFC] text-[#1E293B] overflow-hidden" x-data="{ sidebarOpen: true }">
    <div class="h-screen flex flex-col">
        {{-- Header Superior --}}
        <header class="h-[52px] bg-white border-b border-[#E2E8F0] flex items-center justify-between px-4 shrink-0 z-10">
            {{-- Logo y nombre de empresa --}}
            <div class="flex items-center gap-2 flex-1">
                <button @click="sidebarOpen = !sidebarOpen" class="w-8 h-8 flex items-center justify-center rounded-lg text-[#64748B] hover:bg-[#F1F5F9] hover:text-[#1E293B] transition-colors mr-1">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="w-10 h-10 flex items-center justify-center rounded-lg overflow-hidden">
                    <img src="{{ asset('images/logo_chatbot_novape.png') }}" alt="Novape" class="w-full h-full object-cover scale-[1.5]">
                </div>
                <div class="flex items-center gap-1.5 cursor-pointer group">
                    <span class="text-sm font-bold text-[#1E293B] group-hover:text-[#0056D2] transition-colors">Smart AI Hosting</span>
                    <svg class="w-3.5 h-3.5 text-[#94A3B8] group-hover:text-[#0056D2] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            {{-- Buscador global --}}
            <div class="w-full max-w-lg mx-4">
                <div class="relative">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-[#94A3B8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input
                        type="text"
                        placeholder="Buscar contactos o mensajes"
                        class="w-full pl-10 pr-4 py-2 text-sm bg-[#F8FAFC] border border-[#E2E8F0] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0056D2]/20 focus:border-[#0056D2] placeholder-[#94A3B8] transition-all"
                    >
                </div>
            </div>

            {{-- Iconos derecha --}}
            <div class="flex items-center gap-2 justify-end flex-1">
                {{-- Configuración --}}
                <a href="{{ route('settings.index') }}" class="w-9 h-9 rounded-lg flex items-center justify-center text-[#64748B] hover:bg-[#F1F5F9] hover:text-[#1E293B] transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </a>

                {{-- Notificaciones --}}
                <button class="w-9 h-9 rounded-lg flex items-center justify-center text-[#64748B] hover:bg-[#F1F5F9] hover:text-[#1E293B] transition-colors relative">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center">1</span>
                </button>

                {{-- Calendario --}}
                <a href="{{ route('calendar.index') }}" class="w-9 h-9 rounded-lg flex items-center justify-center text-[#64748B] hover:bg-[#F1F5F9] hover:text-[#1E293B] transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </a>

                {{-- Avatar del usuario --}}
                <a href="{{ route('settings.index') }}" class="w-9 h-9 rounded-full bg-gradient-to-br from-[#8B5CF6] to-[#6D28D9] flex items-center justify-center text-white text-xs font-bold cursor-pointer hover:ring-2 hover:ring-[#8B5CF6]/30 transition-all ml-1">
                    EA
                </a>
            </div>
        </header>

        <div class="flex-1 flex overflow-hidden relative">
            {{-- Sidebar de Navegación --}}
            @include('inbox.partials.sidebar-nav')

            {{-- Main Content --}}
            <main class="flex-1 flex overflow-hidden">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
