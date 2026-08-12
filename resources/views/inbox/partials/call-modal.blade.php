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
        class="bg-[#001B3D]/95 backdrop-blur-xl border border-[#00CEFF]/30 rounded-2xl shadow-[0_15px_40px_-10px_rgba(0,0,0,0.7)] w-[340px] overflow-hidden animate-scale-in"
    >
        {{-- Header con REC --}}
        <div class="flex items-center justify-between px-5 pt-4 pb-2">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 bg-red-500 rounded-full animate-recording shadow-[0_0_8px_rgba(239,68,68,0.8)]"></span>
                <span class="text-red-400 text-sm font-bold tracking-widest">REC</span>
                <span class="text-white text-sm font-mono tracking-widest bg-black/30 px-2 py-0.5 rounded border border-white/10" x-text="callTimer"></span>
            </div>
            <button @click="endCall()" class="text-white/40 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
                </svg>
            </button>
        </div>

        {{-- Info del contacto --}}
        <div class="px-5 py-4 text-center">
            <div
                class="w-16 h-16 rounded-full flex items-center justify-center text-white text-xl font-bold mx-auto mb-3 shadow-[0_0_15px_rgba(0,0,0,0.5)] ring-4 ring-[#00122A]"
                :style="'background-color:' + (selectedConversation ? selectedConversation.avatarColor : '#0056D2')"
                x-text="selectedConversation ? selectedConversation.initials : ''"
            ></div>
            <h3 class="text-white text-lg font-bold" x-text="selectedConversation ? selectedConversation.contactName : ''"></h3>
            <p class="text-[#00CEFF] text-sm mt-0.5 font-mono tracking-wide" x-text="selectedConversation ? selectedConversation.phone : ''"></p>
        </div>

        {{-- País y número --}}
        <div class="mx-5 mb-5 bg-[#002B6A]/50 border border-white/10 rounded-xl px-4 py-2.5 flex items-center gap-3 backdrop-blur-sm">
            <span class="text-xs text-white/50 uppercase tracking-widest font-bold">Desde</span>
            <span class="bg-[#0665E0] border border-[#00CEFF]/30 text-white text-xs font-bold px-2 py-0.5 rounded shadow-[0_0_8px_rgba(6,101,224,0.4)]">MX</span>
            <span class="text-white text-sm font-mono flex-1 text-right" x-text="selectedConversation ? selectedConversation.phone : ''"></span>
        </div>

        {{-- Controles de llamada --}}
        <div class="flex items-center justify-center gap-6 px-5 pb-6">
            {{-- Silenciar --}}
            <button class="flex flex-col items-center gap-1.5 group" @click="isMuted = !isMuted">
                <div :class="isMuted ? 'bg-[#002B6A] border-[#00CEFF]/50 shadow-[0_0_10px_rgba(0,206,255,0.2)]' : 'bg-[#002B6A]/50 border-white/10 group-hover:bg-[#0665E0]/50 group-hover:border-[#00CEFF]/50 group-hover:shadow-[0_0_15px_rgba(0,206,255,0.3)]'" class="w-12 h-12 rounded-full border flex items-center justify-center transition-all backdrop-blur-sm">
                    <svg x-show="!isMuted" class="w-5 h-5 text-white/80 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                    </svg>
                    <svg x-show="isMuted" class="w-5 h-5 text-[#00CEFF]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
                    </svg>
                </div>
                <span class="text-[10px] uppercase tracking-wider font-bold text-white/50 group-hover:text-white/80 transition-colors">Silenciar</span>
            </button>

            {{-- Teclado --}}
            <button class="flex flex-col items-center gap-1.5 group">
                <div class="w-12 h-12 rounded-full border bg-[#002B6A]/50 border-white/10 group-hover:bg-[#0665E0]/50 group-hover:border-[#00CEFF]/50 group-hover:shadow-[0_0_15px_rgba(0,206,255,0.3)] flex items-center justify-center transition-all backdrop-blur-sm">
                    <svg class="w-5 h-5 text-white/80 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM10 5a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1V5zM16 5a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1V5zM4 11a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1v-2zM10 11a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2zM16 11a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2zM10 17a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2z" />
                    </svg>
                </div>
                <span class="text-[10px] uppercase tracking-wider font-bold text-white/50 group-hover:text-white/80 transition-colors">Teclado</span>
            </button>

            {{-- Colgar --}}
            <button class="flex flex-col items-center gap-1.5 group" @click="endCall()">
                <div class="w-16 h-16 rounded-full bg-rose-500 hover:bg-rose-600 flex items-center justify-center transition-all shadow-[0_0_20px_rgba(244,63,94,0.5)] active:scale-95 group-hover:shadow-[0_0_30px_rgba(244,63,94,0.8)] border-2 border-rose-400">
                    <svg class="w-7 h-7 text-white rotate-[135deg]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </div>
                <span class="text-[10px] uppercase tracking-wider font-bold text-rose-400 group-hover:text-rose-300 transition-colors">Colgar</span>
            </button>
        </div>
    </div>
</div>
