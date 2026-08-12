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
                    <button @click="startChatWith(user.id)" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#475569] hover:bg-[#E2E8F0]/50 transition-colors text-left relative group">
                        <div class="relative">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold bg-[#64748B]">
                                <span x-text="user.name.substring(0,2).toUpperCase()"></span>
                            </div>
                            <span class="absolute bottom-0 right-0 w-2.5 h-2.5 border-2 border-[#F8FAFC] rounded-full" :class="user.is_online ? 'bg-[#10B981]' : 'bg-[#94A3B8]'"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-[#1E293B] truncate" x-text="user.name"></p>
                            <p class="text-xs text-[#64748B] truncate">Agente</p>
                        </div>
                    </button>
                </template>
            </div>
        </div>
    </div>

    {{-- Área de Chat Principal --}}
    <div class="flex-1 flex flex-col bg-white">
        
        {{-- Estado vacío --}}
        <div class="flex-1 flex items-center justify-center" x-show="!selectedConversation">
            <div class="text-center">
                <div class="w-20 h-20 mx-auto mb-4 bg-[#EFF6FF] rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-[#0056D2]/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-[#1E293B] mb-1">Tus Mensajes</h3>
                <p class="text-sm text-[#94A3B8]">Selecciona un compañero para iniciar una conversación privada.</p>
            </div>
        </div>

        {{-- Header Chat --}}
        <div class="h-16 px-6 border-b border-[#E2E8F0] flex items-center justify-between shrink-0 bg-white/80 backdrop-blur-sm z-10 shadow-sm" x-show="selectedConversation">
            <div class="flex items-center gap-3">
                <span class="text-2xl font-bold text-[#94A3B8]" x-text="selectedConversation.is_group ? '#' : '@'"></span>
                <div>
                    <h2 class="text-lg font-bold text-[#1E293B]" x-text="selectedConversation.name"></h2>
                    <p class="text-xs text-[#64748B]">Chat Interno</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex mr-4">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm bg-[#64748B]">
                        <span x-text="selectedConversation.name.substring(0,2).toUpperCase()"></span>
                    </div>
                </div>
                <button class="w-9 h-9 rounded-lg flex items-center justify-center text-[#64748B] hover:bg-[#F1F5F9] hover:text-[#1E293B] transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mensajes --}}
        <div class="flex-1 overflow-y-auto p-6 space-y-6" id="chat-messages" x-show="selectedConversation">
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
        <div class="p-4 bg-white border-t border-[#E2E8F0] shrink-0" x-show="selectedConversation">
            <div class="bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl flex items-end shadow-sm focus-within:ring-2 focus-within:ring-[#0056D2]/20 focus-within:border-[#0056D2] transition-all p-2 gap-2">
                <button class="p-2 text-[#94A3B8] hover:text-[#0056D2] rounded-lg transition-colors shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                </button>
                <textarea x-model="newMessage" @keydown.enter.prevent="sendMessage" placeholder="Escribe un mensaje..." class="flex-1 max-h-32 bg-transparent border-none focus:ring-0 resize-none text-sm py-2 text-[#1E293B]" rows="1"></textarea>
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
        me: null,
        newMessage: '',
        team: [],
        conversations: [],
        selectedConversation: null,
        messages: [],
        echoListeners: [],

        async init() {
            // Get logged in user info
            const meRes = await fetch('/api/user', { credentials: 'same-origin', headers: { 'Accept': 'application/json' }});
            this.me = await meRes.json();

            await this.loadUsers();
            await this.loadConversations();
            
            // Set up Echo listener for real-time (Optional: if we had a global user channel to listen for new conversation started)
            // But for now we listen on active conversation channel
        },

        async loadUsers() {
            try {
                const res = await fetch('/api/team-chat/users', { credentials: 'same-origin', headers: { 'Accept': 'application/json' }});
                this.team = await res.json();
            } catch (e) { console.error(e); }
        },

        async loadConversations() {
            try {
                const res = await fetch('/api/team-chat/conversations', { credentials: 'same-origin', headers: { 'Accept': 'application/json' }});
                this.conversations = await res.json();
                
                if (this.conversations.length > 0) {
                    this.selectConversation(this.conversations[0]);
                }
            } catch (e) { console.error(e); }
        },

        async startChatWith(userId) {
            try {
                const res = await fetch('/api/team-chat/conversations', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ user_id: userId })
                });
                const data = await res.json();
                if (data.success) {
                    await this.loadConversations();
                    const conv = this.conversations.find(c => c.id === data.conversation_id);
                    if (conv) this.selectConversation(conv);
                }
            } catch (e) { console.error(e); }
        },

        async selectConversation(conv) {
            this.selectedConversation = conv;
            this.messages = [];
            
            // Clear previous Echo listeners to avoid duplicate events if switching chats
            this.echoListeners.forEach(channel => {
                window.Echo.leave(channel);
            });
            this.echoListeners = [];

            try {
                const res = await fetch(`/api/team-chat/conversations/${conv.id}/messages`, { credentials: 'same-origin', headers: { 'Accept': 'application/json' }});
                const data = await res.json();
                this.messages = data.map(m => ({
                    id: m.id,
                    name: m.sender_name,
                    initials: m.sender_name.substring(0,2).toUpperCase(),
                    userColor: 'bg-[#64748B]', // fallback color
                    text: m.content,
                    time: m.time,
                    isMine: m.user_id === this.me.id
                }));

                this.scrollToBottom();
                this.listenToConversation(conv.id);
            } catch (e) { console.error(e); }
        },

        listenToConversation(convId) {
            if (typeof window.Echo !== 'undefined') {
                const channelName = `team.conversation.${convId}`;
                window.Echo.private(channelName)
                    .listen('TeamMessageSent', (e) => {
                        // Avoid duplicating if we sent it (optimistic update handles it, or we skip if isMine)
                        if (e.user_id === this.me.id) return;
                        
                        this.messages.push({
                            id: e.id,
                            name: e.sender_name,
                            initials: e.sender_name.substring(0,2).toUpperCase(),
                            userColor: 'bg-[#64748B]',
                            text: e.content,
                            time: e.time,
                            isMine: false
                        });
                        this.scrollToBottom();
                    });
                this.echoListeners.push(channelName);
            }
        },

        async sendMessage() {
            if (!this.newMessage.trim() || !this.selectedConversation) return;
            
            const content = this.newMessage.trim();
            this.newMessage = '';
            
            // Optimistic UI
            const tempId = Date.now();
            this.messages.push({
                id: tempId,
                name: this.me.name,
                initials: this.me.name.substring(0,2).toUpperCase(),
                userColor: 'bg-[#0056D2]',
                text: content,
                time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
                isMine: true
            });
            this.scrollToBottom();

            try {
                const res = await fetch(`/api/team-chat/conversations/${this.selectedConversation.id}/messages`, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ content: content })
                });
                const data = await res.json();
                if (data.success) {
                    // Update temp ID with real ID if needed
                }
            } catch (e) { console.error(e); }
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const el = document.getElementById('chat-messages');
                if (el) el.scrollTop = el.scrollHeight;
            });
        }
    }
}
</script>
@endsection
