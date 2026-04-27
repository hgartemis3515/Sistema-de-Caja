# MySQL en Windows (para «Sistema de caja»)

## Opción A — XAMPP (rápido para desarrollo)

1. Descarga **XAMPP** (incluye MySQL/MariaDB): https://www.apachefriends.org/
2. Instalador: deja **Apache + MySQL + PHP + phpMyAdmin** marcados (puedes desmarcar Tomcat/FileZilla si no los usas).
3. Ruta típica: `C:\xampp\`. Arranca **XAMPP Control Panel** y pulsa **Start** en **MySQL**.
4. Puerto por defecto **3306**. Abre http://localhost/phpmyadmin
5. Pestaña **SQL** o **Cuentas de usuario**: crea base y usuario, por ejemplo:
   - Base: `facturacion_local`
   - Usuario: `factura` / contraseña: la que elijas  
   - Otorga todos los privilegios sobre esa base al usuario (o usa `root` sin contraseña solo en local).
6. Importa el **dump .sql** del sistema original (Importar → elegir archivo).
7. En tu `.env` del proyecto:
   - `DB_HOST=127.0.0.1`
   - `DB_PORT=3306` (o el puerto que muestre XAMPP si lo cambiaste)
   - `DB_NAME=facturacion_local`
   - `DB_USER` y `DB_PASS` (o `DB_PASSWORD`, el código acepta ambos)
8. **No mezcles** `DB_PORT=3306` con `MYSQL_PORT=3307` si MySQL real está en 3307: deja **un solo puerto** coherente con el servidor que arranca.

## Opción B — MySQL oficial (winget)

En PowerShell o CMD **como administrador** (recomendado):

```bat
winget install Oracle.MySQL --accept-package-agreements --accept-source-agreements
```

Durante el asistente gráfico (MySQL Installer):

| Pantalla | Qué elegir |
|----------|------------|
| Tipo de instalación | **Developer Default** o **Server only** si solo quieres el motor. |
| Requisitos | Instala **Visual C++** / **Python** si el instalador lo pide. |
| Tipo de configuración | **Development Computer** (menos memoria reservada; válido en PC de desarrollo). |
| Conectividad | **TCP/IP**, puerto **3306** (o anótalo si eliges otro). |
| Autenticación | **Use Strong Password Encryption** (recomendado). |
| Usuario root | Define contraseña de **root** y guárdala. |
| Usuarios Windows | Opcional: “Add User” para tu cuenta Windows (cómodo para herramientas). |

Después:

1. Servicio **MySQL80** (o similar) debe estar **En ejecución** (services.msc).
2. Crea base y usuario (MySQL Shell, Workbench o línea de comandos):

```sql
CREATE DATABASE facturacion_local CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'factura'@'localhost' IDENTIFIED BY 'tu_clave';
GRANT ALL ON facturacion_local.* TO 'factura'@'localhost';
FLUSH PRIVILEGES;
```

3. Importa el dump en `facturacion_local`.
4. Ajusta `.env` como en la opción A (`DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASS` o `DB_PASSWORD`, `DB_NAME`).

## Comprobar conexión desde PHP

```bat
php -r "$pdo=new PDO('mysql:host=127.0.0.1;port=3306;dbname=facturacion_local','factura','tu_clave'); echo 'OK';"
```

Si falla, el mensaje indica host, puerto, usuario o contraseña incorrectos antes de abrir la app Phalcon.

## Variables en `.env` (resumen)

| Variable | Ejemplo | Notas |
|----------|---------|--------|
| `DB_HOST` | `127.0.0.1` | Mejor que `localhost` en algunos entornos IPv6. |
| `DB_PORT` | `3306` | Debe coincidir con el puerto real del servidor MySQL. |
| `DB_NAME` | `facturacion_local` | La base debe existir y tener tablas (dump). |
| `DB_USER` / `DB_PASS` | | `DB_PASSWORD` también se usa como contraseña si `DB_PASS` falta. |
| `MYSQL_PORT` | | Solo se copia a `DB_PORT` si `DB_PORT` está vacío o no definido. |

Tras instalar y configurar, ejecuta de nuevo **`iniciar.bat`**. Si MySQL falla, ahora deberías ver un error **PDO/MySQL** explícito en consola del servidor embebido.
