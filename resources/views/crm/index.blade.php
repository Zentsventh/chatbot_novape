@extends('layouts.app')

@section('title', 'CRM y Ventas — Smart AI Hosting Solutions')

@section('content')
<div class="flex-1 flex flex-col bg-transparent w-full h-full overflow-hidden" x-data="crmApp()">
    {{-- Header de la sección --}}
    <div class="px-8 py-6 border-b border-white/10 bg-[#00122A]/50 backdrop-blur-md shrink-0 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Pipeline de Ventas (CRM)</h1>
            <p class="text-sm text-white/60 mt-1">Arrastra y suelta las oportunidades a través de tu embudo. Ingreso proyectado: <span class="text-[#00CEFF] font-bold shadow-[0_0_10px_rgba(0,206,255,0.3)]" x-text="'$' + totalValue"></span></p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input
                    type="text"
                    x-model="searchQuery"
                    placeholder="Buscar oportunidad..."
                    class="w-64 pl-10 pr-4 py-2 text-sm bg-black/20 border border-white/10 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#00CEFF] focus:border-[#00CEFF] text-white placeholder-white/50 transition-all shadow-inner"
                >
            </div>
            <button @click="showModal = true" class="flex items-center gap-2 px-4 py-2 bg-[#00CEFF] text-[#011B3D] rounded-lg text-sm font-bold hover:shadow-[0_0_15px_rgba(0,206,255,0.6)] hover:bg-[#00E5FF] transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Nueva Oportunidad
            </button>
        </div>
    </div>

    {{-- Kanban Board --}}
    <div class="flex-1 overflow-x-auto overflow-y-hidden p-8 flex gap-6 items-start custom-scrollbar">
        <template x-for="column in columns" :key="column.id">
            <div class="w-80 shrink-0 flex flex-col max-h-full bg-[#002B6A]/40 backdrop-blur-md rounded-xl border border-white/10 shadow-[0_15px_30px_-10px_rgba(0,0,0,0.5)]"
                 @dragover.prevent="dragOverColumn = column.id"
                 @dragleave="dragOverColumn = null"
                 @drop="onDrop($event, column.id)"
                 :class="dragOverColumn === column.id ? 'bg-[#0665E0]/20 border-[#00CEFF]' : ''">
                {{-- Column Header --}}
                <div class="p-4 border-b border-white/10 flex items-center justify-between sticky top-0 bg-[#00122A]/80 backdrop-blur-md rounded-t-xl z-10">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full shadow-[0_0_8px_currentColor]" :class="column.colorClass"></div>
                        <h3 class="font-bold text-white text-sm" x-text="column.title"></h3>
                        <span class="px-2 py-0.5 rounded-full bg-white/10 text-white/80 text-xs font-semibold border border-white/5" x-text="getCardsForColumn(column.id).length"></span>
                    </div>
                    <div class="text-[#00CEFF] text-sm font-semibold" x-text="'$' + getColumnTotal(column.id)"></div>
                </div>

                {{-- Column Body (Cards) --}}
                <div class="p-3 flex-1 overflow-y-auto space-y-3 custom-scrollbar-cards">
                    <template x-for="card in getFilteredCardsForColumn(column.id)" :key="card.id">
                        <div class="bg-gradient-to-b from-[#0665E0]/40 to-[#02449E]/40 backdrop-blur-md border border-white/10 p-4 rounded-lg shadow-sm hover:shadow-[0_10px_25px_-5px_rgba(0,0,0,0.5)] hover:border-[#00CEFF]/50 transition-all cursor-grab group"
                             draggable="true"
                             @dragstart="onDragStart($event, card.id)"
                             @dragend="onDragEnd($event)">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex gap-1 flex-wrap flex-1">
                                    <template x-for="tag in card.tags" :key="tag.name">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded text-[#00CEFF] bg-[#00CEFF]/10 border border-[#00CEFF]/20 tracking-wide uppercase" x-text="tag.name"></span>
                                    </template>
                                </div>
                                <button class="text-white/40 hover:text-white opacity-0 group-hover:opacity-100 transition-opacity p-0.5">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                    </svg>
                                </button>
                            </div>
                            <h4 class="font-bold text-white text-sm mb-1 leading-tight" x-text="card.title"></h4>
                            <p class="text-xs text-white/60 mb-3 line-clamp-2" x-text="card.company"></p>
                            
                            <div class="flex items-center justify-between border-t border-white/10 pt-3 mt-1">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-[#002B6A] border border-white/20 text-white flex items-center justify-center text-[10px] font-bold shadow-inner">
                                        <span x-text="card.initials"></span>
                                    </div>
                                    <span class="text-xs font-medium text-white/80" x-text="card.contact"></span>
                                </div>
                                <span class="text-sm font-bold text-[#00CEFF]" x-text="'$' + card.value"></span>
                            </div>
                        </div>
                    </template>
                    
                    {{-- Drop zone vacía (visual feedback) --}}
                    <div class="h-10 rounded-lg border-2 border-dashed border-white/10 flex items-center justify-center text-xs text-white/40">
                        Soltar aquí
                    </div>
                </div>
            </div>
        </template>
        
        {{-- Añadir columna --}}
        <button class="w-80 shrink-0 h-[57px] rounded-xl border-2 border-dashed border-white/20 flex items-center justify-center text-white/60 font-medium hover:border-[#00CEFF] hover:text-[#00CEFF] transition-colors bg-white/5 hover:bg-[#00CEFF]/5">
            + Añadir Etapa
        </button>
    </div>

    <!-- Modal Nueva Oportunidad -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm" style="display: none;">
        <div @click.away="showModal = false" class="bg-gradient-to-b from-[#0665E0]/90 to-[#02449E]/90 backdrop-blur-xl rounded-xl shadow-[0_30px_60px_-15px_rgba(0,0,0,0.7)] w-[400px] flex flex-col overflow-hidden border border-white/10">
            <div class="p-4 border-b border-white/10 flex justify-between items-center bg-[#011B3D]/50">
                <h3 class="font-semibold text-white">Agregar Oportunidad de Venta</h3>
                <button @click="showModal = false" class="text-white/50 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-white/80 mb-1.5 tracking-wide uppercase">Título de la Oportunidad</label>
                    <input type="text" x-model="newDeal.title" class="w-full px-3 py-2 border border-white/20 bg-black/20 rounded-lg text-sm text-white focus:outline-none focus:border-[#00CEFF] focus:ring-1 focus:ring-[#00CEFF]" placeholder="Ej. Renovación Anual">
                </div>
                <div>
                    <label class="block text-xs font-bold text-white/80 mb-1.5 tracking-wide uppercase">Monto Estimado (USD)</label>
                    <input type="number" x-model="newDeal.value" class="w-full px-3 py-2 border border-white/20 bg-black/20 rounded-lg text-sm text-white focus:outline-none focus:border-[#00CEFF] focus:ring-1 focus:ring-[#00CEFF]" placeholder="Ej. 1500">
                </div>
            </div>
            <div class="p-4 border-t border-white/10 bg-[#011B3D]/50 flex justify-end gap-2">
                <button @click="showModal = false" class="px-4 py-2 text-sm font-medium text-white/60 hover:text-white transition-colors">Cancelar</button>
                <button @click="saveDeal" class="px-4 py-2 bg-[#00CEFF] text-[#011B3D] text-sm font-bold rounded-lg hover:bg-[#00E5FF] hover:shadow-[0_0_15px_rgba(0,206,255,0.5)] transition-all">Crear Oportunidad</button>
            </div>
        </div>
    </div>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar, .custom-scrollbar-cards::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track, .custom-scrollbar-cards::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.05);
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb, .custom-scrollbar-cards::-webkit-scrollbar-thumb {
    background-color: rgba(255,255,255,0.2);
    border-radius: 20px;
}
.custom-scrollbar-thumb:hover, .custom-scrollbar-cards::-webkit-scrollbar-thumb:hover {
    background-color: rgba(255,255,255,0.3);
}
</style>

<script>
function crmApp() {
    return {
        searchQuery: '',
        columns: [
            { id: 'lead', title: 'Nuevos Leads', colorClass: 'text-[#00CEFF]' },
            { id: 'prospect', title: 'Contactados', colorClass: 'text-[#F59E0B]' },
            { id: 'negotiation', title: 'En Negociación', colorClass: 'text-[#8B5CF6]' },
            { id: 'won', title: 'Cerrado Ganado', colorClass: 'text-[#10B981]' },
        ],
        cards: [],
        isLoading: true,
        draggedCardId: null,
        dragOverColumn: null,
        showModal: false,
        newDeal: {
            title: '', value: '', company: ''
        },
        init() {
            this.fetchDeals();
        },
        fetchDeals() {
            this.isLoading = true;
            fetch('/api/deals')
                .then(res => res.json())
                .then(data => {
                    this.cards = data;
                    this.isLoading = false;
                });
        },
        onDragStart(e, cardId) {
            this.draggedCardId = cardId;
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', cardId);
            setTimeout(() => e.target.classList.add('opacity-50'), 0);
        },
        onDragEnd(e) {
            e.target.classList.remove('opacity-50');
            this.draggedCardId = null;
            this.dragOverColumn = null;
        },
        onDrop(e, columnId) {
            this.dragOverColumn = null;
            if (this.draggedCardId) {
                // Optimistic UI update
                let card = this.cards.find(c => c.id === this.draggedCardId);
                if (card && card.columnId !== columnId) {
                    card.columnId = columnId;
                    
                    // Backend sync
                    fetch(`/api/deals/${card.id}/stage`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ stage: columnId })
                    });
                }
            }
        },
        saveDeal() {
            fetch('/api/deals', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(this.newDeal)
            }).then(res => res.json())
              .then(data => {
                  if(data.success) {
                      this.showModal = false;
                      this.fetchDeals();
                      this.newDeal = { title: '', value: '', company: '' };
                  }
              });
        },
        get totalValue() {
            return this.cards.reduce((acc, card) => acc + card.value, 0).toLocaleString();
        },
        getColumnTotal(columnId) {
            return this.getCardsForColumn(columnId).reduce((acc, card) => acc + card.value, 0).toLocaleString();
        },
        getCardsForColumn(columnId) {
            return this.cards.filter(c => c.columnId === columnId);
        },
        getFilteredCardsForColumn(columnId) {
            let columnCards = this.getCardsForColumn(columnId);
            if (this.searchQuery !== '') {
                const q = this.searchQuery.toLowerCase();
                columnCards = columnCards.filter(c => 
                    c.title.toLowerCase().includes(q) || 
                    c.company.toLowerCase().includes(q) ||
                    c.contact.toLowerCase().includes(q)
                );
            }
            return columnCards;
        }
    }
}
</script>
@endsection
