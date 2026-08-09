@extends('layouts.app')

@section('title', 'CRM y Ventas — Smart AI Hosting Solutions')

@section('content')
<div class="flex-1 flex flex-col bg-[#F8FAFC] w-full h-full overflow-hidden" x-data="crmApp()">
    {{-- Header de la sección --}}
    <div class="px-8 py-6 border-b border-[#E2E8F0] bg-white shrink-0 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-[#1E293B]">Pipeline de Ventas (CRM)</h1>
            <p class="text-sm text-[#64748B] mt-1">Arrastra y suelta las oportunidades a través de tu embudo. Ingreso proyectado: <span class="text-[#0D9488] font-bold" x-text="'$' + totalValue"></span></p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-[#94A3B8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input
                    type="text"
                    x-model="searchQuery"
                    placeholder="Buscar oportunidad..."
                    class="w-64 pl-10 pr-4 py-2 text-sm bg-[#F8FAFC] border border-[#E2E8F0] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0056D2]/20 focus:border-[#0056D2] transition-all"
                >
            </div>
            <button @click="showModal = true" class="flex items-center gap-2 px-4 py-2 bg-[#0056D2] text-white rounded-lg text-sm font-medium hover:bg-[#0047B3] transition-colors shadow-sm shadow-[#0056D2]/30">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Nueva Oportunidad
            </button>
        </div>
    </div>

    {{-- Kanban Board --}}
    <div class="flex-1 overflow-x-auto overflow-y-hidden p-8 flex gap-6 items-start">
        <template x-for="column in columns" :key="column.id">
            <div class="w-80 shrink-0 flex flex-col max-h-full bg-white rounded-xl border border-[#E2E8F0] shadow-sm"
                 @dragover.prevent="dragOverColumn = column.id"
                 @dragleave="dragOverColumn = null"
                 @drop="onDrop($event, column.id)"
                 :class="dragOverColumn === column.id ? 'bg-[#F1F5F9] border-[#CBD5E1]' : ''">
                {{-- Column Header --}}
                <div class="p-4 border-b border-[#E2E8F0] flex items-center justify-between sticky top-0 bg-white rounded-t-xl z-10">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full" :class="column.colorClass"></div>
                        <h3 class="font-bold text-[#1E293B] text-sm" x-text="column.title"></h3>
                        <span class="px-2 py-0.5 rounded-full bg-[#F1F5F9] text-[#64748B] text-xs font-semibold" x-text="getCardsForColumn(column.id).length"></span>
                    </div>
                    <div class="text-[#0D9488] text-sm font-semibold" x-text="'$' + getColumnTotal(column.id)"></div>
                </div>

                {{-- Column Body (Cards) --}}
                <div class="p-3 flex-1 overflow-y-auto space-y-3 custom-scrollbar">
                    <template x-for="card in getFilteredCardsForColumn(column.id)" :key="card.id">
                        <div class="bg-white border border-[#E2E8F0] p-4 rounded-lg shadow-sm hover:shadow-md hover:border-[#CBD5E1] transition-all cursor-grab group"
                             draggable="true"
                             @dragstart="onDragStart($event, card.id)"
                             @dragend="onDragEnd($event)">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex gap-1 flex-wrap flex-1">
                                    <template x-for="tag in card.tags" :key="tag">
                                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded text-[#0056D2] bg-[#0056D2]/10" x-text="tag"></span>
                                    </template>
                                </div>
                                <button class="text-[#94A3B8] hover:text-[#1E293B] opacity-0 group-hover:opacity-100 transition-opacity p-0.5">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                    </svg>
                                </button>
                            </div>
                            <h4 class="font-bold text-[#1E293B] text-sm mb-1 leading-tight" x-text="card.title"></h4>
                            <p class="text-xs text-[#64748B] mb-3 line-clamp-2" x-text="card.company"></p>
                            
                            <div class="flex items-center justify-between border-t border-[#F1F5F9] pt-3 mt-1">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-[#8B5CF6] to-[#0056D2] text-white flex items-center justify-center text-[10px] font-bold shadow-sm">
                                        <span x-text="card.initials"></span>
                                    </div>
                                    <span class="text-xs font-medium text-[#475569]" x-text="card.contact"></span>
                                </div>
                                <span class="text-sm font-bold text-[#1E293B]" x-text="'$' + card.value"></span>
                            </div>
                        </div>
                    </template>
                    
                    {{-- Drop zone vacía (visual feedback) --}}
                    <div class="h-10 rounded-lg border-2 border-dashed border-[#E2E8F0] flex items-center justify-center text-xs text-[#94A3B8] opacity-50">
                        Soltar aquí
                    </div>
                </div>
            </div>
        </template>
        
        {{-- Añadir columna --}}
        <button class="w-80 shrink-0 h-[57px] rounded-xl border-2 border-dashed border-[#CBD5E1] flex items-center justify-center text-[#64748B] font-medium hover:border-[#0056D2] hover:text-[#0056D2] transition-colors bg-[#F8FAFC] hover:bg-[#0056D2]/5">
            + Añadir Etapa
        </button>
    </div>

    <!-- Modal Nueva Oportunidad -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display: none;">
        <div @click.away="showModal = false" class="bg-white rounded-xl shadow-xl w-[400px] flex flex-col overflow-hidden">
            <div class="p-4 border-b border-[#E2E8F0] flex justify-between items-center">
                <h3 class="font-semibold text-[#1E293B]">Agregar Oportunidad de Venta</h3>
                <button @click="showModal = false" class="text-[#94A3B8] hover:text-[#1E293B]">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-4 space-y-4">
                <div>
                    <label class="block text-xs font-medium text-[#64748B] mb-1">Título de la Oportunidad</label>
                    <input type="text" x-model="newDeal.title" class="w-full px-3 py-2 border border-[#E2E8F0] rounded-lg text-sm focus:outline-none focus:border-[#0056D2]" placeholder="Ej. Renovación Anual">
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#64748B] mb-1">Monto Estimado (USD)</label>
                    <input type="number" x-model="newDeal.value" class="w-full px-3 py-2 border border-[#E2E8F0] rounded-lg text-sm focus:outline-none focus:border-[#0056D2]" placeholder="Ej. 1500">
                </div>
            </div>
            <div class="p-4 border-t border-[#E2E8F0] bg-[#F8FAFC] flex justify-end gap-2">
                <button @click="showModal = false" class="px-4 py-2 text-sm font-medium text-[#64748B] hover:text-[#1E293B]">Cancelar</button>
                <button @click="saveDeal" class="px-4 py-2 bg-[#0056D2] text-white text-sm font-medium rounded-lg hover:bg-[#0047B3]">Crear Oportunidad</button>
            </div>
        </div>
    </div>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #CBD5E1;
    border-radius: 20px;
}
</style>

<script>
function crmApp() {
    return {
        searchQuery: '',
        columns: [
            { id: 'lead', title: 'Nuevos Leads', colorClass: 'bg-[#3B82F6]' },
            { id: 'prospect', title: 'Contactados', colorClass: 'bg-[#F59E0B]' },
            { id: 'negotiation', title: 'En Negociación', colorClass: 'bg-[#8B5CF6]' },
            { id: 'won', title: 'Cerrado Ganado', colorClass: 'bg-[#10B981]' },
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
