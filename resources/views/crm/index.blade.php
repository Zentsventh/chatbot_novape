@extends('layouts.app')

@section('title', 'CRM y Ventas — Smart AI Hosting Solutions')

@section('content')
<div class="flex-1 flex flex-col w-full h-full overflow-hidden bg-[#FAFCFE]" x-data="crmApp()">
    {{-- Header de la sección --}}
    <div class="px-8 py-5 border-b border-[#F1F5F9] shrink-0 flex items-center justify-between bg-white z-10">
        <div>
            <h1 class="text-[18px] font-bold text-main tracking-tight">Pipeline de Ventas (CRM)</h1>
            <p class="text-[13px] text-text-muted mt-0.5">Arrastra y suelta las oportunidades. Ingreso proyectado: <span class="text-corp font-bold" x-text="formatCurrency(totalValue)"></span></p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input
                    type="text"
                    x-model="searchQuery"
                    placeholder="Buscar oportunidad..."
                    class="w-64 pl-9 pr-4 py-2 text-[13px] input-corp"
                >
            </div>
            <button @click="showModal = true" class="flex items-center gap-1.5 px-4 py-2 bg-corp text-white rounded-xl text-[13px] font-bold hover:bg-[#002052] transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Nueva Oportunidad
            </button>
        </div>
    </div>

    {{-- Kanban Board --}}
    <div class="flex-1 overflow-x-auto overflow-y-hidden p-6 flex gap-5 items-start custom-scrollbar">
        <template x-for="column in columns" :key="column.id">
            <div class="w-80 shrink-0 flex flex-col max-h-full bg-white rounded-[20px] border border-[#E2E8F0] shadow-sm"
                 @dragover.prevent="dragOverColumn = column.id"
                 @dragleave="dragOverColumn = null"
                 @drop="onDrop($event, column.id)"
                 :class="dragOverColumn === column.id ? 'bg-[#F8FAFC] border-corp ring-2 ring-corp/20' : ''">
                {{-- Column Header --}}
                <div class="p-4 border-b border-[#F1F5F9] flex items-center justify-between sticky top-0 bg-white rounded-t-[20px] z-10">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full" :class="column.colorClass"></div>
                        <h3 class="font-bold text-[#1E293B] text-[13px]" x-text="column.title"></h3>
                        <span class="px-2 py-0.5 rounded-full bg-[#F1F5F9] text-text-muted text-[11px] font-bold" x-text="getCardsForColumn(column.id).length"></span>
                    </div>
                    <div class="text-corp text-[13px] font-bold" x-text="formatCurrency(getColumnTotal(column.id))"></div>
                </div>

                {{-- Column Body (Cards) --}}
                <div class="p-3 flex-1 overflow-y-auto space-y-3 custom-scrollbar">
                    <template x-for="card in getFilteredCardsForColumn(column.id)" :key="card.id">
                        <div class="bg-white border border-[#E2E8F0] p-4 rounded-2xl shadow-[0_4px_12px_rgba(0,0,0,0.03)] hover:shadow-[0_8px_24px_rgba(0,0,0,0.08)] hover:border-corp/30 transition-all cursor-grab group relative"
                             draggable="true"
                             @dragstart="onDragStart($event, card.id)"
                             @dragend="onDragEnd($event)">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex gap-1.5 flex-wrap flex-1">
                                    <template x-for="tag in card.tags" :key="tag.name">
                                        <span class="text-[9px] font-bold px-2 py-0.5 rounded uppercase tracking-wide" :style="'background-color:' + tag.color + '15; color:' + tag.color" x-text="tag.name"></span>
                                    </template>
                                </div>
                                <button class="text-[#CBD5E1] hover:text-red-500 opacity-0 group-hover:opacity-100 transition-opacity p-0.5 absolute top-3 right-3">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                            <h4 class="font-bold text-main text-[13px] mb-1 leading-snug pr-4" x-text="card.title"></h4>
                            <p class="text-[11px] text-[#64748B] mb-3 line-clamp-2" x-text="card.company"></p>
                            
                            <div class="flex items-center justify-between border-t border-[#F1F5F9] pt-3 mt-1">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-corp text-white flex items-center justify-center text-[9px] font-bold">
                                        <span x-text="card.initials"></span>
                                    </div>
                                    <span class="text-[11px] font-semibold text-[#64748B]" x-text="card.contact"></span>
                                </div>
                                <span class="text-[13px] font-bold text-corp" x-text="formatCurrency(card.value)"></span>
                            </div>
                        </div>
                    </template>
                    
                    {{-- Drop zone vacía (visual feedback) --}}
                    <div class="h-10 rounded-xl border-2 border-dashed border-[#CBD5E1] flex items-center justify-center text-[11px] font-bold text-[#94A3B8]">
                        Soltar aquí
                    </div>
                </div>
            </div>
        </template>
        
        {{-- Añadir columna --}}
        <button class="w-80 shrink-0 h-14 rounded-[20px] border-2 border-dashed border-[#CBD5E1] flex items-center justify-center text-[#64748B] font-bold text-[13px] hover:border-corp hover:text-corp transition-colors bg-[#F8FAFC]">
            + Añadir Etapa
        </button>
    </div>

    <!-- Modal Nueva Oportunidad -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-[#00122A]/40 backdrop-blur-sm" style="display: none;">
        <div @click.away="showModal = false" class="bg-white rounded-[24px] shadow-[0_30px_60px_-15px_rgba(0,43,106,0.3)] w-[400px] flex flex-col overflow-hidden">
            <div class="p-5 border-b border-[#F1F5F9] flex justify-between items-center bg-[#FAFCFE]">
                <h3 class="font-bold text-[#1E293B] text-[15px]">Agregar Oportunidad</h3>
                <button @click="showModal = false" class="text-[#94A3B8] hover:text-[#EF4444] transition-colors bg-[#F1F5F9] hover:bg-red-50 p-1 rounded-full">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-[11px] font-bold text-[#64748B] mb-1.5 uppercase tracking-wide">Título de la Oportunidad</label>
                    <input type="text" x-model="newDeal.title" class="w-full px-4 py-2.5 input-corp text-[13px]" placeholder="Ej. Renovación Anual">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-[#64748B] mb-1.5 uppercase tracking-wide">Monto Estimado (USD)</label>
                    <input type="number" x-model="newDeal.value" class="w-full px-4 py-2.5 input-corp text-[13px]" placeholder="Ej. 1500">
                </div>
            </div>
            <div class="p-5 border-t border-[#F1F5F9] bg-[#FAFCFE] flex justify-end gap-3">
                <button @click="showModal = false" class="px-4 py-2.5 text-[13px] font-bold text-[#64748B] hover:text-main transition-colors">Cancelar</button>
                <button @click="saveDeal" class="px-5 py-2.5 bg-corp text-white text-[13px] font-bold rounded-xl hover:bg-[#002052] transition-colors shadow-sm">Crear Oportunidad</button>
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
                { id: 'lead', title: 'Nuevos Leads', colorClass: 'bg-[#5BA3E6]' },
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
            formatCurrency(val) {
                return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD', minimumFractionDigits: 0 }).format(val);
            },
            get totalValue() {
                return this.cards.reduce((acc, card) => acc + card.value, 0);
            },
            getColumnTotal(columnId) {
                return this.getCardsForColumn(columnId).reduce((acc, card) => acc + card.value, 0);
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
