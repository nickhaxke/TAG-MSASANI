<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $pdo = new PDO(
        'mysql:host=127.0.0.1;port=3306;dbname=church_cms;charset=utf8mb4',
        'root',
        '/OEGwfI6]WxXSSSt',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "✓ Database Connected Successfully\n";
    
    // Check for users table
    $result = $pdo->query('SELECT COUNT(*) as cnt FROM `users`')->fetch();
    echo "✓ Users table exists with " . $result['cnt'] . " records\n";
    
    // Check for roles
    $result = $pdo->query('SELECT COUNT(*) as cnt FROM roles')->fetch();
    echo "✓ Roles table exists with " . $result['cnt'] . " records\n";
    
} catch (PDOException $e) {
    echo "✗ Database Connection Error:\n";
    echo $e->getMessage() . "\n";
    echo "\nFix steps:\n";
    echo "1. Verify MySQL is running\n";
    echo "2. Create database: CREATE DATABASE church_cms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\n";
    echo "3. Run: source /path/to/full_setup.sql;\n";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
