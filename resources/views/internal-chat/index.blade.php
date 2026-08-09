@extends('layouts.app')

@section('title', 'Chat Interno — Smart AI Hosting Solutions')

@section('content')
<div class="flex-1 flex bg-white w-full h-full overflow-hidden" x-data="internalChatApp()">
    
    {{-- Sidebar Chat Interno (Lista de canales/usuarios) --}}
    <div class="w-80 bg-[#F8FAFC] border-r border-[#E2E8F0] shrink-0 flex flex-col">
        <div class="p-4 border-b border-[#E2E8F0] shrink-0">
            <h2 class="text-xl font-bold text-[#1E293B]">Chat del Equipo</h2>
            <div class="mt-4 relative">
                <input type="text" placeholder="Buscar equipo o mensajes..." class="w-full pl-9 pr-4 py-2 text-sm bg-white border border-[#E2E8F0] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0056D2]/20 focus:border-[#0056D2] transition-all">
                <svg class="w-4 h-4 text-[#94A3B8] absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-3 space-y-1">
            {{-- Canal General --}}
            <div class="mb-4">
                <h3 class="text-xs font-bold text-[#64748B] uppercase tracking-wider mb-2 px-2">Canales</h3>
                <button class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg bg-[#0056D2]/10 text-[#0056D2] font-medium transition-colors text-left">
                    <span class="text-lg font-bold opacity-60">#</span>
                    General
                </button>
                <button class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#475569] hover:bg-[#E2E8F0]/50 transition-colors text-left">
                    <span class="text-lg font-bold opacity-40">#</span>
                    Soporte Técnico
                </button>
            </div>

            {{-- Mensajes Directos --}}
            <div>
                <h3 class="text-xs font-bold text-[#64748B] uppercase tracking-wider mb-2 px-2">Mensajes Directos</h3>
                
                <template x-for="user in team" :key="user.id">
                    <button class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#475569] hover:bg-[#E2E8F0]/50 transition-colors text-left relative group">
                        <div class="relative">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold" :class="user.color">
                                <span x-text="user.initials"></span>
                            </div>
                            <span class="absolute bottom-0 right-0 w-2.5 h-2.5 border-2 border-[#F8FAFC] rounded-full" :class="user.online ? 'bg-[#10B981]' : 'bg-[#94A3B8]'"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-[#1E293B] truncate" x-text="user.name"></p>
                            <p class="text-xs text-[#64748B] truncate" x-text="user.role"></p>
                        </div>
                    </button>
                </template>
            </div>
        </div>
    </div>

    {{-- Área de Chat Principal --}}
    <div class="flex-1 flex flex-col bg-white">
        {{-- Header Chat --}}
        <div class="h-16 px-6 border-b border-[#E2E8F0] flex items-center justify-between shrink-0 bg-white/80 backdrop-blur-sm z-10 shadow-sm">
            <div class="flex items-center gap-3">
                <span class="text-2xl font-bold text-[#94A3B8]">#</span>
                <div>
                    <h2 class="text-lg font-bold text-[#1E293B]">General</h2>
                    <p class="text-xs text-[#64748B]">Comunicación de todo el equipo de Novape.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex -space-x-2 mr-4">
                    <template x-for="(user, idx) in team.slice(0,3)" :key="idx">
                        <div class="w-8 h-8 rounded-full border-2 border-white flex items-center justify-center text-white text-xs font-bold shadow-sm" :class="user.color">
                            <span x-text="user.initials"></span>
                        </div>
                    </template>
                    <div class="w-8 h-8 rounded-full border-2 border-white bg-[#F1F5F9] flex items-center justify-center text-[#64748B] text-xs font-bold shadow-sm">+2</div>
                </div>
                <button class="w-9 h-9 rounded-lg flex items-center justify-center text-[#64748B] hover:bg-[#F1F5F9] hover:text-[#1E293B] transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mensajes --}}
        <div class="flex-1 overflow-y-auto p-6 space-y-6" id="chat-messages">
            <template x-for="msg in messages" :key="msg.id">
                <div class="flex gap-4 group" :class="msg.isMine ? 'flex-row-reverse' : ''">
                    <div class="w-10 h-10 rounded-full shrink-0 flex items-center justify-center text-white text-sm font-bold shadow-sm" :class="msg.userColor">
                        <span x-text="msg.initials"></span>
                    </div>
                    <div class="max-w-[70%]" :class="msg.isMine ? 'text-right' : ''">
                        <div class="flex items-baseline gap-2 mb-1" :class="msg.isMine ? 'justify-end' : ''">
                            <span class="text-sm font-bold text-[#1E293B]" x-text="msg.name"></span>
                            <span class="text-xs text-[#94A3B8]" x-text="msg.time"></span>
                        </div>
                        <div class="px-4 py-2.5 rounded-2xl text-sm" :class="msg.isMine ? 'bg-[#0056D2] text-white rounded-tr-sm shadow-md shadow-[#0056D2]/20' : 'bg-[#F1F5F9] text-[#1E293B] rounded-tl-sm border border-[#E2E8F0]'">
                            <p x-text="msg.text" class="whitespace-pre-wrap"></p>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        {{-- Input Area --}}
        <div class="p-4 bg-white border-t border-[#E2E8F0] shrink-0">
            <div class="bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl flex items-end shadow-sm focus-within:ring-2 focus-within:ring-[#0056D2]/20 focus-within:border-[#0056D2] transition-all p-2 gap-2">
                <button class="p-2 text-[#94A3B8] hover:text-[#0056D2] rounded-lg transition-colors shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                </button>
                <textarea x-model="newMessage" @keydown.enter.prevent="sendMessage" placeholder="Escribe un mensaje al canal #general..." class="flex-1 max-h-32 bg-transparent border-none focus:ring-0 resize-none text-sm py-2 text-[#1E293B]" rows="1"></textarea>
                <button @click="sendMessage" :disabled="!newMessage.trim()" class="p-2 bg-[#0056D2] text-white rounded-lg hover:bg-[#0047B3] transition-colors disabled:opacity-50 disabled:cursor-not-allowed shrink-0 shadow-sm">
                    <svg class="w-5 h-5 translate-x-[1px] translate-y-[1px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </button>
            </div>
            <p class="text-center text-[10px] text-[#94A3B8] mt-2">Presiona Enter para enviar. Shift + Enter para salto de línea.</p>
        </div>
    </div>
</div>

<script>
function internalChatApp() {
    return {
        newMessage: '',
        team: [
            { id: 1, name: 'Valeria Gómez', initials: 'VG', role: 'Ventas', color: 'bg-gradient-to-br from-[#F59E0B] to-[#D97706]', online: true },
            { id: 2, name: 'Carlos Rodríguez', initials: 'CR', role: 'Soporte', color: 'bg-gradient-to-br from-[#10B981] to-[#059669]', online: false },
            { id: 3, name: 'Ana Martínez', initials: 'AM', role: 'Supervisora', color: 'bg-gradient-to-br from-[#EC4899] to-[#BE185D]', online: true },
        ],
        messages: [
            { id: 1, name: 'Valeria Gómez', initials: 'VG', userColor: 'bg-gradient-to-br from-[#F59E0B] to-[#D97706]', text: '¡Hola equipo! Acabo de cerrar el lead de Agencia Creativa.', time: '10:32 AM', isMine: false },
            { id: 2, name: 'Ana Martínez', initials: 'AM', userColor: 'bg-gradient-to-br from-[#EC4899] to-[#BE185D]', text: '¡Excelente noticia Valeria! ¿Ya le enviaste los accesos del servidor?', time: '10:35 AM', isMine: false },
            { id: 3, name: 'Tú', initials: 'EA', userColor: 'bg-gradient-to-br from-[#8B5CF6] to-[#6D28D9]', text: 'Gran trabajo. Procedo a activar el entorno de desarrollo para ellos.', time: '10:40 AM', isMine: true },
        ],
        sendMessage() {
            if (!this.newMessage.trim()) return;
            
            this.messages.push({
                id: Date.now(),
                name: 'Tú',
                initials: 'EA',
                userColor: 'bg-gradient-to-br from-[#8B5CF6] to-[#6D28D9]',
                text: this.newMessage.trim(),
                time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
                isMine: true
            });
            
            this.newMessage = '';
            
            // Auto scroll al fondo
            setTimeout(() => {
                const el = document.getElementById('chat-messages');
                el.scrollTop = el.scrollHeight;
            }, 50);
        }
    }
}
</script>
@endsection
