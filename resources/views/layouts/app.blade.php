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
<body class="antialiased font-['Montserrat'] bg-[#011B3D] text-white overflow-hidden" style="background: radial-gradient(circle at center, #012A5E 0%, #00122A 100%);" x-data="layoutApp()">
    <div class="h-screen flex flex-col relative z-10 p-6 gap-6">
        {{-- Header Superior (Floating Glassmorphism) --}}
        <header class="h-[64px] bg-[#00122A]/80 backdrop-blur-xl border border-white/10 rounded-[32px] flex items-center justify-between px-6 shrink-0 shadow-[0_15px_40px_-10px_rgba(0,0,0,0.5)] z-20 relative">
            {{-- Logo y nombre de empresa --}}
            <div class="flex items-center gap-2 flex-1">
                <button @click="sidebarOpen = !sidebarOpen" class="w-8 h-8 flex items-center justify-center rounded-lg text-white/70 hover:bg-white/10 hover:text-white transition-colors mr-1">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="w-10 h-10 flex items-center justify-center rounded-lg overflow-hidden relative">
                    <img src="{{ asset('images/logo_chatbot_novape.png') }}" alt="Novape" class="w-full h-full object-contain scale-[1.2] filter drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]">
                </div>
                <div class="flex items-center gap-1.5 cursor-pointer group">
                    <span class="text-sm font-bold text-white group-hover:text-[#00CEFF] transition-colors tracking-wide">Smart AI Hosting</span>
                    <svg class="w-3.5 h-3.5 text-white/50 group-hover:text-[#00CEFF] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            {{-- Buscador global --}}
            <div class="w-full max-w-lg mx-4">
                <div class="relative">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input
                        type="text"
                        x-model="globalSearchQuery"
                        @input.debounce.500ms="performGlobalSearch()"
                        placeholder="Buscar contactos o mensajes..."
                        class="w-full pl-10 pr-4 py-1.5 text-sm bg-black/20 border border-white/10 rounded-full focus:outline-none focus:ring-1 focus:ring-[#00CEFF] focus:border-[#00CEFF] focus:bg-black/40 text-white placeholder-white/50 transition-all shadow-inner"
                    >
                    {{-- Dropdown de resultados (Dark Glass) --}}
                    <div x-show="searchResults && (searchResults.contacts.length > 0 || searchResults.conversations.length > 0)" 
                         @click.away="searchResults = null; globalSearchQuery = ''"
                         class="absolute top-full mt-2 w-full bg-[#002B6A]/95 backdrop-blur-xl rounded-xl shadow-[0_15px_40px_-10px_rgba(0,0,0,0.7)] border border-white/10 overflow-hidden z-50" style="display: none;">
                        <div x-show="searchResults.contacts.length > 0" class="p-2 border-b border-white/10">
                            <div class="text-[10px] font-bold text-white/40 mb-1 px-2 uppercase tracking-widest">Contactos</div>
                            <template x-for="c in searchResults.contacts" :key="c.id">
                                <a :href="'/contacts'" class="block px-3 py-2 text-sm hover:bg-white/10 rounded-lg group transition-colors">
                                    <div class="font-semibold text-white group-hover:text-[#00CEFF] transition-colors" x-text="c.name"></div>
                                    <div class="text-xs text-white/60" x-text="c.phone_number || c.email"></div>
                                </a>
                            </template>
                        </div>
                        <div x-show="searchResults.conversations.length > 0" class="p-2">
                            <div class="text-[10px] font-bold text-white/40 mb-1 px-2 uppercase tracking-widest">Conversaciones</div>
                            <template x-for="c in searchResults.conversations" :key="c.id">
                                <a :href="'/inbox'" class="block px-3 py-2 text-sm hover:bg-white/10 rounded-lg group transition-colors">
                                    <div class="font-semibold text-white group-hover:text-[#00CEFF] transition-colors" x-text="'Con ' + c.contact_name"></div>
                                    <div class="text-xs text-white/60 capitalize" x-text="c.channel"></div>
                                </a>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Iconos derecha --}}
            <div class="flex items-center gap-2 justify-end flex-1">
                {{-- Configuración --}}
                <a href="{{ route('settings.index') }}" class="w-9 h-9 rounded-lg flex items-center justify-center text-white/70 hover:bg-white/10 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </a>

                {{-- Notificaciones --}}
                <div class="relative">
                    <button @click="toggleNotifications()" class="w-9 h-9 rounded-lg flex items-center justify-center text-white/70 hover:bg-white/10 hover:text-white transition-colors relative focus:outline-none">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span x-show="unreadNotifications > 0" class="absolute top-1 right-1 w-4 h-4 bg-rose-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center shadow-[0_0_8px_rgba(244,63,94,0.8)]" x-text="unreadNotifications" style="display:none;"></span>
                    </button>
                    
                    {{-- Dropdown de notificaciones (Dark Glass) --}}
                    <div x-show="notificationsOpen" @click.away="notificationsOpen = false" class="absolute right-0 mt-2 w-80 bg-[#002B6A]/95 backdrop-blur-xl rounded-xl shadow-[0_15px_40px_-10px_rgba(0,0,0,0.7)] border border-white/10 overflow-hidden z-50" style="display:none;">
                        <div class="p-3 border-b border-white/10 flex justify-between items-center bg-black/20">
                            <h3 class="font-bold text-sm text-white">Notificaciones</h3>
                            <button @click="markAllAsRead()" class="text-[11px] text-[#00CEFF] hover:text-[#00E5FF] font-medium tracking-wide uppercase">Marcar leídas</button>
                        </div>
                        <div class="max-h-80 overflow-y-auto">
                            <template x-if="notifications.length === 0">
                                <div class="p-4 text-center text-sm text-white/50">No tienes notificaciones nuevas</div>
                            </template>
                            <template x-for="notif in notifications" :key="notif.id">
                                <div class="p-3 border-b border-white/5 last:border-0 hover:bg-white/5 cursor-pointer transition-colors" :class="!notif.read_at ? 'bg-white/10' : ''" @click="markAsRead(notif)">
                                    <div class="text-sm text-white/90" x-text="notif.data.message || 'Nueva notificación'"></div>
                                    <div class="text-[10px] text-white/40 mt-1 uppercase tracking-wide" x-text="new Date(notif.created_at).toLocaleString()"></div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Calendario --}}
                <a href="{{ route('calendar.index') }}" class="w-9 h-9 rounded-lg flex items-center justify-center text-white/70 hover:bg-white/10 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </a>

                {{-- Avatar y Menú del Usuario --}}
                <div class="relative ml-1" x-data="{ userMenuOpen: false }">
                    <button @click="userMenuOpen = !userMenuOpen" @click.away="userMenuOpen = false" class="w-9 h-9 rounded-full bg-[#00CEFF] flex items-center justify-center text-[#011B3D] text-xs font-bold cursor-pointer hover:shadow-[0_0_15px_rgba(0,206,255,0.6)] transition-all focus:outline-none">
                        {{ strtoupper(substr(auth()->user()->name ?? 'EA', 0, 2)) }}
                    </button>

                    {{-- Dropdown Menu (Dark Glass) --}}
                    <div x-show="userMenuOpen" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-56 bg-[#00122A] backdrop-blur-xl rounded-xl shadow-[0_15px_40px_-10px_rgba(0,0,0,0.7)] border border-white/10 py-2 z-50"
                         style="display: none;">
                         
                        <div class="px-4 py-3 border-b border-white/10">
                            <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name ?? 'Usuario' }}</p>
                            <p class="text-xs font-medium text-white/50 truncate mt-0.5">{{ auth()->user()->email ?? 'usuario@empresa.com' }}</p>
                            <div class="mt-2 inline-block px-2 py-0.5 rounded text-[9px] font-bold bg-[#00CEFF]/20 text-[#00CEFF] uppercase tracking-widest border border-[#00CEFF]/30">
                                {{ auth()->user()->role === 'tenant_admin' ? 'Administrador' : (auth()->user()->role === 'tenant_supervisor' ? 'Supervisor' : 'Agente') }}
                            </div>
                        </div>

                        <a href="{{ route('settings.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-white/80 hover:bg-white/10 hover:text-white transition-colors mt-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Mi Perfil & Ajustes
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="mt-1 border-t border-white/10 pt-1">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm font-medium text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex-1 flex overflow-hidden relative gap-6">
            {{-- Sidebar de Navegación --}}
            @include('inbox.partials.sidebar-nav')

            {{-- Main Content --}}
            <main class="flex-1 flex overflow-hidden bg-transparent">
                @yield('content')
            </main>
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

            init() {
                this.fetchNotifications();
                
                if (window.Echo) {
                    const userId = {{ auth()->id() ?? 'null' }};
                    if (userId) {
                        window.Echo.private(`App.Models.User.${userId}`)
                            .notification((notification) => {
                                this.notifications.unshift({
                                    id: notification.id,
                                    data: notification,
                                    created_at: new Date(),
                                    read_at: null
                                });
                            });
                    }
                }
            },

            get unreadNotifications() {
                return this.notifications.filter(n => !n.read_at).length;
            },

            toggleNotifications() {
                this.notificationsOpen = !this.notificationsOpen;
            },

            async fetchNotifications() {
                try {
                    const res = await fetch('/api/notifications', {
                        headers: { 'Accept': 'application/json' }
                    });
                    if (res.ok) {
                        this.notifications = await res.json();
                    }
                } catch(e) { console.error(e); }
            },

            async markAsRead(notif) {
                if (notif.read_at) return;
                try {
                    await fetch(`/api/notifications/${notif.id}/read`, {
                        method: 'POST',
                        headers: { 
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
                        }
                    });
                    notif.read_at = new Date();
                } catch(e) { console.error(e); }
            },

            async markAllAsRead() {
                this.notifications.filter(n => !n.read_at).forEach(n => this.markAsRead(n));
            },

            async performGlobalSearch() {
                if (this.globalSearchQuery.length < 2) {
                    this.searchResults = null;
                    return;
                }
                try {
                    const res = await fetch(`/api/search?q=${encodeURIComponent(this.globalSearchQuery)}`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    if (res.ok) {
                        this.searchResults = await res.json();
                    }
                } catch(e) { console.error(e); }
            }
        }
    }
    </script>
</body>
</html>
