# =========================================================================
# Laravel PostgreSQL Setup Script
# =========================================================================
# This script will:
# 1. Install PostgreSQL if not already installed
# 2. Create a database for your Laravel application
# 3. Update Laravel .env file to use PostgreSQL
# 4. Enable PHP PostgreSQL extensions
# =========================================================================

Write-Host "========================================================" -ForegroundColor Cyan
Write-Host "           Laravel PostgreSQL Setup Script" -ForegroundColor Cyan
Write-Host "========================================================" -ForegroundColor Cyan
Write-Host ""

# -------------------------------------------------------------------------
# Step 1: Install PostgreSQL
# -------------------------------------------------------------------------
Write-Host "STEP 1: Installing PostgreSQL" -ForegroundColor Green
Write-Host "-------------------------------------------------------" -ForegroundColor Green

# Check if PostgreSQL is already installed
$postgresVersion = "16"
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
        Write-Host "PostgreSQL is already installed at: $pgBinPath" -ForegroundColor Yellow
        break
    }
}

if (-not $postgresInstalled) {
    # Download the installer
    $installerUrl = "https://get.enterprisedb.com/postgresql/postgresql-$postgresVersion.2-1-windows-x64.exe"
    $installerPath = "$env:TEMP\postgresql_installer.exe"
    
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

# -------------------------------------------------------------------------
# Step 2: Create PostgreSQL Database
# -------------------------------------------------------------------------
Write-Host ""
Write-Host "STEP 2: Creating PostgreSQL Database" -ForegroundColor Green
Write-Host "-------------------------------------------------------" -ForegroundColor Green

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
        Write-Host "Database 'facebook_clone' already exists." -ForegroundColor Yellow
    }
    else {
        Write-Host "Creating 'facebook_clone' database..."
        try {
            & $createDbPath -U postgres facebook_clone
            Write-Host "Database 'facebook_clone' created successfully!" -ForegroundColor Green
        }
        catch {
            Write-Host "Error creating database: $_" -ForegroundColor Red
            Write-Host "Try creating the database manually with: createdb -U postgres facebook_clone" -ForegroundColor Yellow
        }
    }
    
    # Add PostgreSQL bin directory to PATH temporarily for this session
    $env:Path = "$pgBinPath;$env:Path"
    Write-Host "Added PostgreSQL bin directory to PATH for this session: $pgBinPath"
}
else {
    Write-Host "Could not locate PostgreSQL binaries. Please create the database manually." -ForegroundColor Red
}

# -------------------------------------------------------------------------
# Step 3: Update Laravel .env File
# -------------------------------------------------------------------------
Write-Host ""
Write-Host "STEP 3: Updating Laravel .env File" -ForegroundColor Green
Write-Host "-------------------------------------------------------" -ForegroundColor Green

$envPath = ".env"

# Check if .env exists
if (Test-Path $envPath) {
    # Read existing content
    $content = Get-Content $envPath -Raw
    
    # Update DB configuration
    $content = $content -replace "DB_CONNECTION=sqlite", "DB_CONNECTION=pgsql"
    $content = $content -replace "DB_HOST=.*", "DB_HOST=127.0.0.1"
    $content = $content -replace "DB_PORT=.*", "DB_PORT=5432"
    $content = $content -replace "DB_DATABASE=.*", "DB_DATABASE=facebook_clone"
    $content = $content -replace "DB_USERNAME=.*", "DB_USERNAME=postgres"
    $content = $content -replace "DB_PASSWORD=.*", "DB_PASSWORD=postgres"
    
    # Write updated content
    $content | Set-Content $envPath
    
    Write-Host ".env file has been updated with PostgreSQL configuration." -ForegroundColor Green
} else {
    # Create new .env file with PostgreSQL configuration
    @"
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=facebook_clone
DB_USERNAME=postgres
DB_PASSWORD=postgres

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="\${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="\${APP_NAME}"
"@ | Set-Content $envPath
    
    Write-Host "Created new .env file with PostgreSQL configuration." -ForegroundColor Green
}

# Try to add app key if missing
if ((Get-Content $envPath) -match "APP_KEY=$") {
    try {
        $output = php artisan key:generate --show
        $appKey = $output.Trim()
        
        if ($appKey -match "^base64:") {
            (Get-Content $envPath) -replace "APP_KEY=$", "APP_KEY=$appKey" | Set-Content $envPath
            Write-Host "APP_KEY has been generated and added to .env file." -ForegroundColor Green
        }
    } catch {
        Write-Host "Could not generate APP_KEY. Please run 'php artisan key:generate' manually." -ForegroundColor Yellow
    }
}

# -------------------------------------------------------------------------
# Step 4: Install PHP PostgreSQL Extensions
# -------------------------------------------------------------------------
Write-Host ""
Write-Host "STEP 4: Installing PHP PostgreSQL Extensions" -ForegroundColor Green
Write-Host "-------------------------------------------------------" -ForegroundColor Green

# Get PHP extension directory
$phpIniOutput = php --ini | Select-String -Pattern "Loaded Configuration File"
if ($phpIniOutput -match ":(.*php\.ini)") {
    $phpIniPath = $Matches[1].Trim()
    $phpExtDir = (php -r 'echo ini_get("extension_dir");')
    
    Write-Host "PHP ini file: $phpIniPath"
    Write-Host "PHP extension directory: $phpExtDir"
    
    # Check if extensions are already enabled
    $phpIniContent = Get-Content $phpIniPath -Raw
    $needToUpdatePhpIni = $false
    
    if (-not ($phpIniContent -match 'extension\s*=\s*pdo_pgsql')) {
        $needToUpdatePhpIni = $true
    }
    
    if (-not ($phpIniContent -match 'extension\s*=\s*pgsql')) {
        $needToUpdatePhpIni = $true
    }
    
    if ($needToUpdatePhpIni) {
        Write-Host "Updating PHP ini file to enable PostgreSQL extensions..."
        
        # Create backup of php.ini
        Copy-Item $phpIniPath "$phpIniPath.bak"
        Write-Host "Created backup of php.ini at $phpIniPath.bak"
        
        # Update php.ini
        $phpIniContent = $phpIniContent -replace ";extension=pdo_pgsql", "extension=pdo_pgsql"
        $phpIniContent = $phpIniContent -replace ";extension=pgsql", "extension=pgsql"
        
        # If extensions don't exist, add them
        if (-not ($phpIniContent -match 'extension\s*=\s*pdo_pgsql')) {
            $phpIniContent = $phpIniContent -replace "\[PHP\]", "[PHP]`r`nextension=pdo_pgsql"
        }
        
        if (-not ($phpIniContent -match 'extension\s*=\s*pgsql')) {
            $phpIniContent = $phpIniContent -replace "\[PHP\]", "[PHP]`r`nextension=pgsql"
        }
        
        # Write updated php.ini
        $phpIniContent | Set-Content $phpIniPath
        Write-Host "PHP ini file updated. PostgreSQL extensions enabled." -ForegroundColor Green
    } else {
        Write-Host "PostgreSQL extensions are already enabled in php.ini." -ForegroundColor Yellow
    }
} else {
    Write-Host "Could not determine PHP ini file location." -ForegroundColor Red
    Write-Host "Please edit your php.ini manually to enable the postgresql extensions:" -ForegroundColor Yellow
    Write-Host "- extension=pdo_pgsql" -ForegroundColor Yellow
    Write-Host "- extension=pgsql" -ForegroundColor Yellow
}

# -------------------------------------------------------------------------
# Step 5: Run Laravel Migrations
# -------------------------------------------------------------------------
Write-Host ""
Write-Host "STEP 5: Running Laravel Migrations" -ForegroundColor Green
Write-Host "-------------------------------------------------------" -ForegroundColor Green

try {
    Write-Host "Running migrations in pretend mode first to check for potential issues..."
    php artisan migrate --pretend
    
    $confirmation = Read-Host "Do you want to run the actual migrations now? (y/n)"
    if ($confirmation -eq "y") {
        php artisan migrate
        Write-Host "Migrations completed successfully!" -ForegroundColor Green
    } else {
        Write-Host "Migrations not run. You can run them later with 'php artisan migrate'" -ForegroundColor Yellow
    }
} catch {
    Write-Host "Error running migrations: $_" -ForegroundColor Red
    Write-Host "Please run migrations manually with 'php artisan migrate' after fixing any issues." -ForegroundColor Yellow
}

# -------------------------------------------------------------------------
# Summary
# -------------------------------------------------------------------------
Write-Host ""
Write-Host "========================================================" -ForegroundColor Cyan
Write-Host "                  Setup Completed!" -ForegroundColor Cyan
Write-Host "========================================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "PostgreSQL Configuration:"
Write-Host "- Host: 127.0.0.1"
Write-Host "- Port: 5432"
Write-Host "- Database: facebook_clone"
Write-Host "- Username: postgres"
Write-Host "- Password: postgres"
Write-Host ""
Write-Host "If you encounter any issues, please check the POSTGRESQL-SETUP.md file"
Write-Host "for troubleshooting information."
Write-Host ""
Write-Host "Next steps:"
Write-Host "1. If you haven't already, run migrations with: php artisan migrate"
Write-Host "2. Start developing your application with PostgreSQL!"
Write-Host "" 