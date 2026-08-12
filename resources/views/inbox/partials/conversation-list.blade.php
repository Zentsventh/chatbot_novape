{{-- Panel de Lista de Conversaciones --}}
<div class="w-[300px] bg-[#011B3D]/80 backdrop-blur-xl border border-white/20 rounded-[32px] flex flex-col shrink-0 z-10 shadow-[0_15px_40px_-10px_rgba(0,0,0,0.8)] h-full overflow-hidden py-4">
    {{-- Tabs: Clientes / Equipo --}}
    <div class="px-4 pt-3 pb-0">
        <div class="flex items-center gap-4 mb-3">
            <button
                @click="listTab = 'clientes'"
                :class="listTab === 'clientes' ? 'text-white font-bold' : 'text-white/50 hover:text-white/80'"
                class="text-sm transition-colors flex items-center gap-1.5"
            >
                Clientes
                <span class="badge bg-[#00CEFF] text-[#011B3D]" x-text="conversations.length"></span>
            </button>
            <button
                @click="listTab = 'equipo'"
                :class="listTab === 'equipo' ? 'text-white font-bold' : 'text-white/50 hover:text-white/80'"
                class="text-sm transition-colors"
            >
                Equipo
            </button>
        </div>
    </div>

    {{-- Buscador --}}
    <div class="px-4 mb-2">
        <div class="relative group">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-white/40 group-hover:text-white/70 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input
                type="text"
                placeholder="Buscar en chats..."
                x-model="searchQuery"
                class="w-full pl-9 pr-4 py-2 text-sm bg-black/20 border border-white/10 rounded-xl focus:outline-none focus:ring-1 focus:ring-[#00CEFF] focus:border-[#00CEFF] text-white placeholder-white/50 transition-all hover:bg-black/40 shadow-inner"
            >
        </div>
    </div>

    {{-- Lista de Conversaciones --}}
    <div class="flex-1 overflow-y-auto custom-scrollbar" x-show="listTab === 'clientes'">
        <template x-for="conv in filteredConversations" :key="conv.id">
            <div class="px-2 mb-1">
                <div
                    @click="selectConversation(conv)"
                    :class="[
                        'conversation-item px-3 py-3 rounded-xl flex items-center gap-3 transition-all cursor-pointer',
                        selectedConversation && selectedConversation.id === conv.id ? 'bg-gradient-to-r from-[#0665E0]/40 to-[#02449E]/40 shadow-sm border border-[#00CEFF]/30 ring-1 ring-[#00CEFF]/20' : 'hover:bg-white/10 border border-transparent hover:border-white/5',
                        conv.priority === 'urgent' ? 'border-l-4 border-l-[#EF4444]' : '',
                        conv.priority === 'high' ? 'border-l-4 border-l-[#F59E0B]' : ''
                    ]"
                >
                {{-- Avatar --}}
                <div class="relative shrink-0">
                    <div
                        class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-inner"
                        :style="'background-color:' + conv.avatarColor"
                        x-text="conv.initials"
                    ></div>
                    {{-- Indicador de canal --}}
                    <div
                        class="absolute -bottom-0.5 -right-0.5 w-4 h-4 rounded-full flex items-center justify-center bg-[#011B3D] shadow-sm"
                        x-show="conv.channel"
                    >
                        <template x-if="conv.channel === 'whatsapp'">
                            <svg class="w-3 h-3 text-[#25D366]" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        </template>
                        <template x-if="conv.channel === 'instagram'">
                            <svg class="w-3 h-3 text-[#E1306C]" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                            </svg>
                        </template>
                        <template x-if="conv.channel === 'messenger'">
                            <svg class="w-3 h-3 text-[#0084FF]" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 0C5.373 0 0 4.974 0 11.111c0 3.498 1.744 6.614 4.469 8.654V24l4.088-2.242c1.092.3 2.246.464 3.443.464 6.627 0 12-4.974 12-11.111C24 4.974 18.627 0 12 0zm1.191 14.963l-3.055-3.26-5.963 3.26L10.732 8.2l3.131 3.26 5.886-3.26-6.558 6.763z"/>
                            </svg>
                        </template>
                    </div>
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-white truncate" x-text="conv.contactName"></span>
                        <span class="text-[10px] font-medium text-white/50 shrink-0 ml-2" x-text="conv.lastMessageTime"></span>
                    </div>
                    <p class="text-xs text-white/60 truncate mt-0.5" x-text="conv.lastMessagePreview"></p>
                    {{-- Tags --}}
                    <div class="flex items-center gap-1 mt-1" x-show="conv.tags && conv.tags.length > 0">
                        <template x-for="tag in (conv.tags || [])" :key="tag.name">
                            <span class="tag text-[9px] uppercase font-bold tracking-wide border" :style="'background-color:' + tag.color + '20; color:' + tag.color + '; border-color:' + tag.color + '40'" x-text="tag.name"></span>
                        </template>
                    </div>
                </div>

                {{-- Badge no leídos --}}
                <div x-show="conv.unreadCount > 0" class="shrink-0">
                    <span class="badge bg-[#00CEFF] text-[#011B3D] shadow-[0_0_8px_rgba(0,206,255,0.6)] font-bold" x-text="conv.unreadCount"></span>
                </div>
            </div>
            </div>
        </template>
    </div>

    {{-- Tab Equipo --}}
    <div class="flex-1 overflow-y-auto custom-scrollbar p-4" x-show="listTab === 'equipo'">
        <template x-for="member in teamMembers" :key="member.id">
            <div class="flex items-center gap-3 py-2.5 px-2 rounded-lg hover:bg-white/10 cursor-pointer transition-colors border border-transparent hover:border-white/5">
                <div class="relative">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-inner" :style="'background-color:' + member.color" x-text="member.initials"></div>
                    <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full border-2 border-[#00122A]" :class="member.isOnline ? 'bg-[#10B981] shadow-[0_0_5px_#10B981]' : 'bg-[#94A3B8]'"></div>
                </div>
                <div class="flex-1 min-w-0">
                    <span class="text-sm font-bold text-white truncate block" x-text="member.name"></span>
                    <span class="text-[10px] text-white/50 uppercase tracking-widest" x-text="member.role"></span>
                </div>
                <span class="text-[10px] text-[#00CEFF] font-bold bg-[#00CEFF]/10 px-1.5 py-0.5 rounded" x-show="member.activeChats > 0" x-text="member.activeChats + ' chats'"></span>
            </div>
        </template>
    </div>
</div>
