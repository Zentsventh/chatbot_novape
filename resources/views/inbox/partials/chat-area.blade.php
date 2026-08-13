{{-- Área de Chat Central — Dentro del contenedor blanco --}}
<div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
    {{-- Cabecera Superior del Chat --}}
    <div class="border-b border-[#F1F5F9] px-5 py-3 flex items-center justify-end shrink-0 h-[56px] bg-white">
        <button class="flex items-center gap-1.5 px-3 py-1.5 text-[13px] text-text-muted hover:text-main hover:bg-[#F1F5F9] rounded-full transition-colors">
            <svg class="w-[14px] h-[14px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            Actividades
        </button>
    </div>

    {{-- Estado vacío --}}
    <template x-if="!selectedConversation">
        <div x-show="!selectedConversation" class="flex-1 flex flex-col items-center justify-center bg-[#F8FAFC]">
            <div class="w-24 h-24 bg-white rounded-[32px] flex items-center justify-center mb-6 shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-[#E2E8F0]">
                <svg class="w-10 h-10 text-corp" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </div>
            <h3 class="text-[20px] font-bold text-[#1E293B] mb-2">Selecciona una conversación</h3>
            <p class="text-[14px] text-[#64748B]">Elige un cliente de la lista para ver sus mensajes</p>
        </div>
    </template>

    {{-- Chat activo --}}
    <template x-if="selectedConversation">
        <div class="flex-1 flex flex-col min-h-0">
            <div class="px-4 py-2 text-center" x-show="selectedConversation.leadSource">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-[#ECFDF5] border border-[#A7F3D0] text-[#059669] text-xs font-bold rounded-full animate-fade-in">
                    <span class="w-1.5 h-1.5 bg-[#10B981] rounded-full animate-pulse-dot"></span>
                    <span x-text="selectedConversation.leadSource"></span>
                </div>
            </div>

            {{-- Mensajes --}}
            <div class="flex-1 overflow-y-auto custom-scrollbar px-5 py-4 space-y-3 bg-[#FAFCFE]" id="chat-messages">
                <template x-for="msg in selectedConversation.messages" :key="msg.id">
                    <div>
                        <template x-if="msg.isInternalNote">
                            <div class="flex justify-center animate-fade-in">
                                <div class="bg-[#FFFBEB] border border-[#FDE68A] text-[#92400E] px-4 py-2 rounded-xl">
                                    <div class="flex items-center gap-1.5 mb-0.5">
                                        <svg class="w-3 h-3 text-[#F59E0B]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                        <span class="text-[10px] font-bold text-[#B45309]" x-text="msg.agentName"></span>
                                    </div>
                                    <p class="text-[13px] text-[#78350F]" x-text="msg.content"></p>
                                </div>
                            </div>
                        </template>
                        <template x-if="msg.isSystemEvent">
                            <div class="flex justify-center animate-fade-in">
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-[#F1F5F9] text-[#64748B] text-[11px] rounded-full">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    <span class="font-medium" x-text="msg.content"></span>
                                </div>
                            </div>
                        </template>
                        <div class="flex gap-2 w-full" :class="msg.direction === 'outbound' ? 'flex-row-reverse' : 'flex-row'">
                            <div :class="[
                                    'relative px-5 py-3 group transition-all',
                                    msg.isInternalNote ? 'bg-[#FFFBEB] border border-[#FDE68A] text-[#92400E]' : 
                                    (msg.direction === 'outbound' ? 'text-white shadow-[0_4px_16px_rgba(6,101,224,0.15)]' : 'bg-white border border-[#E2E8F0] text-[#1E293B] shadow-sm'),
                                    msg.direction === 'outbound' ? 'rounded-[20px] rounded-br-[6px]' : 'rounded-[20px] rounded-bl-[6px]',
                                ]"
                                :style="msg.direction === 'outbound' && !msg.isInternalNote ? 'background: linear-gradient(135deg, #0665E0 0%, #00CEFF 100%)' : ''"
                                class="max-w-[85%] sm:max-w-[75%]"
                            >
                                <div x-show="msg.isInternalNote" class="text-[9px] uppercase font-bold text-[#B45309] mb-0.5 flex items-center gap-1">
                                    <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8V7a4 4 0 00-8 0v4h8z" /></svg>
                                    Solo Interno
                                </div>
                                <template x-if="msg.mediaUrl">
                                    <div class="mb-2 overflow-hidden rounded-lg">
                                        <template x-if="msg.messageType === 'image' || msg.messageType === 'sticker'"><img :src="msg.mediaUrl" class="max-w-full h-auto max-h-60 object-contain rounded-lg cursor-pointer" alt="Imagen"></template>
                                        <template x-if="msg.messageType === 'audio'"><audio controls :src="msg.mediaUrl" class="w-full max-w-[240px] h-9"></audio></template>
                                        <template x-if="msg.messageType === 'document' || msg.messageType === 'video'"><a :href="msg.mediaUrl" target="_blank" class="flex items-center gap-2 px-3 py-2 bg-black/5 hover:bg-black/10 rounded-lg text-[13px] font-medium transition-colors"><svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg><span class="truncate" x-text="msg.content || 'Descargar archivo'"></span></a></template>
                                    </div>
                                </template>
                                <p x-show="msg.content && msg.messageType !== 'document' && msg.messageType !== 'video'" class="text-[14px] leading-relaxed whitespace-pre-wrap font-medium" x-text="msg.content"></p>
                                <div class="flex items-center justify-end gap-1.5 mt-1.5">
                                    <span class="text-[10px]" :class="msg.direction === 'outbound' && !msg.isInternalNote ? 'text-white/65' : 'text-[#94A3B8]'" x-text="msg.time"></span>
                                    <template x-if="msg.direction === 'outbound' && !msg.isInternalNote">
                                        <div class="flex">
                                            <template x-if="msg.status === 'read'"><svg class="w-4 h-3 text-white/85" viewBox="0 0 24 14" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 7l5 5L18 1" stroke-linecap="round" stroke-linejoin="round"/><path d="M7 7l5 5L24 1" stroke-linecap="round" stroke-linejoin="round"/></svg></template>
                                            <template x-if="msg.status === 'delivered'"><svg class="w-4 h-3 text-white/55" viewBox="0 0 24 14" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 7l5 5L18 1" stroke-linecap="round" stroke-linejoin="round"/><path d="M7 7l5 5L24 1" stroke-linecap="round" stroke-linejoin="round"/></svg></template>
                                            <template x-if="msg.status === 'sent'"><svg class="w-3 h-3 text-white/55" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 7l5 5L13 1" stroke-linecap="round" stroke-linejoin="round"/></svg></template>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Barra de escritura --}}
            <div class="border-t border-[#F1F5F9] px-6 py-4 shrink-0 relative bg-white">
                <div x-show="showCannedResponses" class="absolute bottom-full mb-3 left-6 w-[400px] bg-white border border-[#E2E8F0] rounded-2xl shadow-[0_12px_40px_rgba(0,40,100,0.08)] z-50 overflow-hidden" style="display: none;">
                    <div class="p-3 bg-[#FAFCFE] border-b border-[#F1F5F9] text-[11px] font-bold text-[#64748B] tracking-wide uppercase">Respuestas Rápidas</div>
                    <ul class="max-h-56 overflow-y-auto custom-scrollbar">
                        <template x-for="cr in filteredCannedResponses" :key="cr.id">
                            <li @click="insertCannedResponse(cr)" class="p-4 hover:bg-[#F8FAFC] cursor-pointer border-b border-[#F1F5F9] last:border-0 transition-colors">
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="text-[10px] font-bold text-white bg-corp px-2 py-0.5 rounded-md" x-text="'/' + cr.shortcut"></span>
                                    <span class="text-[14px] font-bold text-[#1E293B]" x-text="cr.title"></span>
                                </div>
                                <p class="text-[12px] text-[#64748B] line-clamp-2" x-text="cr.content"></p>
                            </li>
                        </template>
                    </ul>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex-1 relative">
                        <input x-ref="messageInput" type="text" x-model="messageInput" @keydown.enter="isInternalNoteMode ? sendInternalNote() : sendMessage()"
                            :placeholder="isInternalNoteMode ? 'Escribe una nota interna...' : 'Escribe tu mensaje... (/ para rápidas)'"
                            :class="isInternalNoteMode ? 'bg-[#FFFBEB] border-[#FDE68A] text-[#92400E] focus:border-[#F59E0B] placeholder-[#B45309]/40' : ''"
                            class="w-full px-5 py-3.5 text-[14px] input-corp pr-12"
                        >
                        <button class="absolute right-4 top-1/2 -translate-y-1/2 text-[#94A3B8] hover:text-corp transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </button>
                    </div>
                    <div class="relative flex items-center shrink-0" x-data="{ openAssign: false }">
                        <div @click="openAssign = !openAssign" class="flex items-center gap-1.5 text-[11px] cursor-pointer hover:bg-[#F1F5F9] px-2.5 py-1.5 rounded-full transition-colors" x-show="selectedConversation.isBotActive">
                            <span class="w-1.5 h-1.5 bg-[#10B981] rounded-full animate-pulse-dot"></span>
                            <span class="text-[#059669] font-bold whitespace-nowrap" x-text="selectedConversation.botName + ' respondiendo'"></span>
                            <svg class="w-2.5 h-2.5 text-[#94A3B8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                        <div @click="openAssign = !openAssign" class="flex items-center gap-1.5 text-[11px] cursor-pointer hover:bg-[#F1F5F9] px-2.5 py-1.5 rounded-full transition-colors" x-show="!selectedConversation.isBotActive && selectedConversation.agentName">
                            <span class="text-[#64748B] whitespace-nowrap">Asignado a: <strong class="text-[#1E293B]" x-text="selectedConversation.agentName"></strong></span>
                            <svg class="w-2.5 h-2.5 text-[#94A3B8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                        <div x-show="openAssign" @click.away="openAssign = false" class="absolute bottom-full mb-2 right-0 w-48 bg-white border border-[#E2E8F0] shadow-[0_8px_24px_rgba(0,40,100,0.12)] rounded-xl overflow-hidden z-50">
                            <div class="p-2 border-b border-[#E2E8F0] bg-[#F8FAFC]"><span class="text-[10px] font-bold text-[#64748B] uppercase tracking-wide">Asignar a...</span></div>
                            <div class="max-h-40 overflow-y-auto">
                                <template x-for="member in teamMembers" :key="member.id">
                                    <button @click="assignAgent(member.id, member.name); openAssign = false" class="w-full text-left px-3 py-2 text-[13px] hover:bg-[#F8FAFC] flex items-center gap-2 transition-colors">
                                        <div class="w-5 h-5 rounded-full text-[8px] text-white flex items-center justify-center font-bold" :style="'background-color: ' + member.color" x-text="member.initials"></div>
                                        <span x-text="member.name" class="text-[#1E293B] font-medium"></span>
                                    </button>
                                </template>
                            </div>
                            <div class="p-1 border-t border-[#E2E8F0]" x-show="!selectedConversation.isBotActive">
                                <button @click="unassignAgent(); openAssign = false" class="w-full text-left px-3 py-1.5 text-[12px] hover:bg-red-50 text-[#EF4444] font-bold flex items-center gap-1.5 transition-colors rounded-lg">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    Devolver al Bot
                                </button>
                            </div>
                        </div>
                    </div>
                    <button @click="isInternalNoteMode ? sendInternalNote() : sendMessage()"
                        :class="isInternalNoteMode ? 'bg-[#F59E0B] hover:bg-[#D97706]' : 'btn-cyan shadow-[0_4px_16px_rgba(0,206,255,0.4)] hover:shadow-[0_6px_24px_rgba(0,206,255,0.6)]'"
                        class="px-6 py-3.5 rounded-2xl text-[14px] text-[#00122A] font-bold flex items-center gap-2 transition-all shrink-0 active:scale-95"
                    >
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                        <span x-text="isInternalNoteMode ? 'Nota' : 'Enviar'"></span>
                    </button>
                </div>
                <div class="flex items-center gap-2 mt-3 pl-1">
                    <button class="text-[#94A3B8] hover:text-corp transition-colors p-1.5 rounded-lg hover:bg-[#F8FAFC]" title="Adjuntar"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg></button>
                    <button @click="isInternalNoteMode = !isInternalNoteMode" :class="isInternalNoteMode ? 'text-white bg-[#F59E0B]' : 'text-[#94A3B8] hover:text-[#F59E0B] hover:bg-[#FFFBEB]'" class="transition-all p-1.5 px-3 rounded-xl flex items-center gap-1.5 text-[12px] font-bold"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>Nota Interna</button>
                    <button class="text-[#94A3B8] hover:text-[#0665E0] hover:bg-[#EFF6FF] transition-colors p-1.5 px-3 rounded-xl flex items-center gap-1.5 text-[12px] font-bold"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>Respuestas Rápidas</button>
                </div>
            </div>
        </div>
    </template>
</div>
