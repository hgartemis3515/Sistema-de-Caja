#Requires -Version 5.1
<#
  Instala para Windows (sin Docker):
  - PHP 8.3 + php.ini (winget)
  - extensiones pdo_mysql, mysqli
  - Phalcon DLL (PECL Windows, misma version TS/NTS y PHP 8.3 x64)
  Opcional: -InstallMySQL instala Oracle.MySQL (servicio, tarda varios minutos).
#>
param(
    [switch]$InstallMySQL
)

$ErrorActionPreference = 'Stop'

function Refresh-Path {
    $env:Path = [System.Environment]::GetEnvironmentVariable('Path', 'Machine') + ';' +
        [System.Environment]::GetEnvironmentVariable('Path', 'User')
}

function Get-PhpRoot {
    Refresh-Path
    $php = Get-Command php -ErrorAction SilentlyContinue
    if (-not $php) { return $null }
    return (Split-Path $php.Source -Parent)
}

Write-Host '=== PHP 8.3 (winget) ===' -ForegroundColor Cyan
Refresh-Path
if (-not (Get-Command winget -ErrorAction SilentlyContinue)) {
    Write-Error 'winget no disponible. Instala App Installer / Windows Package Manager.'
}
$phpRoot = Get-PhpRoot
if (-not $phpRoot) {
    Write-Host 'Instalando PHP.PHP.8.3...'
    winget install -e --id PHP.PHP.8.3 --accept-package-agreements --accept-source-agreements --silent
    Refresh-Path
    $phpRoot = Get-PhpRoot
    if (-not $phpRoot) { Write-Error 'PHP no quedo en PATH. Cierra y abre la terminal o reinicia sesion.' }
}
Write-Host "PHP: $(Join-Path $phpRoot 'php.exe')"
& (Join-Path $phpRoot 'php.exe') -v

$zts = & (Join-Path $phpRoot 'php.exe') -r "echo PHP_ZTS ? 'ts' : 'nts';"
$phalconZip = "https://downloads.php.net/~windows/pecl/releases/phalcon/5.9.3/php_phalcon-5.9.3-8.3-$zts-vs16-x64.zip"
Write-Host "Phalcon: $phalconZip" -ForegroundColor Cyan

$ini = Join-Path $phpRoot 'php.ini'
$iniDev = Join-Path $phpRoot 'php.ini-development'
if (-not (Test-Path $ini)) {
    Copy-Item $iniDev $ini -Force
}
$c = Get-Content $ini -Raw
$c = $c -replace ';extension_dir = "ext"', 'extension_dir = "ext"'
$c = $c -replace ';extension=pdo_mysql', 'extension=pdo_mysql'
$c = $c -replace ';extension=mysqli', 'extension=mysqli'
$c = $c -replace ';extension=mbstring', 'extension=mbstring'
Set-Content -Path $ini -Value $c -NoNewline

$extDir = Join-Path $phpRoot 'ext'
$dllDest = Join-Path $extDir 'php_phalcon.dll'
$tmp = Join-Path $env:TEMP 'phalcon-win-install'
if (Test-Path $tmp) { Remove-Item $tmp -Recurse -Force }
New-Item -ItemType Directory -Path $tmp | Out-Null
$zipPath = Join-Path $tmp 'phalcon.zip'
Invoke-WebRequest -Uri $phalconZip -OutFile $zipPath -UseBasicParsing
Expand-Archive -Path $zipPath -DestinationPath $tmp -Force
$dll = Get-ChildItem $tmp -Recurse -Filter 'php_phalcon.dll' | Select-Object -First 1
if (-not $dll) { Write-Error 'No se encontro php_phalcon.dll en el ZIP' }
Copy-Item $dll.FullName $dllDest -Force
if ($c -notmatch 'extension\s*=\s*php_phalcon\.dll') {
    Add-Content $ini "`r`nextension=php_phalcon.dll`r`n"
}
if ($c -notmatch '(?m)^extension\s*=\s*mbstring') {
    Add-Content $ini "`r`nextension=mbstring`r`n"
}

Write-Host '=== Verificacion ===' -ForegroundColor Cyan
& (Join-Path $phpRoot 'php.exe') -m | Select-String -Pattern 'phalcon|pdo_mysql|mysqli|mbstring'

if ($InstallMySQL) {
    Write-Host '=== MySQL (winget, puede tardar) ===' -ForegroundColor Cyan
    winget install -e --id Oracle.MySQL --accept-package-agreements --accept-source-agreements --silent
    Write-Host 'MySQL instalado. Crea la base y usuario segun tu .env' -ForegroundColor Green
} else {
    Write-Host ''
    Write-Host 'MySQL: no instalado por defecto. Necesitas un servidor MySQL/MariaDB (XAMPP, Laragon, o winget Oracle.MySQL).' -ForegroundColor Yellow
    Write-Host '  Re-ejecuta con:  powershell -File instalar-requisitos.ps1 -InstallMySQL' -ForegroundColor Yellow
}

Write-Host ''
Write-Host 'Listo. Cierra y abre CMD/PowerShell si "php" no se reconoce, luego ejecuta iniciar.bat' -ForegroundColor Green
