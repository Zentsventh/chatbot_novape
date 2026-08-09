@extends('layouts.app')

@section('title', 'Configuración — Smart AI Hosting Solutions')

@section('content')
<div class="flex-1 flex bg-[#F8FAFC] w-full h-full overflow-hidden" x-data="{ activeTab: 'profile' }">
    
    {{-- Sidebar Configuración --}}
    <div class="w-64 bg-white border-r border-[#E2E8F0] shrink-0 flex flex-col">
        <div class="p-6 pb-4">
            <h2 class="text-lg font-bold text-[#1E293B]">Ajustes</h2>
            <p class="text-xs text-[#64748B] mt-1">Administra tu cuenta de Novape</p>
        </div>
        <nav class="flex-1 px-4 space-y-1">
            <button @click="activeTab = 'profile'" :class="activeTab === 'profile' ? 'bg-[#F1F5F9] text-[#0056D2] font-medium' : 'text-[#64748B] hover:bg-[#F8FAFC] hover:text-[#1E293B]'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors text-left">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Mi Perfil
            </button>
            <button @click="activeTab = 'subscription'" :class="activeTab === 'subscription' ? 'bg-[#F1F5F9] text-[#0056D2] font-medium' : 'text-[#64748B] hover:bg-[#F8FAFC] hover:text-[#1E293B]'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors text-left">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                Suscripción
            </button>
            <button @click="activeTab = 'notifications'" :class="activeTab === 'notifications' ? 'bg-[#F1F5F9] text-[#0056D2] font-medium' : 'text-[#64748B] hover:bg-[#F8FAFC] hover:text-[#1E293B]'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors text-left">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                Preferencias
            </button>
        </nav>
    </div>

    {{-- Content Area --}}
    <div class="flex-1 overflow-y-auto p-8">
        <div class="max-w-3xl">
            
            {{-- Tab: Mi Perfil --}}
            <div x-show="activeTab === 'profile'" style="display: none;">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-[#1E293B]">Mi Perfil</h1>
                    <p class="text-sm text-[#64748B] mt-1">Actualiza tu información personal y foto de perfil.</p>
                </div>
                
                <div class="bg-white border border-[#E2E8F0] rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-[#E2E8F0] flex items-center gap-6">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-[#8B5CF6] to-[#6D28D9] flex items-center justify-center text-white text-2xl font-bold shadow-sm">
                            EA
                        </div>
                        <div>
                            <div class="flex gap-2">
                                <button class="px-4 py-2 bg-white border border-[#E2E8F0] rounded-lg text-sm font-medium text-[#64748B] hover:bg-[#F1F5F9] transition-colors">Cambiar Avatar</button>
                                <button class="px-4 py-2 bg-white text-red-500 border border-transparent rounded-lg text-sm font-medium hover:bg-red-50 transition-colors">Eliminar</button>
                            </div>
                            <p class="text-xs text-[#94A3B8] mt-2">JPG, GIF o PNG. Máximo 2MB.</p>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-5">
                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-[#1E293B] mb-1.5">Nombre</label>
                                <input type="text" value="Eduardo" class="w-full px-4 py-2.5 bg-[#F8FAFC] border border-[#E2E8F0] rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0056D2]/20 focus:border-[#0056D2]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[#1E293B] mb-1.5">Apellidos</label>
                                <input type="text" value="Aguilar" class="w-full px-4 py-2.5 bg-[#F8FAFC] border border-[#E2E8F0] rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0056D2]/20 focus:border-[#0056D2]">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#1E293B] mb-1.5">Correo Electrónico</label>
                            <input type="email" value="eduardo@novape.com" class="w-full px-4 py-2.5 bg-[#F8FAFC] border border-[#E2E8F0] rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0056D2]/20 focus:border-[#0056D2]">
                        </div>
                        <div class="pt-4 flex justify-end">
                            <button class="px-6 py-2.5 bg-[#0056D2] text-white rounded-lg text-sm font-medium hover:bg-[#0047B3] shadow-sm shadow-[#0056D2]/30 transition-colors" onclick="alert('Perfil guardado con éxito.')">Guardar Cambios</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab: Suscripción --}}
            <div x-show="activeTab === 'subscription'" style="display: none;">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-[#1E293B]">Plan y Facturación</h1>
                    <p class="text-sm text-[#64748B] mt-1">Gestiona tu suscripción y métodos de pago.</p>
                </div>
                
                <div class="bg-gradient-to-r from-[#0056D2] to-[#1E3A8A] rounded-xl p-8 text-white relative overflow-hidden shadow-lg mb-6">
                    <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="bg-white/20 text-white text-xs font-bold px-2 py-1 rounded tracking-wider uppercase">Plan Actual</span>
                                <h2 class="text-3xl font-bold mt-2">Agencia Pro</h2>
                                <p class="text-white/80 text-sm mt-1">Facturación anual — Próximo cobro: 15 Dic 2026</p>
                            </div>
                            <div class="text-right">
                                <div class="text-4xl font-bold">$99<span class="text-lg font-normal text-white/80">/mes</span></div>
                            </div>
                        </div>
                        <div class="mt-8 flex gap-3">
                            <button class="px-5 py-2.5 bg-white text-[#0056D2] rounded-lg text-sm font-bold hover:bg-gray-50 transition-colors">Actualizar Plan</button>
                            <button class="px-5 py-2.5 bg-transparent border border-white/30 text-white rounded-lg text-sm font-medium hover:bg-white/10 transition-colors">Ver Facturas</button>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white border border-[#E2E8F0] rounded-xl shadow-sm">
                    <div class="p-6 border-b border-[#E2E8F0]">
                        <h3 class="text-lg font-bold text-[#1E293B]">Uso del Plan</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="font-medium text-[#1E293B]">Contactos (CRM)</span>
                                <span class="text-[#64748B]">2,543 / 10,000</span>
                            </div>
                            <div class="w-full bg-[#F1F5F9] rounded-full h-2">
                                <div class="bg-[#0056D2] h-2 rounded-full" style="width: 25%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="font-medium text-[#1E293B]">Mensajes de IA</span>
                                <span class="text-[#64748B]">42,000 / 100,000</span>
                            </div>
                            <div class="w-full bg-[#F1F5F9] rounded-full h-2">
                                <div class="bg-[#10B981] h-2 rounded-full" style="width: 42%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="font-medium text-[#1E293B]">Agentes (Usuarios)</span>
                                <span class="text-[#64748B]">4 / 5</span>
                            </div>
                            <div class="w-full bg-[#F1F5F9] rounded-full h-2">
                                <div class="bg-[#F59E0B] h-2 rounded-full" style="width: 80%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab: Preferencias --}}
            <div x-show="activeTab === 'notifications'" style="display: none;">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-[#1E293B]">Preferencias</h1>
                    <p class="text-sm text-[#64748B] mt-1">Configura las notificaciones y el comportamiento del sistema.</p>
                </div>
                
                <div class="bg-white border border-[#E2E8F0] rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6 flex items-center justify-between border-b border-[#E2E8F0]">
                        <div>
                            <h4 class="font-medium text-[#1E293B]">Notificaciones por Correo</h4>
                            <p class="text-sm text-[#64748B] mt-0.5">Recibir resúmenes diarios de nuevos leads y tareas.</p>
                        </div>
                        <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                            <input type="checkbox" checked class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-[#10B981] transition-transform duration-200 ease-in-out" style="transform: translateX(100%);">
                            <label class="toggle-label block overflow-hidden h-5 rounded-full bg-[#10B981] cursor-pointer"></label>
                        </div>
                    </div>
                    <div class="p-6 flex items-center justify-between">
                        <div>
                            <h4 class="font-medium text-[#1E293B]">Alertas de Escritorio</h4>
                            <p class="text-sm text-[#64748B] mt-0.5">Mostrar notificaciones push cuando un cliente envíe un mensaje.</p>
                        </div>
                        <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                            <input type="checkbox" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-[#CBD5E1] transition-transform duration-200 ease-in-out">
                            <label class="toggle-label block overflow-hidden h-5 rounded-full bg-[#CBD5E1] cursor-pointer"></label>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
