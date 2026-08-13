@extends('layouts.app')

@section('title', 'Configuración — Smart AI Hosting Solutions')

@section('content')
<div class="flex-1 flex bg-[#F8FAFC] w-full h-full overflow-hidden" x-data="settingsApp()">
    
{{-- Sidebar Configuración --}}
    <div class="w-64 bg-white border-r border-[#E2E8F0] shrink-0 flex flex-col z-10">
        <div class="p-6 pb-4">
            <h2 class="text-[15px] font-bold text-main">Ajustes</h2>
            <p class="text-[11px] text-[#64748B] mt-1 font-semibold uppercase tracking-wide">Administra tu cuenta</p>
        </div>
        <nav class="flex-1 px-4 space-y-1">
            <button @click="activeTab = 'profile'" :class="activeTab === 'profile' ? 'bg-[#F8FAFC] text-corp font-bold border border-[#E2E8F0] shadow-sm' : 'text-text-muted hover:bg-[#F1F5F9] hover:text-[#1E293B] border border-transparent'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-semibold transition-all text-left">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Mi Perfil
            </button>
            <button @click="activeTab = 'subscription'" :class="activeTab === 'subscription' ? 'bg-[#F8FAFC] text-corp font-bold border border-[#E2E8F0] shadow-sm' : 'text-text-muted hover:bg-[#F1F5F9] hover:text-[#1E293B] border border-transparent'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-semibold transition-all text-left">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                Suscripción
            </button>
            <button @click="activeTab = 'notifications'" :class="activeTab === 'notifications' ? 'bg-[#F8FAFC] text-corp font-bold border border-[#E2E8F0] shadow-sm' : 'text-text-muted hover:bg-[#F1F5F9] hover:text-[#1E293B] border border-transparent'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-semibold transition-all text-left">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                Preferencias
            </button>
        </nav>
    </div>

    {{-- Content Area --}}
    <div class="flex-1 overflow-y-auto p-8 bg-transparent">
        <div class="max-w-3xl">
            
            {{-- Tab: Mi Perfil --}}
            <div x-show="activeTab === 'profile'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="mb-6">
                    <h1 class="text-[20px] font-bold text-main">Mi Perfil</h1>
                    <p class="text-[13px] text-text-muted mt-0.5">Actualiza tu información personal y foto de perfil.</p>
                </div>
                
                <div class="bg-white border border-[#E2E8F0] rounded-[24px] shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-[#F1F5F9] flex items-center gap-6 bg-[#FAFCFE]">
                        <div class="w-20 h-20 rounded-full bg-corp flex items-center justify-center text-white text-2xl font-bold shadow-md border-4 border-white" x-text="user.initials">
                        </div>
                        <div>
                            <div class="flex gap-2">
                                <button class="px-5 py-2.5 bg-white border border-[#E2E8F0] hover:bg-[#F8FAFC] rounded-xl text-[13px] font-bold text-[#64748B] hover:text-main transition-colors shadow-sm">Cambiar Avatar</button>
                            </div>
                            <p class="text-[11px] text-[#94A3B8] mt-2 uppercase tracking-wider font-bold">Próximamente...</p>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-5">
                        <div>
                            <label class="block text-[11px] font-bold text-[#64748B] mb-1.5 uppercase tracking-wide">Nombre Completo</label>
                            <input type="text" x-model="user.name" class="w-full px-4 py-2.5 input-corp text-[13px]">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-[#64748B] mb-1.5 uppercase tracking-wide">Correo Electrónico</label>
                            <input type="email" x-model="user.email" class="w-full px-4 py-2.5 input-corp text-[13px]">
                        </div>
                        
                        <div class="border-t border-[#F1F5F9] pt-5 mt-5">
                            <h4 class="text-[14px] font-bold text-[#1E293B] mb-4">Cambiar Contraseña</h4>
                            <div class="grid grid-cols-2 gap-5 mb-4">
                                <div>
                                    <label class="block text-[11px] font-bold text-[#64748B] mb-1.5 uppercase tracking-wide">Contraseña Actual</label>
                                    <input type="password" x-model="passwords.current_password" class="w-full px-4 py-2.5 input-corp text-[13px]">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-[#64748B] mb-1.5 uppercase tracking-wide">Nueva Contraseña</label>
                                    <input type="password" x-model="passwords.password" class="w-full px-4 py-2.5 input-corp text-[13px]">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-[#64748B] mb-1.5 uppercase tracking-wide">Confirmar Nueva Contraseña</label>
                                <input type="password" x-model="passwords.password_confirmation" class="w-full max-w-[calc(50%-10px)] px-4 py-2.5 input-corp text-[13px]">
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end gap-3">
                            <span x-show="saveSuccess" class="text-[#10B981] self-center text-[13px] font-bold" style="display:none;">¡Guardado con éxito!</span>
                            <span x-show="saveError" class="text-rose-500 self-center text-[13px] font-bold" style="display:none;" x-text="errorMessage"></span>
                            <button @click="updateProfile()" class="px-6 py-2.5 bg-corp text-white rounded-xl text-[13px] font-bold hover:bg-[#002052] shadow-sm transition-all active:scale-95">Guardar Cambios</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab: Suscripción --}}
            <div x-show="activeTab === 'subscription'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="mb-6">
                    <h1 class="text-[20px] font-bold text-main">Plan y Facturación</h1>
                    <p class="text-[13px] text-text-muted mt-0.5">Gestiona tu suscripción y métodos de pago.</p>
                </div>
                
                <div class="bg-gradient-to-br from-[#0056D2] to-[#003B91] rounded-[24px] p-8 text-white relative overflow-hidden shadow-lg mb-6 border border-[#0056D2]/20">
                    <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-white opacity-[0.03] rounded-full blur-3xl -mr-20 -mt-20"></div>
                    <div class="relative z-10">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="bg-[#10B981]/20 border border-[#10B981]/30 text-[#10B981] text-[10px] font-bold px-3 py-1 rounded-full tracking-widest uppercase">Plan Actual</span>
                                <h2 class="text-3xl font-bold mt-3">Agencia Pro</h2>
                                <p class="text-white/80 text-[13px] mt-1 font-medium">Facturación anual — Próximo cobro: 15 Dic 2026</p>
                            </div>
                            <div class="text-right">
                                <div class="text-4xl font-bold">$99<span class="text-[16px] font-normal text-white/70">/mes</span></div>
                            </div>
                        </div>
                        <div class="mt-8 flex gap-3">
                            <button class="px-5 py-2.5 bg-white text-corp rounded-xl text-[13px] font-bold hover:bg-gray-50 shadow-sm transition-all active:scale-95">Actualizar Plan</button>
                            <button class="px-5 py-2.5 bg-white/10 border border-white/20 text-white rounded-xl text-[13px] font-bold hover:bg-white/20 transition-all active:scale-95">Ver Facturas</button>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white border border-[#E2E8F0] rounded-[24px] shadow-sm">
                    <div class="p-6 border-b border-[#F1F5F9] bg-[#FAFCFE] rounded-t-[24px]">
                        <h3 class="text-[15px] font-bold text-main">Uso del Plan</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <div class="flex justify-between text-[13px] mb-2">
                                <span class="font-bold text-[#1E293B]">Contactos (CRM)</span>
                                <span class="text-[#64748B] font-medium font-mono">2,543 / 10,000</span>
                            </div>
                            <div class="w-full bg-[#F1F5F9] rounded-full h-2">
                                <div class="bg-corp h-2 rounded-full" style="width: 25%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-[13px] mb-2">
                                <span class="font-bold text-[#1E293B]">Mensajes de IA</span>
                                <span class="text-[#64748B] font-medium font-mono">42,000 / 100,000</span>
                            </div>
                            <div class="w-full bg-[#F1F5F9] rounded-full h-2">
                                <div class="bg-[#10B981] h-2 rounded-full" style="width: 42%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-[13px] mb-2">
                                <span class="font-bold text-[#1E293B]">Agentes (Usuarios)</span>
                                <span class="text-[#64748B] font-medium font-mono">4 / 5</span>
                            </div>
                            <div class="w-full bg-[#F1F5F9] rounded-full h-2">
                                <div class="bg-[#F59E0B] h-2 rounded-full" style="width: 80%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab: Preferencias --}}
            <div x-show="activeTab === 'notifications'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="mb-6">
                    <h1 class="text-[20px] font-bold text-main">Preferencias</h1>
                    <p class="text-[13px] text-text-muted mt-0.5">Configura las notificaciones y el comportamiento del sistema.</p>
                </div>
                
                <div class="bg-white border border-[#E2E8F0] rounded-[24px] shadow-sm overflow-hidden">
                    <div class="p-6 flex items-center justify-between border-b border-[#F1F5F9] bg-[#FAFCFE] hover:bg-[#F8FAFC] transition-colors cursor-pointer">
                        <div>
                            <h4 class="font-bold text-[#1E293B] text-[14px]">Notificaciones por Correo</h4>
                            <p class="text-[13px] text-[#64748B] mt-0.5">Recibir resúmenes diarios de nuevos leads y tareas.</p>
                        </div>
                        <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                            <input type="checkbox" checked class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-[#10B981] transition-transform duration-200 ease-in-out shadow-sm" style="transform: translateX(100%);">
                            <label class="toggle-label block overflow-hidden h-5 rounded-full bg-[#10B981] cursor-pointer"></label>
                        </div>
                    </div>
                    <div class="p-6 flex items-center justify-between hover:bg-[#F8FAFC] transition-colors cursor-pointer">
                        <div>
                            <h4 class="font-bold text-[#1E293B] text-[14px]">Alertas de Escritorio</h4>
                            <p class="text-[13px] text-[#64748B] mt-0.5">Mostrar notificaciones push cuando un cliente envíe un mensaje.</p>
                        </div>
                        <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                            <input type="checkbox" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-[#E2E8F0] border-4 appearance-none cursor-pointer border-[#F1F5F9] transition-transform duration-200 ease-in-out">
                            <label class="toggle-label block overflow-hidden h-5 rounded-full bg-[#F1F5F9] cursor-pointer border border-[#E2E8F0]"></label>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function settingsApp() {
    return {
        activeTab: 'profile',
        user: {
            name: '{{ auth()->user()->name }}',
            email: '{{ auth()->user()->email }}',
            initials: '{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}'
        },
        passwords: {
            current_password: '',
            password: '',
            password_confirmation: ''
        },
        saveSuccess: false,
        saveError: false,
        errorMessage: '',

        async updateProfile() {
            this.saveSuccess = false;
            this.saveError = false;

            try {
                // Update basic profile
                const res = await fetch('/api/profile', {
                    method: 'PUT',
                    headers: { 
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
                    },
                    body: JSON.stringify({
                        name: this.user.name,
                        email: this.user.email
                    })
                });

                if (!res.ok) {
                    const data = await res.json();
                    throw new Error(data.message || 'Error al actualizar perfil');
                }

                // If password fields are filled, update password
                if (this.passwords.current_password && this.passwords.password) {
                    const passRes = await fetch('/api/profile/password', {
                        method: 'PUT',
                        headers: { 
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
                        },
                        body: JSON.stringify(this.passwords)
                    });

                    if (!passRes.ok) {
                        const passData = await passRes.json();
                        throw new Error(passData.message || 'Error al actualizar contraseña');
                    }
                    
                    // Clear passwords on success
                    this.passwords.current_password = '';
                    this.passwords.password = '';
                    this.passwords.password_confirmation = '';
                }

                this.saveSuccess = true;
                setTimeout(() => this.saveSuccess = false, 3000);
            } catch (err) {
                this.saveError = true;
                this.errorMessage = err.message;
            }
        }
    }
}
</script>
@endsection
