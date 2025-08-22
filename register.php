<?php
session_start();
require_once 'db.php';


echo "Form submitted! POST data received.<br>";
print_r($_POST);
echo "<br>---<br>";

if ($_POST) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    

    if (empty($username) || empty($email) || empty($password)) {
        die("All fields are required!");
    }
    
    if ($password !== $confirm_password) {
        die("Passwords don't match!");
    }
    
    if (strlen($password) < 6) {
        die("Password must be at least 6 characters!");
    }
    
   
    $check_stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
    $check_stmt->execute([$email, $username]);
    
    if ($check_stmt->rowCount() > 0) {
        die("Email or username already exists!");
    }
    

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashed_password]);
        
        echo "<script>
                alert('Account created successfully! You can now login.');
                window.location.href = 'loginpage.html';
              </script>";
              
    } catch(PDOException $e) {
        die("Registration failed: " . $e->getMessage());
    }
}
?>