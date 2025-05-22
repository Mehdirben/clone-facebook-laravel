# PostgreSQL Setup for Laravel

This guide will help you set up PostgreSQL for your Laravel application.

## Step 1: Install PostgreSQL

### Option 1: Use the installation script
You can run the PowerShell script included in this repository to automatically download and install PostgreSQL:

```powershell
.\install-postgresql.ps1
```

### Option 2: Manual Installation
1. Download PostgreSQL installer from the [official PostgreSQL website](https://www.postgresql.org/download/windows/)
2. Run the installer and follow the installation wizard
3. During setup:
   - Set the password for the 'postgres' user to 'postgres' (or any password of your choice)
   - Keep the default port as 5432
   - Complete the installation

## Step 2: Create the Database

If you used the installation script, the database is already created. Otherwise:

1. Open Command Prompt or PowerShell
2. Run the following command to create a new database:
   ```
   "C:\Program Files\PostgreSQL\16\bin\createdb.exe" -U postgres facebook_clone
   ```
   (Note: The path may vary depending on your PostgreSQL version)

## Step 3: Enable PHP PostgreSQL Extensions

Since you're using XAMPP, you need to enable the PostgreSQL extensions in PHP:

1. Open your php.ini file located at C:\xampp\php\php.ini
2. Find the following lines and uncomment them by removing the semicolon (;) at the beginning:
   ```
   ;extension=pdo_pgsql
   ;extension=pgsql
   ```
3. If you can't find these lines, add them near other extension declarations:
   ```
   extension=pdo_pgsql
   extension=pgsql
   ```
4. Save the php.ini file
5. Restart your Apache server from the XAMPP control panel

To verify if the extensions are properly enabled, run:
```
php enable-pgsql-extensions.php
```

## Step 4: Configure Laravel .env File

Your .env file should have been updated already with the following PostgreSQL configuration:

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=facebook_clone
DB_USERNAME=postgres
DB_PASSWORD=postgres
```

If the password you set during installation is different, update the DB_PASSWORD value accordingly.

## Step 5: Run Migrations

After completing the above steps, you can run your Laravel migrations:

```
php artisan migrate
```

If you encounter any issues, you can run the following to get additional information:

```
php artisan migrate --pretend
```

## Troubleshooting

1. If you encounter issues with the PHP extensions, run:
   ```
   php check-extensions.php
   ```
   This will show which PostgreSQL extensions are installed and which are missing.

2. To verify your database connection, run:
   ```
   php artisan db:monitor
   ```

3. For issues with credentials or connection, check that PostgreSQL service is running:
   1. Press Win+R
   2. Type "services.msc" and press Enter
   3. Locate the PostgreSQL service and ensure it's running 