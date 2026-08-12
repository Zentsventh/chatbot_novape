<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar Sesión — Novape</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Corrección del fondo blanco invasivo al autocompletar en Chrome/Edge */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 50px transparent inset !important;
            -webkit-text-fill-color: white !important;
            transition: background-color 5000s ease-in-out 0s;
            caret-color: white;
        }
    </style>
</head>
<body class="antialiased min-h-screen flex items-center justify-center font-['Montserrat'] bg-[#011B3D]" style="background: radial-gradient(circle at center, #012A5E 0%, #00122A 100%);">
    
    <div class="relative w-full max-w-[420px] px-4 z-10" x-data="{ loading: false }">
        
        {{-- Tarjeta Glassmorphism Ultra Premium --}}
        <div class="bg-gradient-to-b from-[#0665E0]/90 to-[#02449E]/90 backdrop-blur-xl shadow-[0_40px_80px_-20px_rgba(0,0,0,0.8)] rounded-[32px] p-10 relative overflow-hidden">
            
            {{-- Resplandor sutil interno --}}
            <div class="absolute top-0 left-0 w-full h-[1px] bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>

            {{-- Logo NOVAPE --}}
            <div class="flex justify-center mb-12">
                <img src="{{ asset('images/logo_chatbot_novape.png') }}" alt="Novape Logo" class="w-[180px] h-auto object-contain drop-shadow-xl">
            </div>

            {{-- Errores --}}
            @if ($errors->any())
                <div class="mb-8 p-4 rounded-2xl bg-red-500/20 border border-red-500/30 flex items-start gap-3">
                    <svg class="w-5 h-5 text-white shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div class="flex-1">
                        @foreach ($errors->all() as $error)
                            <p class="text-white text-sm font-medium leading-tight">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" @submit="loading = true" id="login-form">
                @csrf

                {{-- Correo Electrónico --}}
                <div class="mb-8 relative group">
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 flex items-center pointer-events-none transition-transform group-focus-within:-translate-y-[60%]">
                        <svg class="w-5 h-5 text-white transition-opacity group-focus-within:opacity-100 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                    </div>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="Correo Electrónico"
                        class="w-full bg-transparent border-0 border-b border-white/60 pl-9 pr-0 py-2.5 text-white placeholder-white/80 text-[14px] font-medium focus:outline-none focus:ring-0 focus:border-white transition-all"
                        style="box-shadow: none;"
                    >
                </div>

                {{-- Contraseña --}}
                <div class="mb-10 relative group">
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 flex items-center pointer-events-none transition-transform group-focus-within:-translate-y-[60%]">
                        <svg class="w-5 h-5 text-white transition-opacity group-focus-within:opacity-100 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </div>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="current-password"
                        placeholder="Contraseña"
                        class="w-full bg-transparent border-0 border-b border-white/60 pl-9 pr-0 py-2.5 text-white placeholder-white/80 text-[14px] font-medium focus:outline-none focus:ring-0 focus:border-white transition-all"
                        style="box-shadow: none;"
                    >
                </div>

                {{-- Recordar sesión & Olvidé contraseña --}}
                <div class="flex items-center justify-between mb-10">
                    <div class="flex items-center" x-data="{ checked: {{ old('remember') ? 'true' : 'false' }} }">
                        <input type="hidden" name="remember" :value="checked ? '1' : '0'">
                        <label class="flex items-center gap-2.5 cursor-pointer select-none group" @click="checked = !checked">
                            <div 
                                class="flex-shrink-0 flex items-center justify-center w-[16px] h-[16px] rounded-[4px] transition-all duration-300"
                                :class="checked ? 'bg-[#002B6A] border-[#002B6A]' : 'bg-[#002B6A] border-[#002B6A] opacity-80 group-hover:opacity-100 group-hover:scale-105'"
                                style="border: 1.5px solid #002B6A;"
                            >
                                <svg class="w-3 h-3 text-white transition-all duration-300" :class="checked ? 'opacity-100 scale-100' : 'opacity-0 scale-50'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </div>
                            <span class="text-[12px] text-white/90 font-medium tracking-wide">Recordar sesión</span>
                        </label>
                    </div>
                    
                    <a href="#" class="text-[12px] text-white/90 font-medium hover:text-white transition-colors tracking-wide">¿Olvidaste tu contraseña?</a>
                </div>

                {{-- Botón de LOGIN Ordenado --}}
                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full h-[56px] rounded-[16px] bg-[#00CEFF] hover:bg-[#00E5FF] text-[#011B3D] text-[15px] font-bold tracking-[0.15em] transition-all duration-300 focus:outline-none flex items-center justify-center shadow-[0_15px_25px_-5px_rgba(0,206,255,0.4)] hover:shadow-[0_20px_30px_-5px_rgba(0,206,255,0.5)] hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-70 disabled:cursor-not-allowed"
                >
                    <template x-if="loading">
                        <svg class="animate-spin w-5 h-5 mr-3 text-[#011B3D]" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </template>
                    <span x-text="loading ? 'INGRESANDO...' : 'INGRESAR'"></span>
                </button>

                {{-- Footer Text (o Regístrate) --}}
                <div class="mt-8 text-center">
                    <p class="text-[13px] text-white/80 font-medium">
                        ¿No tienes una cuenta? 
                        <a href="#" class="text-white font-bold hover:text-[#00CEFF] transition-colors ml-1 tracking-wide">Regístrate</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
