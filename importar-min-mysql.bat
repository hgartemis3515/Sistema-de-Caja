@echo off
cd /d "%~dp0"
where php >nul 2>&1
if errorlevel 1 (
  echo PHP no esta en el PATH. Ejecuta: php importar_min_mysql.php
  pause
  exit /b 1
)
php "%~dp0importar_min_mysql.php"
pause
