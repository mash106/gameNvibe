<?php
session_start();
require_once 'db.php';

if ($_POST) {
    $email_or_username = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Simple validation
    if (empty($email_or_username) || empty($password)) {
        die("Please fill in all fields!");
    }
    
    try {
        // Check if user exists (by email or username)
        $stmt = $pdo->prepare("SELECT id, username, email, password FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$email_or_username, $email_or_username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Check for admin credentials first
        if ($email_or_username === 'mdmashur001@gmail.com' && $password === 'mahir1234') {
            // Admin login
            $_SESSION['user_id'] = 'admin';
            $_SESSION['username'] = 'Admin';
            $_SESSION['email'] = 'mdmashur001@gmail.com';
            $_SESSION['is_admin'] = true;
            
            echo "<script>
                    alert('Admin login successful!');
                    window.location.href = 'admin-dashboard.php';
                  </script>";
        } else if ($user && password_verify($password, $user['password'])) {
            // Regular user login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['is_admin'] = false;
            
            echo "<script>
                    alert('Login successful! Welcome back!');
                    window.location.href = 'dashboard.php';
                  </script>";
        } else {
            // Login failed
            echo "<script>
                    alert('Invalid email/username or password!');
                    window.location.href = 'loginpage.html';
                  </script>";
        }
        
    } catch(PDOException $e) {
        die("Login error: " . $e->getMessage());
    }
}
?>