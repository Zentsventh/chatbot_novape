@extends('layouts.app')

@section('title', 'Contactos — Smart AI Hosting Solutions')

@section('content')
<div class="flex-1 flex flex-col bg-[#F8FAFC] w-full h-full overflow-hidden" x-data="contactsApp()">
    {{-- Header de la sección --}}
    <div class="px-8 py-6 border-b border-[#E2E8F0] bg-white shrink-0 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-[#1E293B]">Directorio de Contactos</h1>
            <p class="text-sm text-[#64748B] mt-1">Gestiona tu base de datos de leads y clientes (Mostrando <span x-text="contacts.length"></span> contactos)</p>
        </div>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <input type="text" x-model="searchQuery" placeholder="Buscar contacto..." class="pl-9 pr-4 py-2 w-64 border border-[#E2E8F0] rounded-lg text-sm focus:outline-none focus:border-[#0056D2] focus:ring-1 focus:ring-[#0056D2]">
                    <svg class="w-4 h-4 text-[#94A3B8] absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button @click="showModal = true" class="flex items-center gap-2 px-4 py-2 bg-[#0056D2] text-white rounded-lg text-sm font-medium hover:bg-[#0047b3] transition-colors shadow-sm shadow-[#0056D2]/20">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nuevo Contacto
                </button>
            </div>
    </div>

    {{-- Filtros --}}
    <div class="px-8 py-4 shrink-0 flex items-center gap-4">
        <div class="relative flex-1 max-w-md">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-[#94A3B8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input
                type="text"
                x-model="searchQuery"
                placeholder="Buscar por nombre, correo o teléfono..."
                class="w-full pl-10 pr-4 py-2 text-sm bg-white border border-[#E2E8F0] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0056D2]/20 focus:border-[#0056D2] shadow-sm transition-all"
            >
        </div>
        
        <select class="px-4 py-2 text-sm bg-white border border-[#E2E8F0] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0056D2]/20 focus:border-[#0056D2] shadow-sm text-[#475569]">
            <option value="">Todas las Etiquetas</option>
            <option value="vip">VIP</option>
            <option value="lead">Nuevo Lead</option>
            <option value="support">Soporte</option>
        </select>

        <select class="px-4 py-2 text-sm bg-white border border-[#E2E8F0] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0056D2]/20 focus:border-[#0056D2] shadow-sm text-[#475569]">
            <option value="">Cualquier Canal</option>
            <option value="whatsapp">WhatsApp</option>
            <option value="messenger">Messenger</option>
            <option value="instagram">Instagram</option>
        </select>
    </div>

    {{-- Tabla de Datos --}}
    <div class="flex-1 overflow-auto px-8 pb-8">
        <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] border-b border-[#E2E8F0] text-xs uppercase tracking-wider text-[#64748B] font-semibold">
                        <th class="px-6 py-4 w-10">
                            <input type="checkbox" class="rounded border-[#CBD5E1] text-[#0056D2] focus:ring-[#0056D2]">
                        </th>
                        <th class="px-6 py-4">Contacto</th>
                        <th class="px-6 py-4">Teléfono / Email</th>
                        <th class="px-6 py-4">Etiquetas</th>
                        <th class="px-6 py-4">Última Interacción</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0]">
                    <template x-for="contact in filteredContacts" :key="contact.id">
                        <tr class="hover:bg-[#F8FAFC] transition-colors group">
                            <td class="px-6 py-4">
                                <input type="checkbox" class="rounded border-[#CBD5E1] text-[#0056D2] focus:ring-[#0056D2]">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="relative">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-sm" :class="contact.bg">
                                            <span x-text="contact.initials"></span>
                                        </div>
                                        <template x-if="contact.channel === 'whatsapp'">
                                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-[#25D366] rounded-full border-2 border-white flex items-center justify-center">
                                                <svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.418-.097.824z"/></svg>
                                            </div>
                                        </template>
                                        <template x-if="contact.channel === 'messenger'">
                                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-[#00B2FF] rounded-full border-2 border-white flex items-center justify-center">
                                                <svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.14 2 11.25c0 2.865 1.455 5.42 3.737 7.087V22l3.411-1.874c.915.253 1.878.374 2.852.374 5.523 0 10-4.14 10-9.25S17.523 2 12 2zm1.096 12.385l-2.784-2.973-5.425 2.973 5.962-6.335 2.825 2.972 5.385-2.972-5.963 6.335z"/></svg>
                                            </div>
                                        </template>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-[#1E293B]" x-text="contact.name"></div>
                                        <div class="text-xs text-[#94A3B8]" x-text="contact.company"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-[#475569]" x-text="contact.phone"></div>
                                <div class="text-xs text-[#94A3B8]" x-text="contact.email"></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    <template x-for="tag in contact.tags" :key="tag">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-[#F1F5F9] text-[#64748B] border border-[#E2E8F0]" x-text="tag"></span>
                                    </template>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-[#475569]" x-text="contact.lastActive"></div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button class="p-1.5 text-[#64748B] hover:text-[#0056D2] hover:bg-[#E0E7FF] rounded transition-colors tooltip" data-tooltip="Enviar Mensaje">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                    </button>
                                    <button class="p-1.5 text-[#64748B] hover:text-[#0D9488] hover:bg-[#CCFBF1] rounded transition-colors tooltip" data-tooltip="Llamar">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </button>
                                    <button class="p-1.5 text-[#64748B] hover:text-[#EF4444] hover:bg-[#FEE2E2] rounded transition-colors tooltip" data-tooltip="Eliminar">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
            
            {{-- Paginación Simluada --}}
            <div class="px-6 py-4 border-t border-[#E2E8F0] flex items-center justify-between text-sm text-[#64748B]">
                <div>Mostrando 1 a 4 de 24 contactos</div>
                <div class="flex gap-1">
                    <button class="px-3 py-1 rounded border border-[#E2E8F0] hover:bg-[#F1F5F9] disabled:opacity-50" disabled>Anterior</button>
                    <button class="px-3 py-1 rounded border border-[#0056D2] bg-[#0056D2] text-white">1</button>
                    <button class="px-3 py-1 rounded border border-[#E2E8F0] hover:bg-[#F1F5F9]">2</button>
                    <button class="px-3 py-1 rounded border border-[#E2E8F0] hover:bg-[#F1F5F9]">3</button>
                    <button class="px-3 py-1 rounded border border-[#E2E8F0] hover:bg-[#F1F5F9]">Siguiente</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nuevo Contacto -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display: none;">
        <div @click.away="showModal = false" class="bg-white rounded-xl shadow-xl w-[400px] flex flex-col overflow-hidden">
            <div class="p-4 border-b border-[#E2E8F0] flex justify-between items-center">
                <h3 class="font-semibold text-[#1E293B]">Agregar Nuevo Contacto</h3>
                <button @click="showModal = false" class="text-[#94A3B8] hover:text-[#1E293B]">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-4 space-y-4">
                <div>
                    <label class="block text-xs font-medium text-[#64748B] mb-1">Nombre Completo</label>
                    <input type="text" x-model="newContact.name" class="w-full px-3 py-2 border border-[#E2E8F0] rounded-lg text-sm focus:outline-none focus:border-[#0056D2]" placeholder="Ej. Juan Pérez">
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#64748B] mb-1">Empresa</label>
                    <input type="text" x-model="newContact.company" class="w-full px-3 py-2 border border-[#E2E8F0] rounded-lg text-sm focus:outline-none focus:border-[#0056D2]" placeholder="Ej. Novape">
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#64748B] mb-1">Correo Electrónico</label>
                    <input type="email" x-model="newContact.email" class="w-full px-3 py-2 border border-[#E2E8F0] rounded-lg text-sm focus:outline-none focus:border-[#0056D2]" placeholder="juan@ejemplo.com">
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#64748B] mb-1">Teléfono</label>
                    <input type="text" x-model="newContact.phone" class="w-full px-3 py-2 border border-[#E2E8F0] rounded-lg text-sm focus:outline-none focus:border-[#0056D2]" placeholder="+52 ...">
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#64748B] mb-1">Canal Principal</label>
                    <select x-model="newContact.channel" class="w-full px-3 py-2 border border-[#E2E8F0] rounded-lg text-sm focus:outline-none focus:border-[#0056D2]">
                        <option value="whatsapp">WhatsApp</option>
                        <option value="messenger">Messenger</option>
                        <option value="instagram">Instagram</option>
                    </select>
                </div>
            </div>
            <div class="p-4 border-t border-[#E2E8F0] bg-[#F8FAFC] flex justify-end gap-2">
                <button @click="showModal = false" class="px-4 py-2 text-sm font-medium text-[#64748B] hover:text-[#1E293B]">Cancelar</button>
                <button @click="saveContact" class="px-4 py-2 bg-[#0056D2] text-white text-sm font-medium rounded-lg hover:bg-[#0047B3]">Guardar Contacto</button>
            </div>
        </div>
    </div>
</div>

<script>
function contactsApp() {
    return {
        searchQuery: '',
        contacts: [],
        isLoading: true,
        showModal: false,
        newContact: {
            name: '', email: '', phone: '', company: '', channel: 'whatsapp'
        },
        init() {
            this.fetchContacts();
        },
        fetchContacts() {
            this.isLoading = true;
            fetch('/api/contacts')
                .then(res => res.json())
                .then(data => {
                    this.contacts = data;
                    this.isLoading = false;
                });
        },
        saveContact() {
            fetch('/api/contacts', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(this.newContact)
            }).then(res => res.json())
              .then(data => {
                  if(data.success) {
                      this.showModal = false;
                      this.fetchContacts();
                      this.newContact = { name: '', email: '', phone: '', company: '', channel: 'whatsapp' };
                  }
              });
        },
        get filteredContacts() {
            if (this.searchQuery === '') return this.contacts;
            const q = this.searchQuery.toLowerCase();
            return this.contacts.filter(c => 
                c.name.toLowerCase().includes(q) || 
                c.email.toLowerCase().includes(q) || 
                c.phone.toLowerCase().includes(q)
            );
        }
    }
}
</script>
@endsection
