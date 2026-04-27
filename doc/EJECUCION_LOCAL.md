# Cómo ejecutar el sistema de caja (análisis local)

## Instalación automática (Windows, sin Docker)

1. Ejecuta **`instalar-requisitos.bat`** (usa `winget` para PHP 8.3 y descarga el DLL de Phalcon acorde TS/NTS).  
2. Si hace falta **solo MySQL** en el equipo: vuelve a ejecutar en consola  
   `powershell -ExecutionPolicy Bypass -File instalar-requisitos.ps1 -InstallMySQL`  
   (tarda; alternativa: XAMPP/Laragon con MySQL y ajusta `DB_HOST` en `.env`).
3. Cierra y abre la terminal si `php` no se reconoce tras el paso 1.
4. Luego **`iniciar.bat`**.

## Sin Docker: `iniciar.bat`

1. **PHP 8.x** en el `PATH`, con extensiones **phalcon**, **mbstring**, **pdo_mysql** (`php -m`). Sin **mbstring**, Phalcon falla con `mb_strtolower` — `instalar-requisitos.ps1` ya la activa.
2. **MySQL** accesible con los datos de tu `.env` (`DB_*` o `MYSQL_*`; ver `.env.example`).
3. Doble clic o consola: `Sistema de caja\iniciar.bat`  
   - Arranca `php -S 127.0.0.1:WEB_PORT -t public` en una ventana nueva.  
   - Abre el navegador en `http://127.0.0.1:WEB_PORT/` (puerto desde `WEB_PORT` en `.env`, por defecto 8080).  
4. El `.env` en la raíz de `Sistema de caja` lo lee **PHP** al iniciar (no hace falta exportar variables en el `.bat`).

## Requisitos (Docker)

- **Docker Desktop** (Windows) con Linux containers.
- **Dump SQL** del entorno original (el repo no incluye esquema). Sin tablas, la app fallará al conectar modelos o al iniciar sesión.

## Arranque con Docker

Desde la carpeta `Sistema de caja/`:

```bash
copy .env.example .env
docker compose up --build
```

- **Web:** http://localhost:8080/facturacionv8/  
  (login típico: ruta bajo el mismo prefijo, p. ej. `/facturacionv8/login` o `/facturacionv8/session` según el despliegue original.)
- **MySQL:** `localhost:3307` (usuario/clave por defecto en `.env.example`: `factura` / `factura_local`).

Importar el dump:

```bash
docker compose exec -T db mysql -ufactura -pfactura_local facturacion_local < tu_backup.sql
```

## Variables útiles

| Variable | Uso |
|----------|-----|
| `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME` | Conexión MySQL (las usa `app/config/config.php` vía `getenv`) |
| `APP_BASE_URI` | Prefijo URL (en Docker: `/facturacionv8/`) |
| `SESSION_SAVE_PATH` | Opcional; por defecto `BASE_PATH/var/sessions` |

## Cambios hechos en el código para que arranque

1. **`public/index.php`:** ajusta `DOCUMENT_ROOT` si hace falta para que funcionen los `require .../facturacionv8/apis/...`.
2. **`app/config/config.php`:** credenciales por **variables de entorno** (sin secretos fijos en repo).
3. **`app/config/services.php`:** sesiones en `var/sessions` (o `SESSION_SAVE_PATH`).

## Sin Docker (solo referencia)

Hace falta **PHP 8.x + extensión Phalcon** y Apache con **DocumentRoot** = carpeta que **contiene** `facturacionv8/` (no solo `public/`), o las rutas de `apis/` fallarán. En Windows es más simple usar Docker.
