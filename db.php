<?php
// Database configuration
$host = 'localhost';
$dbname = 'gamenvibe_db';
$username = 'root';
$password = ''; // Empty for XAMPP default

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Optional: Uncomment to test connection
    // echo "Database connected successfully!";
    
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>