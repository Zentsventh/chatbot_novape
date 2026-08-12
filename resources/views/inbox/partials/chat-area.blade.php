{{-- Área de Chat Central --}}
<div class="flex-1 flex flex-col backdrop-blur-xl border border-white/20 rounded-[32px] shadow-[0_15px_40px_-10px_rgba(0,0,0,0.8)] min-w-0 h-full overflow-hidden" style="background: linear-gradient(180deg, rgba(6,101,224,0.8) 0%, rgba(2,68,158,0.8) 100%);">
    {{-- Tabs de Canal (Header) --}}
    <div class="bg-black/10 border-b border-white/10 px-4 py-1 flex items-center gap-2 shrink-0 sticky top-0 z-10">
        {{-- WhatsApp --}}
        <button
            @click="activeChannel = 'whatsapp'"
            :class="activeChannel === 'whatsapp' ? 'active text-white font-bold' : 'text-white/50 hover:text-white'"
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
            :class="activeChannel === 'instagram' ? 'active text-white font-bold' : 'text-white/50 hover:text-white'"
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
            :class="activeChannel === 'messenger' ? 'active text-white font-bold' : 'text-white/50 hover:text-white'"
            class="channel-tab flex items-center gap-2 px-4 py-3 text-sm transition-colors"
        >
            <svg class="w-4 h-4 text-[#0084FF]" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 0C5.373 0 0 4.974 0 11.111c0 3.498 1.744 6.614 4.469 8.654V24l4.088-2.242c1.092.3 2.246.464 3.443.464 6.627 0 12-4.974 12-11.111C24 4.974 18.627 0 12 0zm1.191 14.963l-3.055-3.26-5.963 3.26L10.732 8.2l3.131 3.26 5.886-3.26-6.558 6.763z"/>
            </svg>
            Messenger
        </button>

        {{-- Agregar canal --}}
        <button class="channel-tab flex items-center justify-center w-8 h-8 rounded-full text-white/50 hover:text-white hover:bg-white/10 transition-colors ml-1">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
        </button>

        {{-- Spacer --}}
        <div class="flex-1"></div>

        {{-- Actividades --}}
        <button class="flex items-center gap-2 px-3 py-2 text-sm text-white/50 hover:text-white hover:bg-white/10 rounded-lg transition-colors">
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
                <div class="w-20 h-20 mx-auto mb-4 bg-[#00CEFF]/10 border border-[#00CEFF]/20 rounded-full flex items-center justify-center shadow-[0_0_20px_rgba(0,206,255,0.2)]">
                    <svg class="w-10 h-10 text-[#00CEFF]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-1">Selecciona una conversación</h3>
                <p class="text-sm text-white/60">Elige un cliente de la lista para ver sus mensajes</p>
            </div>
        </div>
    </template>

    {{-- Chat activo --}}
    <template x-if="selectedConversation">
        <div class="flex-1 flex flex-col min-h-0">
            {{-- Notificación de evento (lead de Meta, etc.) --}}
            <div class="px-4 py-2 text-center" x-show="selectedConversation.leadSource">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-[#10B981]/10 border border-[#10B981]/30 text-[#10B981] text-xs font-bold rounded-full animate-fade-in shadow-[0_0_10px_rgba(16,185,129,0.2)]">
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
                                <div class="bg-[#F59E0B]/20 border border-[#F59E0B]/40 text-white px-4 py-2.5 rounded-xl backdrop-blur-sm shadow-[0_0_10px_rgba(245,158,11,0.1)]">
                                    <div class="flex items-center gap-2 mb-1">
                                        <svg class="w-3.5 h-3.5 text-[#F59E0B]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        <span class="text-xs font-bold text-[#F59E0B]" x-text="msg.agentName"></span>
                                    </div>
                                    <p class="text-sm text-white/90 font-medium" x-text="msg.content"></p>
                                </div>
                            </div>
                        </template>

                        {{-- Evento del sistema (asignación, etc.) --}}
                        <template x-if="msg.isSystemEvent">
                            <div class="flex justify-center animate-fade-in">
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-black/20 border border-white/5 text-white/60 text-xs rounded-full">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="font-medium" x-text="msg.content"></span>
                                </div>
                            </div>
                        </template>

                        {{-- Mensaje entrante (cliente) --}}
                    {{-- Burbuja (Nota interna o Mensaje) --}}
                    <div class="flex gap-2 w-full" :class="msg.direction === 'outbound' ? 'flex-row-reverse' : 'flex-row'">
                        <div 
                            :class="[
                                'relative px-5 py-3 shadow-sm group transition-all backdrop-blur-md',
                                msg.isInternalNote ? 'bg-[#F59E0B]/20 border border-[#F59E0B]/40 text-white' : 
                                (msg.direction === 'outbound' ? 'bg-gradient-to-r from-[#0665E0] to-[#02449E] text-white shadow-[0_5px_15px_rgba(6,101,224,0.4)] border border-[#0665E0]/50' : 'bg-[#002B6A]/80 text-white border border-white/10 shadow-[0_5px_15px_rgba(0,0,0,0.3)]'),
                                msg.direction === 'outbound' ? 'rounded-2xl rounded-tr-[4px]' : 'rounded-2xl rounded-tl-[4px]',
                            ]"
                            class="max-w-[85%] sm:max-w-[75%]"
                        >
                            {{-- Badge Nota Interna --}}
                            <div x-show="msg.isInternalNote" class="text-[10px] uppercase font-bold text-[#F59E0B] mb-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Solo Interno
                            </div>

                            {{-- Renderizado Multimedia --}}
                            <template x-if="msg.mediaUrl">
                                <div class="mb-2 overflow-hidden rounded-xl">
                                    <template x-if="msg.messageType === 'image' || msg.messageType === 'sticker'">
                                        <img :src="msg.mediaUrl" class="max-w-full h-auto max-h-64 object-contain rounded-xl cursor-pointer" alt="Imagen enviada">
                                    </template>
                                    <template x-if="msg.messageType === 'audio'">
                                        <audio controls :src="msg.mediaUrl" class="w-full max-w-[250px] h-10 mt-1"></audio>
                                    </template>
                                    <template x-if="msg.messageType === 'document' || msg.messageType === 'video'">
                                        <a :href="msg.mediaUrl" target="_blank" class="flex items-center gap-2 px-3 py-2 bg-black/10 hover:bg-black/20 rounded-lg text-sm font-medium transition-colors">
                                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <span class="truncate" x-text="msg.content || 'Descargar archivo'"></span>
                                        </a>
                                    </template>
                                </div>
                            </template>

                            <p x-show="msg.content && msg.messageType !== 'document' && msg.messageType !== 'video'" class="text-[14px] leading-relaxed whitespace-pre-wrap font-medium" x-text="msg.content"></p>

                            {{-- Pie de mensaje --}}
                            <div class="flex items-center justify-end gap-1.5 mt-1">
                                <span class="text-[10px] font-medium text-white/60" x-text="msg.time"></span>
                                
                                {{-- Ticks de lectura (solo outbound) --}}
                                <template x-if="msg.direction === 'outbound' && !msg.isInternalNote">
                                    <div class="flex">
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
            <div class="bg-black/10 border-t border-white/10 px-4 py-3 shrink-0 relative">
                
                {{-- Dropdown de Respuestas Rápidas --}}
                <div x-show="showCannedResponses" class="absolute bottom-full mb-2 left-4 w-96 bg-[#001B3D] border border-white/10 rounded-xl shadow-[0_15px_40px_-10px_rgba(0,0,0,0.7)] z-50 overflow-hidden" style="display: none;">
                    <div class="p-2 bg-[#00122A] border-b border-white/10 text-xs font-bold text-white/60 tracking-wide uppercase">Respuestas Rápidas</div>
                    <ul class="max-h-60 overflow-y-auto custom-scrollbar-cards">
                        <template x-for="cr in filteredCannedResponses" :key="cr.id">
                            <li @click="insertCannedResponse(cr)" class="p-3 hover:bg-white/5 cursor-pointer border-b border-white/5 last:border-0 transition-colors">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-bold text-[#00CEFF] bg-[#00CEFF]/10 px-1.5 py-0.5 rounded border border-[#00CEFF]/20" x-text="'/' + cr.shortcut"></span>
                                    <span class="text-sm font-bold text-white" x-text="cr.title"></span>
                                </div>
                                <p class="text-xs text-white/60 line-clamp-2" x-text="cr.content"></p>
                            </li>
                        </template>
                    </ul>
                </div>

                <div class="flex items-center gap-3">
                    {{-- Input --}}
                    <div class="flex-1 relative group">
                        <input
                            x-ref="messageInput"
                            type="text"
                            x-model="messageInput"
                            @keydown.enter="isInternalNoteMode ? sendInternalNote() : sendMessage()"
                            :placeholder="isInternalNoteMode ? 'Escribe una nota interna para el equipo...' : 'Escribe tu mensaje... (Usa / para respuestas rápidas)'"
                            :class="isInternalNoteMode ? 'bg-[#F59E0B]/10 border-[#F59E0B]/30 text-white focus:ring-[#F59E0B]/30 focus:border-[#F59E0B] placeholder-[#F59E0B]/50' : 'bg-black/30 border-white/10 text-white focus:ring-[#00CEFF]/30 focus:border-[#00CEFF] placeholder-white/40 shadow-inner'"
                            class="w-full px-5 py-3.5 text-sm border rounded-full focus:outline-none focus:ring-2 transition-all pr-12"
                        >
                        {{-- Botón emoji --}}
                        <button class="absolute right-3 top-1/2 -translate-y-1/2 text-white/40 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                    </div>

                    {{-- Estado IA / Agente (Dropdown de Asignación) --}}
                    <div class="relative flex items-center shrink-0" x-data="{ openAssign: false }">
                        {{-- Bot activo: Click para asignar --}}
                        <div @click="openAssign = !openAssign" class="flex items-center gap-2 text-xs cursor-pointer hover:bg-white/5 px-3 py-1.5 border border-transparent hover:border-white/10 rounded-full transition-colors" x-show="selectedConversation.isBotActive" title="Haz clic para asignar">
                            <span class="w-2 h-2 bg-[#10B981] rounded-full animate-pulse-dot shadow-[0_0_5px_#10B981]"></span>
                            <span class="text-[#10B981] font-bold whitespace-nowrap" x-text="selectedConversation.botName + ' respondiendo'"></span>
                            <svg class="w-3 h-3 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                        
                        {{-- Humano activo: Click para reasignar o devolver a bot --}}
                        <div @click="openAssign = !openAssign" class="flex items-center gap-2 text-xs cursor-pointer hover:bg-white/5 px-3 py-1.5 border border-transparent hover:border-white/10 rounded-full transition-colors" x-show="!selectedConversation.isBotActive && selectedConversation.agentName" title="Haz clic para reasignar">
                            <span class="text-white/60 font-medium whitespace-nowrap">Asignado a: <strong class="text-white" x-text="selectedConversation.agentName"></strong></span>
                            <svg class="w-3 h-3 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                        </div>

                        {{-- Dropdown Menu --}}
                        <div x-show="openAssign" @click.away="openAssign = false" class="absolute bottom-full mb-2 right-0 w-52 bg-[#001B3D] border border-white/10 shadow-[0_15px_40px_-10px_rgba(0,0,0,0.7)] rounded-xl overflow-hidden z-50">
                            <div class="p-2 border-b border-white/10 bg-[#00122A]">
                                <span class="text-xs font-bold text-white/60 tracking-wide uppercase">Asignar a...</span>
                            </div>
                            <div class="max-h-48 overflow-y-auto custom-scrollbar-cards">
                                <template x-for="member in teamMembers" :key="member.id">
                                    <button @click="assignAgent(member.id, member.name); openAssign = false" class="w-full text-left px-3 py-2 text-sm hover:bg-white/10 flex items-center gap-2 transition-colors">
                                        <div class="w-6 h-6 rounded-full text-[10px] text-white flex items-center justify-center font-bold shadow-inner" :style="'background-color: ' + member.color" x-text="member.initials"></div>
                                        <span x-text="member.name" class="text-white font-medium"></span>
                                    </button>
                                </template>
                            </div>
                            <div class="p-1 border-t border-white/10 bg-[#00122A]" x-show="!selectedConversation.isBotActive">
                                <button @click="unassignAgent(); openAssign = false" class="w-full text-left px-3 py-2 text-sm hover:bg-rose-500/10 text-rose-400 font-bold flex items-center gap-2 transition-colors rounded-lg">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    Devolver al Bot
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Botón Enviar --}}
                    <button
                        @click="isInternalNoteMode ? sendInternalNote() : sendMessage()"
                        :class="isInternalNoteMode ? 'bg-[#F59E0B] text-[#011B3D] hover:bg-[#FCD34D] shadow-[0_0_15px_rgba(245,158,11,0.4)]' : 'bg-[#00CEFF] text-[#011B3D] hover:bg-[#00E5FF] shadow-[0_0_15px_rgba(0,206,255,0.4)]'"
                        class="px-6 py-3 rounded-full text-sm font-bold flex items-center gap-2 transition-all shrink-0 active:scale-95"
                    >
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                        </svg>
                        <span x-text="isInternalNoteMode ? 'Guardar Nota' : 'Enviar'"></span>
                    </button>
                </div>

                {{-- Acciones rápidas --}}
                <div class="flex items-center gap-2 mt-2 pl-1">
                    {{-- Adjuntar --}}
                    <button class="text-white/40 hover:text-white transition-colors p-1 rounded" title="Adjuntar archivo">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                    </button>
                    {{-- Nota interna --}}
                    <button @click="isInternalNoteMode = !isInternalNoteMode" :class="isInternalNoteMode ? 'text-[#011B3D] bg-[#F59E0B] font-bold' : 'text-white/40 hover:text-[#F59E0B] hover:bg-[#F59E0B]/10'" class="transition-all p-1.5 px-2 rounded-lg flex items-center gap-1" title="Nota interna">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        <span class="text-[11px] font-medium" :class="isInternalNoteMode ? 'font-bold' : ''">Nota Interna</span>
                    </button>
                    {{-- Respuestas rápidas --}}
                    <button class="text-white/40 hover:text-[#00CEFF] hover:bg-[#00CEFF]/10 transition-colors p-1.5 px-2 rounded-lg flex items-center gap-1" title="Respuestas rápidas">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span class="text-[11px] font-medium">Rápidas</span>
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
