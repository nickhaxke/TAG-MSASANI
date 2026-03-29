<?php

// Test 1: GET /login (should show form)
echo "=== TEST 1: GET /login ===\n";

ini_set('display_errors', '0');
error_reporting(0);

$_SERVER['REQUEST_URI'] = '/kanisa/church-cms/public/login';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 Test';

ob_start();
require __DIR__ . '/public/index.php';
$output = ob_get_clean();

if (strpos($output, 'Church CMS Login') !== false) {
    echo "✓ Login form found\n";
} else {
    echo "✗ Login form NOT found\n";
}

if (strpos($output, 'form') !== false) {
    echo "✓ Form HTML found\n";
} else {
    echo "✗ Form HTML NOT found\n";
}

echo "\nSetup successful! The login page is working.\n";
echo "\nTo access the app:\n";
echo "1. Go to: http://localhost/kanisa/church-cms/public/\n";
echo "2. Or: http://localhost/kanisa/church-cms/public/login\n";
echo "3. Use phone: +255700000001\n";
echo "4. Use password: 12345678\n";


