{{-- Sidebar de Navegación (D-Shape) --}}
<aside :class="sidebarOpen ? 'w-[220px]' : 'w-0 overflow-hidden opacity-0'" class="sidebar-navy flex flex-col py-10 shrink-0 transition-all duration-300 relative z-20 h-full rounded-l-[20px] rounded-r-[100px]">
    
    {{-- Navegación Principal --}}
    <nav class="flex flex-col gap-6 flex-1 w-full mt-6 px-6">
        {{-- Home (Bandeja) --}}
        <a href="{{ route('inbox.index') }}" class="w-full flex items-center gap-4 relative group transition-all pl-2">
            <div class="w-6 flex justify-center">
                <svg class="w-[20px] h-[20px] text-[#FFFFFF] {{ request()->routeIs('inbox.index') ? '' : 'opacity-80 group-hover:opacity-100' }}" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3l9 8h-3v8h-4v-6H10v6H6v-8H3l9-8z" />
                </svg>
            </div>
            <span class="text-[14px] font-medium text-[#FFFFFF] {{ request()->routeIs('inbox.index') ? '' : 'opacity-80 group-hover:opacity-100' }} transition-opacity">Bandeja</span>
        </a>

        {{-- Result (CRM) --}}
        <a href="{{ route('crm.index') }}" class="w-full flex items-center gap-4 relative group transition-all pl-2">
            <div class="w-6 flex justify-center">
                <svg class="w-[20px] h-[20px] text-[#FFFFFF] {{ request()->routeIs('crm.index') ? '' : 'opacity-80 group-hover:opacity-100' }}" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M4 19h16v2H4v-2zm12-8h4v6h-4v-6zm-6 3h4v3h-4v-3zM4 6h4v11H4V6z" />
                </svg>
            </div>
            <span class="text-[14px] font-medium text-[#FFFFFF] {{ request()->routeIs('crm.index') ? '' : 'opacity-80 group-hover:opacity-100' }} transition-opacity">CRM</span>
        </a>

        {{-- Device (Chatbot) --}}
        <a href="{{ route('chatbot.index') }}" class="w-full flex items-center gap-4 relative group transition-all pl-2">
            <div class="w-6 flex justify-center">
                <svg class="w-[20px] h-[20px] text-[#FFFFFF] {{ request()->routeIs('chatbot.index') ? '' : 'opacity-80 group-hover:opacity-100' }}" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M21 16V6c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h4v2H7v2h10v-2h-2v-2h4c1.1 0 2-.9 2-2zm-2 0H5V6h14v10z" />
                </svg>
            </div>
            <span class="text-[14px] font-medium text-[#FFFFFF] {{ request()->routeIs('chatbot.index') ? '' : 'opacity-80 group-hover:opacity-100' }} transition-opacity">Bot</span>
        </a>

        {{-- Member (Contactos) --}}
        <a href="{{ route('contactos.index') }}" class="w-full flex items-center gap-4 relative group transition-all pl-2">
            <div class="w-6 flex justify-center">
                <svg class="w-[20px] h-[20px] text-[#FFFFFF] {{ request()->routeIs('contactos.index') ? '' : 'opacity-80 group-hover:opacity-100' }}" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                </svg>
            </div>
            <span class="text-[14px] font-medium text-[#FFFFFF] {{ request()->routeIs('contactos.index') ? '' : 'opacity-80 group-hover:opacity-100' }} transition-opacity">Contactos</span>
        </a>
    </nav>

    {{-- Settings (al fondo) --}}
    <div class="mt-auto flex flex-col w-full px-6 mb-8">
        <a href="{{ route('settings.index') }}" class="w-full flex items-center gap-4 relative group transition-all pl-2">
            <div class="w-6 flex justify-center">
                <svg class="w-[20px] h-[20px] text-[#FFFFFF] group-hover:rotate-90 transition-transform duration-500 {{ request()->routeIs('settings.index') ? '' : 'opacity-80 group-hover:opacity-100' }}" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.06-.94l2.03-1.58a.49.49 0 00.12-.61l-1.92-3.32a.488.488 0 00-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54a.484.484 0 00-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.56-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.06.94l-2.03 1.58a.49.49 0 00-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .43-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.49-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z" />
                </svg>
            </div>
            <span class="text-[14px] font-medium text-[#FFFFFF] {{ request()->routeIs('settings.index') ? '' : 'opacity-80 group-hover:opacity-100' }} transition-opacity">Ajustes</span>
        </a>
    </div>
</aside>
