{{-- Panel Derecho: Contacto / CRM --}}
<aside
    class="w-[280px] flex flex-col shrink-0 overflow-y-auto custom-scrollbar z-10 h-full overflow-hidden border-l border-[#E2E8F0] bg-white"
    x-show="selectedConversation"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-x-4"
    x-transition:enter-end="opacity-100 translate-x-0"
    style="display: none;"
>
    <template x-if="selectedConversation">
        <div class="flex flex-col h-full w-full">
            {{-- Header del panel --}}
            <div class="px-5 py-4 border-b border-[#F1F5F9] sticky top-0 bg-white z-10 flex items-center justify-between">
                <h3 class="font-bold text-main text-[14px]">Info. del Contacto</h3>
                <button class="text-text-muted hover:text-main transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                    </svg>
                </button>
            </div>

            {{-- Cabecera del contacto --}}
            <div class="p-6 text-center border-b border-[#F1F5F9] bg-[#FAFCFE]">
                <div class="relative inline-block mb-4">
                    <div class="w-20 h-20 rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto shadow-[0_4px_12px_rgba(0,0,0,0.05)] ring-4 ring-white" :style="'background-color:' + (selectedConversation ? selectedConversation.avatarColor : '#0056D2')" x-text="selectedConversation ? selectedConversation.initials : ''"></div>
                    <div class="absolute bottom-0 right-1 w-4 h-4 bg-[#10B981] rounded-full border-2 border-white shadow-sm"></div>
                </div>
                <div class="flex items-center justify-center gap-1.5">
                    <h2 class="text-[16px] font-bold text-[#1E293B]" x-text="selectedConversation ? selectedConversation.contactName : ''"></h2>
                    <button class="text-[#94A3B8] hover:text-[#0665E0] transition-colors"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg></button>
                </div>
                <p class="text-[13px] text-[#64748B] mt-1 font-medium" x-text="selectedConversation ? selectedConversation.phone : ''"></p>
                <div class="mt-2 flex items-center justify-center gap-1" x-show="selectedConversation && selectedConversation.contactSource">
                    <span class="text-[11px] text-[#94A3B8] font-medium bg-[#F1F5F9] px-2 py-0.5 rounded-full">
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
            <div class="px-6 py-4 border-b border-[#F1F5F9] space-y-3 bg-white">
                <div class="flex items-center gap-3" x-show="selectedConversation && selectedConversation.email">
                    <div class="w-8 h-8 rounded-[10px] bg-[#F8FAFC] flex items-center justify-center shrink-0 text-[#64748B]"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg></div>
                    <span class="text-[13px] text-[#1E293B] font-medium truncate" x-text="selectedConversation ? selectedConversation.email : ''"></span>
                    <button class="ml-auto text-[#CBD5E1] hover:text-[#0665E0] shrink-0 transition-colors"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg></button>
                </div>
                <div class="flex items-center gap-3" x-show="selectedConversation && selectedConversation.company">
                    <div class="w-8 h-8 rounded-[10px] bg-[#F8FAFC] flex items-center justify-center shrink-0 text-[#64748B]"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg></div>
                    <span class="text-[13px] text-[#1E293B] font-medium" x-text="selectedConversation ? selectedConversation.company : ''"></span>
                </div>
                <div class="flex flex-wrap gap-2 mt-3" x-show="selectedConversation && selectedConversation.tags && selectedConversation.tags.length > 0">
                    <template x-for="tag in (selectedConversation ? selectedConversation.tags : [])" :key="tag.name">
                        <span class="tag text-[10px] uppercase font-bold tracking-wider border rounded-full px-2.5 py-1" :style="'background-color:' + tag.color + '12; color:' + tag.color + '; border-color:' + tag.color + '25'" x-text="tag.name"></span>
                    </template>
                    <button class="tag bg-[#F8FAFC] border border-[#E2E8F0] text-[#64748B] hover:text-[#0665E0] hover:border-[#BFDBFE] hover:bg-[#EFF6FF] transition-colors uppercase font-bold tracking-wider text-[10px] rounded-full px-2.5 py-1">+ Tag</button>
                </div>
            </div>

            {{-- CRM --}}
            <div class="px-5 py-5 space-y-3 bg-[#FAFCFE] flex-1">
                <h3 class="text-[12px] font-bold text-[#64748B] uppercase tracking-widest mb-2 pl-1">Actividad en CRM</h3>
                
                <div class="crm-card group !py-3 !px-4">
                    <div class="w-8 h-8 rounded-[10px] bg-[#EFF6FF] flex items-center justify-center group-hover:bg-[#DBEAFE] transition-colors shrink-0">
                        <svg class="w-4 h-4 text-[#0665E0]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" /></svg>
                    </div>
                    <div class="min-w-0">
                        <span class="text-[13px] font-bold text-[#1E293B] block truncate">VENTAS</span>
                    </div>
                    <span class="badge bg-[#F1F5F9] text-[#64748B] px-2.5 py-1 rounded-full text-[11px] font-bold group-hover:bg-[#0665E0] group-hover:text-white ml-auto transition-colors" x-text="selectedConversation ? selectedConversation.salesCount : 0"></span>
                </div>

                <div class="crm-card group !py-3 !px-4">
                    <div class="w-8 h-8 rounded-[10px] bg-[#ECFDF5] flex items-center justify-center group-hover:bg-[#D1FAE5] transition-colors shrink-0">
                        <svg class="w-4 h-4 text-[#10B981]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.11 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </div>
                    <div class="min-w-0">
                        <span class="text-[13px] font-bold text-[#1E293B] block truncate">OPORTUNIDADES</span>
                    </div>
                    <span class="badge bg-[#F1F5F9] text-[#64748B] px-2.5 py-1 rounded-full text-[11px] font-bold group-hover:bg-[#10B981] group-hover:text-white ml-auto transition-colors" x-text="selectedConversation ? selectedConversation.opportunitiesCount : 0"></span>
                </div>
            </div>

            {{-- Botones de Acción --}}
            <div class="p-5 border-t border-[#F1F5F9] space-y-3 bg-white shrink-0">
                <button class="w-full flex items-center justify-center gap-2 bg-[#1E293B] hover:bg-[#0F172A] text-white py-3.5 rounded-2xl text-[13px] font-bold tracking-wide transition-all active:scale-[0.98] shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    CREAR COTIZACIÓN
                </button>
                <button @click="startCall()" class="w-full flex items-center justify-center gap-2 bg-[#0D9488] hover:bg-[#0F766E] text-white py-3.5 rounded-2xl text-[13px] font-bold tracking-wide transition-all active:scale-[0.98] shadow-[0_4px_12px_rgba(13,148,136,0.25)] hover:shadow-[0_6px_16px_rgba(13,148,136,0.35)]">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    LLAMAR AHORA
                </button>
            </div>
        </div>
    </template>
</aside>
