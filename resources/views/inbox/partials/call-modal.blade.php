{{-- Modal de Llamada --}}
<div
    x-show="showCallModal"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center modal-backdrop"
    @click.self="endCall()"
>
    <div
        x-show="showCallModal"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="bg-[#1E293B] rounded-2xl shadow-2xl w-[340px] overflow-hidden animate-scale-in"
    >
        {{-- Header con REC --}}
        <div class="flex items-center justify-between px-5 pt-4 pb-2">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 bg-red-500 rounded-full animate-recording"></span>
                <span class="text-red-400 text-sm font-bold">REC</span>
                <span class="text-white text-sm font-mono" x-text="callTimer"></span>
            </div>
            <button @click="endCall()" class="text-[#94A3B8] hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
                </svg>
            </button>
        </div>

        {{-- Info del contacto --}}
        <div class="px-5 py-4 text-center">
            <div
                class="w-14 h-14 rounded-full flex items-center justify-center text-white text-lg font-bold mx-auto mb-3"
                :style="'background-color:' + (selectedConversation ? selectedConversation.avatarColor : '#0056D2')"
                x-text="selectedConversation ? selectedConversation.initials : ''"
            ></div>
            <h3 class="text-white text-base font-bold" x-text="selectedConversation ? selectedConversation.contactName : ''"></h3>
            <p class="text-[#94A3B8] text-sm mt-0.5" x-text="selectedConversation ? selectedConversation.phone : ''"></p>
        </div>

        {{-- País y número --}}
        <div class="mx-5 mb-4 bg-[#334155] rounded-xl px-4 py-2.5 flex items-center gap-3">
            <span class="text-sm text-[#94A3B8]">Desde</span>
            <span class="bg-[#475569] text-white text-xs font-bold px-2 py-0.5 rounded">MX</span>
            <span class="text-white text-sm font-mono flex-1" x-text="selectedConversation ? selectedConversation.phone : ''"></span>
        </div>

        {{-- Controles de llamada --}}
        <div class="flex items-center justify-center gap-6 px-5 pb-5">
            {{-- Silenciar --}}
            <button class="flex flex-col items-center gap-1.5 group" @click="isMuted = !isMuted">
                <div :class="isMuted ? 'bg-[#475569]' : 'bg-[#334155] hover:bg-[#475569]'" class="w-12 h-12 rounded-full flex items-center justify-center transition-colors">
                    <svg x-show="!isMuted" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                    </svg>
                    <svg x-show="isMuted" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
                    </svg>
                </div>
                <span class="text-[11px] text-[#94A3B8]">Silenciar</span>
            </button>

            {{-- Teclado --}}
            <button class="flex flex-col items-center gap-1.5 group">
                <div class="w-12 h-12 rounded-full bg-[#334155] hover:bg-[#475569] flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM10 5a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1V5zM16 5a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1V5zM4 11a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1v-2zM10 11a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2zM16 11a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2zM10 17a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2z" />
                    </svg>
                </div>
                <span class="text-[11px] text-[#94A3B8]">Teclado</span>
            </button>

            {{-- Colgar --}}
            <button class="flex flex-col items-center gap-1.5" @click="endCall()">
                <div class="w-14 h-14 rounded-full bg-red-500 hover:bg-red-600 flex items-center justify-center transition-colors shadow-lg shadow-red-500/30 active:scale-95">
                    <svg class="w-6 h-6 text-white rotate-[135deg]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </div>
                <span class="text-[11px] text-red-400 font-medium">Colgar</span>
            </button>
        </div>
    </div>
</div>
