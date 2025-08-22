<?php
session_start();
session_destroy();


if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}


echo "<script>
        alert('You have been logged out successfully!');
        window.location.href = 'loginpage.html';
      </script>";
?>