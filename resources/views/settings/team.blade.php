@extends('layouts.app')

@section('title', 'Gestión de Equipo — Smart AI Hosting Solutions')

@section('content')
<div class="flex-1 overflow-y-auto bg-slate-50 relative" x-data="{ showModal: false, selectedRole: 'tenant_agent' }">
    
    {{-- Decoración de Fondo (Subtle Gradients) --}}
    <div class="absolute top-0 left-0 w-full h-64 bg-gradient-to-b from-[#0056D2]/5 to-transparent pointer-events-none"></div>

    <div class="max-w-6xl mx-auto px-6 py-10 relative z-10">
        
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white border border-slate-200 shadow-sm mb-4">
                    <span class="w-2 h-2 rounded-full bg-[#10B981] animate-pulse"></span>
                    <span class="text-xs font-semibold text-slate-600 tracking-wide uppercase">Workspace Team</span>
                </div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Gestión de Equipo</h1>
                <p class="text-base text-slate-500 mt-2 max-w-xl">Administra los agentes, supervisores y accesos a tu plataforma omnicanal. Todo tu equipo en un solo lugar.</p>
            </div>
            
            @if(auth()->user()->role === 'tenant_admin' || auth()->user()->role === 'super_admin')
            <button @click="showModal = true" class="group relative inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#0056D2] text-white rounded-xl font-medium transition-all duration-300 hover:bg-[#0047B3] hover:shadow-lg hover:shadow-[#0056D2]/30 active:scale-95 overflow-hidden">
                <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-in-out"></div>
                <svg class="w-5 h-5 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span class="relative z-10">Añadir Trabajador</span>
            </button>
            @endif
        </div>

        {{-- Alertas --}}
        @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50/80 backdrop-blur-sm text-emerald-800 rounded-2xl border border-emerald-200 shadow-sm flex items-start gap-3 animate-fade-in">
            <svg class="w-5 h-5 text-emerald-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <div>
                <h4 class="font-bold text-sm">¡Éxito!</h4>
                <p class="text-sm mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
        @endif
        
        @if(session('error'))
        <div class="mb-8 p-4 bg-rose-50/80 backdrop-blur-sm text-rose-800 rounded-2xl border border-rose-200 shadow-sm flex items-start gap-3 animate-fade-in">
            <svg class="w-5 h-5 text-rose-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <div>
                <h4 class="font-bold text-sm">Error</h4>
                <p class="text-sm mt-0.5">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        {{-- Tarjeta de Tabla --}}
        <div class="bg-white border border-slate-200/60 rounded-3xl shadow-xl shadow-slate-200/40 overflow-hidden relative">
            <div class="absolute inset-0 bg-gradient-to-br from-white/40 to-slate-50/40 pointer-events-none"></div>
            
            <div class="overflow-x-auto relative z-10">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/50">
                            <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-wider">Usuario</th>
                            <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-wider">Rol de Acceso</th>
                            <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-wider">Fecha Creación</th>
                            <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/80">
                        @foreach($team as $user)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center text-slate-600 font-extrabold text-sm shadow-inner relative border border-slate-200/50">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                        @if($user->is_online)
                                            <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full"></span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900 group-hover:text-[#0056D2] transition-colors">{{ $user->name }}</p>
                                        <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                @if($user->role === 'tenant_admin' || $user->role === 'super_admin')
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#0056D2]/10 text-[#0056D2] border border-[#0056D2]/20">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                        Administrador
                                    </div>
                                @elseif($user->role === 'tenant_supervisor')
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        Supervisor
                                    </div>
                                @else
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                                        Agente
                                    </div>
                                @endif
                            </td>
                            <td class="px-8 py-5">
                                <div class="text-sm font-medium text-slate-600">
                                    {{ $user->created_at->format('d M, Y') }}
                                </div>
                                <div class="text-xs text-slate-400 mt-0.5">
                                    {{ $user->created_at->format('h:i A') }}
                                </div>
                            </td>
                            <td class="px-8 py-5 text-right">
                                @if(auth()->id() !== $user->id)
                                    @if(auth()->user()->role === 'tenant_admin' || auth()->user()->role === 'super_admin')
                                    <form action="{{ route('settings.team.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar a este trabajador? No podrá volver a iniciar sesión.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all" title="Eliminar Usuario">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                @else
                                    <span class="inline-flex px-3 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-400">Es tu cuenta</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                @if($team->isEmpty())
                <div class="p-16 text-center flex flex-col items-center justify-center">
                    <div class="w-24 h-24 mb-6 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center">
                        <svg class="w-12 h-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700">Sin miembros de equipo</h3>
                    <p class="text-slate-500 mt-2 max-w-sm text-sm">Parece que estás solo aquí. Empieza a invitar a tus agentes y administradores para crecer.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal Añadir Trabajador Premium --}}
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 sm:p-0">
            {{-- Backdrop con Blur --}}
            <div x-show="showModal" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" 
                 @click="showModal = false"></div>
            
            {{-- Contenedor del Modal --}}
            <div x-show="showModal" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="relative bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-xl w-full border border-slate-100">
                
                {{-- Header Decorativo del Modal --}}
                <div class="relative bg-gradient-to-r from-[#0056D2] to-[#0047B3] px-8 py-8 overflow-hidden">
                    {{-- Decoración Vectorial --}}
                    <svg class="absolute top-0 right-0 text-white/10 w-48 h-48 -mr-12 -mt-12" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="12"/></svg>
                    
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20 shadow-inner">
                            <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white tracking-tight" id="modal-title">Nuevo Trabajador</h3>
                            <p class="text-white/80 text-sm font-medium mt-1">Ingresa los datos para crear la cuenta de acceso.</p>
                        </div>
                    </div>
                </div>

                {{-- Cuerpo del Formulario --}}
                <div class="px-8 py-8 bg-white">
                    <form action="{{ route('settings.team.store') }}" method="POST" id="add-team-form" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            {{-- Input Nombre --}}
                            <div class="space-y-1.5 sm:col-span-2">
                                <label for="name" class="block text-sm font-bold text-slate-700">Nombre Completo</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#0056D2] transition-colors">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    </div>
                                    <input type="text" name="name" id="name" required placeholder="Ej. Ana Martínez" class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#0056D2]/10 focus:border-[#0056D2] transition-all font-medium text-slate-900 placeholder:font-normal placeholder:text-slate-400">
                                </div>
                                @error('name') <span class="text-rose-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>

                            {{-- Input Email --}}
                            <div class="space-y-1.5 sm:col-span-2">
                                <label for="email" class="block text-sm font-bold text-slate-700">Correo de Acceso</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#0056D2] transition-colors">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                                    </div>
                                    <input type="email" name="email" id="email" required placeholder="ana@empresa.com" class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#0056D2]/10 focus:border-[#0056D2] transition-all font-medium text-slate-900 placeholder:font-normal placeholder:text-slate-400">
                                </div>
                                @error('email') <span class="text-rose-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>

                            {{-- Selector de Rol Visual --}}
                            <div class="space-y-1.5 sm:col-span-2">
                                <label class="block text-sm font-bold text-slate-700 mb-2">Asignación de Rol</label>
                                <input type="hidden" name="role" x-model="selectedRole">
                                
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    {{-- Opción Agente --}}
                                    <div @click="selectedRole = 'tenant_agent'" 
                                         class="relative cursor-pointer rounded-xl border-2 p-3 text-center transition-all"
                                         :class="selectedRole === 'tenant_agent' ? 'border-[#0056D2] bg-[#0056D2]/5 shadow-sm' : 'border-slate-200 hover:border-slate-300'">
                                        <div class="w-8 h-8 mx-auto rounded-full bg-slate-100 flex items-center justify-center mb-2"
                                             :class="selectedRole === 'tenant_agent' ? 'bg-[#0056D2]/10 text-[#0056D2]' : 'text-slate-500'">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                                        </div>
                                        <h4 class="text-sm font-bold text-slate-900">Agente</h4>
                                        <p class="text-[10px] text-slate-500 font-medium mt-1 leading-tight">Solo responde chats asignados</p>
                                    </div>
                                    {{-- Opción Supervisor --}}
                                    <div @click="selectedRole = 'tenant_supervisor'" 
                                         class="relative cursor-pointer rounded-xl border-2 p-3 text-center transition-all"
                                         :class="selectedRole === 'tenant_supervisor' ? 'border-amber-500 bg-amber-50 shadow-sm' : 'border-slate-200 hover:border-slate-300'">
                                        <div class="w-8 h-8 mx-auto rounded-full bg-slate-100 flex items-center justify-center mb-2"
                                             :class="selectedRole === 'tenant_supervisor' ? 'bg-amber-100 text-amber-600' : 'text-slate-500'">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </div>
                                        <h4 class="text-sm font-bold text-slate-900">Supervisor</h4>
                                        <p class="text-[10px] text-slate-500 font-medium mt-1 leading-tight">Ve reportes y audita chats</p>
                                    </div>
                                    {{-- Opción Admin --}}
                                    <div @click="selectedRole = 'tenant_admin'" 
                                         class="relative cursor-pointer rounded-xl border-2 p-3 text-center transition-all"
                                         :class="selectedRole === 'tenant_admin' ? 'border-purple-500 bg-purple-50 shadow-sm' : 'border-slate-200 hover:border-slate-300'">
                                        <div class="w-8 h-8 mx-auto rounded-full bg-slate-100 flex items-center justify-center mb-2"
                                             :class="selectedRole === 'tenant_admin' ? 'bg-purple-100 text-purple-600' : 'text-slate-500'">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                        </div>
                                        <h4 class="text-sm font-bold text-slate-900">Admin</h4>
                                        <p class="text-[10px] text-slate-500 font-medium mt-1 leading-tight">Acceso total al sistema</p>
                                    </div>
                                </div>
                                @error('role') <span class="text-rose-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>

                            {{-- Input Contraseña --}}
                            <div class="space-y-1.5">
                                <label for="password" class="block text-sm font-bold text-slate-700">Contraseña Temporal</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#0056D2] transition-colors">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8V7a4 4 0 00-8 0v4h8z" /></svg>
                                    </div>
                                    <input type="password" name="password" id="password" required minlength="8" placeholder="••••••••" class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#0056D2]/10 focus:border-[#0056D2] transition-all font-medium text-slate-900 placeholder:font-normal placeholder:text-slate-400">
                                </div>
                                @error('password') <span class="text-rose-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>

                            {{-- Input Confirmar Contraseña --}}
                            <div class="space-y-1.5">
                                <label for="password_confirmation" class="block text-sm font-bold text-slate-700">Confirmar Contraseña</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#0056D2] transition-colors">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                    </div>
                                    <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8" placeholder="••••••••" class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#0056D2]/10 focus:border-[#0056D2] transition-all font-medium text-slate-900 placeholder:font-normal placeholder:text-slate-400">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Footer del Modal --}}
                <div class="bg-slate-50 px-8 py-5 border-t border-slate-100 flex flex-col sm:flex-row-reverse gap-3">
                    <button type="submit" form="add-team-form" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-xl border border-transparent px-8 py-3 bg-[#0056D2] text-sm font-bold text-white hover:bg-[#0047B3] hover:shadow-lg hover:shadow-[#0056D2]/30 focus:outline-none focus:ring-4 focus:ring-[#0056D2]/20 transition-all active:scale-95">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                        Crear Cuenta
                    </button>
                    <button type="button" @click="showModal = false" class="w-full sm:w-auto inline-flex justify-center items-center rounded-xl border border-slate-300 px-6 py-3 bg-white text-sm font-bold text-slate-700 hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-4 focus:ring-slate-100 transition-all active:scale-95">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
