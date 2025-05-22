<?php
/*
INSTRUCTIONS TO ENABLE POSTGRESQL EXTENSIONS IN PHP

For XAMPP users:
----------------
1. Open your php.ini file located at C:\xampp\php\php.ini

2. Find the following lines and uncomment them by removing the semicolon (;) at the beginning:
   ;extension=pdo_pgsql
   ;extension=pgsql

3. If you can't find these lines, add them near other extension declarations:
   extension=pdo_pgsql
   extension=pgsql

4. Save the php.ini file

5. Restart your Apache server from the XAMPP control panel

For standalone PHP installations:
--------------------------------
1. Open your php.ini file (as shown by 'php --ini' command)

2. Uncomment or add the following lines:
   extension=pdo_pgsql
   extension=pgsql

3. Save the php.ini file

4. Restart your web server if you're using one

After making these changes, run this script again to verify the extensions are properly loaded.
*/

echo "========== PHP POSTGRESQL EXTENSIONS CHECK ==========\n\n";
echo "This script checks if the required PostgreSQL extensions are installed and enabled.\n\n";

$required_extensions = [
    'pdo',
    'pdo_pgsql',
    'pgsql'
];

$loaded_extensions = get_loaded_extensions();

echo "Checking for required PostgreSQL extensions:\n";
$all_installed = true;
foreach ($required_extensions as $ext) {
    if (in_array($ext, $loaded_extensions)) {
        echo "✓ {$ext} is installed and enabled\n";
    } else {
        echo "✗ {$ext} is NOT installed or enabled\n";
        $all_installed = false;
    }
}

if ($all_installed) {
    echo "\nAll required extensions are installed. Your Laravel application can now use PostgreSQL!\n";
} else {
    echo "\nSome extensions are missing. Please follow the instructions at the top of this file.\n";
}

echo "\nPHP version: " . PHP_VERSION . "\n";
echo "PHP ini file: " . php_ini_loaded_file() . "\n"; 