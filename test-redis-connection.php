#!/usr/bin/env php
<?php
/**
 * Script de prueba de conexión a Redis
 * 
 * Este script verifica que la conexión a Redis es exitosa.
 * Se puede ejecutar localmente o en Railway.
 */

// Cargar variables de entorno
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
        putenv(trim($name) . '=' . trim($value));
    }
}

// Obtener la URL de Redis desde la variable de entorno
$redisUrl = getenv('REDIS_URL');

if (empty($redisUrl)) {
    echo "❌ ERROR: La variable de entorno REDIS_URL no está definida.\n";
    echo "   En Railway, esta variable se inyecta automáticamente cuando añades un servicio Redis.\n";
    echo "   Localmente, puedes configurarla en tu archivo .env.\n";
    exit(1);
}

echo "🔗 Probando conexión a Redis...\n";
echo "   URL: " . preg_replace('/:[^:@]+@/', ':****@', $redisUrl) . "\n\n";

// Verificar si la extensión Redis está instalada
if (!extension_loaded('redis')) {
    echo "❌ ERROR: La extensión de Redis para PHP no está instalada.\n";
    echo "   Instálala con: pecl install redis\n";
    echo "   O usa: apt-get install php-redis (en sistemas basados en Debian)\n";
    exit(1);
}

// Parsear la URL de Redis
$urlParts = parse_url($redisUrl);
if ($urlParts === false) {
    echo "❌ ERROR: No se pudo parsear la URL de Redis.\n";
    exit(1);
}

$host = $urlParts['host'] ?? '127.0.0.1';
$port = $urlParts['port'] ?? 6379;
$password = $urlParts['pass'] ?? null;
$user = $urlParts['user'] ?? null;

echo "📋 Configuración de conexión:\n";
echo "   Host: $host\n";
echo "   Puerto: $port\n";
echo "   Usuario: " . ($user ?? 'N/A') . "\n";
echo "   Contraseña: " . ($password ? '***' : 'N/A') . "\n\n";

// Crear conexión Redis
$redis = new Redis();

try {
    echo "🔄 Conectando a Redis en $host:$port...\n";
    $connected = $redis->connect($host, $port, 5);
    
    if (!$connected) {
        echo "❌ ERROR: No se pudo conectar a Redis.\n";
        exit(1);
    }
    
    echo "✅ Conexión exitosa a Redis.\n";
    
    // Autenticar si hay contraseña
    if ($password) {
        echo "🔐 Autenticando...\n";
        $authResult = $redis->auth($password);
        
        if (!$authResult) {
            echo "❌ ERROR: Autenticación fallida.\n";
            $redis->close();
            exit(1);
        }
        
        echo "✅ Autenticación exitosa.\n";
    }
    
    // Probar operaciones básicas
    echo "\n🧪 Probando operaciones básicas...\n";
    
    // PING
    $pingResult = $redis->ping();
    echo "   PING: " . ($pingResult ? '✅' : '❌') . "\n";
    
    // SET
    $testKey = 'hoja_test_' . time();
    $testValue = 'test_value_' . time();
    $setResult = $redis->set($testKey, $testValue);
    echo "   SET: " . ($setResult ? '✅' : '❌') . "\n";
    
    // GET
    $getResult = $redis->get($testKey);
    echo "   GET: " . ($getResult === $testValue ? '✅' : '❌') . "\n";
    
    // DEL
    $delResult = $redis->del($testKey);
    echo "   DEL: " . ($delResult > 0 ? '✅' : '❌') . "\n";
    
    // INFO
    $info = $redis->info();
    echo "   INFO: ✅\n";
    echo "   Versión de Redis: " . ($info['redis_version'] ?? 'N/A') . "\n";
    echo "   Memoria usada: " . ($info['used_memory_human'] ?? 'N/A') . "\n";
    echo "   Clientes conectados: " . ($info['connected_clients'] ?? 'N/A') . "\n";
    
    // Cerrar conexión
    $redis->close();
    
    echo "\n✅ Todas las pruebas pasaron exitosamente.\n";
    echo "🎉 Redis está configurado y funcionando correctamente.\n";
    exit(0);
    
} catch (RedisException $e) {
    echo "❌ ERROR de Redis: " . $e->getMessage() . "\n";
    if (isset($redis) && $redis->isConnected()) {
        $redis->close();
    }
    exit(1);
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    if (isset($redis) && $redis->isConnected()) {
        $redis->close();
    }
    exit(1);
}
