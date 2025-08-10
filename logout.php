<?php
session_start();

// Destroy all session data
session_destroy();

// Clear the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// Redirect to login page with success message
echo "<script>
        alert('You have been logged out successfully!');
        window.location.href = 'loginpage.html';
      </script>";
?>