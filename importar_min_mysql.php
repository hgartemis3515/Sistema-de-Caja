<?php
declare(strict_types=1);
/**
 * Importa esquema completo (tablas desde modelos Phalcon) + datos mínimos locales.
 * Lee .env en esta carpeta. Uso: php importar_min_mysql.php
 *
 * Regenerar solo el SQL de tablas: node public_html/facturacionv8/tools/generate_schema_from_models.mjs
 */
$root = __DIR__;
$envFile = $root . DIRECTORY_SEPARATOR . '.env';
if (!is_readable($envFile)) {
    fwrite(STDERR, "No se encuentra .env en: {$root}\n");
    exit(1);
}
$env = [];
foreach (file($envFile, FILE_IGNORE_NEW_LINES) ?: [] as $line) {
    $line = trim($line);
    if ($line === '' || str_starts_with($line, '#')) {
        continue;
    }
    $eq = strpos($line, '=');
    if ($eq === false) {
        continue;
    }
    $env[trim(substr($line, 0, $eq))] = trim(substr($line, $eq + 1));
}
$host = $env['DB_HOST'] ?? '127.0.0.1';
$port = (int) ($env['DB_PORT'] ?? '3306');
$user = $env['DB_USER'] ?? 'root';
$pass = $env['DB_PASSWORD'] ?? ($env['DB_PASS'] ?? '');
$dbname = $env['DB_NAME'] ?? 'facturacion_local';

$sqlFiles = [
    $root . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR . 'schema_from_models.sql',
    $root . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR . 'seed_local.sql',
];

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $mysqli = new mysqli($host, $user, $pass, $dbname, $port);
} catch (Throwable $e) {
    fwrite(STDERR, 'MySQL: ' . $e->getMessage() . "\n");
    exit(1);
}
$mysqli->set_charset('utf8mb4');

foreach ($sqlFiles as $sqlPath) {
    if (!is_readable($sqlPath)) {
        fwrite(STDERR, "No se encuentra: {$sqlPath}\n");
        exit(1);
    }
    $sql = file_get_contents($sqlPath);
    if ($sql === false) {
        fwrite(STDERR, "No se pudo leer: {$sqlPath}\n");
        exit(1);
    }
    try {
        $mysqli->multi_query($sql);
        do {
            if ($res = $mysqli->store_result()) {
                $res->free();
            }
        } while ($mysqli->more_results() && $mysqli->next_result());
    } catch (Throwable $e) {
        fwrite(STDERR, basename($sqlPath) . ': ' . $e->getMessage() . "\n");
        exit(1);
    }
}

echo "Importado en `{$dbname}`: schema_from_models.sql + seed_local.sql\n";
echo "Login local: admin@local.test / Admin123\n";
