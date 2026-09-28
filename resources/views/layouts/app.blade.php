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
</head>
<body class="antialiased font-['Inter'] text-[#1E293B] overflow-hidden" x-data="layoutApp()">
    {{-- Estructura: Fondo azul vibrante, Padding general --}}
    <div class="h-screen p-6 flex items-center justify-center">

        {{-- EL ÚNICO GRAN CONTENEDOR BLANCO con su sombra y bordes redondeados --}}
        <div class="main-white-container w-full h-full flex relative overflow-hidden bg-white shadow-[0_20px_50px_rgba(0,0,0,0.1)] rounded-[32px] p-4">
            
            {{-- Sidebar DENTRO del contenedor blanco --}}
            @include('inbox.partials.sidebar-nav')

            {{-- Área principal a la derecha del sidebar --}}
            <div class="flex-1 flex flex-col h-full bg-white relative z-10 overflow-hidden">
                
                {{-- Header integrado --}}
                <header class="h-[72px] flex items-center justify-between px-8 shrink-0 border-b border-[#F1F5F9]">
                    {{-- Logo y saludo --}}
                    <div class="flex items-center gap-3 flex-1">
                        <button @click="sidebarOpen = !sidebarOpen" class="w-8 h-8 flex items-center justify-center rounded-lg text-[#94A3B8] hover:bg-[#F1F5F9] hover:text-[#1E293B] transition-colors -ml-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div>
                            <h1 class="text-[20px] font-bold text-main tracking-tight mt-1">Hola, {{ explode(' ', auth()->user()->name ?? 'Admin')[0] }}</h1>
                        </div>
                    </div>

                    {{-- Buscador global --}}
                    <div class="w-full max-w-md mx-4">
                        <div class="relative w-full max-w-[280px]">
                            <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-[#94A3B8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input
                                type="text"
                                x-model="globalSearchQuery"
                                @input.debounce.500ms="performGlobalSearch()"
                                placeholder="Buscar en el sistema..."
                                class="w-full pl-11 pr-4 py-2 text-[14px] input-corp"
                            >
                        </div>
                    </div>

                    {{-- Iconos derecha --}}
                    <div class="flex items-center gap-4">
                        <button class="w-8 h-8 flex items-center justify-center rounded-full text-[#94A3B8] hover:bg-[#F1F5F9] hover:text-[#1E293B] transition-colors relative">
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                            <svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </button>
                        
                        <button class="w-8 h-8 flex items-center justify-center rounded-full text-[#94A3B8] hover:bg-[#F1F5F9] hover:text-[#1E293B] transition-colors relative">
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                            <svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </button>
                        
                        <div class="w-8 h-8 rounded-full bg-corp cursor-pointer shadow-md border-2 border-white flex items-center justify-center text-white text-[12px] font-bold">
                            {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                        </div>
                    </div>
                </header>

                {{-- Main Content --}}
                <main class="flex-1 flex overflow-hidden">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <script>
    function layoutApp() {
        return {
            sidebarOpen: true,
            notificationsOpen: false,
            notifications: [],
            globalSearchQuery: '',
            searchResults: null,
        }
    }
    </script>
</body>
</html>
