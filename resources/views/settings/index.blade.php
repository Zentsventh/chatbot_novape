@extends('layouts.app')

@section('title', 'Configuración — Smart AI Hosting Solutions')

@section('content')
<div class="flex-1 flex bg-[#F8FAFC] w-full h-full overflow-hidden" x-data="settingsApp()">
    
{{-- Sidebar Configuración --}}
    <div class="w-64 bg-[#00122A]/80 backdrop-blur-md border-r border-white/10 shrink-0 flex flex-col z-10 shadow-[5px_0_15px_rgba(0,0,0,0.3)]">
        <div class="p-6 pb-4">
            <h2 class="text-lg font-bold text-white">Ajustes</h2>
            <p class="text-xs text-white/50 mt-1 font-medium">Administra tu cuenta de Novape</p>
        </div>
        <nav class="flex-1 px-4 space-y-1">
            <button @click="activeTab = 'profile'" :class="activeTab === 'profile' ? 'bg-gradient-to-r from-[#0665E0]/40 to-[#02449E]/40 text-[#00CEFF] font-bold border border-[#00CEFF]/30 shadow-sm' : 'text-white/60 hover:bg-white/10 hover:text-white border border-transparent'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all text-left">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Mi Perfil
            </button>
            <button @click="activeTab = 'subscription'" :class="activeTab === 'subscription' ? 'bg-gradient-to-r from-[#0665E0]/40 to-[#02449E]/40 text-[#00CEFF] font-bold border border-[#00CEFF]/30 shadow-sm' : 'text-white/60 hover:bg-white/10 hover:text-white border border-transparent'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all text-left">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                Suscripción
            </button>
            <button @click="activeTab = 'notifications'" :class="activeTab === 'notifications' ? 'bg-gradient-to-r from-[#0665E0]/40 to-[#02449E]/40 text-[#00CEFF] font-bold border border-[#00CEFF]/30 shadow-sm' : 'text-white/60 hover:bg-white/10 hover:text-white border border-transparent'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all text-left">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
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
                    <h1 class="text-2xl font-bold text-white">Mi Perfil</h1>
                    <p class="text-sm text-white/60 mt-1">Actualiza tu información personal y foto de perfil.</p>
                </div>
                
                <div class="bg-[#002B6A]/40 backdrop-blur-md border border-white/10 rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.3)] overflow-hidden">
                    <div class="p-6 border-b border-white/10 flex items-center gap-6 bg-[#011B3D]/30">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-[#0665E0] to-[#02449E] flex items-center justify-center text-white text-2xl font-bold shadow-[0_0_15px_rgba(6,101,224,0.5)] border-2 border-white/10" x-text="user.initials">
                        </div>
                        <div>
                            <div class="flex gap-2">
                                <button class="px-4 py-2 bg-transparent border border-white/20 rounded-lg text-sm font-bold text-white/80 hover:text-white hover:bg-white/10 transition-colors">Cambiar Avatar</button>
                            </div>
                            <p class="text-xs text-white/40 mt-2 uppercase tracking-wider font-bold">Próximamente...</p>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-white/80 mb-1.5 uppercase tracking-wide">Nombre Completo</label>
                            <input type="text" x-model="user.name" class="w-full px-4 py-2.5 bg-black/30 border border-white/10 rounded-lg text-sm text-white focus:outline-none focus:ring-1 focus:ring-[#00CEFF] focus:border-[#00CEFF] shadow-inner transition-all hover:bg-black/40">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-white/80 mb-1.5 uppercase tracking-wide">Correo Electrónico</label>
                            <input type="email" x-model="user.email" class="w-full px-4 py-2.5 bg-black/30 border border-white/10 rounded-lg text-sm text-white focus:outline-none focus:ring-1 focus:ring-[#00CEFF] focus:border-[#00CEFF] shadow-inner transition-all hover:bg-black/40">
                        </div>
                        
                        <div class="border-t border-white/10 pt-5 mt-5">
                            <h4 class="text-sm font-bold text-white mb-4">Cambiar Contraseña</h4>
                            <div class="grid grid-cols-2 gap-5 mb-4">
                                <div>
                                    <label class="block text-sm font-bold text-white/80 mb-1.5 uppercase tracking-wide">Contraseña Actual</label>
                                    <input type="password" x-model="passwords.current_password" class="w-full px-4 py-2.5 bg-black/30 border border-white/10 rounded-lg text-sm text-white focus:outline-none focus:ring-1 focus:ring-[#00CEFF] focus:border-[#00CEFF] shadow-inner transition-all hover:bg-black/40">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-white/80 mb-1.5 uppercase tracking-wide">Nueva Contraseña</label>
                                    <input type="password" x-model="passwords.password" class="w-full px-4 py-2.5 bg-black/30 border border-white/10 rounded-lg text-sm text-white focus:outline-none focus:ring-1 focus:ring-[#00CEFF] focus:border-[#00CEFF] shadow-inner transition-all hover:bg-black/40">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-white/80 mb-1.5 uppercase tracking-wide">Confirmar Nueva Contraseña</label>
                                <input type="password" x-model="passwords.password_confirmation" class="w-full max-w-[calc(50%-10px)] px-4 py-2.5 bg-black/30 border border-white/10 rounded-lg text-sm text-white focus:outline-none focus:ring-1 focus:ring-[#00CEFF] focus:border-[#00CEFF] shadow-inner transition-all hover:bg-black/40">
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end gap-3">
                            <span x-show="saveSuccess" class="text-[#10B981] self-center text-sm font-bold" style="display:none;">¡Guardado con éxito!</span>
                            <span x-show="saveError" class="text-rose-400 self-center text-sm font-bold" style="display:none;" x-text="errorMessage"></span>
                            <button @click="updateProfile()" class="px-6 py-2.5 bg-[#00CEFF] text-[#011B3D] rounded-lg text-sm font-bold hover:bg-[#00E5FF] shadow-[0_0_15px_rgba(0,206,255,0.4)] transition-all active:scale-95">Guardar Cambios</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab: Suscripción --}}
            <div x-show="activeTab === 'subscription'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-white">Plan y Facturación</h1>
                    <p class="text-sm text-white/60 mt-1">Gestiona tu suscripción y métodos de pago.</p>
                </div>
                
                <div class="bg-gradient-to-br from-[#0665E0] to-[#02449E] rounded-xl p-8 text-white relative overflow-hidden shadow-[0_15px_40px_-10px_rgba(6,101,224,0.5)] mb-6 border border-[#0665E0]/50">
                    <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="bg-[#00CEFF]/20 border border-[#00CEFF]/30 text-[#00CEFF] text-xs font-bold px-3 py-1 rounded tracking-widest uppercase shadow-[0_0_10px_rgba(0,206,255,0.2)]">Plan Actual</span>
                                <h2 class="text-3xl font-bold mt-3">Agencia Pro</h2>
                                <p class="text-white/80 text-sm mt-1">Facturación anual — Próximo cobro: 15 Dic 2026</p>
                            </div>
                            <div class="text-right">
                                <div class="text-4xl font-bold">$99<span class="text-lg font-normal text-white/70">/mes</span></div>
                            </div>
                        </div>
                        <div class="mt-8 flex gap-3">
                            <button class="px-5 py-2.5 bg-[#00CEFF] text-[#011B3D] rounded-lg text-sm font-bold hover:bg-[#00E5FF] shadow-[0_0_15px_rgba(0,206,255,0.4)] transition-all active:scale-95">Actualizar Plan</button>
                            <button class="px-5 py-2.5 bg-transparent border border-white/30 text-white rounded-lg text-sm font-bold hover:bg-white/10 transition-all active:scale-95">Ver Facturas</button>
                        </div>
                    </div>
                </div>
                
                <div class="bg-[#002B6A]/40 backdrop-blur-md border border-white/10 rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.3)]">
                    <div class="p-6 border-b border-white/10 bg-[#011B3D]/30">
                        <h3 class="text-lg font-bold text-white">Uso del Plan</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="font-bold text-white/90">Contactos (CRM)</span>
                                <span class="text-white/60 font-mono">2,543 / 10,000</span>
                            </div>
                            <div class="w-full bg-black/40 rounded-full h-2 shadow-inner">
                                <div class="bg-[#0665E0] h-2 rounded-full shadow-[0_0_10px_#0665E0]" style="width: 25%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="font-bold text-white/90">Mensajes de IA</span>
                                <span class="text-white/60 font-mono">42,000 / 100,000</span>
                            </div>
                            <div class="w-full bg-black/40 rounded-full h-2 shadow-inner">
                                <div class="bg-[#10B981] h-2 rounded-full shadow-[0_0_10px_#10B981]" style="width: 42%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="font-bold text-white/90">Agentes (Usuarios)</span>
                                <span class="text-white/60 font-mono">4 / 5</span>
                            </div>
                            <div class="w-full bg-black/40 rounded-full h-2 shadow-inner">
                                <div class="bg-[#F59E0B] h-2 rounded-full shadow-[0_0_10px_#F59E0B]" style="width: 80%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab: Preferencias --}}
            <div x-show="activeTab === 'notifications'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-white">Preferencias</h1>
                    <p class="text-sm text-white/60 mt-1">Configura las notificaciones y el comportamiento del sistema.</p>
                </div>
                
                <div class="bg-[#002B6A]/40 backdrop-blur-md border border-white/10 rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.3)] overflow-hidden">
                    <div class="p-6 flex items-center justify-between border-b border-white/10 bg-[#011B3D]/30 hover:bg-[#002B6A]/60 transition-colors">
                        <div>
                            <h4 class="font-bold text-white">Notificaciones por Correo</h4>
                            <p class="text-sm text-white/60 mt-0.5">Recibir resúmenes diarios de nuevos leads y tareas.</p>
                        </div>
                        <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                            <input type="checkbox" checked class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-[#10B981] transition-transform duration-200 ease-in-out shadow-[0_0_10px_#10B981]" style="transform: translateX(100%);">
                            <label class="toggle-label block overflow-hidden h-5 rounded-full bg-[#10B981] cursor-pointer shadow-[0_0_5px_rgba(16,185,129,0.5)]"></label>
                        </div>
                    </div>
                    <div class="p-6 flex items-center justify-between hover:bg-[#002B6A]/60 transition-colors">
                        <div>
                            <h4 class="font-bold text-white">Alertas de Escritorio</h4>
                            <p class="text-sm text-white/60 mt-0.5">Mostrar notificaciones push cuando un cliente envíe un mensaje.</p>
                        </div>
                        <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                            <input type="checkbox" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-[#94A3B8] border-4 appearance-none cursor-pointer border-[#011B3D] transition-transform duration-200 ease-in-out">
                            <label class="toggle-label block overflow-hidden h-5 rounded-full bg-[#011B3D] cursor-pointer border border-white/20"></label>
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
