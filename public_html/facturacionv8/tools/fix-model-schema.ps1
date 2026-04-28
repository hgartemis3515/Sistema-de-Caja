$dir = Join-Path $PSScriptRoot '..\app\models' | Resolve-Path
Get-ChildItem -LiteralPath $dir -Filter '*.php' | ForEach-Object {
    $c = [System.IO.File]::ReadAllText($_.FullName)
    $old = '$this->setSchema("humbertoguadalup_bd_sys_facturacion");'
    $new = '$this->setSchema(APP_DB_SCHEMA);'
    $n = $c.Replace($old, $new)
    if ($c -ne $n) {
        $utf8 = New-Object System.Text.UTF8Encoding $false
        [System.IO.File]::WriteAllText($_.FullName, $n, $utf8)
        Write-Output $_.Name
    }
}
