<?php
// Simulate GET request to /login
$_SERVER['REQUEST_URI'] = '/kanisa/church-cms/public/login';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['HTTP_HOST'] = 'localhost';

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    chdir(__DIR__ . '/public');
    ob_start();
    require 'public/index.php';
    $output = ob_get_clean();
    echo "✓ App Loaded OK\n";
    echo "Output (first 500 chars):\n";
    echo substr($output, 0, 500);
} catch (Throwable $e) {
    echo "✗ Error:\n";
    echo get_class($e) . ": " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
