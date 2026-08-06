{{-- Área de Chat Central --}}
<div class="flex-1 flex flex-col bg-[#F8FAFC] min-w-0">
    {{-- Tabs de Canal --}}
    <div class="bg-white border-b border-[#E2E8F0] px-4 py-0 flex items-center gap-1 shrink-0">
        {{-- WhatsApp --}}
        <button
            @click="activeChannel = 'whatsapp'"
            :class="activeChannel === 'whatsapp' ? 'active text-[#1E293B] font-medium' : 'text-[#64748B] hover:text-[#1E293B]'"
            class="channel-tab flex items-center gap-2 px-4 py-3 text-sm transition-colors"
        >
            <svg class="w-4 h-4 text-[#25D366]" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            WhatsApp
        </button>

        {{-- Instagram --}}
        <button
            @click="activeChannel = 'instagram'"
            :class="activeChannel === 'instagram' ? 'active text-[#1E293B] font-medium' : 'text-[#64748B] hover:text-[#1E293B]'"
            class="channel-tab flex items-center gap-2 px-4 py-3 text-sm transition-colors"
        >
            <svg class="w-4 h-4 text-[#E1306C]" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
            </svg>
            Instagram
        </button>

        {{-- Messenger --}}
        <button
            @click="activeChannel = 'messenger'"
            :class="activeChannel === 'messenger' ? 'active text-[#1E293B] font-medium' : 'text-[#64748B] hover:text-[#1E293B]'"
            class="channel-tab flex items-center gap-2 px-4 py-3 text-sm transition-colors"
        >
            <svg class="w-4 h-4 text-[#0084FF]" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 0C5.373 0 0 4.974 0 11.111c0 3.498 1.744 6.614 4.469 8.654V24l4.088-2.242c1.092.3 2.246.464 3.443.464 6.627 0 12-4.974 12-11.111C24 4.974 18.627 0 12 0zm1.191 14.963l-3.055-3.26-5.963 3.26L10.732 8.2l3.131 3.26 5.886-3.26-6.558 6.763z"/>
            </svg>
            Messenger
        </button>

        {{-- Agregar canal --}}
        <button class="channel-tab flex items-center justify-center w-8 h-8 rounded-full text-[#94A3B8] hover:text-[#64748B] hover:bg-[#F1F5F9] transition-colors ml-1">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
        </button>

        {{-- Spacer --}}
        <div class="flex-1"></div>

        {{-- Actividades --}}
        <button class="flex items-center gap-2 px-3 py-2 text-sm text-[#64748B] hover:text-[#1E293B] hover:bg-[#F1F5F9] rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Actividades
        </button>
    </div>

    {{-- Estado vacío --}}
    <template x-if="!selectedConversation">
        <div class="flex-1 flex items-center justify-center">
            <div class="text-center">
                <div class="w-20 h-20 mx-auto mb-4 bg-[#EFF6FF] rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-[#0056D2]/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-[#1E293B] mb-1">Selecciona una conversación</h3>
                <p class="text-sm text-[#94A3B8]">Elige un cliente de la lista para ver sus mensajes</p>
            </div>
        </div>
    </template>

    {{-- Chat activo --}}
    <template x-if="selectedConversation">
        <div class="flex-1 flex flex-col min-h-0">
            {{-- Notificación de evento (lead de Meta, etc.) --}}
            <div class="px-4 py-2 text-center" x-show="selectedConversation.leadSource">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-[#F0FDF4] text-[#166534] text-xs font-medium rounded-full animate-fade-in">
                    <span class="w-1.5 h-1.5 bg-[#10B981] rounded-full animate-pulse-dot"></span>
                    <span x-text="selectedConversation.leadSource"></span>
                </div>
            </div>

            {{-- Mensajes --}}
            <div class="flex-1 overflow-y-auto custom-scrollbar px-4 py-4 space-y-4" id="chat-messages">
                <template x-for="msg in selectedConversation.messages" :key="msg.id">
                    <div>
                        {{-- Nota interna --}}
                        <template x-if="msg.isInternalNote">
                            <div class="flex justify-center animate-fade-in">
                                <div class="bubble-internal-note px-4 py-2.5">
                                    <div class="flex items-center gap-2 mb-1">
                                        <svg class="w-3.5 h-3.5 text-[#F59E0B]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        <span class="text-xs font-semibold" x-text="msg.agentName"></span>
                                    </div>
                                    <p class="text-sm" x-text="msg.content"></p>
                                </div>
                            </div>
                        </template>

                        {{-- Evento del sistema (asignación, etc.) --}}
                        <template x-if="msg.isSystemEvent">
                            <div class="flex justify-center animate-fade-in">
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-[#F1F5F9] text-[#64748B] text-xs rounded-full">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span x-text="msg.content"></span>
                                </div>
                            </div>
                        </template>

                        {{-- Mensaje entrante (cliente) --}}
                        <template x-if="msg.direction === 'inbound' && !msg.isInternalNote && !msg.isSystemEvent">
                            <div class="flex justify-start animate-slide-left">
                                <div class="bubble-incoming px-4 py-2.5">
                                    <p class="text-sm leading-relaxed" x-html="msg.content"></p>
                                    <div class="flex items-center justify-end gap-1 mt-1">
                                        <span class="text-[10px] text-[#94A3B8]" x-text="msg.time"></span>
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- Mensaje saliente (bot/agente) --}}
                        <template x-if="msg.direction === 'outbound' && !msg.isInternalNote && !msg.isSystemEvent">
                            <div class="flex justify-end animate-slide-right">
                                <div class="bubble-outgoing px-4 py-2.5">
                                    <p class="text-sm leading-relaxed" x-html="msg.content"></p>
                                    <div class="flex items-center justify-end gap-1.5 mt-1">
                                        <span class="text-[10px] text-white/70" x-text="msg.time"></span>
                                        {{-- Ticks de lectura --}}
                                        <template x-if="msg.status === 'read'">
                                            <svg class="w-4 h-3 text-white/90" viewBox="0 0 24 14" fill="none" stroke="currentColor" stroke-width="2.5">
                                                <path d="M1 7l5 5L18 1" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M7 7l5 5L24 1" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </template>
                                        <template x-if="msg.status === 'delivered'">
                                            <svg class="w-4 h-3 text-white/50" viewBox="0 0 24 14" fill="none" stroke="currentColor" stroke-width="2.5">
                                                <path d="M1 7l5 5L18 1" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M7 7l5 5L24 1" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </template>
                                        <template x-if="msg.status === 'sent'">
                                            <svg class="w-3 h-3 text-white/50" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2.5">
                                                <path d="M1 7l5 5L13 1" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            {{-- Barra de escritura --}}
            <div class="bg-white border-t border-[#E2E8F0] px-4 py-3 shrink-0">
                <div class="flex items-center gap-3">
                    {{-- Input --}}
                    <div class="flex-1 relative">
                        <input
                            type="text"
                            x-model="messageInput"
                            @keydown.enter="sendMessage()"
                            placeholder="Escribe tu mensaje..."
                            class="w-full px-4 py-2.5 text-sm bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0056D2]/20 focus:border-[#0056D2] placeholder-[#94A3B8] transition-all pr-12"
                        >
                        {{-- Botón emoji --}}
                        <button class="absolute right-3 top-1/2 -translate-y-1/2 text-[#94A3B8] hover:text-[#64748B] transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                    </div>

                    {{-- Estado IA / Agente --}}
                    <div class="flex items-center gap-2 text-xs shrink-0" x-show="selectedConversation.isBotActive">
                        <span class="w-2 h-2 bg-[#10B981] rounded-full animate-pulse-dot"></span>
                        <span class="text-[#10B981] font-medium whitespace-nowrap" x-text="selectedConversation.botName + ' IA activa'"></span>
                    </div>
                    <div class="flex items-center gap-2 text-xs shrink-0" x-show="!selectedConversation.isBotActive && selectedConversation.agentName">
                        <span class="text-[#94A3B8] font-medium whitespace-nowrap" x-text="selectedConversation.agentName + ' está atendiendo'"></span>
                    </div>

                    {{-- Botón Enviar --}}
                    <button
                        @click="sendMessage()"
                        class="bg-[#0056D2] hover:bg-[#0047B3] text-white px-5 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 transition-colors shrink-0 active:scale-95"
                    >
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                        </svg>
                        Enviar
                    </button>
                </div>

                {{-- Acciones rápidas --}}
                <div class="flex items-center gap-2 mt-2 pl-1">
                    {{-- Adjuntar --}}
                    <button class="text-[#94A3B8] hover:text-[#64748B] transition-colors p-1 rounded" title="Adjuntar archivo">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                    </button>
                    {{-- Nota interna --}}
                    <button @click="showNoteInput = !showNoteInput" class="text-[#94A3B8] hover:text-[#F59E0B] transition-colors p-1 rounded flex items-center gap-1" title="Nota interna">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        <span class="text-[10px] font-medium">Nota</span>
                    </button>
                    {{-- Respuestas rápidas --}}
                    <button class="text-[#94A3B8] hover:text-[#0056D2] transition-colors p-1 rounded flex items-center gap-1" title="Respuestas rápidas">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span class="text-[10px] font-medium">Rápidas</span>
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
