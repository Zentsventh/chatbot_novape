{{-- Sidebar de Navegación Principal --}}
<aside :class="sidebarOpen ? 'w-[72px]' : 'w-0 overflow-hidden opacity-0'" class="bg-[#011B3D]/80 backdrop-blur-xl border border-white/20 rounded-[32px] flex flex-col items-center py-6 shrink-0 transition-all duration-300 relative z-20 shadow-[0_15px_40px_-10px_rgba(0,0,0,0.8)] h-full">
    
    {{-- Navegación Principal --}}
    <nav class="flex flex-col items-center gap-3 flex-1 w-full mt-2">
        {{-- Bandeja de Entrada --}}
        <a href="{{ route('inbox.index') }}" class="w-12 h-12 rounded-xl flex items-center justify-center {{ request()->routeIs('inbox.index') ? 'bg-[#00CEFF]/10 text-[#00CEFF] shadow-[inset_0_0_10px_rgba(0,206,255,0.2)]' : 'text-white/60 hover:bg-white/10 hover:text-white' }} relative group transition-all" data-tooltip="Bandeja">
            @if(request()->routeIs('inbox.index'))
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-[#00CEFF] rounded-r-md shadow-[0_0_8px_rgba(0,206,255,0.8)]"></div>
            @endif
            <svg class="w-5 h-5 {{ request()->routeIs('inbox.index') ? '' : 'group-hover:scale-110 transition-transform' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
        </a>

        {{-- Contactos --}}
        <a href="{{ route('contactos.index') }}" class="w-11 h-11 rounded-xl flex items-center justify-center {{ request()->routeIs('contactos.index') ? 'bg-[#00CEFF]/10 text-[#00CEFF] shadow-[inset_0_0_10px_rgba(0,206,255,0.2)]' : 'text-white/60 hover:bg-white/10 hover:text-white' }} relative group transition-all" data-tooltip="Contactos">
            @if(request()->routeIs('contactos.index'))
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-[#00CEFF] rounded-r-md shadow-[0_0_8px_rgba(0,206,255,0.8)]"></div>
            @endif
            <svg class="w-5 h-5 {{ request()->routeIs('contactos.index') ? '' : 'group-hover:scale-110 transition-transform' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </a>

        {{-- CRM / Ventas --}}
        <a href="{{ route('crm.index') }}" class="w-11 h-11 rounded-xl flex items-center justify-center {{ request()->routeIs('crm.index') ? 'bg-[#00CEFF]/10 text-[#00CEFF] shadow-[inset_0_0_10px_rgba(0,206,255,0.2)]' : 'text-white/60 hover:bg-white/10 hover:text-white' }} relative group transition-all" data-tooltip="CRM">
            @if(request()->routeIs('crm.index'))
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-[#00CEFF] rounded-r-md shadow-[0_0_8px_rgba(0,206,255,0.8)]"></div>
            @endif
            <svg class="w-5 h-5 {{ request()->routeIs('crm.index') ? '' : 'group-hover:scale-110 transition-transform' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
        </a>

        {{-- Chatbot Config --}}
        <a href="{{ route('chatbot.index') }}" class="w-11 h-11 rounded-xl flex items-center justify-center {{ request()->routeIs('chatbot.index') ? 'bg-[#00CEFF]/10 text-[#00CEFF] shadow-[inset_0_0_10px_rgba(0,206,255,0.2)]' : 'text-white/60 hover:bg-white/10 hover:text-white' }} relative group transition-all" data-tooltip="Chatbot">
            @if(request()->routeIs('chatbot.index'))
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-[#00CEFF] rounded-r-md shadow-[0_0_8px_rgba(0,206,255,0.8)]"></div>
            @endif
            <svg class="w-5 h-5 {{ request()->routeIs('chatbot.index') ? '' : 'group-hover:scale-110 transition-transform' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </a>

        {{-- Calendario / Agenda --}}
        <a href="{{ route('calendar.index') }}" class="w-11 h-11 rounded-xl flex items-center justify-center {{ request()->routeIs('calendar.index') ? 'bg-[#00CEFF]/10 text-[#00CEFF] shadow-[inset_0_0_10px_rgba(0,206,255,0.2)]' : 'text-white/60 hover:bg-white/10 hover:text-white' }} relative group transition-all" data-tooltip="Agenda">
            @if(request()->routeIs('calendar.index'))
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-[#00CEFF] rounded-r-md shadow-[0_0_8px_rgba(0,206,255,0.8)]"></div>
            @endif
            <svg class="w-5 h-5 {{ request()->routeIs('calendar.index') ? '' : 'group-hover:scale-110 transition-transform' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </a>

        {{-- Chat Interno --}}
        <a href="{{ route('internal-chat.index') }}" class="w-11 h-11 rounded-xl flex items-center justify-center {{ request()->routeIs('internal-chat.index') ? 'bg-[#00CEFF]/10 text-[#00CEFF] shadow-[inset_0_0_10px_rgba(0,206,255,0.2)]' : 'text-white/60 hover:bg-white/10 hover:text-white' }} relative group transition-all" data-tooltip="Chat Interno">
            @if(request()->routeIs('internal-chat.index'))
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-[#00CEFF] rounded-r-md shadow-[0_0_8px_rgba(0,206,255,0.8)]"></div>
            @endif
            <svg class="w-5 h-5 {{ request()->routeIs('internal-chat.index') ? '' : 'group-hover:scale-110 transition-transform' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
            </svg>
        </a>

        {{-- Equipo --}}
        <a href="{{ route('settings.team.index') }}" class="w-11 h-11 rounded-xl flex items-center justify-center {{ request()->routeIs('settings.team.index') ? 'bg-[#00CEFF]/10 text-[#00CEFF] shadow-[inset_0_0_10px_rgba(0,206,255,0.2)]' : 'text-white/60 hover:bg-white/10 hover:text-white' }} relative group transition-all" data-tooltip="Equipo">
            @if(request()->routeIs('settings.team.index'))
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-[#00CEFF] rounded-r-md shadow-[0_0_8px_rgba(0,206,255,0.8)]"></div>
            @endif
            <svg class="w-5 h-5 {{ request()->routeIs('settings.team.index') ? '' : 'group-hover:scale-110 transition-transform' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </a>
    </nav>

    {{-- Configuración (al fondo) --}}
    <div class="mt-auto flex flex-col items-center gap-2 w-full">
        <a href="{{ route('settings.index') }}" class="w-11 h-11 rounded-xl flex items-center justify-center {{ request()->routeIs('settings.index') ? 'bg-[#00CEFF]/10 text-[#00CEFF] shadow-[inset_0_0_10px_rgba(0,206,255,0.2)]' : 'text-white/60 hover:bg-white/10 hover:text-white' }} relative group transition-all" data-tooltip="Configuración">
            @if(request()->routeIs('settings.index'))
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-[#00CEFF] rounded-r-md shadow-[0_0_8px_rgba(0,206,255,0.8)]"></div>
            @endif
            <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </a>
    </div>
</aside>
