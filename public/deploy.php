<?php
/**
 * Script de despliegue para cPanel sin terminal
 * Accede a: https://zentdev.store/deploy.php?key=smart_ai_deploy_2026
 * 
 * ELIMINAR ESTE ARCHIVO DESPUÉS DE USARLO
 */

// Clave de seguridad para evitar acceso no autorizado
$deployKey = 'smart_ai_deploy_2026';

if (!isset($_GET['key']) || $_GET['key'] !== $deployKey) {
    http_response_code(403);
    die('❌ Acceso denegado. Usa ?key=smart_ai_deploy_2026');
}

set_time_limit(300); // 5 minutos máximo
header('Content-Type: text/plain; charset=utf-8');

echo "🚀 Iniciando despliegue...\n\n";

$commands = [
    'echo "📂 Directorio actual:" && pwd',
    'echo "🔍 Versión de PHP:" && php -v | head -1',
    'echo "📥 Descargando Composer..." && cd ' . dirname(__FILE__) . '/../chatbot_novape && php -r "copy(\'https://getcomposer.org/installer\', \'composer-setup.php\');" && php composer-setup.php && php -r "unlink(\'composer-setup.php\');" 2>&1',
    'echo "📦 Instalando dependencias..." && cd ' . dirname(__FILE__) . '/../chatbot_novape && php composer.phar install --no-dev --optimize-autoloader 2>&1',
    'echo "🔑 Generando clave de aplicación..." && cd ' . dirname(__FILE__) . '/../chatbot_novape && php artisan key:generate --force 2>&1',
    'echo "⚡ Limpiando caché..." && cd ' . dirname(__FILE__) . '/../chatbot_novape && php artisan config:cache 2>&1',
    'echo "📋 Listando rutas de webhook..." && cd ' . dirname(__FILE__) . '/../chatbot_novape && php artisan route:list --path=webhooks 2>&1',
];

foreach ($commands as $command) {
    echo "$ {$command}\n";
    echo shell_exec($command);
    echo "\n" . str_repeat('-', 60) . "\n\n";
}

echo "✅ Despliegue completado.\n";
echo "⚠️ IMPORTANTE: Elimina este archivo (deploy.php) después de usarlo.\n";
