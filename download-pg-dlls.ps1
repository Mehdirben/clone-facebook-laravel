# Define extension directory
$phpExtDir = "C:\xampp\php\ext"

# Define PHP version - based on your php -v output
$phpVersion = "8.2"

# Define URLs for PostgreSQL PHP extensions
$urls = @{
    "php_pdo_pgsql.dll" = "https://windows.php.net/downloads/pecl/releases/pdo_pgsql/$phpVersion/php_pdo_pgsql-$phpVersion-nts-vs16-x64.zip"
    "php_pgsql.dll" = "https://windows.php.net/downloads/pecl/releases/pgsql/$phpVersion/php_pgsql-$phpVersion-nts-vs16-x64.zip"
}

# Create temp directory for downloads
$tempDir = Join-Path $env:TEMP "php-pg-extensions"
New-Item -ItemType Directory -Force -Path $tempDir | Out-Null

# Download and extract each extension
foreach ($ext in $urls.Keys) {
    $url = $urls[$ext]
    $zipFile = Join-Path $tempDir "$ext.zip"
    
    Write-Host "Downloading $ext from $url..."
    try {
        Invoke-WebRequest -Uri $url -OutFile $zipFile
        
        Write-Host "Extracting $ext..."
        Expand-Archive -Path $zipFile -DestinationPath $tempDir -Force
        
        # Find the DLL file
        $dllFile = Get-ChildItem -Path $tempDir -Filter "*.dll" -Recurse | Select-Object -First 1
        
        if ($dllFile) {
            # Copy to PHP extension directory
            Copy-Item -Path $dllFile.FullName -Destination (Join-Path $phpExtDir $ext) -Force
            Write-Host "Installed $ext to $phpExtDir"
        } else {
            Write-Host "Could not find DLL file in the extracted contents for $ext" -ForegroundColor Red
        }
    }
    catch {
        Write-Host "Error downloading or extracting $ext`: $_" -ForegroundColor Red
    }
}

# Clean up
Remove-Item -Path $tempDir -Recurse -Force

Write-Host "Installation completed. Please verify extensions are working by running 'php -m'" 