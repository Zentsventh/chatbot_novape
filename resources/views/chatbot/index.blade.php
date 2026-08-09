@extends('layouts.app')

@section('title', 'Configuración del Chatbot AI — Smart AI Hosting Solutions')

@section('content')
<div class="flex-1 flex bg-[#F8FAFC] w-full h-full overflow-hidden" x-data="chatbotApp()">
    
    {{-- Sidebar de Ajustes --}}
    <div class="w-64 bg-white border-r border-[#E2E8F0] shrink-0 flex flex-col">
        <div class="p-6 border-b border-[#E2E8F0]">
            <h2 class="text-lg font-bold text-[#1E293B]">Ajustes de IA</h2>
            <p class="text-xs text-[#64748B] mt-1">Configura el motor del bot</p>
        </div>
        
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <template x-for="tab in tabs" :key="tab.id">
                <button 
                    @click="activeTab = tab.id"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors"
                    :class="activeTab === tab.id ? 'bg-[#0056D2]/10 text-[#0056D2]' : 'text-[#64748B] hover:bg-[#F1F5F9] hover:text-[#1E293B]'"
                >
                    <div x-html="tab.icon"></div>
                    <span x-text="tab.name"></span>
                </button>
            </template>
        </nav>
    </div>

    {{-- Área de Configuración --}}
    <div class="flex-1 flex flex-col overflow-hidden bg-[#F8FAFC]">
        {{-- Top Bar del Área --}}
        <div class="px-8 py-5 border-b border-[#E2E8F0] bg-white flex items-center justify-between shrink-0">
            <h1 class="text-xl font-bold text-[#1E293B]" x-text="activeTabName"></h1>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 text-sm text-[#64748B] mr-4">
                    <span class="relative flex h-2.5 w-2.5">
                      <span class="absolute inline-flex h-full w-full rounded-full bg-[#10B981] opacity-75" :class="isActive ? 'animate-ping' : 'hidden'"></span>
                      <span class="relative inline-flex rounded-full h-2.5 w-2.5" :class="isActive ? 'bg-[#10B981]' : 'bg-[#94A3B8]'"></span>
                    </span>
                    <span x-text="isActive ? 'Motor GPT-4o Activo' : 'Motor Apagado'"></span>
                </div>
                <button @click="saveSettings" class="px-6 py-2 bg-[#0056D2] text-white rounded-lg text-sm font-medium hover:bg-[#0047B3] transition-colors shadow-sm shadow-[#0056D2]/30 flex items-center gap-2" :disabled="isLoading">
                    Guardar Cambios
                </button>
            </div>
        </div>

        {{-- Content Scroll --}}
        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-3xl mx-auto space-y-8">
                
                {{-- TAB: Comportamiento Base --}}
                <div x-show="activeTab === 'behavior'" x-transition.opacity>
                    <div class="bg-white rounded-xl shadow-sm border border-[#E2E8F0] overflow-hidden">
                        <div class="p-6 border-b border-[#E2E8F0]">
                            <h3 class="text-lg font-bold text-[#1E293B] mb-1">Prompt del Sistema (System Prompt)</h3>
                            <p class="text-sm text-[#64748B]">Estas son las instrucciones maestras que dictan cómo debe comportarse la IA.</p>
                        </div>
                        <div class="p-6 bg-[#F8FAFC]">
                            <textarea x-model="systemPrompt" class="w-full h-48 p-4 text-sm bg-white border border-[#E2E8F0] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0056D2]/20 focus:border-[#0056D2] font-mono resize-none shadow-inner" placeholder="Eres un asistente virtual de ventas para..."></textarea>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-[#E2E8F0] overflow-hidden mt-8">
                        <div class="p-6 border-b border-[#E2E8F0]">
                            <h3 class="text-lg font-bold text-[#1E293B] mb-1">Parámetros de Inferencia</h3>
                            <p class="text-sm text-[#64748B]">Ajusta cómo la IA genera sus respuestas.</p>
                        </div>
                        <div class="p-6 space-y-6">
                            {{-- Slider Temperatura --}}
                            <div>
                                <div class="flex justify-between items-end mb-2">
                                    <label class="block text-sm font-semibold text-[#1E293B]">Creatividad (Temperatura)</label>
                                    <span class="text-sm font-bold text-[#0056D2]" x-text="temperature"></span>
                                </div>
                                <input type="range" min="0" max="1" step="0.1" x-model="temperature" class="w-full h-2 bg-[#E2E8F0] rounded-lg appearance-none cursor-pointer accent-[#0056D2]">
                                <div class="flex justify-between text-xs text-[#94A3B8] mt-1">
                                    <span>Más preciso (0.0)</span>
                                    <span>Más creativo (1.0)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB: Canales --}}
                <div x-show="activeTab === 'channels'" style="display: none;" x-transition.opacity>
                    <div class="bg-white rounded-xl shadow-sm border border-[#E2E8F0] overflow-hidden">
                        <div class="p-6 border-b border-[#E2E8F0]">
                            <h3 class="text-lg font-bold text-[#1E293B] mb-1">Canales Atendidos por IA</h3>
                            <p class="text-sm text-[#64748B]">Activa o desactiva la intervención automática de la IA por canal.</p>
                        </div>
                        <div class="p-0">
                            {{-- Toggle 1 --}}
                            <div class="flex items-center justify-between p-6 border-b border-[#E2E8F0]">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-[#25D366]/10 flex items-center justify-center text-[#25D366]">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.418-.097.824z"/></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-[#1E293B]">WhatsApp Business</h4>
                                        <p class="text-xs text-[#64748B]">Atender mensajes entrantes de WhatsApp</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                  <input type="checkbox" value="" class="sr-only peer" checked>
                                  <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#10B981]"></div>
                                </label>
                            </div>

                            {{-- Toggle 2 --}}
                            <div class="flex items-center justify-between p-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-[#E1306C]/10 flex items-center justify-center text-[#E1306C]">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-[#1E293B]">Instagram DM</h4>
                                        <p class="text-xs text-[#64748B]">Atender mensajes directos de IG</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                  <input type="checkbox" value="" class="sr-only peer">
                                  <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#10B981]"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB: Conocimiento --}}
                <div x-show="activeTab === 'knowledge'" style="display: none;" x-transition.opacity>
                     <div class="bg-white rounded-xl shadow-sm border border-[#E2E8F0] overflow-hidden">
                        <div class="p-6 border-b border-[#E2E8F0] flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-bold text-[#1E293B] mb-1">Base de Conocimientos (Knowledge Base)</h3>
                                <p class="text-sm text-[#64748B]">Sube documentos PDF o enlaces web para que la IA aprenda sobre tu negocio.</p>
                            </div>
                            <div class="flex gap-2">
                                <button @click="addUrl" class="px-4 py-2 bg-white border border-[#E2E8F0] text-[#1E293B] rounded-lg text-sm font-medium hover:bg-[#F1F5F9] transition-colors shadow-sm">
                                    + Añadir URL
                                </button>
                                <input type="file" id="pdfUpload" class="hidden" accept=".pdf" @change="uploadPdf">
                                <button @click="document.getElementById('pdfUpload').click()" class="px-4 py-2 bg-[#F1F5F9] text-[#1E293B] rounded-lg text-sm font-medium hover:bg-[#E2E8F0] transition-colors shadow-sm" :disabled="isUploading">
                                    <span x-show="!isUploading">Subir Archivo PDF</span>
                                    <span x-show="isUploading">Subiendo...</span>
                                </button>
                            </div>
                        </div>
                        <div class="p-6 bg-[#F8FAFC]">
                            
                            <div class="grid grid-cols-2 gap-4">
                                <template x-for="kb in knowledgeBases" :key="kb.id">
                                    <div class="bg-white p-4 rounded-lg border border-[#E2E8F0] flex gap-3 items-start shadow-sm">
                                        
                                        {{-- Icono PDF --}}
                                        <template x-if="kb.type === 'pdf'">
                                            <div class="w-8 h-8 rounded bg-[#EF4444]/10 text-[#EF4444] flex items-center justify-center shrink-0">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                            </div>
                                        </template>

                                        {{-- Icono URL --}}
                                        <template x-if="kb.type === 'url'">
                                            <div class="w-8 h-8 rounded bg-[#0056D2]/10 text-[#0056D2] flex items-center justify-center shrink-0">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                                            </div>
                                        </template>

                                        <div class="flex-1 overflow-hidden">
                                            <h4 class="text-sm font-bold text-[#1E293B] truncate" x-text="kb.name"></h4>
                                            <p class="text-xs text-[#94A3B8]">
                                                <span x-text="kb.status === 'processed' ? 'Procesado' : 'Procesando...'"></span>
                                                <template x-if="kb.metadata && kb.metadata.size">
                                                    <span x-text="' • ' + (kb.metadata.size / 1024 / 1024).toFixed(2) + ' MB'"></span>
                                                </template>
                                            </p>
                                        </div>
                                        <button @click="deleteKnowledgeBase(kb.id)" class="text-[#EF4444] hover:bg-[#FEE2E2] p-1.5 rounded transition-colors"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                    </div>
                                </template>

                                <div x-show="knowledgeBases.length === 0" class="col-span-2 text-center py-8 text-[#94A3B8] text-sm">
                                    No hay documentos en la base de conocimientos.
                                </div>
                            </div>
                        </div>
                     </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
function chatbotApp() {
    return {
        activeTab: 'behavior',
        temperature: 0.7,
        systemPrompt: '',
        isActive: true,
        isLoading: true,
        isUploading: false,
        knowledgeBases: [],
        tabs: [
            { 
                id: 'behavior', 
                name: 'Comportamiento',
                icon: '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>' 
            },
            { 
                id: 'knowledge', 
                name: 'Base de Conocimiento',
                icon: '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>' 
            },
            { 
                id: 'channels', 
                name: 'Canales Activos',
                icon: '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" /></svg>' 
            },
        ],
        init() {
            this.loadSettings();
            this.loadKnowledgeBases();
        },
        loadSettings() {
            fetch('/api/chatbot/settings')
                .then(res => res.json())
                .then(data => {
                    this.temperature = data.temperature;
                    this.systemPrompt = data.system_prompt;
                    this.isActive = data.is_active;
                    this.isLoading = false;
                });
        },
        loadKnowledgeBases() {
            fetch('/api/chatbot/knowledge')
                .then(res => res.json())
                .then(data => {
                    this.knowledgeBases = data;
                });
        },
        uploadPdf(event) {
            const file = event.target.files[0];
            if (!file) return;

            if (file.type !== 'application/pdf') {
                alert('Solo se permiten archivos PDF');
                return;
            }

            if (file.size > 10 * 1024 * 1024) {
                alert('El archivo es muy pesado (Max 10MB)');
                return;
            }

            this.isUploading = true;
            const formData = new FormData();
            formData.append('file', file);

            fetch('/api/chatbot/knowledge/upload', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                this.isUploading = false;
                if (data.success) {
                    this.knowledgeBases.unshift(data.data);
                    alert('PDF subido y procesado con éxito');
                } else {
                    alert('Error al subir PDF: ' + data.message);
                }
            })
            .catch(err => {
                this.isUploading = false;
                alert('Error de conexión');
            });

            event.target.value = ''; // Reset input
        },
        addUrl() {
            const url = prompt("Ingresa la URL pública a procesar (Ej. https://misitio.com/faq):");
            if (!url) return;

            try {
                new URL(url);
            } catch (e) {
                alert("URL inválida.");
                return;
            }

            this.isUploading = true;
            fetch('/api/chatbot/knowledge/url', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ url: url })
            })
            .then(res => res.json())
            .then(data => {
                this.isUploading = false;
                if (data.success) {
                    this.knowledgeBases.unshift(data.data);
                    alert('URL procesada con éxito');
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(err => {
                this.isUploading = false;
                alert('Error de conexión');
            });
        },
        deleteKnowledgeBase(id) {
            if(!confirm("¿Estás seguro de eliminar este documento de la base de conocimientos? La IA lo olvidará.")) return;

            fetch('/api/chatbot/knowledge/' + id, {
                method: 'DELETE',
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    this.knowledgeBases = this.knowledgeBases.filter(kb => kb.id !== id);
                }
            });
        },
        saveSettings() {
            this.isLoading = true;
            fetch('/api/chatbot/settings', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    temperature: this.temperature,
                    system_prompt: this.systemPrompt,
                    is_active: this.isActive
                })
            }).then(res => res.json())
              .then(data => {
                  this.isLoading = false;
                  if(data.success) {
                      alert('Ajustes guardados correctamente.');
                  }
              });
        },
        get activeTabName() {
            return this.tabs.find(t => t.id === this.activeTab).name;
        }
    }
}
</script>
@endsection
