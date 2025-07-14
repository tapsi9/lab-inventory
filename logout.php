<?php
session_start();

// Destroy session
session_unset();
session_destroy();

// Expire "remember me" cookies
setcookie('remember_user', '', time() - 3600, '/');
setcookie('remember_pass', '', time() - 3600, '/');

// Redirect to login
header('Location: login.php');
exit;
