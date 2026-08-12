@extends('layouts.app')

@section('title', 'Bandeja Omnicanal — Smart AI Hosting Solutions')

@section('content')
<div class="flex-1 flex overflow-hidden w-full h-full gap-6" x-data="inboxApp()" x-init="init()">
    {{-- Lista de Conversaciones --}}
    @include('inbox.partials.conversation-list')

    {{-- Área de Chat --}}
    @include('inbox.partials.chat-area')

    {{-- Panel de Contacto / CRM --}}
    @include('inbox.partials.contact-sidebar')
    {{-- Modal de Llamada --}}
    @include('inbox.partials.call-modal')
</div>

<script>
function inboxApp() {
    return {
        // Estado general
        tenantName: 'Novape',
        listTab: 'clientes',
        activeChannel: 'whatsapp',
        searchQuery: '',
        messageInput: '',
        showCallModal: false,
        showNoteInput: false,
        callSeconds: 0,
        callInterval: null,
        isMuted: false,
        isInternalNoteMode: false,
        sidebarOpen: true,
        selectedConversation: null,
        
        // Datos dinámicos
        conversations: [],
        cannedResponses: [],
        pollingInterval: null,

        // Datos reales - Equipo (Cargados desde la BD)
        teamMembers: [
            @foreach(\App\Models\User::all() as $idx => $user)
            { 
                id: {{ $user->id }}, 
                name: '{{ $user->name }}', 
                initials: '{{ strtoupper(substr($user->name, 0, 2)) }}', 
                color: '{{ ['#8B5CF6', '#0056D2', '#10B981', '#F59E0B'][$idx % 4] }}', 
                role: 'Agente', 
                isOnline: true, 
                activeChats: 0 
            },
            @endforeach
        ],

        get filteredConversations() {
            if (!this.searchQuery) return this.conversations;
            const q = this.searchQuery.toLowerCase();
            return this.conversations.filter(c =>
                c.contactName.toLowerCase().includes(q) ||
                (c.lastMessagePreview && c.lastMessagePreview.toLowerCase().includes(q))
            );
        },

        get showCannedResponses() {
            return this.messageInput.startsWith('/') && this.filteredCannedResponses.length > 0;
        },

        get filteredCannedResponses() {
            if (!this.messageInput.startsWith('/')) return [];
            const query = this.messageInput.slice(1).toLowerCase();
            return this.cannedResponses.filter(cr => 
                (cr.shortcut && cr.shortcut.toLowerCase().includes(query)) ||
                cr.title.toLowerCase().includes(query) ||
                cr.content.toLowerCase().includes(query)
            ).slice(0, 5); // Max 5 results
        },

        get callTimer() {
            const mins = Math.floor(this.callSeconds / 60).toString().padStart(2, '0');
            const secs = (this.callSeconds % 60).toString().padStart(2, '0');
            return mins + ':' + secs;
        },

        // Métodos
        init() {
            this.fetchConversations();
            this.fetchCannedResponses();
            this.setupWebSockets();
        },
        
        setupWebSockets() {
            const tenantId = {{ auth()->user()->tenant_id ?? 1 }};
            if (typeof window.Echo !== 'undefined') {
                window.Echo.private(`tenant.${tenantId}`)
                    .listen('MessageReceived', (e) => {
                        console.log('MessageReceived event:', e);
                        
                        // Si hay menciones, mostrar alerta
                        if (e.isInternalNote && e.mentions && e.mentions.length > 0) {
                            alert(`Has sido mencionado en una nota interna por @${e.mentions.join(', ')}`);
                        }

                        // Refrescar conversaciones para actualizar unread counts y previews
                        this.fetchConversations();
                        
                        // Si la conversación actual es a la que pertenece el mensaje, refrescar mensajes
                        if (this.selectedConversation && this.selectedConversation.id === e.conversation_id) {
                            this.fetchMessages(this.selectedConversation);
                        }
                    })
                    .listen('ConversationUpdated', (e) => {
                        console.log('ConversationUpdated event:', e);
                        this.fetchConversations();
                    })
                    .listen('MessageStatusUpdated', (e) => {
                        console.log('MessageStatusUpdated event:', e);
                        if (this.selectedConversation && this.selectedConversation.id === e.conversation_id) {
                            const msg = this.selectedConversation.messages.find(m => m.id === e.id);
                            if (msg) {
                                msg.status = e.status;
                            }
                        }
                    });
            } else {
                console.warn("Laravel Echo no está disponible.");
            }
        },

        async fetchConversations() {
            try {
                const res = await fetch('/api/inbox/conversations', {
                    credentials: 'same-origin',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                const data = await res.json();
                this.conversations = data.data || data;
            } catch(e) {
                console.error(e);
            }
        },

        async fetchCannedResponses() {
            try {
                const res = await fetch('/api/canned-responses', {
                    credentials: 'same-origin',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                const data = await res.json();
                this.cannedResponses = data;
            } catch(e) {
                console.error(e);
            }
        },

        insertCannedResponse(cr) {
            this.messageInput = cr.content;
            this.$refs.messageInput.focus();
        },

        async fetchMessages(conv) {
            try {
                const res = await fetch(`/api/inbox/conversations/${conv.id}/messages`, {
                    credentials: 'same-origin',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                const data = await res.json();
                const messages = data.data || data;
                // Actualizar mensajes sin perder referencia (para no parpadear)
                if(!conv.messages) conv.messages = [];
                if (JSON.stringify(conv.messages) !== JSON.stringify(messages)) {
                    conv.messages = messages;
                    // Scroll al fondo si hubo nuevos
                    this.$nextTick(() => {
                        const chatContainer = document.getElementById('chat-messages');
                        if (chatContainer) chatContainer.scrollTop = chatContainer.scrollHeight;
                    });
                }
            } catch(e) {
                console.error(e);
            }
        },

        selectConversation(conv) {
            this.selectedConversation = conv;
            this.selectedConversation.messages = [];
            this.fetchMessages(conv);
        },

        async sendMessage() {
            if (!this.messageInput.trim() || !this.selectedConversation) return;

            const content = this.messageInput;
            this.messageInput = '';

            // Optimistic update
            const tempMsg = {
                id: Date.now(),
                direction: 'outbound',
                content: content,
                time: new Date().toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' }),
                status: 'queued'
            };
            this.selectedConversation.messages.push(tempMsg);
            this.selectedConversation.lastMessagePreview = content.substring(0, 30) + '...';

            this.$nextTick(() => {
                const chatContainer = document.getElementById('chat-messages');
                if (chatContainer) chatContainer.scrollTop = chatContainer.scrollHeight;
            });

            try {
                const res = await fetch(`/api/inbox/conversations/${this.selectedConversation.id}/messages`, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ content: content })
                });
                const data = await res.json();
                
                // Actualizar el estado del mensaje con lo que devolvió el servidor
                if (data.success) {
                    const idx = this.selectedConversation.messages.findIndex(m => m.id === tempMsg.id);
                    if(idx !== -1) {
                        this.selectedConversation.messages.splice(idx, 1, data.message);
                    }
                }
            } catch(e) {
                console.error(e);
            }
        },

        async assignAgent(userId, userName) {
            if (!this.selectedConversation) return;
            try {
                const res = await fetch(`/api/inbox/conversations/${this.selectedConversation.id}/assign`, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ user_id: userId })
                });
                const data = await res.json();
                if (data.success) {
                    this.selectedConversation.isBotActive = false;
                    this.selectedConversation.assignedUserId = data.assignedUserId;
                    this.selectedConversation.agentName = data.agentName;
                    
                    const idx = this.conversations.findIndex(c => c.id === this.selectedConversation.id);
                    if (idx !== -1) {
                        this.conversations[idx].isBotActive = false;
                        this.conversations[idx].assignedUserId = data.assignedUserId;
                        this.conversations[idx].agentName = data.agentName;
                    }
                }
            } catch(e) { console.error(e); }
        },

        async unassignAgent() {
            if (!this.selectedConversation) return;
            try {
                const res = await fetch(`/api/inbox/conversations/${this.selectedConversation.id}/unassign`, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                const data = await res.json();
                if (data.success) {
                    this.selectedConversation.isBotActive = true;
                    this.selectedConversation.agentName = null;
                }
            } catch(e) { console.error(e); }
        },

        async sendInternalNote() {
            if (!this.messageInput.trim() || !this.selectedConversation) return;
            
            const content = this.messageInput;
            this.messageInput = '';

            const tempMsg = {
                id: Date.now(),
                direction: 'outbound',
                content: content,
                time: new Date().toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' }),
                status: 'sent',
                isInternalNote: true
            };
            this.selectedConversation.messages.push(tempMsg);

            this.$nextTick(() => {
                const chatContainer = document.getElementById('chat-messages');
                if (chatContainer) chatContainer.scrollTop = chatContainer.scrollHeight;
            });

            try {
                const res = await fetch(`/api/inbox/conversations/${this.selectedConversation.id}/notes`, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ content: content })
                });
                const data = await res.json();
                if (data.success) {
                    const idx = this.selectedConversation.messages.findIndex(m => m.id === tempMsg.id);
                    if(idx !== -1) this.selectedConversation.messages[idx] = data.message;
                }
            } catch(e) {
                console.error(e);
            }
        },

        startCall() {
            this.showCallModal = true;
            this.callSeconds = 0;
            this.isMuted = false;
            this.callInterval = setInterval(() => {
                this.callSeconds++;
            }, 1000);
        },

        endCall() {
            this.showCallModal = false;
            if (this.callInterval) {
                clearInterval(this.callInterval);
                this.callInterval = null;
            }
            this.callSeconds = 0;
            this.isMuted = false;
        }
    };
}
</script>
@endsection
