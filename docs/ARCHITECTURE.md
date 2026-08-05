# 🏗️ Smart AI Hosting Solutions — Documento de Arquitectura

> **Versión:** 2.0  
> **Fecha de Creación:** 2026-08-05  
> **Última Actualización:** 2026-08-05  
> **Tipo de Proyecto:** B2B SaaS — Plataforma Comercial  
> **Metodología:** MVP Iterativo (Agile)

---

## 1. Visión General del Proyecto

**Smart AI Hosting Solutions** es una plataforma SaaS comercial que ofrece soluciones "llave en mano"
a clientes B2B (clínicas, restaurantes, e-commerce). Incluye:

- Venta y configuración de hosting.
- Venta y administración de dominios.
- Desarrollo de chatbots inteligentes.
- Integración con WhatsApp Business, Facebook Messenger e Instagram Direct.
- Automatización de respuestas frecuentes.
- Implementación de asistentes virtuales con IA.
- Sistemas automáticos de reservas y citas.
- Integración con APIs de Inteligencia Artificial.
- Soporte técnico y mantenimiento.
- **Bandeja Omnicanal Unificada** con gestión de equipos.
- **CRM integrado** (oportunidades de venta, etiquetas, agenda).
- **Chat interno y llamadas** entre trabajadores.
- **Secuencias automáticas** de mensajes.

### Modelo de Negocio

| Aspecto              | Detalle                                                                                   |
|----------------------|-------------------------------------------------------------------------------------------|
| **Tipo**             | SaaS Multi-tenant (Base de datos única con aislamiento por `tenant_id`)                   |
| **Clientes**         | Empresas B2B: clínicas, restaurantes, tiendas e-commerce                                  |
| **Monetización**     | Suscripciones mensuales/anuales con planes escalonados                                    |
| **Planes Ofrecidos** | **Plan Básico** (IA local), **Plan Avanzado** (Gemini API), **Plan Enterprise** (ambos)   |

---

## 2. Arquitectura de la Plataforma (3 Bloques)

```
┌─────────────────────────────────────────────────────────────────┐
│                    SMART AI HOSTING SOLUTIONS                   │
├───────────────────┬──────────────────────┬──────────────────────┤
│  BLOQUE 1         │  BLOQUE 2            │  BLOQUE 3            │
│  Landing Page     │  Panel Admin B2B     │  Motor Omnicanal     │
│  Pública          │  (Dashboard SaaS)    │  (Core del SaaS)     │
├───────────────────┼──────────────────────┼──────────────────────┤
│ - Presentación    │ - Vista SuperAdmin   │ - Webhook Laravel    │
│ - Planes/Precios  │   (Propietario)      │ - Enrutamiento IA    │
│ - Registro de     │ - Vista Cliente      │ - Bandeja de Entrada │
│   clientes        │   (Inquilino/Tenant) │   Multiagente        │
└───────────────────┴──────────────────────┴──────────────────────┘
```

### Bloque 1: Landing Page Pública
- Sitio web corporativo con presentación del servicio.
- Planes de precios reales.
- Embudo de captación y registro de nuevos clientes.

### Bloque 2: Panel Administrativo B2B (Dashboard SaaS)
- **Vista SuperAdmin (Propietario del SaaS):** Gestión comercial de Tenants, control de
  suscripciones, límites de consumo de APIs, monitoreo global.
- **Vista Admin del Tenant:** Control total de configuración del negocio.
- **Vista Supervisor:** Ve TODAS las conversaciones, métricas y ROI.
- **Vista Agente:** Solo ve sus conversaciones asignadas.

### Bloque 3: Motor Omnicanal (Core del SaaS)
- **Webhook de alto rendimiento** en Laravel para procesar mensajes entrantes
  (WhatsApp, Messenger, Instagram) en tiempo real.
- **Enrutamiento inteligente:** Detecta canal → Identifica Tenant → Consulta base de
  conocimiento → Envía contexto a IA → Devuelve respuesta automatizada.
- **Bandeja Omnicanal Unificada:** Vista en vivo con WhatsApp, Instagram y Messenger
  en una sola ventana. El equipo ve en línea lo que el chatbot conversa con el cliente.
- **Asignación Automática:** Reglas basadas en canal, horario, carga de trabajo o tema.
  Las conversaciones llegan al agente correcto automáticamente.
- **Notas Internas y Menciones:** Notas visibles solo para el equipo con @menciones.
  Todo queda documentado y visible.
- **Historial Completo de Asignaciones:** Cuando un cliente es reasignado, el nuevo
  agente ve todo el historial sin que el cliente repita nada.
- **CRM Integrado:** Pipeline de ventas (oportunidades), agenda de citas/reservas y
  sistema de etiquetas para segmentar contactos.
- **Chat Interno del Equipo:** Comunicación por chat y llamadas entre trabajadores.
- **Secuencias Automáticas:** Flujos de mensajes programados en el tiempo.

---

## 3. Stack Tecnológico Completo

### 3.1 Backend (Lógica del Servidor)

| Tecnología      | Versión  | Rol en el Proyecto                                                                              |
|-----------------|----------|-------------------------------------------------------------------------------------------------|
| **PHP**         | 8.2+     | Lenguaje principal. Todo el backend corre sobre PHP a través de Laravel.                        |
| **Laravel**     | 11.x     | Framework PHP. Eloquent ORM, Migraciones, Middleware, Jobs/Queues, Blade.                       |
| **Python**      | 3.x      | Lenguaje secundario. Exclusivo para el microservicio de IA local (Plan Básico).                 |

### 3.2 Base de Datos

| Tecnología      | Versión  | Rol en el Proyecto                                                                              |
|-----------------|----------|-------------------------------------------------------------------------------------------------|
| **MySQL**       | 8.0+     | Motor único. Almacena toda la data multi-tenant. Se interactúa vía Eloquent ORM.                |

### 3.3 Frontend (Navegador)

| Tecnología      | Versión  | Rol en el Proyecto                                                                              |
|-----------------|----------|-------------------------------------------------------------------------------------------------|
| **Blade**       | (Laravel)| Motor de plantillas. Archivos `.blade.php` con herencia, componentes y directivas.              |
| **Tailwind CSS**| 3.x      | Framework CSS utilitario. Estilizado rápido del Dashboard y Landing Page.                       |
| **Alpine.js**   | 3.x      | Micro-framework JS (~15kb). Interactividad: modales, tabs, dropdowns, bandeja en vivo.          |
| **HTML5**       | —        | Estructura de vistas (a través de Blade).                                                       |
| **JavaScript**  | ES6+     | Interactividad del navegador (a través de Alpine.js).                                           |

### 3.4 Integraciones API

| Servicio                     | Protocolo     | Uso                                                                           |
|------------------------------|---------------|-------------------------------------------------------------------------------|
| **WhatsApp Business API**    | REST + JSON   | Recibir/enviar mensajes de WhatsApp (Cloud API de Meta).                      |
| **Facebook Messenger API**   | REST + JSON   | Recibir/enviar mensajes de Messenger.                                         |
| **Instagram Direct API**     | REST + JSON   | Recibir/enviar mensajes de Instagram.                                         |
| **Google Gemini API**        | REST + JSON   | Motor de IA premium (Plan Avanzado).                                          |
| **Motor IA Local (Python)**  | HTTP interno  | Motor de IA económico (Plan Básico). Comunicación interna Laravel ↔ Python.   |

### 3.5 Infraestructura y DevOps

| Tecnología      | Rol en el Proyecto                                                                              |
|-----------------|-------------------------------------------------------------------------------------------------|
| **cPanel**      | Panel del servidor. Configuración de dominio, SSL, MySQL, Cron, Setup Python App.               |
| **Composer**    | Gestor de paquetes PHP. Instala Laravel y dependencias.                                         |
| **NPM / Node**  | Gestor de paquetes JS. Solo en desarrollo para compilar Tailwind/Alpine con Vite.              |
| **Vite**        | Bundler de assets. Compila y optimiza CSS/JS para producción. Integrado con Laravel.            |
| **Git**         | Control de versiones del código fuente.                                                         |

### 3.6 Formatos de Datos

| Formato | Uso                                                                                              |
|---------|--------------------------------------------------------------------------------------------------|
| **JSON**| APIs externas, respuestas de Laravel, datos flexibles en MySQL (horarios, FAQs).                 |
| **SQL** | Generado automáticamente por Eloquent. Visible solo en logs de depuración.                       |

---

## 4. Diseño de Base de Datos (Multi-Tenant — DB Única)

### 4.1 Principio de Aislamiento

Todas las tablas que contienen datos de clientes incluyen una columna `tenant_id` (FK) con índice.
Laravel usa **Global Scopes** en los modelos Eloquent para que un Tenant **jamás** pueda acceder
a los datos de otro.

### 4.2 Diagrama Entidad-Relación (ERD)

```
┌──────────────────────┐
│ subscription_plans   │
│──────────────────────│
│ PK id                │
│    name              │
│    price             │
│    billing_cycle     │          ┌──────────────────────┐
│    max_agents        │          │ users                │
│    message_limit     │          │──────────────────────│
│    ai_engine_allowed │          │ PK id                │
└──────────┬───────────┘          │ FK tenant_id ────────┼──┐
           │ 1:N                  │    name              │  │
           ▼                      │    email             │  │
┌──────────────────────┐          │    password          │  │
│ tenants              │          │    role (enum)       │  │
│──────────────────────│ 1:N      └──────────┬───────────┘  │
│ PK id                │◄─────────────────────┘             │
│ FK subscription_plan │                                    │
│    company_name      │ 1:1  ┌──────────────────────┐      │
│    contact_email     │─────►│ chatbot_knowledges   │      │
│    status (enum)     │      │──────────────────────│      │
│    subscription_ends │      │ PK id                │      │
│    current_msg_count │      │ FK tenant_id         │      │
└──────────┬───────────┘      │    system_prompt     │      │
           │                  │    business_hours    │      │
           │ 1:N              │    faqs (json)       │      │
           ▼                  │    is_bot_active     │      │
┌──────────────────────┐      └──────────────────────┘      │
│ contacts             │                                    │
│──────────────────────│                                    │
│ PK id                │                                    │
│ FK tenant_id         │      ┌──────────────────────┐      │
│    name              │      │ messages             │      │
│    phone_number      │      │──────────────────────│      │
│    messenger_id      │      │ PK id                │      │
│    instagram_id      │      │ FK tenant_id ────────┼──────┘
│    last_interaction  │◄─────│ FK contact_id        │
└──────────────────────┘ 1:N  │ FK user_id (nullable)│
                              │    channel (enum)    │
                              │    direction (enum)  │
                              │    content           │
                              │    is_ai_generated   │
                              │    status (enum)     │
                              └──────────────────────┘
```

### 4.3 Detalle de Entidades

---

#### 📋 `subscription_plans` — Planes de Suscripción

| Campo                       | Tipo           | Descripción                                                        |
|-----------------------------|----------------|--------------------------------------------------------------------|
| `id`                        | BIGINT PK      | Identificador único                                                |
| `name`                      | VARCHAR(100)   | Nombre del plan (Ej: "Plan Básico", "Plan Avanzado")               |
| `slug`                      | VARCHAR(100)   | Identificador URL-friendly único                                   |
| `price`                     | DECIMAL(10,2)  | Precio del plan                                                    |
| `billing_cycle`             | ENUM           | Ciclo de facturación: `monthly`, `yearly`                          |
| `max_agents`                | INT            | Máximo de agentes humanos permitidos por cuenta                    |
| `message_limit_per_month`   | INT            | **Interruptor de presupuesto.** Límite de mensajes IA por mes      |
| `ai_engine_allowed`         | ENUM           | Motor de IA: `local` (Python), `gemini` (API Google)               |
| `features`                  | JSON           | Características adicionales del plan en formato flexible           |
| `is_active`                 | BOOLEAN        | Si el plan está disponible para nuevas contrataciones              |
| `timestamps`                | DATETIME       | `created_at`, `updated_at`                                         |

**Relaciones:** Un plan → muchas empresas (1:N).

---

#### 🏢 `tenants` — Empresas / Inquilinos

| Campo                       | Tipo           | Descripción                                                        |
|-----------------------------|----------------|--------------------------------------------------------------------|
| `id`                        | BIGINT PK      | Identificador único                                                |
| `subscription_plan_id`      | BIGINT FK      | Plan contratado → `subscription_plans.id`                          |
| `company_name`              | VARCHAR(150)   | Nombre de la empresa cliente                                       |
| `slug`                      | VARCHAR(150)   | Identificador URL-friendly único                                   |
| `contact_email`             | VARCHAR(255)   | Email principal de contacto                                        |
| `phone`                     | VARCHAR(20)    | Teléfono de contacto                                               |
| `status`                    | ENUM           | Estado: `trial`, `active`, `suspended`, `cancelled`                |
| `subscription_starts_at`    | DATETIME       | Inicio de la suscripción actual                                    |
| `subscription_ends_at`      | DATETIME       | Fecha de corte / vencimiento                                       |
| `current_month_message_count`| INT           | Contador dinámico de mensajes IA del mes actual                    |
| `api_token`                 | VARCHAR(100)   | Token de identificación para Webhooks (encriptado)                 |
| `settings`                  | JSON           | Configuraciones adicionales flexibles                              |
| `timestamps`                | DATETIME       | `created_at`, `updated_at`                                         |
| `soft_deletes`              | DATETIME       | `deleted_at` — No se elimina, se marca como borrado               |

**Relaciones:**
- Pertenece a un plan (N:1 → `subscription_plans`).
- Tiene muchos usuarios (1:N → `users`).
- Tiene una configuración de chatbot (1:1 → `chatbot_knowledges`).
- Tiene muchos contactos (1:N → `contacts`).
- Tiene muchos mensajes (1:N → `messages`).

**Índices críticos:** `subscription_plan_id`, `status`, `api_token` (UNIQUE).

---

#### 👤 `users` — Usuarios del Sistema

| Campo                       | Tipo           | Descripción                                                        |
|-----------------------------|----------------|--------------------------------------------------------------------|
| `id`                        | BIGINT PK      | Identificador único                                                |
| `tenant_id`                 | BIGINT FK NULL | Empresa a la que pertenece. **NULL = SuperAdmin del SaaS**         |
| `name`                      | VARCHAR(150)   | Nombre completo                                                    |
| `email`                     | VARCHAR(255)   | Email único (usado para login)                                     |
| `password`                  | VARCHAR(255)   | Contraseña hasheada (bcrypt)                                       |
| `role`                      | ENUM           | `super_admin`, `tenant_owner`, `tenant_agent`                      |
| `is_active`                 | BOOLEAN        | Si el usuario puede iniciar sesión                                 |
| `email_verified_at`         | DATETIME       | Verificación de email                                              |
| `last_login_at`             | DATETIME       | Último acceso al sistema                                           |
| `remember_token`            | VARCHAR(100)   | Token de "Recuérdame" de Laravel                                   |
| `timestamps`                | DATETIME       | `created_at`, `updated_at`                                         |
| `soft_deletes`              | DATETIME       | `deleted_at`                                                       |

**Relaciones:**
- Pertenece a un Tenant (N:1 → `tenants`). Nullable para SuperAdmin.
- Puede tener muchos mensajes como agente (1:N → `messages`).
- Puede estar asignado a muchas conversaciones.
- Puede participar en chats internos.

**Índices críticos:** `tenant_id`, `email` (UNIQUE), `role`, `(tenant_id, is_available, is_online)`.

**Campos nuevos v2.0:**
- `is_available`: Disponible para asignación automática.
- `max_concurrent_conversations`: Límite de conversaciones simultáneas (para balanceo).
- `current_conversation_count`: Contador de conversaciones activas asignadas.

**Roles (Expandidos v2.0):**
| Rol                  | Acceso                                                                    |
|----------------------|---------------------------------------------------------------------------|
| `super_admin`        | Todo el sistema. Gestión de todos los Tenants. `tenant_id = NULL`         |
| `tenant_admin`       | Control total de config: bot, canales, reglas, usuarios, facturación.     |
| `tenant_supervisor`  | Ve TODAS las conversaciones del tenant. Métricas y ROI.                   |
| `tenant_agent`       | Solo ve sus conversaciones asignadas. Bandeja personal.                   |

---

#### 🤖 `chatbot_knowledges` — Base de Conocimiento del Bot

| Campo                       | Tipo           | Descripción                                                        |
|-----------------------------|----------------|--------------------------------------------------------------------|
| `id`                        | BIGINT PK      | Identificador único                                                |
| `tenant_id`                 | BIGINT FK UNQ  | Empresa dueña. **UNIQUE** = Relación 1:1                          |
| `bot_name`                  | VARCHAR(100)   | Nombre del asistente (Ej: "Asistente de Clínica Dental X")        |
| `system_prompt`             | TEXT           | Instrucción principal para la IA (personalidad, reglas, tono)      |
| `business_hours`            | JSON           | Horarios de atención estructurados por día                         |
| `catalog`                   | JSON           | Catálogo de productos/servicios del negocio                        |
| `faqs`                      | JSON           | Preguntas y respuestas frecuentes                                  |
| `welcome_message`           | TEXT           | Mensaje de bienvenida automático                                   |
| `out_of_hours_message`      | TEXT           | Mensaje fuera de horario                                           |
| `is_bot_active`             | BOOLEAN        | **Interruptor maestro.** Si `false`, el bot no responde.           |
| `timestamps`                | DATETIME       | `created_at`, `updated_at`                                         |

**Relaciones:** Pertenece a un Tenant (1:1 → `tenants`).

**Ejemplo `business_hours` (JSON):**
```json
{
  "monday":    {"open": "09:00", "close": "18:00"},
  "tuesday":   {"open": "09:00", "close": "18:00"},
  "saturday":  {"open": "09:00", "close": "13:00"},
  "sunday":    null
}
```

**Ejemplo `faqs` (JSON):**
```json
[
  {"question": "¿Cuál es su dirección?", "answer": "Av. Principal 123, Lima"},
  {"question": "¿Aceptan tarjetas?", "answer": "Sí, aceptamos Visa y Mastercard"}
]
```

---

#### 📇 `contacts` — Clientes Finales (Leads)

| Campo                       | Tipo           | Descripción                                                        |
|-----------------------------|----------------|--------------------------------------------------------------------|
| `id`                        | BIGINT PK      | Identificador único                                                |
| `tenant_id`                 | BIGINT FK      | Empresa a la que escribieron                                       |
| `name`                      | VARCHAR(150)   | Nombre del contacto (si se conoce)                                 |
| `phone_number`              | VARCHAR(20)    | Número de WhatsApp (formato internacional)                         |
| `messenger_id`              | VARCHAR(100)   | ID único de Facebook Messenger                                     |
| `instagram_id`              | VARCHAR(100)   | ID único de Instagram                                              |
| `email`                     | VARCHAR(255)   | Email del contacto (opcional)                                      |
| `metadata`                  | JSON           | Datos adicionales flexibles (país, idioma, etc.)                   |
| `last_interaction_at`       | DATETIME       | Fecha del último mensaje (útil para ventanas de 24h de Meta)       |
| `timestamps`                | DATETIME       | `created_at`, `updated_at`                                         |

**Relaciones:**
- Pertenece a un Tenant (N:1 → `tenants`).
- Tiene muchos mensajes (1:N → `messages`).

**Índices críticos:** `tenant_id`, `phone_number`, `messenger_id`, `instagram_id`.

---

#### 💬 `messages` — Historial Omnicanal (Tabla de Alto Tráfico)

| Campo                       | Tipo           | Descripción                                                        |
|-----------------------------|----------------|--------------------------------------------------------------------|
| `id`                        | BIGINT PK      | Identificador único                                                |
| `tenant_id`                 | BIGINT FK      | Empresa dueña del mensaje (aislamiento obligatorio)                |
| `contact_id`                | BIGINT FK      | Cliente final que envió/recibió el mensaje                         |
| `user_id`                   | BIGINT FK NULL | Agente humano que intervino. **NULL = respondió el bot**           |
| `channel`                   | ENUM           | Canal de origen: `whatsapp`, `messenger`, `instagram`              |
| `direction`                 | ENUM           | `inbound` (entrante del lead), `outbound` (saliente de la empresa)|
| `content`                   | TEXT           | Texto del mensaje                                                  |
| `media_url`                 | VARCHAR(500)   | URL de imagen/audio/documento adjunto (si aplica)                  |
| `media_type`                | VARCHAR(50)    | Tipo de media: `image`, `audio`, `document`, `video`               |
| `is_ai_generated`           | BOOLEAN        | `true` si la IA lo generó. **Clave para calcular ROI.**            |
| `ai_tokens_used`            | INT            | Tokens consumidos en esta interacción (para control de costos)     |
| `status`                    | ENUM           | Estado: `sent`, `delivered`, `read`, `failed`                      |
| `external_message_id`       | VARCHAR(255)   | ID del mensaje en la API externa (Meta) para tracking              |
| `timestamps`                | DATETIME       | `created_at`, `updated_at`                                         |

**Relaciones:**
- Pertenece a un Tenant (N:1 → `tenants`).
- Pertenece a un Contacto (N:1 → `contacts`).
- Pertenece a un Usuario/Agente (N:1 → `users`, nullable).

**Índices críticos:** `tenant_id`, `contact_id`, `channel`, `direction`, `created_at`.
Índice compuesto recomendado: `(tenant_id, contact_id, created_at)`.

> ⚠️ **NOTA:** Esta es la tabla de mayor volumen. En producción, considerar particionamiento
> por fecha o archivado de mensajes antiguos (>6 meses) a una tabla `messages_archive`.

---

## 5. Flujo del Webhook (Core del Sistema)

```
1. Entra mensaje de WhatsApp/Messenger/Instagram
                    │
                    ▼
2. Webhook Laravel recibe la petición POST
                    │
                    ▼
3. Identifica el canal (channel) y el Tenant (api_token)
                    │
                    ▼
4. Verifica:
   ├── ¿Tenant activo?              → Si NO → Ignorar mensaje
   ├── ¿Cuota de mensajes agotada?  → Si SÍ → Respuesta de cortesía + Notificar al cliente
   └── ¿Bot activo?                 → Si NO → Solo guardar mensaje (intervención humana)
                    │
                    ▼
5. Busca o crea el Contacto (contact)
                    │
                    ▼
6. Guarda el mensaje entrante (direction = inbound)
                    │
                    ▼
7. Consulta la Base de Conocimiento (chatbot_knowledges)
                    │
                    ▼
8. Envía contexto a la IA:
   ├── Plan Básico  → Motor Python local (HTTP interno)
   └── Plan Avanzado → Google Gemini API (HTTPS externo)
                    │
                    ▼
9. Recibe respuesta de la IA
                    │
                    ▼
10. Guarda el mensaje saliente (direction = outbound, is_ai_generated = true)
                    │
                    ▼
11. Envía la respuesta al usuario final vía API de Meta
                    │
                    ▼
12. Incrementa current_month_message_count del Tenant
```

---

## 6. Seguridad y Buenas Prácticas

### 6.1 Autenticación y Autorización
- **Laravel Sanctum** o **Session-based Auth** para el Dashboard.
- **Middleware por rol:** `super_admin`, `tenant_owner`, `tenant_agent`.
- **Global Scopes en Eloquent:** Filtro automático por `tenant_id` en todas las consultas.

### 6.2 Protección de Datos
- Contraseñas hasheadas con **bcrypt** (estándar de Laravel).
- **Encriptación** de tokens API sensibles (`api_token` del Tenant).
- **Validación estricta** con FormRequests en todas las entradas de datos.
- **CSRF Protection** activa en todas las rutas web.

### 6.3 Control de Costos (Interruptor de Presupuesto)
- El campo `current_month_message_count` en `tenants` se compara contra
  `message_limit_per_month` del `subscription_plans` asociado.
- Si se alcanza el límite, el bot se pausa automáticamente y se notifica al cliente.
- Se reinicia el contador cada mes vía **Cron Job** de Laravel (`php artisan schedule:run`).

### 6.4 Rendimiento del Webhook
- Los mensajes entrantes se procesan con **Jobs/Queues** de Laravel para evitar
  cuellos de botella (si el hosting lo soporta).
- Alternativa en cPanel compartido: **Cron Jobs** periódicos para procesar una cola
  basada en base de datos (`database` driver).

---

## 7. Metodología de Desarrollo

### Enfoque MVP Iterativo

```
Fase 1 (MVP Core)
├── Migraciones de base de datos (6 tablas core)
├── Modelos Eloquent con relaciones y Global Scopes
├── Webhook funcional para WhatsApp
├── Integración con IA (al menos un motor)
└── Dashboard básico del cliente (configurar bot + ver mensajes)

Fase 2 (Expansión)
├── Landing Page pública con registro
├── Panel SuperAdmin completo
├── Integración con Messenger e Instagram
├── Bandeja de Entrada Multiagente en vivo
└── Métricas y ROI para el cliente

Fase 3 (Optimización Comercial)
├── Sistema de facturación y pagos
├── Notificaciones por email
├── Archivado automático de mensajes antiguos
├── Dashboard analítico avanzado
└── Documentación API para integraciones
```

### Regla de Oro para Migraciones en Producción

> **NUNCA modificar un archivo de migración ya ejecutado.**
> Siempre crear una nueva migración para alterar tablas existentes.

```bash
# ✅ Correcto: Crear nueva migración para agregar un campo
php artisan make:migration add_country_code_to_contacts_table --table=contacts

# ❌ Incorrecto: Editar la migración original create_contacts_table
```

---

## 8. Estructura de Archivos del Proyecto (Estándar Laravel)

```
chatbot_novape/
├── app/
│   ├── Http/
│   │   ├── Controllers/         # Controladores por módulo
│   │   ├── Middleware/           # Middleware de roles y tenant
│   │   └── Requests/            # FormRequests (validación estricta)
│   ├── Models/                  # Modelos Eloquent con relaciones
│   ├── Services/                # Lógica de negocio (AI, WhatsApp, etc.)
│   ├── Jobs/                    # Tareas en cola (procesar mensajes)
│   └── Scopes/                  # Global Scopes (TenantScope)
├── config/                      # Configuración de la app
├── database/
│   ├── migrations/              # Migraciones de BD (versionadas)
│   ├── seeders/                 # Datos de prueba
│   └── factories/               # Fábricas para testing
├── docs/                        # 📂 Documentación del proyecto
│   └── ARCHITECTURE.md          # ← ESTE ARCHIVO
├── resources/
│   └── views/                   # Vistas Blade (HTML + Tailwind)
├── routes/
│   ├── web.php                  # Rutas del Dashboard y Landing
│   └── api.php                  # Rutas API y Webhooks
├── public/                      # Assets públicos (CSS, JS compilados)
├── storage/                     # Logs, cache, archivos subidos
└── tests/                       # Tests unitarios y de integración
```

---

> **Próximo Paso:** Con este documento aprobado, proceder a generar las **migraciones de Laravel**
> optimizadas para las 6 tablas core con claves foráneas, índices y enums.
