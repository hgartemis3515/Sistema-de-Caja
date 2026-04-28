import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const base = path.join(__dirname, '..');
const modelsDir = path.join(base, 'app', 'models');
const sqlDir = path.join(base, '..', '..', 'sql');
const outFile = path.join(sqlDir, 'schema_from_models.sql');

function classToTable(className) {
  let s = className.replace(/(.)([A-Z][a-z]+)/g, '$1_$2');
  s = s.replace(/([a-z0-9])([A-Z])/g, '$1_$2');
  return s.toLowerCase();
}

function mapSqlType(varType, colName) {
  const t = String(varType).toLowerCase().trim();
  if (t === 'integer') return 'INT(11) DEFAULT NULL';
  if (t === 'double') return 'DECIMAL(18,6) DEFAULT NULL';
  // TEXT se almacena fuera de fila (InnoDB): evita "Row size too large" con muchas columnas string.
  const long = /html|mensaje|detalle|json|observaci|descripcion|ruta_|contenido|xml|body|token|logo_|firma|hash|cadena|data_/i.test(colName);
  return long ? 'MEDIUMTEXT' : 'TEXT';
}

function guessPrimaryKey(props, table) {
  const names = props.map((p) => p.name);
  const byName = Object.fromEntries(props.map((p) => [p.name, p.type]));

  const special = {
    contribuyente_opciones: { cols: ['id_contribuyente', 'opcion_nombre'], auto: false },
    usuario_opcion: { cols: ['id_contribuyente', 'idusuario', 'opcion_nombre'], auto: false },
    sucursal_opcion: { cols: ['id_contribuyente', 'id_sucursal', 'opcion_nombre'], auto: false },
    sunat_codigoubigeo: { cols: ['codigo_ubigeo'], auto: false },
    doc_electronico: {
      cols: ['id_contribuyente', 'id_tipodoc_electronico', 'serie_comprobante', 'numero_comprobante'],
      auto: false,
    },
    producto_movimiento: {
      cols: ['id_contribuyente', 'id_tipo_movimiento', 'serie', 'correlativo', 'tipo_envio_sunat'],
      auto: false,
    },
  };
  if (special[table]) {
    const req = special[table].cols;
    if (req.every((c) => names.includes(c))) return special[table];
  }

  const prefer = [
    'idusuario',
    'iddetalle',
    'id_movimiento_det',
    'idprecio',
    'idproducto',
    'idcliente',
    'id_compra',
    'id_orden',
    'id_kardex',
    'id_dataextra',
    'id_plantilla',
    'id_rol',
    'idsucursal',
  ];
  for (const cand of prefer) {
    if (names.includes(cand) && byName[cand] === 'integer') return { cols: [cand], auto: true };
  }

  if (table === 'contribuyente' && names.includes('id_contribuyente'))
    return { cols: ['id_contribuyente'], auto: true };

  const skipGenericPk = new Set(['id_contribuyente', 'id_sucursal', 'id_usuario', 'destino_id_sucursal', 'destino_id_contribuyente']);
  for (const n of names) {
    if (skipGenericPk.has(n)) continue;
    if (/^id[a-z0-9_]+$/i.test(n) && byName[n] === 'integer') return { cols: [n], auto: true };
  }

  for (const p of props) {
    if (p.type === 'integer') return { cols: [p.name], auto: false };
  }
  return null;
}

function parseModel(src) {
  const cm = src.match(/class\s+(\w+)\s+extends\s+\\?Phalcon\\Mvc\\Model/);
  if (!cm) return null;
  const className = cm[1];
  let table = classToTable(className);
  const tm = src.match(/\$this->setSource\s*\(\s*["']([^"']+)["']\s*\)/);
  if (tm) table = tm[1];

  const lines = src.split(/\r?\n/);
  const props = [];
  let pendingVar = 'string';
  for (const line of lines) {
    const vm = line.match(/@var\s+(integer|double|string)/i);
    if (vm) {
      pendingVar = vm[1];
      continue;
    }
    const pm = line.match(/^\s*public\s+\$(\w+)\s*;/);
    if (pm) {
      props.push({ name: pm[1], type: pendingVar });
      pendingVar = 'string';
    }
  }
  if (!props.length) return null;
  return { className, table, props };
}

if (!fs.existsSync(sqlDir)) fs.mkdirSync(sqlDir, { recursive: true });

const parts = ['SET NAMES utf8mb4;\nSET FOREIGN_KEY_CHECKS=0;\n'];
const files = fs.readdirSync(modelsDir).filter((f) => f.endsWith('.php')).sort();
for (const f of files) {
  const src = fs.readFileSync(path.join(modelsDir, f), 'utf8');
  const parsed = parseModel(src);
  if (!parsed) continue;
  const { table, props } = parsed;
  const pkInfo = guessPrimaryKey(props, table);
  const pkCols = pkInfo?.cols ?? [];
  const pkAuto = pkInfo?.auto ?? false;

  const colSql = [];
  for (const p of props) {
    const cn = p.name;
    let def = mapSqlType(p.type, cn);
    const isPkSingle = pkCols.length === 1 && pkCols[0] === cn && pkAuto;
    if (isPkSingle && p.type === 'integer') def = 'INT(11) NOT NULL AUTO_INCREMENT';
    else if (pkCols.includes(cn) && p.type === 'integer' && !pkAuto) def = 'INT(11) NOT NULL';
    else if (pkCols.includes(cn) && p.type === 'string') def = 'VARCHAR(191) NOT NULL';
    colSql.push(`  \`${cn.replace(/`/g, '``')}\` ${def}`);
  }

  let pkClause = '';
  if (pkCols.length) {
    const pkQuoted = pkCols.map((c) => `\`${c.replace(/`/g, '``')}\``);
    pkClause = `,\n  PRIMARY KEY (${pkQuoted.join(', ')})`;
  }

  parts.push(
    `CREATE TABLE IF NOT EXISTS \`${table.replace(/`/g, '``')}\` (\n${colSql.join(',\n')}${pkClause}\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n`,
  );
}
parts.push('SET FOREIGN_KEY_CHECKS=1;\n');
fs.writeFileSync(outFile, parts.join(''), 'utf8');
console.log('Escrito:', outFile, '(' + files.length + ' archivos en models/)');
