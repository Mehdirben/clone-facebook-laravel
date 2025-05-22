# PostgreSQL Installer Script

# Define the URL for PostgreSQL installer
$postgresVersion = "16"
$installerUrl = "https://get.enterprisedb.com/postgresql/postgresql-$postgresVersion.2-1-windows-x64.exe"
$installerPath = "$env:TEMP\postgresql_installer.exe"

# Check if PostgreSQL is already installed
$postgresInstalled = $false
$pgPaths = @(
    "C:\Program Files\PostgreSQL\$postgresVersion\bin\psql.exe", 
    "C:\Program Files\PostgreSQL\$($postgresVersion - 1)\bin\psql.exe", 
    "C:\Program Files\PostgreSQL\$($postgresVersion - 2)\bin\psql.exe"
)

foreach ($path in $pgPaths) {
    if (Test-Path $path) {
        $postgresInstalled = $true
        $pgBinPath = [System.IO.Path]::GetDirectoryName($path)
        Write-Host "PostgreSQL is already installed at: $pgBinPath"
        break
    }
}

if (-not $postgresInstalled) {
    # Download the installer
    Write-Host "Downloading PostgreSQL installer from $installerUrl..."
    try {
        Invoke-WebRequest -Uri $installerUrl -OutFile $installerPath -TimeoutSec 300
    }
    catch {
        Write-Host "Failed to download PostgreSQL installer: $_" -ForegroundColor Red
        Write-Host "Please download and install PostgreSQL manually from https://www.postgresql.org/download/windows/"
        exit 1
    }

    # Install PostgreSQL with basic settings
    Write-Host "Installing PostgreSQL..."
    try {
        $installArgs = "--mode unattended --superpassword postgres --servicename PostgreSQL --servicepassword postgres --serverport 5432"
        Start-Process -FilePath $installerPath -ArgumentList $installArgs -Wait
        Write-Host "PostgreSQL installation completed!"
        
        # Set the bin path
        $pgBinPath = "C:\Program Files\PostgreSQL\$postgresVersion\bin"
        if (-not (Test-Path $pgBinPath)) {
            # Try to find the actual bin path
            $pgInstallDir = "C:\Program Files\PostgreSQL"
            if (Test-Path $pgInstallDir) {
                $versions = Get-ChildItem -Path $pgInstallDir -Directory | Sort-Object -Property Name -Descending
                if ($versions.Count -gt 0) {
                    $pgBinPath = Join-Path (Join-Path $pgInstallDir $versions[0].Name) "bin"
                }
            }
        }
    }
    catch {
        Write-Host "Failed to install PostgreSQL: $_" -ForegroundColor Red
        Write-Host "Please install PostgreSQL manually from https://www.postgresql.org/download/windows/"
        exit 1
    }
}

# Create the facebook_clone database
if ($pgBinPath) {
    Write-Host "PostgreSQL binaries found at: $pgBinPath"
    $createDbPath = Join-Path $pgBinPath "createdb.exe"
    $psqlPath = Join-Path $pgBinPath "psql.exe"
    
    # Check if database already exists
    $dbExists = $false
    try {
        $dbListOutput = & $psqlPath -U postgres -c "SELECT datname FROM pg_database WHERE datname='facebook_clone'" -t
        $dbExists = $dbListOutput -match "facebook_clone"
    }
    catch {
        Write-Host "Error checking if database exists: $_" -ForegroundColor Yellow
    }
    
    if ($dbExists) {
        Write-Host "Database 'facebook_clone' already exists."
    }
    else {
        Write-Host "Creating 'facebook_clone' database..."
        try {
            & $createDbPath -U postgres facebook_clone
            Write-Host "Database 'facebook_clone' created successfully!"
        }
        catch {
            Write-Host "Error creating database: $_" -ForegroundColor Red
            Write-Host "Try creating the database manually with: createdb -U postgres facebook_clone"
        }
    }
}
else {
    Write-Host "Could not locate PostgreSQL binaries. Please create the database manually." -ForegroundColor Yellow
}

Write-Host ""
Write-Host "PostgreSQL Setup Information:"
Write-Host "------------------------------"
Write-Host "Default superuser: postgres"
Write-Host "Default password: postgres"
Write-Host "Default port: 5432"
Write-Host "Default database: facebook_clone"

# Add PostgreSQL bin directory to PATH temporarily for this session
if ($pgBinPath -and (Test-Path $pgBinPath)) {
    $env:Path = "$pgBinPath;$env:Path"
    Write-Host "Added PostgreSQL bin directory to PATH for this session: $pgBinPath"
}

Write-Host "Setup completed!" -ForegroundColor Green 