@extends('layouts.app')

@section('title', 'Calendario y Agenda — Smart AI Hosting Solutions')

@section('content')
<div class="flex-1 flex bg-[#F8FAFC] w-full h-full overflow-hidden" x-data="calendarApp()">
    
    {{-- Main Calendar Area --}}
    <div class="flex-1 flex flex-col h-full border-r border-[#E2E8F0] overflow-hidden bg-white">
        {{-- Header --}}
        <div class="px-8 py-6 border-b border-[#E2E8F0] shrink-0 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <h1 class="text-2xl font-bold text-[#1E293B]" x-text="currentMonthName + ' ' + currentYear"></h1>
                <div class="flex bg-[#F1F5F9] rounded-lg p-1">
                    <button class="p-1 rounded hover:bg-white hover:shadow-sm transition-all text-[#64748B]">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                    </button>
                    <button class="px-3 py-1 rounded text-sm font-medium hover:bg-white hover:shadow-sm transition-all text-[#475569]">Hoy</button>
                    <button class="p-1 rounded hover:bg-white hover:shadow-sm transition-all text-[#64748B]">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </button>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex bg-[#F1F5F9] rounded-lg p-1">
                    <button class="px-4 py-1.5 rounded-md text-sm font-medium bg-white shadow-sm text-[#1E293B]">Mes</button>
                    <button class="px-4 py-1.5 rounded-md text-sm font-medium text-[#64748B] hover:text-[#1E293B] transition-colors">Semana</button>
                </div>
                <button @click="showModal = true" class="flex items-center gap-2 px-4 py-2 bg-[#0056D2] text-white rounded-lg text-sm font-medium hover:bg-[#0047B3] transition-colors shadow-sm shadow-[#0056D2]/30">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Nuevo Evento
                </button>
            </div>
        </div>

        {{-- Grid Header (Days) --}}
        <div class="grid grid-cols-7 border-b border-[#E2E8F0] bg-[#F8FAFC] shrink-0">
            <template x-for="day in ['LUN', 'MAR', 'MIÉ', 'JUE', 'VIE', 'SÁB', 'DOM']">
                <div class="px-2 py-3 text-center text-xs font-semibold text-[#64748B]" x-text="day"></div>
            </template>
        </div>

        {{-- Calendar Grid --}}
        <div class="flex-1 grid grid-cols-7 grid-rows-5 overflow-hidden bg-[#E2E8F0] gap-px">
            <template x-for="(day, index) in calendarDays" :key="index">
                <div class="bg-white p-2 flex flex-col hover:bg-[#F8FAFC] transition-colors cursor-pointer group" :class="!day.isCurrentMonth ? 'opacity-50 bg-[#F8FAFC]' : ''">
                    <div class="flex justify-between items-start mb-1">
                        <span class="text-sm font-medium w-7 h-7 flex items-center justify-center rounded-full" :class="day.isToday ? 'bg-[#0056D2] text-white' : 'text-[#475569] group-hover:text-[#0056D2]'">
                            <span x-text="day.date"></span>
                        </span>
                    </div>
                    
                    {{-- Eventos del día --}}
                    <div class="flex-1 flex flex-col gap-1 overflow-y-auto no-scrollbar">
                        <template x-for="event in day.events">
                            <div class="px-2 py-1 text-xs rounded truncate font-medium border border-transparent hover:border-black/10 transition-colors" :class="event.colorClass">
                                <span x-text="event.time + ' ' + event.title"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- Agenda Sidebar --}}
    <div class="w-80 bg-[#F8FAFC] flex flex-col shrink-0 border-r border-[#E2E8F0]">
        <div class="p-6 border-b border-[#E2E8F0] bg-white">
            <h2 class="text-lg font-bold text-[#1E293B]">Agenda de Hoy</h2>
            <p class="text-sm text-[#64748B]">Jueves, 12 de Octubre</p>
        </div>
        
        <div class="flex-1 p-6 overflow-y-auto">
            <div class="relative border-l-2 border-[#E2E8F0] ml-3 space-y-6">
                
                {{-- Evento 1 --}}
                <div class="relative pl-6">
                    <div class="absolute w-3 h-3 bg-[#10B981] rounded-full -left-[7px] top-1.5 ring-4 ring-[#F8FAFC]"></div>
                    <div class="text-xs font-bold text-[#64748B] mb-1">09:00 AM - 09:30 AM</div>
                    <div class="bg-white border border-[#E2E8F0] p-3 rounded-lg shadow-sm">
                        <h4 class="font-bold text-[#1E293B] text-sm mb-1">Demo de Chatbot IA</h4>
                        <p class="text-xs text-[#64748B] mb-3">Con Carlos Rodríguez (Tech Solutions)</p>
                        <div class="flex gap-2">
                            <button class="flex-1 text-xs bg-[#F1F5F9] hover:bg-[#E2E8F0] text-[#1E293B] font-medium py-1.5 rounded transition-colors">Ver Detalles</button>
                            <button class="w-8 flex items-center justify-center bg-[#0056D2]/10 hover:bg-[#0056D2]/20 text-[#0056D2] rounded transition-colors">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14v-4zM5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Evento 2 --}}
                <div class="relative pl-6">
                    <div class="absolute w-3 h-3 bg-[#0056D2] rounded-full -left-[7px] top-1.5 ring-4 ring-[#F8FAFC]"></div>
                    <div class="text-xs font-bold text-[#0056D2] mb-1">11:00 AM - 12:00 PM (Ahora)</div>
                    <div class="bg-white border-2 border-[#0056D2]/30 p-3 rounded-lg shadow-sm">
                        <h4 class="font-bold text-[#1E293B] text-sm mb-1">Reunión de Equipo</h4>
                        <p class="text-xs text-[#64748B] mb-3">Sincronización semanal ventas</p>
                        <div class="flex -space-x-2 overflow-hidden mb-3">
                            <div class="inline-block h-6 w-6 rounded-full ring-2 ring-white bg-[#8B5CF6] text-white flex items-center justify-center text-[10px] font-bold">EA</div>
                            <div class="inline-block h-6 w-6 rounded-full ring-2 ring-white bg-[#10B981] text-white flex items-center justify-center text-[10px] font-bold">MO</div>
                            <div class="inline-block h-6 w-6 rounded-full ring-2 ring-white bg-[#F59E0B] text-white flex items-center justify-center text-[10px] font-bold">CL</div>
                        </div>
                        <button class="w-full text-xs bg-[#0056D2] hover:bg-[#0047B3] text-white font-medium py-1.5 rounded transition-colors shadow-sm">Unirse por Zoom</button>
                    </div>
                </div>

                {{-- Evento 3 --}}
                <div class="relative pl-6">
                    <div class="absolute w-3 h-3 bg-[#F59E0B] rounded-full -left-[7px] top-1.5 ring-4 ring-[#F8FAFC]"></div>
                    <div class="text-xs font-bold text-[#64748B] mb-1">15:30 PM - 16:00 PM</div>
                    <div class="bg-white border border-[#E2E8F0] p-3 rounded-lg shadow-sm opacity-60 hover:opacity-100 transition-opacity">
                        <h4 class="font-bold text-[#1E293B] text-sm mb-1">Llamada de Cierre</h4>
                        <p class="text-xs text-[#64748B]">Agencia Creativa S.A.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Nuevo Evento -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display: none;">
        <div @click.away="showModal = false" class="bg-white rounded-xl shadow-xl w-[400px] flex flex-col overflow-hidden">
            <div class="p-4 border-b border-[#E2E8F0] flex justify-between items-center">
                <h3 class="font-semibold text-[#1E293B]">Programar Evento</h3>
                <button @click="showModal = false" class="text-[#94A3B8] hover:text-[#1E293B]">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-4 space-y-4">
                <div>
                    <label class="block text-xs font-medium text-[#64748B] mb-1">Título de la Reunión</label>
                    <input type="text" x-model="newEvent.title" class="w-full px-3 py-2 border border-[#E2E8F0] rounded-lg text-sm focus:outline-none focus:border-[#0056D2]" placeholder="Ej. Presentación de Resultados">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-[#64748B] mb-1">Día (Octubre)</label>
                        <input type="number" x-model="newEvent.date" class="w-full px-3 py-2 border border-[#E2E8F0] rounded-lg text-sm focus:outline-none focus:border-[#0056D2]" min="1" max="31">
                    </div>
                </div>
            </div>
            <div class="p-4 border-t border-[#E2E8F0] bg-[#F8FAFC] flex justify-end gap-2">
                <button @click="showModal = false" class="px-4 py-2 text-sm font-medium text-[#64748B] hover:text-[#1E293B]">Cancelar</button>
                <button @click="saveEvent" class="px-4 py-2 bg-[#0056D2] text-white text-sm font-medium rounded-lg hover:bg-[#0047B3]">Guardar Evento</button>
            </div>
        </div>
    </div>
</div>

<style>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>

<script>
function calendarApp() {
    return {
        currentMonthName: 'Octubre',
        currentYear: 2026,
        events: [],
        isLoading: true,
        calendarDays: [],
        showModal: false,
        newEvent: {
            title: '', date: 12, month: 10, year: 2026
        },
        
        init() {
            this.generateCalendar();
            this.fetchEvents();
        },
        
        fetchEvents() {
            this.isLoading = true;
            fetch('/api/events')
                .then(res => res.json())
                .then(data => {
                    this.events = data;
                    this.mapEventsToDays();
                    this.isLoading = false;
                });
        },
        
        saveEvent() {
            fetch('/api/events', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(this.newEvent)
            }).then(res => res.json())
              .then(data => {
                  if(data.success) {
                      this.showModal = false;
                      this.fetchEvents();
                      this.newEvent.title = '';
                  }
              });
        },
        
        generateCalendar() {
            let days = [];
            for (let i = 28; i <= 30; i++) days.push({ date: i, isCurrentMonth: false, isToday: false, events: [] });
            for (let i = 1; i <= 31; i++) {
                let isToday = i === 12; // Simulamos que el día 12 es "hoy"
                days.push({ date: i, isCurrentMonth: true, isToday: isToday, events: [] });
            }
            days.push({ date: 1, isCurrentMonth: false, isToday: false, events: [] });
            this.calendarDays = days;
        },

        mapEventsToDays() {
            this.events.forEach(ev => {
                let day = this.calendarDays.find(d => d.date === ev.date && d.isCurrentMonth === (ev.month === 10)); // asumiendo Octubre (10)
                if(day) {
                    day.events.push(ev);
                }
            });
        }
    }
}
</script>
@endsection
