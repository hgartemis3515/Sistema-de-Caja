@echo off
cd /d "%~dp0"
where node >nul 2>&1
if errorlevel 1 (
  echo Instala Node.js o ejecuta manualmente:
  echo node "%%~dp0public_html\facturacionv8\tools\generate_schema_from_models.mjs"
  pause
  exit /b 1
)
node "%~dp0public_html\facturacionv8\tools\generate_schema_from_models.mjs"
echo Listo: sql\schema_from_models.sql
pause
