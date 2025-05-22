<?php

$required_extensions = [
    'pdo',
    'pdo_pgsql',
    'pgsql'
];

$loaded_extensions = get_loaded_extensions();

echo "Checking for required PostgreSQL extensions:\n";
foreach ($required_extensions as $ext) {
    if (in_array($ext, $loaded_extensions)) {
        echo "✓ {$ext} is installed\n";
    } else {
        echo "✗ {$ext} is NOT installed\n";
    }
}

echo "\nAll loaded extensions:\n";
echo implode(', ', $loaded_extensions);
echo "\n"; 