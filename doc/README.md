# Documentación — Sistema de caja (clon)

Este directorio describe el **clon** bajo `Sistema de caja/`, su arquitectura, módulos y cómo se relaciona conceptualmente con **Backend-LasGambusinas** y la **app mozos** (Las Gambusinas).

| Documento | Contenido |
|-----------|------------|
| [ANALISIS_COMPLETO.md](./ANALISIS_COMPLETO.md) | Análisis técnico detallado: carpetas, stack, seguridad, APIs, caja/POS, restaurante, integración posible |
| [EJECUCION_LOCAL.md](./EJECUCION_LOCAL.md) | **Poner en marcha** con Docker, importar MySQL, URLs y variables |
| [MYSQL_WINDOWS.md](./MYSQL_WINDOWS.md) | Instalar MySQL (XAMPP u Oracle), opciones del instalador y `.env` |

## Advertencia de seguridad (obligatoria)

El repositorio clonado incluye **credenciales de base de datos** y **rutas absolutas** de otro entorno (ej. `app/config/config.php`, ruta de sesiones en `services.php`). **No usar en producción** sin sustituir por variables de entorno y **rotar contraseñas** expuestas en el clon.

## Resumen en una frase

Aplicación **PHP Phalcon** de facturación electrónica y POS (Perú / SUNAT), con **MySQL**, APIs REST (`api`, `apiapp`), módulo **caja chica**, **punto de venta web** (`systempos`) y pantallas de **gestión restaurante** mayormente contenedores de assets; **no comparte código** con el backend Node/Mongo de Las Gambusinas.
