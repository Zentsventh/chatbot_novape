-- ============================================================================
-- 🏗️ SMART AI HOSTING SOLUTIONS — SCRIPT COMPLETO DE BASE DE DATOS v2.0
-- ============================================================================
-- Versión:       2.0.0
-- Motor:         MySQL 8.0+
-- Charset:       utf8mb4 (soporte completo de emojis y caracteres especiales)
-- Collation:     utf8mb4_unicode_ci
-- Arquitectura:  Multi-tenant (base de datos única con aislamiento por tenant_id)
-- Fecha:         2026-08-05
-- Cambios v2.0:  Bandeja Omnicanal completa, CRM, Notas internas, Asignación
--                automática, Roles expandidos, Reservas/Citas, Chat interno,
--                Secuencias automáticas, Sistema de etiquetas.
-- ============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'STRICT_TRANS_TABLES,NO_ZERO_DATE,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';


-- ============================================================================
-- SECCIÓN 1: TABLAS DE INFRAESTRUCTURA DE LARAVEL
-- ============================================================================

CREATE TABLE IF NOT EXISTS `sessions` (
    `id`            VARCHAR(255) NOT NULL,
    `user_id`       BIGINT UNSIGNED NULL,
    `ip_address`    VARCHAR(45) NULL,
    `user_agent`    TEXT NULL,
    `payload`       LONGTEXT NOT NULL,
    `last_activity` INT NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_sessions_user_id` (`user_id`),
    INDEX `idx_sessions_last_activity` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache` (
    `key`        VARCHAR(255) NOT NULL,
    `value`      MEDIUMTEXT NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache_locks` (
    `key`        VARCHAR(255) NOT NULL,
    `owner`      VARCHAR(255) NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
    `email`      VARCHAR(255) NOT NULL,
    `token`      VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `jobs` (
    `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `queue`        VARCHAR(255) NOT NULL,
    `payload`      LONGTEXT NOT NULL,
    `attempts`     TINYINT UNSIGNED NOT NULL,
    `reserved_at`  INT UNSIGNED NULL,
    `available_at` INT UNSIGNED NOT NULL,
    `created_at`   INT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_jobs_queue` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_batches` (
    `id`             VARCHAR(255) NOT NULL,
    `name`           VARCHAR(255) NOT NULL,
    `total_jobs`     INT NOT NULL,
    `pending_jobs`   INT NOT NULL,
    `failed_jobs`    INT NOT NULL,
    `failed_job_ids` LONGTEXT NOT NULL,
    `options`        MEDIUMTEXT NULL,
    `cancelled_at`   INT NULL,
    `created_at`     INT NOT NULL,
    `finished_at`    INT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `failed_jobs` (
    `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `uuid`       VARCHAR(255) NOT NULL,
    `connection` TEXT NOT NULL,
    `queue`      TEXT NOT NULL,
    `payload`    LONGTEXT NOT NULL,
    `exception`  LONGTEXT NOT NULL,
    `failed_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_failed_jobs_uuid` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `migrations` (
    `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `migration` VARCHAR(255) NOT NULL,
    `batch`     INT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================================
-- SECCIÓN 2: TABLAS CORE DEL NEGOCIO
-- ============================================================================

-- ----------------------------------------------------------------------------
-- 2.1 subscription_plans (Planes de Suscripción)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `subscription_plans` (
    `id`                      BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`                    VARCHAR(100) NOT NULL COMMENT 'Nombre del plan',
    `slug`                    VARCHAR(100) NOT NULL,
    `description`             TEXT NULL COMMENT 'Descripción para la landing page',
    `price`                   DECIMAL(10,2) UNSIGNED NOT NULL DEFAULT 0.00,
    `currency`                VARCHAR(3) NOT NULL DEFAULT 'USD',
    `billing_cycle`           ENUM('monthly','yearly') NOT NULL DEFAULT 'monthly',
    `max_agents`              SMALLINT UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Máximo agentes humanos',
    `max_channels`            TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Máximo canales activos',
    `message_limit_per_month` INT UNSIGNED NOT NULL DEFAULT 1000 COMMENT 'Interruptor de presupuesto',
    `ai_engine_allowed`       ENUM('local','gemini','both') NOT NULL DEFAULT 'local',
    `has_crm`                 TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Acceso al módulo CRM (deals/oportunidades)',
    `has_appointments`        TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Acceso al sistema de reservas/citas',
    `has_sequences`           TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Acceso a secuencias automáticas',
    `has_internal_chat`       TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Chat interno del equipo',
    `has_auto_assignment`     TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Asignación automática de conversaciones',
    `features`                JSON NULL COMMENT 'Características adicionales flexibles',
    `sort_order`              TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `is_active`               TINYINT(1) NOT NULL DEFAULT 1,
    `created_at`              TIMESTAMP NULL DEFAULT NULL,
    `updated_at`              TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_subscription_plans_slug` (`slug`),
    INDEX `idx_subscription_plans_active` (`is_active`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Catálogo comercial de planes de suscripción del SaaS';

-- ----------------------------------------------------------------------------
-- 2.2 tenants (Empresas / Inquilinos)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tenants` (
    `id`                          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `subscription_plan_id`        BIGINT UNSIGNED NOT NULL,
    `company_name`                VARCHAR(150) NOT NULL,
    `slug`                        VARCHAR(150) NOT NULL,
    `legal_name`                  VARCHAR(200) NULL COMMENT 'Razón social',
    `tax_id`                      VARCHAR(30) NULL COMMENT 'RUC/NIF/Tax ID',
    `contact_email`               VARCHAR(255) NOT NULL,
    `phone`                       VARCHAR(20) NULL,
    `country`                     VARCHAR(3) NULL COMMENT 'ISO 3166-1 alpha-3',
    `timezone`                    VARCHAR(50) NOT NULL DEFAULT 'America/Lima',
    `logo_url`                    VARCHAR(500) NULL,
    `status`                      ENUM('trial','active','suspended','cancelled') NOT NULL DEFAULT 'trial',
    `trial_ends_at`               TIMESTAMP NULL DEFAULT NULL,
    `subscription_starts_at`      TIMESTAMP NULL DEFAULT NULL,
    `subscription_ends_at`        TIMESTAMP NULL DEFAULT NULL,
    `current_month_message_count` INT UNSIGNED NOT NULL DEFAULT 0,
    `message_count_reset_at`      TIMESTAMP NULL DEFAULT NULL,
    `api_token`                   VARCHAR(100) NULL COMMENT 'Token webhook (encriptado)',
    `settings`                    JSON NULL,
    `notes`                       TEXT NULL COMMENT 'Notas internas del SuperAdmin',
    `created_at`                  TIMESTAMP NULL DEFAULT NULL,
    `updated_at`                  TIMESTAMP NULL DEFAULT NULL,
    `deleted_at`                  TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_tenants_slug` (`slug`),
    UNIQUE KEY `uk_tenants_api_token` (`api_token`),
    INDEX `idx_tenants_subscription_plan` (`subscription_plan_id`),
    INDEX `idx_tenants_status` (`status`),
    INDEX `idx_tenants_deleted_at` (`deleted_at`),
    CONSTRAINT `fk_tenants_subscription_plan`
        FOREIGN KEY (`subscription_plan_id`) REFERENCES `subscription_plans` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Empresas clientes (inquilinos). Corazón multi-tenant';

-- ----------------------------------------------------------------------------
-- 2.3 users (Usuarios del Sistema) — ROLES EXPANDIDOS v2.0
-- Roles: super_admin | tenant_admin | tenant_supervisor | tenant_agent
-- super_admin:      Dueño del SaaS (tenant_id = NULL)
-- tenant_admin:     Control total de config del tenant
-- tenant_supervisor: Ve TODAS las conversaciones del tenant
-- tenant_agent:     Ve SOLO sus conversaciones asignadas
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
    `id`                            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tenant_id`                     BIGINT UNSIGNED NULL COMMENT 'NULL = SuperAdmin del SaaS',
    `name`                          VARCHAR(150) NOT NULL,
    `email`                         VARCHAR(255) NOT NULL,
    `email_verified_at`             TIMESTAMP NULL DEFAULT NULL,
    `password`                      VARCHAR(255) NOT NULL,
    `role`                          ENUM('super_admin','tenant_admin','tenant_supervisor','tenant_agent') NOT NULL DEFAULT 'tenant_agent',
    `avatar_url`                    VARCHAR(500) NULL,
    `is_active`                     TINYINT(1) NOT NULL DEFAULT 1,
    `is_online`                     TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Conectado en la bandeja',
    `is_available`                  TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Disponible para recibir asignaciones automáticas',
    `max_concurrent_conversations`  TINYINT UNSIGNED NOT NULL DEFAULT 10 COMMENT 'Máximo de conversaciones simultáneas (para balanceo de carga)',
    `current_conversation_count`    TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Conversaciones activas asignadas actualmente',
    `last_login_at`                 TIMESTAMP NULL DEFAULT NULL,
    `last_login_ip`                 VARCHAR(45) NULL,
    `remember_token`                VARCHAR(100) NULL,
    `created_at`                    TIMESTAMP NULL DEFAULT NULL,
    `updated_at`                    TIMESTAMP NULL DEFAULT NULL,
    `deleted_at`                    TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_users_email` (`email`),
    INDEX `idx_users_tenant_id` (`tenant_id`),
    INDEX `idx_users_role` (`role`),
    INDEX `idx_users_tenant_role` (`tenant_id`, `role`),
    INDEX `idx_users_available` (`tenant_id`, `is_available`, `is_online`),
    INDEX `idx_users_deleted_at` (`deleted_at`),
    CONSTRAINT `fk_users_tenant`
        FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Usuarios: SuperAdmin, Admins, Supervisores y Agentes';

-- ----------------------------------------------------------------------------
-- 2.4 chatbot_knowledges (Base de Conocimiento del Bot)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `chatbot_knowledges` (
    `id`                     BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tenant_id`              BIGINT UNSIGNED NOT NULL,
    `bot_name`               VARCHAR(100) NOT NULL DEFAULT 'Asistente Virtual',
    `system_prompt`          TEXT NULL,
    `business_hours`         JSON NULL,
    `catalog`                JSON NULL,
    `faqs`                   JSON NULL,
    `custom_instructions`    TEXT NULL,
    `welcome_message`        TEXT NULL,
    `out_of_hours_message`   TEXT NULL,
    `bot_paused_message`     TEXT NULL,
    `quota_exceeded_message` TEXT NULL,
    `is_bot_active`          TINYINT(1) NOT NULL DEFAULT 1,
    `max_context_messages`   TINYINT UNSIGNED NOT NULL DEFAULT 10,
    `ai_temperature`         DECIMAL(2,1) NOT NULL DEFAULT 0.7,
    `created_at`             TIMESTAMP NULL DEFAULT NULL,
    `updated_at`             TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_chatbot_knowledges_tenant` (`tenant_id`),
    CONSTRAINT `fk_chatbot_knowledges_tenant`
        FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Base de conocimiento del bot (1:1 con tenants)';

-- ----------------------------------------------------------------------------
-- 2.5 tenant_channels (Configuración de Canales por Tenant)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tenant_channels` (
    `id`                   BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tenant_id`            BIGINT UNSIGNED NOT NULL,
    `channel`              ENUM('whatsapp','messenger','instagram') NOT NULL,
    `channel_name`         VARCHAR(100) NULL,
    `phone_number_id`      VARCHAR(50) NULL,
    `whatsapp_business_id` VARCHAR(50) NULL,
    `page_id`              VARCHAR(50) NULL,
    `instagram_account_id` VARCHAR(50) NULL,
    `access_token`         TEXT NULL COMMENT 'ENCRIPTADO en Laravel',
    `webhook_verify_token` VARCHAR(100) NULL,
    `is_active`            TINYINT(1) NOT NULL DEFAULT 1,
    `is_verified`          TINYINT(1) NOT NULL DEFAULT 0,
    `last_webhook_at`      TIMESTAMP NULL DEFAULT NULL,
    `settings`             JSON NULL,
    `created_at`           TIMESTAMP NULL DEFAULT NULL,
    `updated_at`           TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_tenant_channels_tenant` (`tenant_id`, `channel`),
    INDEX `idx_tenant_channels_phone` (`phone_number_id`),
    INDEX `idx_tenant_channels_page` (`page_id`),
    INDEX `idx_tenant_channels_instagram` (`instagram_account_id`),
    CONSTRAINT `fk_tenant_channels_tenant`
        FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Configuración de canales de mensajería por tenant';

-- ----------------------------------------------------------------------------
-- 2.6 contacts (Clientes Finales / Leads) — EXPANDIDO v2.0 con CRM
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `contacts` (
    `id`                  BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tenant_id`           BIGINT UNSIGNED NOT NULL,
    `name`                VARCHAR(150) NULL,
    `phone_number`        VARCHAR(20) NULL,
    `messenger_id`        VARCHAR(100) NULL,
    `instagram_id`        VARCHAR(100) NULL,
    `email`               VARCHAR(255) NULL,
    `company`             VARCHAR(150) NULL COMMENT 'Empresa del contacto (visible en sidebar CRM)',
    `profile_picture_url` VARCHAR(500) NULL,
    `notes`               TEXT NULL COMMENT 'Notas generales sobre el contacto',
    `metadata`            JSON NULL,
    `is_blocked`          TINYINT(1) NOT NULL DEFAULT 0,
    `assigned_user_id`    BIGINT UNSIGNED NULL COMMENT 'Agente principal asignado al contacto',
    `first_interaction_at` TIMESTAMP NULL DEFAULT NULL,
    `last_interaction_at` TIMESTAMP NULL DEFAULT NULL,
    `created_at`          TIMESTAMP NULL DEFAULT NULL,
    `updated_at`          TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_contacts_tenant_id` (`tenant_id`),
    INDEX `idx_contacts_phone` (`tenant_id`, `phone_number`),
    INDEX `idx_contacts_messenger` (`tenant_id`, `messenger_id`),
    INDEX `idx_contacts_instagram` (`tenant_id`, `instagram_id`),
    INDEX `idx_contacts_last_interaction` (`tenant_id`, `last_interaction_at`),
    INDEX `idx_contacts_assigned` (`tenant_id`, `assigned_user_id`),
    CONSTRAINT `fk_contacts_tenant`
        FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_contacts_assigned_user`
        FOREIGN KEY (`assigned_user_id`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Directorio unificado de clientes finales (leads) con CRM';

-- ----------------------------------------------------------------------------
-- 2.7 conversations (Hilos de Conversación) — EXPANDIDO v2.0
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `conversations` (
    `id`                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tenant_id`         BIGINT UNSIGNED NOT NULL,
    `contact_id`        BIGINT UNSIGNED NOT NULL,
    `assigned_user_id`  BIGINT UNSIGNED NULL COMMENT 'Agente asignado actualmente',
    `channel`           ENUM('whatsapp','messenger','instagram') NOT NULL,
    `status`            ENUM('open','bot_active','human_active','waiting','resolved','closed') NOT NULL DEFAULT 'bot_active',
    `priority`          ENUM('low','normal','high','urgent') NOT NULL DEFAULT 'normal',
    `subject`           VARCHAR(255) NULL,
    `is_bot_paused`     TINYINT(1) NOT NULL DEFAULT 0,
    `bot_paused_at`     TIMESTAMP NULL DEFAULT NULL,
    `bot_paused_by`     BIGINT UNSIGNED NULL,
    `auto_assigned`     TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'TRUE si fue asignada por regla automática',
    `assignment_rule_id` BIGINT UNSIGNED NULL COMMENT 'Regla que asignó esta conversación',
    `last_message_at`   TIMESTAMP NULL DEFAULT NULL,
    `last_message_preview` VARCHAR(255) NULL COMMENT 'Preview del último mensaje para la bandeja',
    `message_count`     INT UNSIGNED NOT NULL DEFAULT 0,
    `unread_count`      INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Mensajes no leídos por el agente',
    `resolved_at`       TIMESTAMP NULL DEFAULT NULL,
    `resolved_by`       BIGINT UNSIGNED NULL,
    `created_at`        TIMESTAMP NULL DEFAULT NULL,
    `updated_at`        TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_conversations_tenant_status` (`tenant_id`, `status`),
    INDEX `idx_conversations_tenant_channel` (`tenant_id`, `channel`),
    INDEX `idx_conversations_contact` (`contact_id`),
    INDEX `idx_conversations_assigned_user` (`assigned_user_id`),
    INDEX `idx_conversations_last_message` (`tenant_id`, `last_message_at` DESC),
    INDEX `idx_conversations_priority` (`tenant_id`, `priority`, `status`),
    INDEX `idx_conversations_unread` (`tenant_id`, `assigned_user_id`, `unread_count`),
    CONSTRAINT `fk_conversations_tenant`
        FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_conversations_contact`
        FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_conversations_assigned_user`
        FOREIGN KEY (`assigned_user_id`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT `fk_conversations_paused_by`
        FOREIGN KEY (`bot_paused_by`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT `fk_conversations_resolved_by`
        FOREIGN KEY (`resolved_by`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Hilos de conversación para la Bandeja Omnicanal';

-- ----------------------------------------------------------------------------
-- 2.8 messages (Historial Omnicanal — Big Data)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `messages` (
    `id`                   BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tenant_id`            BIGINT UNSIGNED NOT NULL,
    `conversation_id`      BIGINT UNSIGNED NOT NULL,
    `contact_id`           BIGINT UNSIGNED NOT NULL,
    `user_id`              BIGINT UNSIGNED NULL COMMENT 'NULL = respondió el bot o es entrante',
    `channel`              ENUM('whatsapp','messenger','instagram') NOT NULL,
    `direction`            ENUM('inbound','outbound') NOT NULL,
    `message_type`         ENUM('text','image','audio','video','document','sticker','location','contact','template','interactive','reaction','unsupported') NOT NULL DEFAULT 'text',
    `content`              TEXT NULL,
    `media_url`            VARCHAR(500) NULL,
    `media_mime_type`      VARCHAR(100) NULL,
    `media_file_size`      INT UNSIGNED NULL,
    `is_ai_generated`      TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'CLAVE PARA ROI',
    `ai_engine_used`       ENUM('local','gemini') NULL,
    `ai_tokens_used`       INT UNSIGNED NULL DEFAULT 0,
    `ai_response_time_ms`  INT UNSIGNED NULL,
    `is_internal_note`     TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'TRUE = Nota interna, NO se envía al cliente',
    `status`               ENUM('queued','sent','delivered','read','failed','deleted') NOT NULL DEFAULT 'queued',
    `error_message`        VARCHAR(500) NULL,
    `external_message_id`  VARCHAR(255) NULL,
    `metadata`             JSON NULL,
    `created_at`           TIMESTAMP NULL DEFAULT NULL,
    `updated_at`           TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_messages_tenant_created` (`tenant_id`, `created_at` DESC),
    INDEX `idx_messages_conversation` (`conversation_id`, `created_at` ASC),
    INDEX `idx_messages_contact` (`contact_id`, `created_at` DESC),
    INDEX `idx_messages_tenant_channel` (`tenant_id`, `channel`, `created_at` DESC),
    INDEX `idx_messages_external_id` (`external_message_id`),
    INDEX `idx_messages_ai_metrics` (`tenant_id`, `is_ai_generated`, `created_at`),
    INDEX `idx_messages_direction` (`tenant_id`, `direction`, `created_at` DESC),
    INDEX `idx_messages_internal` (`conversation_id`, `is_internal_note`),
    CONSTRAINT `fk_messages_tenant`
        FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_messages_conversation`
        FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_messages_contact`
        FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_messages_user`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Historial omnicanal. Tabla de mayor volumen (Big Data)';


-- ============================================================================
-- SECCIÓN 3: TABLAS DE COLABORACIÓN EN EQUIPO (NUEVO v2.0)
-- ============================================================================

-- ----------------------------------------------------------------------------
-- 3.1 conversation_notes (Notas Internas en Conversaciones)
-- Notas visibles SOLO para el equipo. El cliente NUNCA las ve.
-- Soporte para @menciones a compañeros de equipo.
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `conversation_notes` (
    `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tenant_id`       BIGINT UNSIGNED NOT NULL,
    `conversation_id` BIGINT UNSIGNED NOT NULL,
    `user_id`         BIGINT UNSIGNED NOT NULL COMMENT 'Quien escribió la nota',
    `content`         TEXT NOT NULL COMMENT 'Texto de la nota interna',
    `mentioned_users` JSON NULL COMMENT 'IDs de usuarios mencionados con @: [2, 5, 8]',
    `is_pinned`       TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Nota fijada arriba',
    `created_at`      TIMESTAMP NULL DEFAULT NULL,
    `updated_at`      TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_conv_notes_conversation` (`conversation_id`, `created_at` ASC),
    INDEX `idx_conv_notes_tenant` (`tenant_id`),
    CONSTRAINT `fk_conv_notes_tenant`
        FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_conv_notes_conversation`
        FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_conv_notes_user`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Notas internas en conversaciones (visibles solo para el equipo)';

-- ----------------------------------------------------------------------------
-- 3.2 conversation_assignments (Historial de Asignaciones)
-- Registra CADA cambio de agente para auditoría completa.
-- "Todo queda documentado y visible."
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `conversation_assignments` (
    `id`                     BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tenant_id`              BIGINT UNSIGNED NOT NULL,
    `conversation_id`        BIGINT UNSIGNED NOT NULL,
    `assigned_from_user_id`  BIGINT UNSIGNED NULL COMMENT 'Agente anterior (NULL = primera asignación)',
    `assigned_to_user_id`    BIGINT UNSIGNED NOT NULL COMMENT 'Nuevo agente asignado',
    `assigned_by_user_id`    BIGINT UNSIGNED NULL COMMENT 'Quién hizo la asignación (NULL = automática)',
    `assignment_type`        ENUM('auto','manual','transfer','escalation') NOT NULL DEFAULT 'manual',
    `assignment_rule_id`     BIGINT UNSIGNED NULL COMMENT 'Regla que la asignó (si auto)',
    `reason`                 VARCHAR(500) NULL COMMENT 'Motivo de la reasignación',
    `created_at`             TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_conv_assign_conversation` (`conversation_id`, `created_at` DESC),
    INDEX `idx_conv_assign_to_user` (`assigned_to_user_id`),
    INDEX `idx_conv_assign_tenant` (`tenant_id`, `created_at` DESC),
    CONSTRAINT `fk_conv_assign_tenant`
        FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_conv_assign_conversation`
        FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_conv_assign_from`
        FOREIGN KEY (`assigned_from_user_id`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT `fk_conv_assign_to`
        FOREIGN KEY (`assigned_to_user_id`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_conv_assign_by`
        FOREIGN KEY (`assigned_by_user_id`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Historial completo de asignaciones/transferencias de conversaciones';

-- ----------------------------------------------------------------------------
-- 3.3 assignment_rules (Reglas de Asignación Automática)
-- "Configura reglas basadas en canal, horario, carga de trabajo o tema"
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `assignment_rules` (
    `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tenant_id`       BIGINT UNSIGNED NOT NULL,
    `name`            VARCHAR(100) NOT NULL COMMENT 'Nombre de la regla',
    `description`     TEXT NULL,
    `priority`        TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Orden de evaluación (menor = primero)',
    `conditions`      JSON NOT NULL COMMENT '{"channel": "whatsapp", "hours": {"from": "09:00", "to": "18:00"}, "keywords": ["precio", "cita"]}',
    `target_type`     ENUM('specific_agent','round_robin','least_busy','random') NOT NULL DEFAULT 'round_robin',
    `target_user_id`  BIGINT UNSIGNED NULL COMMENT 'Agente específico (si target_type = specific_agent)',
    `fallback_action` ENUM('queue','assign_admin','leave_unassigned') NOT NULL DEFAULT 'queue' COMMENT 'Si no hay agente disponible',
    `is_active`       TINYINT(1) NOT NULL DEFAULT 1,
    `times_triggered` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Contador de veces que se activó',
    `created_at`      TIMESTAMP NULL DEFAULT NULL,
    `updated_at`      TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_assignment_rules_tenant` (`tenant_id`, `is_active`, `priority`),
    CONSTRAINT `fk_assignment_rules_tenant`
        FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_assignment_rules_user`
        FOREIGN KEY (`target_user_id`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Reglas de asignación automática de conversaciones';


-- ============================================================================
-- SECCIÓN 4: TABLAS CRM Y GESTIÓN COMERCIAL (NUEVO v2.0)
-- ============================================================================

-- ----------------------------------------------------------------------------
-- 4.1 tags (Sistema de Etiquetas)
-- Etiquetas personalizables para contactos y conversaciones.
-- Polimórfico: se aplican a múltiples entidades vía taggables.
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tags` (
    `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tenant_id`  BIGINT UNSIGNED NOT NULL,
    `name`       VARCHAR(50) NOT NULL,
    `slug`       VARCHAR(50) NOT NULL,
    `color`      VARCHAR(7) NOT NULL DEFAULT '#6B7280' COMMENT 'Color hex para la UI',
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_tags_tenant_slug` (`tenant_id`, `slug`),
    INDEX `idx_tags_tenant` (`tenant_id`),
    CONSTRAINT `fk_tags_tenant`
        FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Etiquetas personalizables por tenant';

-- ----------------------------------------------------------------------------
-- 4.2 taggables (Tabla Pivote Polimórfica)
-- Permite etiquetar Contacts, Conversations, o cualquier entidad futura.
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `taggables` (
    `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tag_id`         BIGINT UNSIGNED NOT NULL,
    `taggable_type`  VARCHAR(100) NOT NULL COMMENT 'Modelo: App\\Models\\Contact, App\\Models\\Conversation',
    `taggable_id`    BIGINT UNSIGNED NOT NULL COMMENT 'ID del registro etiquetado',
    `created_at`     TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_taggables_taggable` (`taggable_type`, `taggable_id`),
    INDEX `idx_taggables_tag` (`tag_id`),
    UNIQUE KEY `uk_taggables_unique` (`tag_id`, `taggable_type`, `taggable_id`),
    CONSTRAINT `fk_taggables_tag`
        FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Pivot polimórfico: etiquetas en contactos, conversaciones, etc.';

-- ----------------------------------------------------------------------------
-- 4.3 deals (Oportunidades de Venta / CRM)
-- Pipeline de ventas visible en el sidebar del contacto.
-- "01 VENTAS" y "02 OPORTUNIDADES" de la imagen.
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `deals` (
    `id`                  BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tenant_id`           BIGINT UNSIGNED NOT NULL,
    `contact_id`          BIGINT UNSIGNED NOT NULL,
    `assigned_user_id`    BIGINT UNSIGNED NULL COMMENT 'Agente responsable del deal',
    `title`               VARCHAR(200) NOT NULL COMMENT 'Título de la oportunidad',
    `description`         TEXT NULL,
    `value`               DECIMAL(12,2) UNSIGNED NULL COMMENT 'Valor monetario estimado',
    `currency`            VARCHAR(3) NOT NULL DEFAULT 'USD',
    `stage`               ENUM('lead','prospect','proposal','negotiation','won','lost') NOT NULL DEFAULT 'lead',
    `probability`         TINYINT UNSIGNED NULL COMMENT 'Probabilidad de cierre 0-100%',
    `expected_close_date` DATE NULL,
    `closed_at`           TIMESTAMP NULL DEFAULT NULL,
    `lost_reason`         VARCHAR(500) NULL,
    `metadata`            JSON NULL,
    `created_at`          TIMESTAMP NULL DEFAULT NULL,
    `updated_at`          TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_deals_tenant_stage` (`tenant_id`, `stage`),
    INDEX `idx_deals_contact` (`contact_id`),
    INDEX `idx_deals_assigned` (`assigned_user_id`),
    CONSTRAINT `fk_deals_tenant`
        FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_deals_contact`
        FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_deals_assigned_user`
        FOREIGN KEY (`assigned_user_id`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Oportunidades de venta (pipeline CRM)';

-- ----------------------------------------------------------------------------
-- 4.4 appointments (Reservas y Citas)
-- "Sistemas automáticos de reservas y citas"
-- "03 AGENDA" de la imagen.
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `appointments` (
    `id`               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tenant_id`        BIGINT UNSIGNED NOT NULL,
    `contact_id`       BIGINT UNSIGNED NOT NULL,
    `assigned_user_id` BIGINT UNSIGNED NULL COMMENT 'Profesional/agente asignado a la cita',
    `title`            VARCHAR(200) NOT NULL,
    `description`      TEXT NULL,
    `scheduled_at`     TIMESTAMP NOT NULL COMMENT 'Fecha y hora de la cita',
    `duration_minutes` SMALLINT UNSIGNED NOT NULL DEFAULT 30,
    `status`           ENUM('scheduled','confirmed','in_progress','completed','cancelled','no_show') NOT NULL DEFAULT 'scheduled',
    `location`         VARCHAR(300) NULL COMMENT 'Dirección física o link de videollamada',
    `notes`            TEXT NULL COMMENT 'Notas sobre la cita',
    `reminder_sent_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Cuándo se envió el recordatorio',
    `cancelled_at`     TIMESTAMP NULL DEFAULT NULL,
    `cancel_reason`    VARCHAR(500) NULL,
    `source`           ENUM('manual','chatbot','api') NOT NULL DEFAULT 'manual' COMMENT 'Quién creó la cita',
    `metadata`         JSON NULL,
    `created_at`       TIMESTAMP NULL DEFAULT NULL,
    `updated_at`       TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_appointments_tenant_date` (`tenant_id`, `scheduled_at`),
    INDEX `idx_appointments_contact` (`contact_id`),
    INDEX `idx_appointments_assigned` (`assigned_user_id`, `scheduled_at`),
    INDEX `idx_appointments_status` (`tenant_id`, `status`, `scheduled_at`),
    CONSTRAINT `fk_appointments_tenant`
        FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_appointments_contact`
        FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_appointments_assigned_user`
        FOREIGN KEY (`assigned_user_id`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Sistema de reservas y citas (agenda)';


-- ============================================================================
-- SECCIÓN 5: COMUNICACIÓN INTERNA DEL EQUIPO (NUEVO v2.0)
-- ============================================================================

-- ----------------------------------------------------------------------------
-- 5.1 internal_chats (Salas de Chat Interno)
-- Chat entre trabajadores del mismo tenant (directo o grupal).
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `internal_chats` (
    `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tenant_id`  BIGINT UNSIGNED NOT NULL,
    `name`       VARCHAR(100) NULL COMMENT 'Nombre del grupo (NULL para chat directo)',
    `type`       ENUM('direct','group') NOT NULL DEFAULT 'direct',
    `created_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_internal_chats_tenant` (`tenant_id`),
    CONSTRAINT `fk_internal_chats_tenant`
        FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_internal_chats_created_by`
        FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Salas de chat interno entre trabajadores';

-- ----------------------------------------------------------------------------
-- 5.2 internal_chat_participants (Participantes de Chat Interno)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `internal_chat_participants` (
    `id`               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `internal_chat_id` BIGINT UNSIGNED NOT NULL,
    `user_id`          BIGINT UNSIGNED NOT NULL,
    `role`             ENUM('member','admin') NOT NULL DEFAULT 'member',
    `last_read_at`     TIMESTAMP NULL DEFAULT NULL COMMENT 'Último mensaje leído (para conteo de no leídos)',
    `joined_at`        TIMESTAMP NULL DEFAULT NULL,
    `left_at`          TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_internal_chat_participants` (`internal_chat_id`, `user_id`),
    INDEX `idx_internal_chat_participants_user` (`user_id`),
    CONSTRAINT `fk_int_chat_part_chat`
        FOREIGN KEY (`internal_chat_id`) REFERENCES `internal_chats` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_int_chat_part_user`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Participantes de cada sala de chat interno';

-- ----------------------------------------------------------------------------
-- 5.3 internal_messages (Mensajes de Chat Interno)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `internal_messages` (
    `id`               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tenant_id`        BIGINT UNSIGNED NOT NULL,
    `internal_chat_id` BIGINT UNSIGNED NOT NULL,
    `user_id`          BIGINT UNSIGNED NOT NULL COMMENT 'Quien envió el mensaje',
    `content`          TEXT NULL,
    `message_type`     ENUM('text','image','file','audio','call_started','call_ended') NOT NULL DEFAULT 'text',
    `media_url`        VARCHAR(500) NULL,
    `call_duration_seconds` INT UNSIGNED NULL COMMENT 'Duración de la llamada (si aplica)',
    `created_at`       TIMESTAMP NULL DEFAULT NULL,
    `updated_at`       TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_internal_messages_chat` (`internal_chat_id`, `created_at` ASC),
    INDEX `idx_internal_messages_tenant` (`tenant_id`),
    CONSTRAINT `fk_int_msg_tenant`
        FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_int_msg_chat`
        FOREIGN KEY (`internal_chat_id`) REFERENCES `internal_chats` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_int_msg_user`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Mensajes del chat interno entre trabajadores';


-- ============================================================================
-- SECCIÓN 6: SECUENCIAS AUTOMÁTICAS (NUEVO v2.0)
-- ============================================================================

-- ----------------------------------------------------------------------------
-- 6.1 sequences (Secuencias de Mensajes Automáticos)
-- Flujos automatizados de mensajes programados en el tiempo.
-- "SECUENCIAS" de la imagen de referencia.
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `sequences` (
    `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tenant_id`    BIGINT UNSIGNED NOT NULL,
    `name`         VARCHAR(100) NOT NULL,
    `description`  TEXT NULL,
    `channel`      ENUM('whatsapp','messenger','instagram','all') NOT NULL DEFAULT 'all',
    `trigger_type` ENUM('manual','new_contact','tag_added','inactivity','appointment_reminder') NOT NULL DEFAULT 'manual',
    `trigger_config` JSON NULL COMMENT 'Config del trigger: {"tag_id": 5, "inactivity_hours": 24}',
    `is_active`    TINYINT(1) NOT NULL DEFAULT 0,
    `total_enrolled` INT UNSIGNED NOT NULL DEFAULT 0,
    `total_completed` INT UNSIGNED NOT NULL DEFAULT 0,
    `created_by`   BIGINT UNSIGNED NULL,
    `created_at`   TIMESTAMP NULL DEFAULT NULL,
    `updated_at`   TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_sequences_tenant` (`tenant_id`, `is_active`),
    CONSTRAINT `fk_sequences_tenant`
        FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_sequences_created_by`
        FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Secuencias de mensajes automáticos programados';

-- ----------------------------------------------------------------------------
-- 6.2 sequence_steps (Pasos de una Secuencia)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `sequence_steps` (
    `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `sequence_id`     BIGINT UNSIGNED NOT NULL,
    `step_order`      TINYINT UNSIGNED NOT NULL COMMENT 'Orden del paso (1, 2, 3...)',
    `delay_minutes`   INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Espera antes de ejecutar (0 = inmediato)',
    `message_content` TEXT NOT NULL COMMENT 'Contenido del mensaje a enviar',
    `message_type`    ENUM('text','image','template','interactive') NOT NULL DEFAULT 'text',
    `media_url`       VARCHAR(500) NULL,
    `condition`       JSON NULL COMMENT 'Condición para ejecutar: {"only_if_no_reply": true}',
    `created_at`      TIMESTAMP NULL DEFAULT NULL,
    `updated_at`      TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_sequence_steps_sequence` (`sequence_id`, `step_order`),
    CONSTRAINT `fk_sequence_steps_sequence`
        FOREIGN KEY (`sequence_id`) REFERENCES `sequences` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Pasos individuales dentro de una secuencia';

-- ----------------------------------------------------------------------------
-- 6.3 sequence_enrollments (Contactos en una Secuencia)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `sequence_enrollments` (
    `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tenant_id`       BIGINT UNSIGNED NOT NULL,
    `sequence_id`     BIGINT UNSIGNED NOT NULL,
    `contact_id`      BIGINT UNSIGNED NOT NULL,
    `current_step`    TINYINT UNSIGNED NOT NULL DEFAULT 1,
    `status`          ENUM('active','paused','completed','cancelled','failed') NOT NULL DEFAULT 'active',
    `next_step_at`    TIMESTAMP NULL DEFAULT NULL COMMENT 'Cuándo se ejecuta el siguiente paso',
    `enrolled_at`     TIMESTAMP NULL DEFAULT NULL,
    `completed_at`    TIMESTAMP NULL DEFAULT NULL,
    `cancelled_at`    TIMESTAMP NULL DEFAULT NULL,
    `cancel_reason`   VARCHAR(255) NULL,
    `created_at`      TIMESTAMP NULL DEFAULT NULL,
    `updated_at`      TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_seq_enroll_sequence` (`sequence_id`, `status`),
    INDEX `idx_seq_enroll_contact` (`contact_id`),
    INDEX `idx_seq_enroll_next_step` (`status`, `next_step_at`),
    INDEX `idx_seq_enroll_tenant` (`tenant_id`),
    UNIQUE KEY `uk_seq_enroll_active` (`sequence_id`, `contact_id`, `status`),
    CONSTRAINT `fk_seq_enroll_tenant`
        FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_seq_enroll_sequence`
        FOREIGN KEY (`sequence_id`) REFERENCES `sequences` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_seq_enroll_contact`
        FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Contactos inscritos en secuencias automáticas';


-- ============================================================================
-- SECCIÓN 7: TABLAS COMPLEMENTARIAS
-- ============================================================================

-- ----------------------------------------------------------------------------
-- 7.1 canned_responses (Respuestas Rápidas)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `canned_responses` (
    `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tenant_id`   BIGINT UNSIGNED NOT NULL,
    `title`       VARCHAR(100) NOT NULL,
    `shortcut`    VARCHAR(50) NULL,
    `content`     TEXT NOT NULL,
    `category`    VARCHAR(50) NULL,
    `is_active`   TINYINT(1) NOT NULL DEFAULT 1,
    `usage_count` INT UNSIGNED NOT NULL DEFAULT 0,
    `created_by`  BIGINT UNSIGNED NULL,
    `created_at`  TIMESTAMP NULL DEFAULT NULL,
    `updated_at`  TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_canned_responses_tenant` (`tenant_id`, `is_active`),
    INDEX `idx_canned_responses_shortcut` (`tenant_id`, `shortcut`),
    CONSTRAINT `fk_canned_responses_tenant`
        FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_canned_responses_created_by`
        FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Respuestas rápidas predefinidas para la bandeja';

-- ----------------------------------------------------------------------------
-- 7.2 api_usage_logs (Registro de Consumo de APIs)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `api_usage_logs` (
    `id`               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tenant_id`        BIGINT UNSIGNED NOT NULL,
    `service`          ENUM('gemini','local_ai','whatsapp','messenger','instagram') NOT NULL,
    `endpoint`         VARCHAR(255) NULL,
    `tokens_input`     INT UNSIGNED NOT NULL DEFAULT 0,
    `tokens_output`    INT UNSIGNED NOT NULL DEFAULT 0,
    `tokens_total`     INT UNSIGNED NOT NULL DEFAULT 0,
    `estimated_cost`   DECIMAL(10,6) UNSIGNED NOT NULL DEFAULT 0,
    `response_time_ms` INT UNSIGNED NULL,
    `status_code`      SMALLINT UNSIGNED NULL,
    `is_successful`    TINYINT(1) NOT NULL DEFAULT 1,
    `error_message`    TEXT NULL,
    `created_at`       TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_api_usage_tenant_service` (`tenant_id`, `service`, `created_at`),
    INDEX `idx_api_usage_created` (`created_at`),
    CONSTRAINT `fk_api_usage_tenant`
        FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Registro de consumo de APIs (Interruptor de Presupuesto)';

-- ----------------------------------------------------------------------------
-- 7.3 activity_logs (Auditoría)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `activity_logs` (
    `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tenant_id`      BIGINT UNSIGNED NULL,
    `user_id`        BIGINT UNSIGNED NULL,
    `action`         VARCHAR(100) NOT NULL,
    `entity_type`    VARCHAR(100) NULL,
    `entity_id`      BIGINT UNSIGNED NULL,
    `description`    TEXT NULL,
    `old_values`     JSON NULL,
    `new_values`     JSON NULL,
    `ip_address`     VARCHAR(45) NULL,
    `user_agent`     VARCHAR(500) NULL,
    `created_at`     TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_activity_logs_tenant` (`tenant_id`, `created_at` DESC),
    INDEX `idx_activity_logs_user` (`user_id`, `created_at` DESC),
    INDEX `idx_activity_logs_action` (`action`, `created_at` DESC),
    INDEX `idx_activity_logs_entity` (`entity_type`, `entity_id`),
    CONSTRAINT `fk_activity_logs_tenant`
        FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT `fk_activity_logs_user`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Registro de auditoría de acciones del sistema';

-- ----------------------------------------------------------------------------
-- 7.4 notifications (Notificaciones del Sistema — Laravel Notifications)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `notifications` (
    `id`              CHAR(36) NOT NULL,
    `type`            VARCHAR(255) NOT NULL,
    `notifiable_type` VARCHAR(255) NOT NULL,
    `notifiable_id`   BIGINT UNSIGNED NOT NULL,
    `data`            JSON NOT NULL,
    `read_at`         TIMESTAMP NULL DEFAULT NULL,
    `created_at`      TIMESTAMP NULL DEFAULT NULL,
    `updated_at`      TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_notifications_notifiable` (`notifiable_type`, `notifiable_id`),
    INDEX `idx_notifications_read` (`notifiable_id`, `read_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Notificaciones internas (Laravel Notifications)';


-- ============================================================================
-- SECCIÓN 8: VISTAS
-- ============================================================================

CREATE OR REPLACE VIEW `v_tenant_dashboard_stats` AS
SELECT
    t.id AS tenant_id,
    t.company_name,
    t.status AS tenant_status,
    sp.name AS plan_name,
    sp.message_limit_per_month,
    t.current_month_message_count,
    ROUND((t.current_month_message_count / NULLIF(sp.message_limit_per_month, 0)) * 100, 2) AS quota_used_percent,
    (SELECT COUNT(*) FROM contacts c WHERE c.tenant_id = t.id) AS total_contacts,
    (SELECT COUNT(*) FROM conversations cv WHERE cv.tenant_id = t.id AND cv.status IN ('open','bot_active','human_active','waiting')) AS open_conversations,
    (SELECT COUNT(*) FROM messages m WHERE m.tenant_id = t.id AND m.is_ai_generated = 1 AND m.created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')) AS ai_messages_this_month,
    (SELECT COUNT(*) FROM messages m WHERE m.tenant_id = t.id AND m.is_ai_generated = 0 AND m.direction = 'outbound' AND m.created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')) AS human_messages_this_month,
    (SELECT COUNT(*) FROM deals d WHERE d.tenant_id = t.id AND d.stage NOT IN ('won','lost')) AS active_deals,
    (SELECT COALESCE(SUM(d.value), 0) FROM deals d WHERE d.tenant_id = t.id AND d.stage = 'won' AND d.closed_at >= DATE_FORMAT(NOW(), '%Y-%m-01')) AS revenue_this_month,
    (SELECT COUNT(*) FROM appointments a WHERE a.tenant_id = t.id AND a.scheduled_at >= NOW() AND a.status IN ('scheduled','confirmed')) AS upcoming_appointments
FROM tenants t
JOIN subscription_plans sp ON t.subscription_plan_id = sp.id
WHERE t.deleted_at IS NULL;

CREATE OR REPLACE VIEW `v_agent_workload` AS
SELECT
    u.id AS user_id,
    u.tenant_id,
    u.name AS agent_name,
    u.role,
    u.is_online,
    u.is_available,
    u.max_concurrent_conversations,
    u.current_conversation_count,
    (u.max_concurrent_conversations - u.current_conversation_count) AS available_slots,
    (SELECT COUNT(*) FROM conversations c WHERE c.assigned_user_id = u.id AND c.status IN ('open','human_active','waiting')) AS active_conversations,
    (SELECT COUNT(*) FROM messages m WHERE m.user_id = u.id AND m.direction = 'outbound' AND m.created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')) AS messages_sent_this_month
FROM users u
WHERE u.role IN ('tenant_agent','tenant_supervisor','tenant_admin')
  AND u.deleted_at IS NULL
  AND u.is_active = 1;


-- ============================================================================
-- SECCIÓN 9: DATOS INICIALES (SEEDERS)
-- ============================================================================

INSERT INTO `subscription_plans` (`name`, `slug`, `description`, `price`, `currency`, `billing_cycle`, `max_agents`, `max_channels`, `message_limit_per_month`, `ai_engine_allowed`, `has_crm`, `has_appointments`, `has_sequences`, `has_internal_chat`, `has_auto_assignment`, `features`, `sort_order`, `is_active`, `created_at`, `updated_at`)
VALUES
(
    'Plan Básico', 'plan-basico',
    'Ideal para emprendedores. Chatbot con IA local, 1 canal y bandeja omnicanal.',
    29.99, 'USD', 'monthly', 2, 1, 1000, 'local',
    0, 0, 0, 1, 0,
    JSON_OBJECT('analytics_basic', true, 'analytics_advanced', false, 'export_data', false, 'canned_responses', true, 'custom_branding', false, 'priority_support', false),
    1, 1, NOW(), NOW()
),
(
    'Plan Avanzado', 'plan-avanzado',
    'Para empresas en crecimiento. IA Gemini, CRM, citas, multicanal y asignación automática.',
    79.99, 'USD', 'monthly', 10, 3, 5000, 'gemini',
    1, 1, 1, 1, 1,
    JSON_OBJECT('analytics_basic', true, 'analytics_advanced', true, 'export_data', true, 'canned_responses', true, 'custom_branding', true, 'priority_support', true),
    2, 1, NOW(), NOW()
),
(
    'Plan Enterprise', 'plan-enterprise',
    'Solución completa. Ambos motores de IA, agentes ilimitados, soporte dedicado y API access.',
    199.99, 'USD', 'monthly', 255, 3, 20000, 'both',
    1, 1, 1, 1, 1,
    JSON_OBJECT('analytics_basic', true, 'analytics_advanced', true, 'export_data', true, 'canned_responses', true, 'custom_branding', true, 'priority_support', true, 'dedicated_account_manager', true, 'api_access', true),
    3, 1, NOW(), NOW()
);


-- ============================================================================
-- RESUMEN v2.0
-- ============================================================================
-- INFRAESTRUCTURA LARAVEL:        8 tablas
-- CORE NEGOCIO:                   8 tablas (subscription_plans, tenants, users,
--                                           chatbot_knowledges, tenant_channels,
--                                           contacts, conversations, messages)
-- COLABORACIÓN EQUIPO (NUEVO):    3 tablas (conversation_notes, conversation_assignments,
--                                           assignment_rules)
-- CRM Y GESTIÓN (NUEVO):         4 tablas (tags, taggables, deals, appointments)
-- COMUNICACIÓN INTERNA (NUEVO):   3 tablas (internal_chats, internal_chat_participants,
--                                           internal_messages)
-- SECUENCIAS (NUEVO):            3 tablas (sequences, sequence_steps, sequence_enrollments)
-- COMPLEMENTARIAS:                4 tablas (canned_responses, api_usage_logs,
--                                           activity_logs, notifications)
-- VISTAS:                         2 views (v_tenant_dashboard_stats, v_agent_workload)
--
-- TOTAL: 33 tablas + 2 vistas
-- ============================================================================

SET FOREIGN_KEY_CHECKS = 1;
