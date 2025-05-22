# Script to update .env file with PostgreSQL configuration
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
    
    Write-Host ".env file has been updated with PostgreSQL configuration."
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
    
    Write-Host "Created new .env file with PostgreSQL configuration."
}

# Try to add app key if missing
if ((Get-Content $envPath) -match "APP_KEY=$") {
    try {
        $output = php artisan key:generate --show
        $appKey = $output.Trim()
        
        if ($appKey -match "^base64:") {
            (Get-Content $envPath) -replace "APP_KEY=$", "APP_KEY=$appKey" | Set-Content $envPath
            Write-Host "APP_KEY has been generated and added to .env file."
        }
    } catch {
        Write-Host "Could not generate APP_KEY. Please run 'php artisan key:generate' manually."
    }
} 