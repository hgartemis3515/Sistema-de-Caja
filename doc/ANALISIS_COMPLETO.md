# Análisis completo del clon «Sistema de caja»

## 1. Ubicación y árbol principal

```
Sistema de caja/
├── doc/                          ← esta documentación
└── public_html/
    ├── .htaccess                 Redirección HTTPS + PHP 8.3 (cPanel)
    └── facturacionv8/            Aplicación principal (Phalcon)
    └── facturacionv8_anetrior_10.5/   Copia de respaldo / versión anterior (misma naturaleza)
```

**Versión activa recomendada para análisis:** `public_html/facturacionv8/`. La carpeta `facturacionv8_anetrior_10.5` duplica la estructura (nombre sugiere «anterior 10.5»); al integrar o auditar, conviene **tratarla como archivo histórico** y no mezclar cambios entre ambas sin control de versiones explícito.

---

## 2. Qué es este sistema (visión de producto)

Es un **ERP/POS de facturación electrónica** orientado al mercado peruano (SUNAT, CPE, boletas/facturas, notas, guías, SIRE, detracciones, tablas SUNAT en BD). La marca y textos en código apuntan al ecosistema tipo **Facturalaya** (dominios, patrones de API, patrocinadores, PSE).

No es un «plugin» del monorepo Las Gambusinas: es un **proyecto PHP independiente** desplegable bajo Apache/cPanel, con su propia base **MySQL**.

---

## 3. Stack tecnológico

| Capa | Tecnología |
|------|------------|
| Framework | **Phalcon** (PHP), `FactoryDefault`, `Mvc\Application` |
| Vistas | **Volt** (plantillas compiladas en `cache/`) |
| BD | **MySQL** (adapter PDO en `services.php`) |
| Front POS | JS/CSS empaquetados vía manifiesto `public/js/systempos/manifest_systempos.json` |
| API móvil / SPA | **JWT** (`firebase/php-jwt`, HS512) en `ApiappController` |
| Librerías locales (`apis/`) | Dompdf, Snappy PDF, PhpSpreadsheet, Google Cloud Storage, QR, JWT, etc. (cada una con `vendor/` propio) |

**PHP:** `.htaccess` fuerza **ea-php83** (PHP 8.3).

**Phalcon:** entrada en `public/index.php` — carga `services.php`, `router.php`, `loader.php` y despacha `$_SERVER['REQUEST_URI']`.

---

## 4. Enrutamiento y URL base

`app/config/router.php` define rutas del estilo:

- `{baseUri}{controller}/{action}/{params}`
- `{baseUri}{controller}` → `action = index`

`baseUri` se calcula en `config.php` a partir de `PHP_SELF` (típico cuando la app vive bajo un subdirectorio, p. ej. `/facturacionv8/public/index.php` → base sin `public/index.php`).

Ejemplos conceptuales de URL (ajustar según hosting):

- Punto de venta web: `/facturacionv8/systempos/`
- Caja chica: `/facturacionv8/cajachica/`
- API app (acción Phalcon): `/facturacionv8/apiapp/login` (convención Phalcon: `login` → `loginAction`)

---

## 5. Configuración y dependencias del entorno

### 5.1 Base de datos y aplicación

`app/config/config.php` define `database` (adapter MySQL, host, usuario, contraseña, dbname, charset) y `application` (directorios, `baseUri`, `cacheDir`).

**Riesgo:** credenciales reales del entorno de origen. Deben **eliminarse del historial si se publicó el repo** y sustituirse por env vars o `config.local.php` no versionado.

### 5.2 Sesiones

En `app/config/services.php`, el adaptador de sesión usa `savePath` con ruta absoluta de Linux (`/home/.../.temp_facturalaya/...`). En Windows u otro servidor **fallará** hasta reconfigurar.

### 5.3 Rutas fijas `DOCUMENT_ROOT`

Varios controladores usan:

`require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/.../vendor/autoload.php";`

Implica que el docroot del servidor debe exponer **`/facturacionv8/`** como en el servidor original, o hay que **refactorizar** esas rutas a `BASE_PATH` / constantes.

### 5.4 Migraciones

La carpeta `app/migrations/` no contiene migraciones enumeradas en el análisis actual; el esquema se infiere de los **modelos Phalcon** (~93 archivos en `app/models/`).

---

## 6. Seguridad y permisos (ACL)

`app/plugins/SecurityPlugin.php` implementa **Phalcon ACL** con roles:

- `super_admin`, `super_soporte`, `patrocinador`, `contribuyente_admin`, `contribuyente_vendedor`, `guest`

Cada rol tiene listas de `{controlador => [acciones permitidas]}`.

**Áreas relevantes para «caja» y restaurante:**

- **`cajachica`**: movimientos de caja chica, reportes, tickets, Excel, etc. (acciones listadas en el plugin).
- **`systempos`**: POS web — productos, carrito, clientes, validación, `procesar_venta_carrito`, etc.
- **`gestionrestaurante`**: `index`, `dashboard`, `iniciarcuenta`, `cocina`, `reserva`, `gestioncocina`, `gestionreservas`, `reserva2` — pensado para UI de restaurante dentro del mismo producto.
- **`api`**: endpoints públicos/guest documentados en ACL (login, procesar venta, productos, etc.).
- **`apiapp`**: API amplia para apps (JWT, catálogos SUNAT, ventas, caja chica, etc.).

Los visitantes (`guest`) tienen acceso explícito a conjuntos base (login, consultas, `api`, `apiapp`, descargas PDF, etc.) según el arreglo `$GuestAreas`.

---

## 7. Módulos funcionales (controladores)

Hay **116 controladores** en `app/controllers/`. Agrupación lógica:

### 7.1 Facturación y documentos SUNAT

`FacturaController`, `NotadeventaController`, `NotadecreditoController`, `NotadedebitoController`, `DocumentoelectronicoController`, `GuiaderemisionController`, `GuiatransportistaController`, resúmenes, SIRE (`SireController`, `SireventasController`, `SirecomprasController`), importación CPE, plantillas PDF múltiples (`Template*Controller`).

### 7.2 Punto de venta (POS web)

- **`SystemposController`**: carga manifiesto de assets, valida sesión `authv8`, contribuyente, sucursal; expone datos a la vista (tipo cambio, ICBPER, sucursales, decimales). Incluye flujo pesado hacia **`procesar_venta_carrito`** y uso de **`SystemposvalidacionController`** para armar el `datapost` de venta.
- **`SystemposvalidacionController`**: validación de datos del carrito.

### 7.3 Caja chica

- **`CajachicaController`**: interfaz administrativa de **caja chica** (no es solo «caja registradora» del salón: registra movimientos, ingresos/egresos, vinculación con sucursal y vendedor, exportaciones). Modelo principal: **`Movimientocaja`** (`app/models/Movimientocaja.php`) con campos como `id_contribuyente`, `id_sucursal`, `id_vendedor`, `monto`, `tipo_movimiento`, `moneda`, fechas, `tipo_envio_sunat`, etc.

### 7.4 Restaurante (plantillas UI)

- **`GestionrestauranteController`**: acciones casi idénticas que solo configuran título, plantilla (`template_new`, `layout1`, `vacio`) y cargan los mismos JS/CSS de administración. **No contiene lógica de negocio de comandas** en el fragmento revisado: actúa como **shell** para vistas Volt bajo `app/views/gestionrestaurante/` (pantallas tipo cocina / reservas / dashboard de prototipo o integración futura).

### 7.5 APIs REST

| Controlador | Uso típico |
|-------------|------------|
| **`ApiController`** | Integraciones servidor-a-servidor: `user_login`, `procesarVenta`, notas, guías, cotización, comunicación de baja, consulta de CPE, etc. Token de contribuyente según pestaña de configuración empresa. |
| **`ApiappController`** | App móvil / cliente rico: **JWT** en cabecera `Authorization`, `login`, `refresh_token`, `get_user_info`, listados SUNAT, `procesar_venta`, `get_detalle_caja_chica`, estadísticas, productos, etc. |

En `SecurityPlugin`, **`apiapp`** lista decenas de acciones (`get_lista_cpe`, `get_stats_totals`, `get_totales_venta`, `get_entradas_salidas`, `get_detalle_caja_chica`, …).

### 7.6 Configuración empresa e integración

`ConfigcompanyController` + vistas como `configcompany/tab_integracion_api_rest.volt`: UI para **token del contribuyente** y documentación embebida de uso de la API REST (facturas, boletas, notas, guías, cotización, nota de venta, productos, comunicación de baja).

### 7.7 Otros módulos frecuentes

Productos, categorías, clientes, compras, proveedores, kardex, cuentas por cobrar/pagar, sucursales, usuarios, reportes, dashboard, sitio web del contribuyente (`miwebsite`, `editorweb`), personalización, suscripciones, recuperación de contraseña, etc.

---

## 8. Modelos de datos (muestra)

`app/models/` incluye entidades alineadas con facturación peruana y operación comercial: `Cliente`, `Producto*`, `DetalleDoc`, `Compra`, `Kardex`, `Movimientocaja`, `ResumenBoletas`, tablas `Sunat*`, `Sucursal`, `Usuario`, `Contribuyente`, logs, etc.

La noción de **«vendedor»** en caja chica y POS se mapea a **`Usuario`** / permisos, no a «mozo» de restaurante.

---

## 9. Integración con Backend-LasGambusinas y app mozos

### 9.1 Diferencias arquitectónicas

| Aspecto | Este clon (Sistema de caja) | Las Gambusinas (mozos + backend) |
|---------|-----------------------------|----------------------------------|
| Runtime | PHP Phalcon + MySQL | Node/Express + MongoDB |
| Dominio | Facturación SUNAT, CPE, PSE, catálogos oficiales | Mesas, comandas, cocina, bouchers, cierre de caja **restaurante** |
| «Caja» | Caja chica + POS que emiten **comprobantes electrónicos** | Cierre agrega **comandas/bouchers**, mozos, mesas en Mongo |

**No hay referencias en código** (búsqueda en `app/`) a «mozo», «mesa» o al backend Node de este monorepo. La relación es **de negocio / producto**, no de código compartido.

### 9.2 Solapamiento conceptual

- **Pagos y totales:** el POS PHP y las APIs pueden registrar ventas formales con CPE; la app mozos maneja **boucher** y estados de mesa en otro modelo.
- **Cierre:** `Backend-LasGambusinas` implementa **`POST /api/cierre-caja`** (`cierreCajaRestauranteController.js`) con agregación de comandas, mozos y mesas. La **caja chica** PHP es otro libro (movimientos `Movimientocaja`).
- **Restaurante:** `GestionrestauranteController` sugiere que el producto PHP **podría** alojar o enlazar pantallas de cocina/reserva; Las Gambusinas ya tiene **app cocina** y **mozos** en React Native.

### 9.3 Estrategias de integración posibles (si se desea unificar)

1. **Puente API:** el backend Node expone eventos de pago/boucher; un servicio intermedio llama a `ApiController` / `ApiappController` para emitir factura/boleta con el mismo RUC/contribuyente.
2. **Sincronización de catálogo:** productos SUNAT en MySQL vs platos en Mongo — requiere mapeo explícito (SKU, código de producto SUNAT, IGV).
3. **Doble registro controlado:** mozos cierran mesa en Node; caja oficial en PHP solo para comprobante — definir **fuente de verdad** del total y del stock.
4. **Sustitución gradual:** reimplementar en Node las piezas necesarias (SUNAT) y dejar el clon solo como referencia — evita dos stacks en producción.

---

## 10. Archivos y carpetas a tener en cuenta (operación)

| Ruta | Notas |
|------|--------|
| `public/index.php` | Front controller |
| `app/config/*` | Config, servicios, router, loader |
| `app/plugins/SecurityPlugin.php` | ACL |
| `app/controllers/*.php` | Toda la lógica MVC |
| `app/models/*.php` | ORM Phalcon |
| `app/views/**/*.volt` | Plantillas |
| `public/js/`, `public/css/` | Assets; POS bajo `public/js/systempos/` |
| `cache/` | Volt compilado y cachés; **no editar a mano**; puede ignorarse en git |
| `apis/*/vendor/` | Dependencias Composer por subproyecto |

---

## 11. Despliegue mínimo (checklist)

1. Servidor con **Phalcon** para la versión de PHP usada (Phalcon debe coincidir con PHP 8.3).
2. **MySQL** con esquema compatible (import desde dump del entorno original si existe).
3. Ajustar **`config.php`**: BD, y revisar `baseUri`.
4. Ajustar **`services.php`**: ruta de sesiones y permisos de escritura.
5. Sustituir **`DOCUMENT_ROOT`/facturacionv8** en requires o alinear virtual host.
6. HTTPS ya forzado en `.htaccess` del `public_html` superior.
7. Revisar permisos de escritura en `cache/`.

---

## 12. Conclusión

El clon es un **sistema maduro de facturación y POS en PHP**, con **API REST y API app con JWT**, **caja chica** y esqueleto de **gestión restaurante**. Para Las Gambusinas sirve como **referencia de facturación electrónica** o como **segundo sistema** enlazado por API; la integración con mozos/backend actual es un **proyecto de integración**, no algo ya cableado en el repositorio.
