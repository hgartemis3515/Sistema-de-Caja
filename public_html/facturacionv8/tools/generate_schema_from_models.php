<?php
declare(strict_types=1);
/**
 * Genera sql/schema_from_models.sql a partir de app/models/*.php
 * Uso (desde facturacionv8): php tools/generate_schema_from_models.php
 */
$base = dirname(__DIR__);
$modelsDir = $base . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'models';
$sqlDir = realpath($base . '/../../sql');
if ($sqlDir === false) {
    $sqlDir = dirname($base, 2) . DIRECTORY_SEPARATOR . 'sql';
}
if (!is_dir($sqlDir)) {
    mkdir($sqlDir, 0775, true);
}
$outFile = $sqlDir . DIRECTORY_SEPARATOR . 'schema_from_models.sql';

function classToTable(string $class): string
{
    $s = preg_replace('/(.)([A-Z][a-z]+)/', '$1_$2', $class);
    $s = preg_replace('/([a-z0-9])([A-Z])/', '$1_$2', $s ?? $class);

    return strtolower($s ?? $class);
}

function mapSqlType(string $varType, string $colName): string
{
    $varType = strtolower(trim($varType));
    if ($varType === 'integer') {
        return 'INT(11) DEFAULT NULL';
    }
    if ($varType === 'double') {
        return 'DECIMAL(18,6) DEFAULT NULL';
    }
    $long = preg_match('/html|mensaje|detalle|json|observaci|descripcion|ruta_|contenido|xml|body|token|logo_|firma|hash|cadena|data_/i', $colName);

    return $long ? 'MEDIUMTEXT' : 'TEXT';
}

function parseModel(string $path, string $file): ?array
{
    $src = file_get_contents($path);
    if ($src === false) {
        return null;
    }
    if (!preg_match('/class\s+(\w+)\s+extends\s+\\\\?Phalcon\\\\Mvc\\\\Model/', $src, $cm)) {
        return null;
    }
    $class = $cm[1];
    $table = classToTable($class);
    if (preg_match('/\$this->setSource\s*\(\s*["\']([^"\']+)["\']\s*\)/', $src, $tm)) {
        $table = $tm[1];
    }

    $lines = preg_split('/\R/', $src);
    $props = [];
    $pendingVar = 'string';
    foreach ($lines as $line) {
        if (preg_match('/@var\s+(integer|double|string)/i', $line, $vm)) {
            $pendingVar = $vm[1];
            continue;
        }
        if (preg_match('/^\s*public\s+\$(\w+)\s*;/', $line, $pm)) {
            $props[] = ['name' => $pm[1], 'type' => $pendingVar];
            $pendingVar = 'string';
        }
    }
    if ($props === []) {
        return null;
    }

    return ['class' => $class, 'table' => $table, 'props' => $props];
}

function guessPrimaryKey(array $props, string $table): ?array
{
    $names = array_column($props, 'name');
    $byName = [];
    foreach ($props as $p) {
        $byName[$p['name']] = $p['type'];
    }

    $special = [
        'contribuyente_opciones' => ['cols' => ['id_contribuyente', 'opcion_nombre'], 'auto' => false],
        'usuario_opcion' => ['cols' => ['id_contribuyente', 'idusuario', 'opcion_nombre'], 'auto' => false],
        'sucursal_opcion' => ['cols' => ['id_contribuyente', 'id_sucursal', 'opcion_nombre'], 'auto' => false],
        'sunat_codigoubigeo' => ['cols' => ['codigo_ubigeo'], 'auto' => false],
        'doc_electronico' => ['cols' => ['id_contribuyente', 'id_tipodoc_electronico', 'serie_comprobante', 'numero_comprobante'], 'auto' => false],
        'producto_movimiento' => ['cols' => ['id_contribuyente', 'id_tipo_movimiento', 'serie', 'correlativo', 'tipo_envio_sunat'], 'auto' => false],
    ];
    if (isset($special[$table])) {
        $req = $special[$table]['cols'];
        foreach ($req as $c) {
            if (!in_array($c, $names, true)) {
                return null;
            }
        }

        return $special[$table];
    }

    $prefer = ['idusuario', 'iddetalle', 'id_movimiento_det', 'idprecio', 'idproducto', 'idcliente', 'id_compra', 'id_orden', 'id_kardex', 'id_dataextra', 'id_plantilla', 'id_rol', 'idsucursal'];
    foreach ($prefer as $cand) {
        if (in_array($cand, $names, true) && ($byName[$cand] ?? '') === 'integer') {
            return ['cols' => [$cand], 'auto' => true];
        }
    }

    if ($table === 'contribuyente' && in_array('id_contribuyente', $names, true)) {
        return ['cols' => ['id_contribuyente'], 'auto' => true];
    }

    $skipGenericPk = ['id_contribuyente', 'id_sucursal', 'id_usuario', 'destino_id_sucursal', 'destino_id_contribuyente'];
    foreach ($names as $n) {
        if (in_array($n, $skipGenericPk, true)) {
            continue;
        }
        if (preg_match('/^id[a-z0-9_]+$/i', $n) && ($byName[$n] ?? '') === 'integer') {
            return ['cols' => [$n], 'auto' => true];
        }
    }

    foreach ($props as $p) {
        if ($p['type'] === 'integer') {
            return ['cols' => [$p['name']], 'auto' => false];
        }
    }

    return null;
}

$parts = ["SET NAMES utf8mb4;\nSET FOREIGN_KEY_CHECKS=0;\n"];
$files = glob($modelsDir . DIRECTORY_SEPARATOR . '*.php') ?: [];
sort($files);
foreach ($files as $path) {
    $parsed = parseModel($path, basename($path));
    if ($parsed === null) {
        continue;
    }
    $table = $parsed['table'];
    $props = $parsed['props'];
    $pkInfo = guessPrimaryKey($props, $table);
    $pkCols = $pkInfo['cols'] ?? [];
    $pkAuto = $pkInfo['auto'] ?? false;

    $colSql = [];
    foreach ($props as $p) {
        $cn = $p['name'];
        $def = mapSqlType($p['type'], $cn);
        $isPkSingle = count($pkCols) === 1 && $pkCols[0] === $cn && $pkAuto;
        if ($isPkSingle && $p['type'] === 'integer') {
            $def = 'INT(11) NOT NULL AUTO_INCREMENT';
        } elseif (in_array($cn, $pkCols, true) && $p['type'] === 'integer' && !$pkAuto) {
            $def = 'INT(11) NOT NULL';
        } elseif (in_array($cn, $pkCols, true) && $p['type'] === 'string') {
            $def = 'VARCHAR(191) NOT NULL';
        }
        $colSql[] = '  `' . str_replace('`', '``', $cn) . '` ' . $def;
    }

    $pkClause = '';
    if ($pkCols !== []) {
        $pkQuoted = array_map(static fn ($c) => '`' . str_replace('`', '``', $c) . '`', $pkCols);
        $pkClause = ",\n  PRIMARY KEY (" . implode(', ', $pkQuoted) . ')';
    }

    $parts[] = 'CREATE TABLE IF NOT EXISTS `' . str_replace('`', '``', $table) . "` (\n"
        . implode(",\n", $colSql)
        . $pkClause
        . "\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";
}

$parts[] = "SET FOREIGN_KEY_CHECKS=1;\n";

$out = implode('', $parts);
if (file_put_contents($outFile, $out) === false) {
    fwrite(STDERR, "No se pudo escribir: {$outFile}\n");
    exit(1);
}
echo "Escrito: {$outFile} (" . count($files) . " modelos)\n";
