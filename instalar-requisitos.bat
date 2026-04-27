@echo off
cd /d "%~dp0"
echo Instalando PHP 8.3, extensiones MySQL y Phalcon...
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0instalar-requisitos.ps1" %*
if errorlevel 1 (
  echo.
  echo Fallo el script. Si ves error de politica, ejecuta PowerShell como administrador una vez.
  pause
  exit /b 1
)
echo.
pause
