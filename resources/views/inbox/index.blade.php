@extends('layouts.app')

@section('title', 'Bandeja Omnicanal — Smart AI Hosting Solutions')

@section('content')
<div
    class="h-screen flex flex-col"
    x-data="inboxApp()"
    x-init="init()"
>
    {{-- Header Superior --}}
    <header class="h-[52px] bg-white border-b border-[#E2E8F0] flex items-center px-4 shrink-0 z-10">
        {{-- Logo y nombre de empresa --}}
        <div class="flex items-center gap-3 mr-6">
            <div class="w-8 h-8 bg-[#0056D2] rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </div>
            <div class="flex items-center gap-1.5 cursor-pointer group">
                <span class="text-sm font-bold text-[#1E293B] group-hover:text-[#0056D2] transition-colors" x-text="tenantName"></span>
                <svg class="w-3.5 h-3.5 text-[#94A3B8] group-hover:text-[#0056D2] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>

        {{-- Buscador global --}}
        <div class="flex-1 max-w-lg">
            <div class="relative">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-[#94A3B8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input
                    type="text"
                    placeholder="Buscar contactos o mensajes"
                    class="w-full pl-10 pr-4 py-2 text-sm bg-[#F8FAFC] border border-[#E2E8F0] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0056D2]/20 focus:border-[#0056D2] placeholder-[#94A3B8] transition-all"
                >
            </div>
        </div>

        {{-- Iconos derecha --}}
        <div class="flex items-center gap-2 ml-6">
            {{-- Configuración --}}
            <button class="w-9 h-9 rounded-lg flex items-center justify-center text-[#64748B] hover:bg-[#F1F5F9] hover:text-[#1E293B] transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </button>

            {{-- Notificaciones --}}
            <button class="w-9 h-9 rounded-lg flex items-center justify-center text-[#64748B] hover:bg-[#F1F5F9] hover:text-[#1E293B] transition-colors relative">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center">1</span>
            </button>

            {{-- Calendario --}}
            <button class="w-9 h-9 rounded-lg flex items-center justify-center text-[#64748B] hover:bg-[#F1F5F9] hover:text-[#1E293B] transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </button>

            {{-- Avatar del usuario --}}
            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#8B5CF6] to-[#6D28D9] flex items-center justify-center text-white text-xs font-bold cursor-pointer hover:ring-2 hover:ring-[#8B5CF6]/30 transition-all ml-1">
                EA
            </div>
        </div>
    </header>

    {{-- Cuerpo principal (3 columnas) --}}
    <div class="flex-1 flex overflow-hidden">
        {{-- Sidebar de Navegación --}}
        @include('inbox.partials.sidebar-nav')

        {{-- Lista de Conversaciones --}}
        @include('inbox.partials.conversation-list')

        {{-- Área de Chat --}}
        @include('inbox.partials.chat-area')

        {{-- Panel de Contacto / CRM --}}
        @include('inbox.partials.contact-sidebar')
    </div>

    {{-- Modal de Llamada --}}
    @include('inbox.partials.call-modal')
</div>

<script>
function inboxApp() {
    return {
        // Estado general
        tenantName: 'Smart AI Solutions',
        listTab: 'clientes',
        activeChannel: 'whatsapp',
        searchQuery: '',
        messageInput: '',
        showCallModal: false,
        showNoteInput: false,
        callSeconds: 0,
        callInterval: null,
        isMuted: false,
        selectedConversation: null,

        // Datos demo - Equipo
        teamMembers: [
            { id: 1, name: 'María Ortiz', initials: 'MO', color: '#8B5CF6', role: 'Supervisora', isOnline: true, activeChats: 4 },
            { id: 2, name: 'Carlos López', initials: 'CL', color: '#0056D2', role: 'Agente', isOnline: true, activeChats: 3 },
            { id: 3, name: 'Ana Rodríguez', initials: 'AR', color: '#10B981', role: 'Agente', isOnline: false, activeChats: 0 },
            { id: 4, name: 'Pedro Sánchez', initials: 'PS', color: '#F59E0B', role: 'Agente', isOnline: true, activeChats: 2 },
        ],

        // Datos demo - Conversaciones
        conversations: [
            {
                id: 1,
                contactName: 'Laura García',
                initials: 'LG',
                avatarColor: '#8B5CF6',
                phone: '+52 81 •• •• 57',
                email: 'laura.garcia@correo.com',
                company: 'Boutique La Rosa',
                channel: 'whatsapp',
                lastMessagePreview: 'Busco una bolsa...',
                lastMessageTime: '11:02 p.m.',
                unreadCount: 2,
                priority: 'normal',
                isBotActive: true,
                botName: 'Aurora',
                agentName: null,
                contactSource: 'WhatsApp directo',
                leadSource: null,
                salesCount: 1,
                opportunitiesCount: 1,
                appointmentInfo: null,
                socialHandle: null,
                tags: [
                    { name: 'VIP', color: '#8B5CF6' },
                    { name: 'Bolsas', color: '#0056D2' }
                ],
                messages: [
                    { id: 1, direction: 'inbound', content: 'Hola 👋 Busco una bolsa para regalo, ¿qué me recomiendan?', time: '11:02 p.m.', status: 'read' },
                    { id: 2, direction: 'outbound', content: '¡Hola Laura! 🎁 Las dos más pedidas: la Tote Camel (piel, grande) y la Mini Croc Negra (de mano). ¿Cuál te gusta?', time: '11:02 p.m.', status: 'read' },
                    { id: 3, direction: 'inbound', content: 'La Mini Croc Negra 🖤 ¿Cuánto cuesta?', time: '11:04 p.m.', status: 'read' },
                    { id: 4, direction: 'outbound', content: 'La Mini Croc Negra cuesta <strong>$1,290</strong> y sí, la tenemos en existencia ✅ ¿Te paso con una asesora para cerrar tu compra?', time: '11:04 p.m.', status: 'read' }
                ]
            },
            {
                id: 2,
                contactName: 'Carlos Ruiz',
                initials: 'CR',
                avatarColor: '#0056D2',
                phone: '+52 81 •• •• 04',
                email: 'carlos.ruiz@correo.com',
                company: 'Motos del Valle',
                channel: 'whatsapp',
                lastMessagePreview: 'Para reparto, que...',
                lastMessageTime: '2:49 a.m.',
                unreadCount: 0,
                priority: 'high',
                isBotActive: true,
                botName: 'Aurora',
                agentName: null,
                contactSource: 'Lead · Campaña de Meta',
                leadSource: 'Nuevo lead de tu campaña de Meta · formulario completado 2:47 a.m.',
                salesCount: 1,
                opportunitiesCount: 1,
                appointmentInfo: null,
                socialHandle: null,
                tags: [
                    { name: 'Lead Meta', color: '#0084FF' }
                ],
                messages: [
                    { id: 1, direction: 'outbound', content: 'Hola Carlos 👋 Gracias por dejar tus datos en Motos del Valle. ¿Buscas una moto para trabajo o para uso personal?', time: '2:47 a.m.', status: 'read' },
                    { id: 2, direction: 'inbound', content: 'Para reparto, que rinda mucho 🏍️', time: '2:49 a.m.', status: 'read' },
                    { id: 3, direction: 'outbound', content: 'La Italika FT150 es la más rendidora para reparto: desde <strong>$28,900</strong> y la tenemos en piso 🏍️ ¿Te agendo una prueba de manejo?', time: '2:49 a.m.', status: 'read' }
                ]
            },
            {
                id: 3,
                contactName: 'Sofía Méndez',
                initials: 'SM',
                avatarColor: '#E1306C',
                phone: '+52 55 •• •• 18',
                email: 'sofia.mendez@correo.com',
                company: 'Clínica Dental Sonrisa',
                channel: 'instagram',
                lastMessagePreview: 'Centro, jueves 11...',
                lastMessageTime: '6:17 p.m.',
                unreadCount: 0,
                priority: 'normal',
                isBotActive: true,
                botName: 'Aurora',
                agentName: null,
                contactSource: '@sofia.mendez · Instagram',
                leadSource: null,
                salesCount: 1,
                opportunitiesCount: 1,
                appointmentInfo: 'Limpieza · jue 11:00 · Centro',
                socialHandle: 'sofia.mendez',
                tags: [
                    { name: 'Cita', color: '#10B981' }
                ],
                messages: [
                    { id: 1, direction: 'inbound', content: 'Hola 👋 Quiero agendar una cita', time: '6:15 p.m.', status: 'read' },
                    { id: 2, direction: 'outbound', content: '¡Claro Sofía! Manejo <strong>limpieza</strong> (40 min), <strong>valoración</strong> (30 min) y <strong>blanqueamiento</strong> (60 min), en 📍 Centro y 📍 Sur. ¿Cuál y en qué sucursal?', time: '6:15 p.m.', status: 'read' },
                    { id: 3, direction: 'inbound', content: 'Limpieza, en Centro 🦷', time: '6:16 p.m.', status: 'read' },
                    { id: 4, direction: 'outbound', content: '¡Agendada! 🗓️ <strong>Limpieza · jueves 11:00 · Sucursal Centro</strong> con la Dra. Ramírez — el primer espacio libre. Te mando recordatorio.', time: '6:16 p.m.', status: 'read' }
                ]
            },
            {
                id: 4,
                contactName: 'Ana Torres',
                initials: 'AT',
                avatarColor: '#F59E0B',
                phone: '+52 81 •• •• 22',
                email: 'ana.torres@correo.com',
                company: 'Colegio Alborada',
                channel: 'whatsapp',
                lastMessagePreview: '¡Gracias María! 😊',
                lastMessageTime: '5:42 p.m.',
                unreadCount: 0,
                priority: 'normal',
                isBotActive: false,
                botName: 'Aurora',
                agentName: 'María',
                contactSource: 'Admisión · 1° de primaria',
                leadSource: null,
                salesCount: 1,
                opportunitiesCount: 1,
                appointmentInfo: null,
                socialHandle: null,
                tags: [
                    { name: 'Admisión', color: '#F59E0B' }
                ],
                messages: [
                    { id: 1, direction: 'inbound', content: 'Hola, ¿me pueden llamar? 📞 Quiero inscribir a mi hija a 1° de primaria', time: '5:40 p.m.', status: 'read' },
                    { id: 2, direction: 'outbound', content: '¡Claro que sí, Ana! 😊 Te comunico con Admisiones para que te marquen en un momento 👋', time: '5:41 p.m.', status: 'read' },
                    { id: 3, isSystemEvent: true, content: '👤 María Ortiz tomó la conversación', time: '5:41 p.m.' },
                    { id: 4, direction: 'inbound', content: '¡Gracias María! 😊', time: '5:42 p.m.', status: 'read' }
                ]
            },
            {
                id: 5,
                contactName: 'Jorge Vargas',
                initials: 'JV',
                avatarColor: '#64748B',
                phone: '+52 33 •• •• 91',
                email: 'jorge.v@correo.com',
                company: null,
                channel: 'messenger',
                lastMessagePreview: 'Quiero cotización...',
                lastMessageTime: '8:40 p.m.',
                unreadCount: 1,
                priority: 'normal',
                isBotActive: true,
                botName: 'Aurora',
                agentName: null,
                contactSource: 'Messenger',
                leadSource: null,
                salesCount: 0,
                opportunitiesCount: 0,
                appointmentInfo: null,
                socialHandle: null,
                tags: [],
                messages: [
                    { id: 1, direction: 'inbound', content: 'Buenas tardes, quiero cotización de hosting para mi tienda online', time: '8:40 p.m.', status: 'read' },
                    { id: 2, direction: 'outbound', content: '¡Hola Jorge! Con gusto te ayudo 💻 ¿Tu tienda está en WordPress, Shopify o es desarrollo propio? Así te recomiendo el plan ideal.', time: '8:40 p.m.', status: 'delivered' }
                ]
            },
            {
                id: 6,
                contactName: 'Hugo Martínez',
                initials: 'HM',
                avatarColor: '#0D9488',
                phone: '+52 81 •• •• 33',
                email: null,
                company: null,
                channel: 'whatsapp',
                lastMessagePreview: '¿Tienen servicio...',
                lastMessageTime: '7:15 p.m.',
                unreadCount: 0,
                priority: 'normal',
                isBotActive: true,
                botName: 'Aurora',
                agentName: null,
                contactSource: 'WhatsApp',
                leadSource: null,
                salesCount: 0,
                opportunitiesCount: 0,
                appointmentInfo: null,
                socialHandle: null,
                tags: [],
                messages: [
                    { id: 1, direction: 'inbound', content: '¿Tienen servicio de mantenimiento mensual para páginas web?', time: '7:15 p.m.', status: 'read' },
                    { id: 2, direction: 'outbound', content: '¡Sí! Nuestro plan de mantenimiento incluye actualizaciones, backups y soporte técnico desde <strong>$499/mes</strong>. ¿Te envío los detalles?', time: '7:15 p.m.', status: 'read' }
                ]
            },
            {
                id: 7,
                contactName: 'Sandra Castillo',
                initials: 'SC',
                avatarColor: '#EC4899',
                phone: '+52 55 •• •• 67',
                email: 'sandra.c@correo.com',
                company: null,
                channel: 'instagram',
                lastMessagePreview: 'Me interesa el pla...',
                lastMessageTime: '6:03 p.m.',
                unreadCount: 0,
                priority: 'normal',
                isBotActive: true,
                botName: 'Aurora',
                agentName: null,
                contactSource: '@sandra_castillo · Instagram',
                leadSource: null,
                salesCount: 0,
                opportunitiesCount: 1,
                appointmentInfo: null,
                socialHandle: 'sandra_castillo',
                tags: [],
                messages: [
                    { id: 1, direction: 'inbound', content: 'Me interesa el plan avanzado para mi restaurante, ¿incluye WhatsApp?', time: '6:03 p.m.', status: 'read' },
                    { id: 2, direction: 'outbound', content: '¡Hola Sandra! 🍽️ Sí, el Plan Avanzado incluye <strong>WhatsApp + Instagram + Messenger</strong>, CRM integrado y hasta 10 agentes. ¿Te agendo una demo?', time: '6:03 p.m.', status: 'read' }
                ]
            },
            {
                id: 8,
                contactName: 'Pablo Contreras',
                initials: 'PC',
                avatarColor: '#6366F1',
                phone: '+52 81 •• •• 45',
                email: 'pablo.c@empresa.com',
                company: null,
                channel: 'whatsapp',
                lastMessagePreview: 'Necesito dominio...',
                lastMessageTime: 'ayer',
                unreadCount: 0,
                priority: 'low',
                isBotActive: true,
                botName: 'Aurora',
                agentName: null,
                contactSource: 'WhatsApp',
                leadSource: null,
                salesCount: 0,
                opportunitiesCount: 0,
                appointmentInfo: null,
                socialHandle: null,
                tags: [],
                messages: [
                    { id: 1, direction: 'inbound', content: 'Necesito dominio .com.mx y hosting, ¿cuánto sale el paquete?', time: 'ayer', status: 'read' },
                    { id: 2, direction: 'outbound', content: '¡Hola Pablo! El paquete dominio + hosting básico empieza en <strong>$1,200/año</strong>. Incluye SSL gratis y soporte 24/7. ¿Quieres más detalles?', time: 'ayer', status: 'read' }
                ]
            }
        ],

        // Computed
        get filteredConversations() {
            if (!this.searchQuery) return this.conversations;
            const q = this.searchQuery.toLowerCase();
            return this.conversations.filter(c =>
                c.contactName.toLowerCase().includes(q) ||
                (c.lastMessagePreview && c.lastMessagePreview.toLowerCase().includes(q))
            );
        },

        get callTimer() {
            const mins = Math.floor(this.callSeconds / 60).toString().padStart(1, '0');
            const secs = (this.callSeconds % 60).toString().padStart(2, '0');
            return mins + ':' + secs;
        },

        // Métodos
        init() {
            // Seleccionar primera conversación por defecto
            if (this.conversations.length > 0) {
                this.selectedConversation = this.conversations[0];
            }
        },

        selectConversation(conv) {
            this.selectedConversation = conv;
            conv.unreadCount = 0;
            // Scroll al fondo del chat
            this.$nextTick(() => {
                const chatContainer = document.getElementById('chat-messages');
                if (chatContainer) {
                    chatContainer.scrollTop = chatContainer.scrollHeight;
                }
            });
        },

        sendMessage() {
            if (!this.messageInput.trim() || !this.selectedConversation) return;

            const newMsg = {
                id: Date.now(),
                direction: 'outbound',
                content: this.messageInput,
                time: new Date().toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' }),
                status: 'sent'
            };

            this.selectedConversation.messages.push(newMsg);
            this.selectedConversation.lastMessagePreview = this.messageInput.substring(0, 30) + '...';
            this.messageInput = '';

            // Scroll al fondo
            this.$nextTick(() => {
                const chatContainer = document.getElementById('chat-messages');
                if (chatContainer) {
                    chatContainer.scrollTop = chatContainer.scrollHeight;
                }
            });

            // Simular tick de entregado después de 1s
            setTimeout(() => { newMsg.status = 'delivered'; }, 1000);
            setTimeout(() => { newMsg.status = 'read'; }, 2500);
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
