@echo off
setlocal EnableExtensions EnableDelayedExpansion
cd /d "%~dp0"

set "ROOT=%~dp0public_html\facturacionv8"
if not exist "%ROOT%\public\index.php" (
  echo No se encontro: "%ROOT%\public\index.php"
  pause
  exit /b 1
)

where php >nul 2>&1
if errorlevel 1 (
  echo PHP no esta en el PATH. Ejecuta antes instalar-requisitos.bat
  pause
  exit /b 1
)

php -r "exit(extension_loaded('phalcon')?0:1);" 2>nul
if errorlevel 1 (
  echo Falta la extension PHP Phalcon.
  pause
  exit /b 1
)

set "WEB_PORT=8080"
if exist "%~dp0.env" (
  for /f "usebackq tokens=2 delims==" %%P in (`findstr /i /b /c:"WEB_PORT=" "%~dp0.env" 2^>nul`) do set "WEB_PORT=%%P"
)
for /f "delims=" %%W in ("!WEB_PORT!") do set "WEB_PORT=%%W"
set "WEB_PORT=!WEB_PORT: =!"

REM /D = directorio inicial del proceso (rutas con espacios en "Sistema de caja")
REM No usar pipes ni comillas raras en el titulo: CMD las interpreta mal.
start "Facturacionv8" /D "%ROOT%" cmd /k php -S 127.0.0.1:!WEB_PORT! router-dev-server.php

timeout /t 2 /nobreak >nul
REM Sin comillas alrededor de la URL: evita error "sintaxis de etiqueta del volumen"
start http://127.0.0.1:!WEB_PORT!/

echo Listo. Ventana del servidor aparte; navegador en puerto !WEB_PORT!
echo Si faltan tablas MySQL: ejecuta una vez "%~dp0importar-min-mysql.bat"
echo Si la web muestra error de MySQL, revisa DB_HOST y DB_* en "%~dp0.env"
endlocal
