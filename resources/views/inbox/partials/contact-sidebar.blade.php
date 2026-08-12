{{-- Panel Derecho: Contacto / CRM --}}
<aside
    class="w-[300px] bg-[#011B3D]/80 backdrop-blur-xl border border-white/20 rounded-[32px] flex flex-col shrink-0 overflow-y-auto custom-scrollbar z-10 shadow-[0_15px_40px_-10px_rgba(0,0,0,0.8)] h-full overflow-hidden"
    x-show="selectedConversation"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 translate-x-4"
    x-transition:enter-end="opacity-100 translate-x-0"
>
    {{-- Cabecera del contacto --}}
    <div class="p-5 text-center border-b border-white/10 bg-[#011B3D]/30">
        {{-- Avatar grande --}}
        <div class="relative inline-block mb-4">
            <div
                class="w-20 h-20 rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto shadow-[0_0_15px_rgba(0,0,0,0.5)] ring-4 ring-[#011B3D]"
                :style="'background-color:' + (selectedConversation ? selectedConversation.avatarColor : '#0056D2')"
                x-text="selectedConversation ? selectedConversation.initials : ''"
            ></div>
            {{-- Indicador online --}}
            <div class="absolute bottom-0.5 right-0.5 w-4 h-4 bg-[#10B981] rounded-full border-2 border-[#00122A] shadow-[0_0_8px_#10B981]"></div>
        </div>

        {{-- Nombre y teléfono --}}
        <div class="flex items-center justify-center gap-2">
            <h2 class="text-base font-bold text-white" x-text="selectedConversation ? selectedConversation.contactName : ''"></h2>
            <button class="text-white/40 hover:text-[#00CEFF] transition-colors" title="Editar contacto">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
            </button>
        </div>
        <p class="text-sm text-white/60 mt-0.5 font-medium" x-text="selectedConversation ? selectedConversation.phone : ''"></p>

        {{-- Origen del contacto --}}
        <div class="mt-2 flex items-center justify-center gap-1" x-show="selectedConversation && selectedConversation.contactSource">
            <span class="text-xs text-white/50 font-medium">
                <template x-if="selectedConversation && selectedConversation.channel === 'instagram'">
                    <span>@<span x-text="selectedConversation.socialHandle"></span> · Instagram</span>
                </template>
                <template x-if="selectedConversation && selectedConversation.channel !== 'instagram'">
                    <span x-text="selectedConversation ? selectedConversation.contactSource : ''"></span>
                </template>
            </span>
        </div>
    </div>

    {{-- Info de contacto --}}
    <div class="px-5 py-3 border-b border-white/10 space-y-2.5 bg-[#00122A]/50">
        {{-- Email --}}
        <div class="flex items-center gap-3" x-show="selectedConversation && selectedConversation.email">
            <svg class="w-4 h-4 text-white/40 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <span class="text-sm text-white/90 truncate font-medium" x-text="selectedConversation ? selectedConversation.email : ''"></span>
            <button class="ml-auto text-white/30 hover:text-[#00CEFF] shrink-0 transition-colors" title="Copiar">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
            </button>
        </div>

        {{-- Empresa --}}
        <div class="flex items-center gap-3" x-show="selectedConversation && selectedConversation.company">
            <svg class="w-4 h-4 text-white/40 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <span class="text-sm text-white/90 font-medium" x-text="selectedConversation ? selectedConversation.company : ''"></span>
        </div>

        {{-- Cita programada --}}
        <div class="flex items-center gap-3" x-show="selectedConversation && selectedConversation.appointmentInfo">
            <svg class="w-4 h-4 text-white/40 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span class="text-sm text-white/90 font-medium" x-text="selectedConversation ? selectedConversation.appointmentInfo : ''"></span>
        </div>

        {{-- Tags del contacto --}}
        <div class="flex flex-wrap gap-1.5 mt-2" x-show="selectedConversation && selectedConversation.tags && selectedConversation.tags.length > 0">
            <template x-for="tag in (selectedConversation ? selectedConversation.tags : [])" :key="tag.name">
                <span class="tag text-[9px] uppercase font-bold tracking-wide border" :style="'background-color:' + tag.color + '20; color:' + tag.color + '; border-color:' + tag.color + '40'" x-text="tag.name"></span>
            </template>
            <button class="tag bg-white/5 border border-white/10 text-white/50 hover:text-white hover:border-white/30 transition-colors uppercase font-bold text-[9px]">
                + Tag
            </button>
        </div>
    </div>

    {{-- CRM: Ventas, Oportunidades, Agenda --}}
    <div class="px-5 py-4 border-b border-white/10 space-y-3 bg-[#011B3D]/30">
        {{-- 01 VENTAS --}}
        <div class="bg-[#002B6A]/40 border border-white/10 shadow-sm rounded-xl p-3 flex items-center gap-3 hover:shadow-[0_0_15px_rgba(0,206,255,0.15)] hover:border-[#00CEFF]/30 cursor-pointer transition-all group backdrop-blur-sm">
            <div class="w-8 h-8 rounded-lg bg-black/20 flex items-center justify-center group-hover:bg-[#00CEFF]/10 transition-colors">
                <svg class="w-4 h-4 text-white/50 group-hover:text-[#00CEFF]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] text-white/40 font-bold mb-0.5 tracking-wider">01</p>
                <span class="text-xs font-bold text-white tracking-wide">VENTAS</span>
            </div>
            <span class="badge bg-[#00CEFF] text-[#011B3D] font-bold ml-auto shadow-[0_0_8px_rgba(0,206,255,0.6)]" x-text="selectedConversation ? selectedConversation.salesCount : 0"></span>
        </div>

        {{-- 02 OPORTUNIDADES --}}
        <div class="bg-[#002B6A]/40 border border-white/10 shadow-sm rounded-xl p-3 flex items-center gap-3 hover:shadow-[0_0_15px_rgba(16,185,129,0.15)] hover:border-[#10B981]/30 cursor-pointer transition-all group backdrop-blur-sm">
            <div class="w-8 h-8 rounded-lg bg-black/20 flex items-center justify-center group-hover:bg-[#10B981]/10 transition-colors">
                <svg class="w-4 h-4 text-white/50 group-hover:text-[#10B981]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] text-white/40 font-bold mb-0.5 tracking-wider">02</p>
                <span class="text-xs font-bold text-white tracking-wide">OPORTUNIDADES</span>
            </div>
            <span class="badge bg-[#10B981] text-white font-bold ml-auto shadow-[0_0_8px_rgba(16,185,129,0.5)]" x-text="selectedConversation ? selectedConversation.opportunitiesCount : 0"></span>
        </div>

        {{-- 03 AGENDA --}}
        <div class="bg-[#002B6A]/40 border border-white/10 shadow-sm rounded-xl p-3 flex items-center gap-3 hover:shadow-[0_0_15px_rgba(245,158,11,0.15)] hover:border-[#F59E0B]/30 cursor-pointer transition-all group backdrop-blur-sm">
            <div class="w-8 h-8 rounded-lg bg-black/20 flex items-center justify-center group-hover:bg-[#F59E0B]/10 transition-colors">
                <svg class="w-4 h-4 text-white/50 group-hover:text-[#F59E0B]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] text-white/40 font-bold mb-0.5 tracking-wider">03</p>
                <span class="text-xs font-bold text-white tracking-wide">AGENDA</span>
            </div>
            <svg class="w-4 h-4 text-white/30 opacity-0 group-hover:opacity-100 group-hover:text-[#F59E0B] transition-all ml-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        </div>
    </div>

    {{-- Botones de Acción --}}
    <div class="px-5 py-5 space-y-3">
        {{-- COTIZAR --}}
        <button class="w-full flex items-center justify-center gap-2 bg-[#00CEFF]/10 border border-[#00CEFF]/30 hover:bg-[#00CEFF]/20 hover:shadow-[0_0_15px_rgba(0,206,255,0.2)] text-[#00CEFF] py-3 rounded-xl text-sm font-bold transition-all active:scale-[0.98]">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            COTIZAR
        </button>

        {{-- LLAMAR --}}
        <button
            @click="startCall()"
            class="w-full flex items-center justify-center gap-2 bg-[#00CEFF] hover:bg-[#00E5FF] hover:shadow-[0_0_20px_rgba(0,206,255,0.5)] text-[#011B3D] py-3 rounded-xl text-sm font-bold transition-all active:scale-[0.98]"
        >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
            LLAMAR
        </button>

        {{-- SECUENCIAS --}}
        <button class="w-full flex items-center justify-center gap-2 bg-transparent border border-white/20 hover:border-[#00CEFF] hover:text-[#00CEFF] hover:shadow-[0_0_10px_rgba(0,206,255,0.2)] text-white py-3 rounded-xl text-sm font-bold transition-all active:scale-[0.98]">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            SECUENCIAS
        </button>
    </div>
</aside>
