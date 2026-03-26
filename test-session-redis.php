<?php

declare(strict_types=1);

/**
 * Prueba de Sesión en Redis
 * -------------------------
 * Script para verificar que las sesiones se guardan correctamente en Redis
 */

require __DIR__ . '/vendor/autoload.php';

// Load environment variables from .env file (if exists)
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $envVars = parse_ini_file($envFile);
    if (is_array($envVars)) {
        foreach ($envVars as $key => $value) {
            if (getenv($key) === false) {
                putenv("$key=$value");
                $_ENV[$key] = $value;
            }
        }
    }
}

// Load configuration helpers
$configDir = __DIR__ . '/config';
if (is_dir($configDir)) {
    foreach (glob($configDir . '/*.php') as $configFile) {
        $configName = basename($configFile, '.php');
        $config = require $configFile;
        if (is_array($config)) {
            app()->config($configName, $config);
        }
    }
}

// Iniciar sesión
session_start();

// Guardar datos en sesión
$_SESSION['test'] = [
    'timestamp' => time(),
    'message' => 'Prueba de sesión en Redis',
    'user_id' => 123,
    'random' => bin2hex(random_bytes(16)),
];

// Mostrar información
echo "<!DOCTYPE html>\n";
echo "<html lang='es'>\n";
echo "<head>\n";
echo "    <meta charset='UTF-8'>\n";
echo "    <meta name='viewport' content='width=device-width, initial-scale=1.0'>\n";
echo "    <title>Prueba de Sesión en Redis</title>\n";
echo "    <style>\n";
echo "        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }\n";
echo "        .success { color: green; font-weight: bold; }\n";
echo "        .error { color: red; font-weight: bold; }\n";
echo "        .info { color: blue; }\n";
echo "        table { border-collapse: collapse; width: 100%; margin: 20px 0; }\n";
echo "        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }\n";
echo "        th { background-color: #f2f2f2; }\n";
echo "    </style>\n";
echo "</head>\n";
echo "<body>\n";
echo "<h1>Prueba de Sesión en Redis</h1>\n";

echo "<h2>Información de Sesión</h2>\n";
echo "<table>\n";
echo "<tr><th>Parámetro</th><th>Valor</th></tr>\n";
echo "<tr><td>ID de sesión</td><td><code>" . htmlspecialchars(session_id()) . "</code></td></tr>\n";
echo "<tr><td>Nombre de sesión</td><td><code>" . htmlspecialchars(session_name()) . "</code></td></tr>\n";
echo "<tr><td>Driver configurado</td><td><code>" . htmlspecialchars(_env('SESSION_DRIVER', 'file')) . "</code></td></tr>\n";
echo "<tr><td>Lifetime (minutos)</td><td><code>" . htmlspecialchars(_env('SESSION_LIFETIME', '120')) . "</code></td></tr>\n";
echo "<tr><td>Timestamp</td><td><code>" . date('Y-m-d H:i:s', $_SESSION['test']['timestamp']) . "</code></td></tr>\n";
echo "</table>\n";

echo "<h2>Datos Guardados en Sesión</h2>\n";
echo "<pre>" . htmlspecialchars(json_encode($_SESSION, JSON_PRETTY_PRINT)) . "</pre>\n";

echo "<h2>Verificación de Redis</h2>\n";

$redisUrl = _env('REDIS_URL');
if ($redisUrl) {
    echo "<p><span class='info'>REDIS_URL configurada:</span> <code>" . preg_replace('/:[^:]*@/', ':****@', $redisUrl) . "</code></p>\n";

    if (!extension_loaded('redis')) {
        echo "<p><span class='error'>ERROR:</span> La extensión Redis de PHP no está cargada.</p>\n";
    } else {
        // Intentar conectar a Redis
        $parsed = parse_url($redisUrl);
        $redisHost = $parsed['host'] ?? '127.0.0.1';
        $redisPort = $parsed['port'] ?? 6379;
        $redisPassword = $parsed['pass'] ?? null;
        $redisDatabase = isset($parsed['path']) ? (int) ltrim($parsed['path'], '/') : 0;

        $redis = new Redis();
        $connected = $redis->connect($redisHost, $redisPort, 5);

        if ($connected) {
            echo "<p><span class='success'>Conexión a Redis: EXITOSA</span></p>\n";

            if ($redisPassword) {
                $authResult = $redis->auth($redisPassword);
                if (!$authResult) {
                    echo "<p><span class='error'>ERROR:</span> Autenticación fallida en Redis.</p>\n";
                }
            }

            if ($redisDatabase > 0) {
                $redis->select($redisDatabase);
            }

            // Buscar clave de sesión en Redis
            $sessionId = session_id();
            $sessionKeys = [
                "PHPREDIS_SESSION:$sessionId",
                "sess_$sessionId",
                $sessionId,
            ];

            $found = false;
            foreach ($sessionKeys as $sessionKey) {
                $sessionData = $redis->get($sessionKey);
                if ($sessionData !== false) {
                    echo "<p><span class='success'>Sesión encontrada en Redis: SÍ</span></p>\n";
                    echo "<p>Clave Redis: <code>" . htmlspecialchars($sessionKey) . "</code></p>\n";
                    echo "<p>Tamaño de datos: " . strlen($sessionData) . " bytes</p>\n";
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                echo "<p><span class='error'>Sesión encontrada en Redis: NO</span></p>\n";
                echo "<p><span class='info'>Nota:</span> Puede que la sesión no se haya guardado aún o use un formato diferente.</p>\n";
                echo "<p><span class='info'>Claves buscadas:</span></p>\n";
                echo "<ul>\n";
                foreach ($sessionKeys as $sessionKey) {
                    echo "<li><code>" . htmlspecialchars($sessionKey) . "</code></li>\n";
                }
                echo "</ul>\n";

                // Mostrar todas las claves que coinciden con el patrón
                echo "<p><span class='info'>Claves en Redis que contienen 'SESSION':</span></p>\n";
                $allKeys = $redis->keys('*SESSION*');
                if (count($allKeys) > 0) {
                    echo "<ul>\n";
                    foreach ($allKeys as $key) {
                        echo "<li><code>" . htmlspecialchars($key) . "</code></li>\n";
                    }
                    echo "</ul>\n";
                } else {
                    echo "<p>No se encontraron claves con el patrón '*SESSION*'</p>\n";
                }
            }

            $redis->close();
        } else {
            echo "<p><span class='error'>Conexión a Redis: FALLIDA</span></p>\n";
            echo "<p>No se pudo conectar a $redisHost:$redisPort</p>\n";
        }
    }
} else {
    echo "<p><span class='error'>REDIS_URL no configurada</span></p>\n";
}

echo "<h2>Configuración de PHP</h2>\n";
echo "<table>\n";
echo "<tr><th>Parámetro</th><th>Valor</th></tr>\n";
echo "<tr><td>session.save_handler</td><td><code>" . htmlspecialchars(ini_get('session.save_handler')) . "</code></td></tr>\n";
echo "<tr><td>session.save_path</td><td><code>" . htmlspecialchars(ini_get('session.save_path')) . "</code></td></tr>\n";
echo "<tr><td>session.gc_maxlifetime</td><td><code>" . htmlspecialchars(ini_get('session.gc_maxlifetime')) . "</code></td></tr>\n";
echo "<tr><td>session.cookie_lifetime</td><td><code>" . htmlspecialchars(ini_get('session.cookie_lifetime')) . "</code></td></tr>\n";
echo "<tr><td>session.cookie_httponly</td><td><code>" . htmlspecialchars(ini_get('session.cookie_httponly')) . "</code></td></tr>\n";
echo "<tr><td>session.cookie_samesite</td><td><code>" . htmlspecialchars(ini_get('session.cookie_samesite')) . "</code></td></tr>\n";
echo "</table>\n";

echo "<p><a href='?refresh=" . time() . "'>Recargar página</a> (para verificar persistencia de sesión)</p>\n";
echo "<p><a href='?clear'>Cerrar sesión</a></p>\n";

if (isset($_GET['clear'])) {
    session_destroy();
    echo "<script>window.location.href = window.location.pathname;</script>\n";
}

echo "</body>\n";
echo "</html>\n";
