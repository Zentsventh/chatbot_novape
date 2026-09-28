<div class="w-[280px] flex flex-col shrink-0 z-10 h-full overflow-hidden border-r border-[#E2E8F0] bg-white">
    {{-- Tabs: Clientes / Equipo --}}
    <div class="px-5 pt-5 pb-0 flex items-center border-b border-[#E2E8F0] gap-6">
        <button @click="listTab = 'clientes'" :class="listTab === 'clientes' ? 'text-[#0056D2] border-b-2 border-[#0056D2] font-semibold' : 'text-[#64748B] hover:text-[#1E293B] border-b-2 border-transparent font-medium'" class="pb-3 text-[14px] transition-colors flex items-center gap-2">
            Clientes 
            <span :class="listTab === 'clientes' ? 'bg-[#0056D2] text-white' : 'bg-[#F1F5F9] text-[#64748B]'" class="px-2 py-0.5 rounded-full text-[11px] font-bold" x-text="conversations.length"></span>
        </button>
        <button @click="listTab = 'equipo'" :class="listTab === 'equipo' ? 'text-[#0056D2] border-b-2 border-[#0056D2] font-semibold' : 'text-[#64748B] hover:text-[#1E293B] border-b-2 border-transparent font-medium'" class="pb-3 text-[14px] transition-colors">
            Equipo
        </button>
    </div>

    {{-- Tabs de Canal (Movidos a la izquierda) --}}
    <div class="px-5 py-4 flex items-center justify-between shrink-0">
        <div class="flex items-center gap-2">
            <button @click="activeChannel = 'whatsapp'" :class="activeChannel === 'whatsapp' ? 'ring-2 ring-offset-2 ring-[#25D366]' : 'opacity-70 hover:opacity-100'" class="w-9 h-9 flex items-center justify-center rounded-full bg-[#25D366] text-white shadow-sm transition-all" title="WhatsApp">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            </button>
            <button @click="activeChannel = 'instagram'" :class="activeChannel === 'instagram' ? 'ring-2 ring-offset-2 ring-[#E1306C]' : 'opacity-70 hover:opacity-100'" class="w-9 h-9 flex items-center justify-center rounded-full bg-gradient-to-tr from-[#FFDC80] via-[#F56040] to-[#833AB4] text-white shadow-sm transition-all" title="Instagram">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
            </button>
            <button @click="activeChannel = 'messenger'" :class="activeChannel === 'messenger' ? 'ring-2 ring-offset-2 ring-[#0084FF]' : 'opacity-70 hover:opacity-100'" class="w-9 h-9 flex items-center justify-center rounded-full bg-[#0084FF] text-white shadow-sm transition-all" title="Messenger">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 4.974 0 11.111c0 3.498 1.744 6.614 4.469 8.654V24l4.088-2.242c1.092.3 2.246.464 3.443.464 6.627 0 12-4.974 12-11.111C24 4.974 18.627 0 12 0zm1.191 14.963l-3.055-3.26-5.963 3.26L10.732 8.2l3.131 3.26 5.886-3.26-6.558 6.763z"/></svg>
            </button>
        </div>
        
        <button class="w-8 h-8 flex items-center justify-center rounded-full bg-[#F1F5F9] text-[#64748B] hover:bg-[#E2E8F0] hover:text-[#1E293B] transition-colors" title="Filtros avanzados">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
        </button>
    </div>

    {{-- Buscador de Chats --}}
    <div class="px-5 pb-4 pt-0">
        <div class="relative group">
            <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-[#94A3B8] transition-colors group-focus-within:text-[#0056D2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" x-model="searchQuery" placeholder="Buscar conversación..." class="w-full pl-10 pr-3 py-2.5 bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl text-[13px] focus:outline-none focus:ring-2 focus:ring-[#0056D2]/20 focus:border-[#0056D2] transition-all placeholder:text-[#94A3B8]">
        </div>
    </div>

    {{-- Lista de Conversaciones --}}
    <div class="flex-1 overflow-y-auto custom-scrollbar px-3 pt-2" x-show="listTab === 'clientes'">
        <template x-for="conv in filteredConversations" :key="conv.id">
            <div class="mb-1">
                <div
                    @click="selectConversation(conv)"
                    :class="[
                        'conversation-item px-3 py-2.5 flex items-center gap-3 transition-all cursor-pointer',
                        selectedConversation && selectedConversation.id === conv.id ? 'bg-[#EFF6FF] border border-[#C4DBF6]' : 'hover:bg-[#F8FAFC] border border-transparent',
                        conv.priority === 'urgent' ? 'border-l-[3px] !border-l-[#EF4444]' : '',
                        conv.priority === 'high' ? 'border-l-[3px] !border-l-[#F59E0B]' : ''
                    ]"
                >
                <div class="relative shrink-0">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-[12px] font-bold" :style="'background-color:' + conv.avatarColor" x-text="conv.initials"></div>
                    <div class="absolute -bottom-0.5 -right-0.5 w-[14px] h-[14px] rounded-full flex items-center justify-center bg-white shadow-sm border border-[#E2E8F0]" x-show="conv.channel">
                        <template x-if="conv.channel === 'whatsapp'">
                            <svg class="w-[10px] h-[10px] text-[#25D366]" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </template>
                        <template x-if="conv.channel === 'instagram'">
                            <svg class="w-[10px] h-[10px] text-[#E1306C]" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </template>
                        <template x-if="conv.channel === 'messenger'">
                            <svg class="w-[10px] h-[10px] text-[#0084FF]" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 4.974 0 11.111c0 3.498 1.744 6.614 4.469 8.654V24l4.088-2.242c1.092.3 2.246.464 3.443.464 6.627 0 12-4.974 12-11.111C24 4.974 18.627 0 12 0zm1.191 14.963l-3.055-3.26-5.963 3.26L10.732 8.2l3.131 3.26 5.886-3.26-6.558 6.763z"/></svg>
                        </template>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <span class="text-[14px] font-bold text-[#1E293B] truncate" x-text="conv.contactName"></span>
                        <span class="text-[11px] font-medium text-[#94A3B8] shrink-0 ml-2" x-text="conv.lastMessageTime"></span>
                    </div>
                    <p class="text-[13px] text-[#64748B] truncate mt-0.5 font-medium" x-text="conv.lastMessagePreview"></p>
                    <div class="flex items-center gap-1.5 mt-2" x-show="conv.tags && conv.tags.length > 0">
                        <template x-for="tag in (conv.tags || [])" :key="tag.name">
                            <span class="tag text-[9px] uppercase font-bold tracking-wider border rounded-full px-2 py-0.5" :style="'background-color:' + tag.color + '12; color:' + tag.color + '; border-color:' + tag.color + '25'" x-text="tag.name"></span>
                        </template>
                    </div>
                </div>
                <div x-show="conv.unreadCount > 0" class="shrink-0">
                    <span class="badge text-white font-bold px-2 py-1 rounded-full text-[11px] shadow-sm" style="background: linear-gradient(135deg, #0665E0 0%, #00CEFF 100%);" x-text="conv.unreadCount"></span>
                </div>
            </div>
            </div>
        </template>
    </div>

    {{-- Tab Equipo --}}
    <div class="flex-1 overflow-y-auto custom-scrollbar p-3" x-show="listTab === 'equipo'">
        <template x-for="member in teamMembers" :key="member.id">
            <div class="flex items-center gap-3 py-2 px-2 rounded-xl hover:bg-[#F8FAFC] cursor-pointer transition-colors">
                <div class="relative">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-[10px] font-bold" :style="'background-color:' + member.color" x-text="member.initials"></div>
                    <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full border-[1.5px] border-white" :class="member.isOnline ? 'bg-[#10B981]' : 'bg-[#94A3B8]'"></div>
                </div>
                <div class="flex-1 min-w-0">
                    <span class="text-[13px] font-semibold text-[#1E293B] truncate block" x-text="member.name"></span>
                    <span class="text-[9px] text-[#94A3B8] uppercase tracking-widest" x-text="member.role"></span>
                </div>
                <span class="text-[9px] text-[#5BA3E6] font-bold bg-[#EFF6FF] px-1.5 py-0.5 rounded" x-show="member.activeChats > 0" x-text="member.activeChats + ' chats'"></span>
            </div>
        </template>
    </div>
</div>
