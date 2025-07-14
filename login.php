<?php
session_start();
require_once('config.php');
include('db-connection.php');

// Redirect if already logged in
if (isset($_SESSION['id'])) {
    header('Location: ' . BASE_URL);
    exit;
}

// Handle "remember me" cookie login
if (!isset($_SESSION['id']) && isset($_COOKIE['remember_user']) && isset($_COOKIE['remember_pass'])) {
    $user_name = $_COOKIE['remember_user'];
    $user_password = $_COOKIE['remember_pass'];

    $sql = "SELECT * FROM tbl_users WHERE user_name = '$user_name' AND user_password = '$user_password'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $_SESSION['id'] = $row['id'];
        $_SESSION['user_name'] = $row['user_name'];
        $_SESSION['full_name'] = $row['full_name'];
        $_SESSION['user_email'] = $row['user_email'];
        $_SESSION['user_course'] = $row['user_course'];
        $_SESSION['role'] = $row['role'];

        header('Location: ' . ($row['role'] === 'admin' ? 'index.php' : 'user-dashboard.php'));
        exit;
    }
}

// Login logic
$alert_msg = '';
if (isset($_POST['login_submit'])) {
    $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
    $user_password = md5(mysqli_real_escape_string($conn, $_POST['user_password']));
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']); // Captured but not validated
    $remember = isset($_POST['remember_me']);

    $sql = "SELECT * FROM tbl_users WHERE user_name = '$user_name' AND user_password = '$user_password'";
$result = mysqli_query($conn, $sql);

if ($result && $row = mysqli_fetch_assoc($result)) {
    // If user is a student, validate the Student ID
    if ($row['role'] === 'user') {
        if (empty($student_id) || $row['student_id'] !== $student_id) {
            $alert_msg = '<div class="alert alert-danger" role="alert">Invalid Student ID.</div>';
        } else {
            // Valid student login
            $_SESSION['id'] = $row['id'];
            $_SESSION['user_name'] = $row['user_name'];
            $_SESSION['full_name'] = $row['full_name'];
            $_SESSION['user_email'] = $row['user_email'];
            $_SESSION['user_course'] = $row['user_course'];
            $_SESSION['role'] = $row['role'];

            if ($remember) {
                setcookie('remember_user', $user_name, time() + (86400 * 30), "/");
                setcookie('remember_pass', $user_password, time() + (86400 * 30), "/");
            }

            header('Location: user-dashboard.php');
            exit;
        }
    } else {
        // Admin login (no need for Student ID)
        $_SESSION['id'] = $row['id'];
        $_SESSION['user_name'] = $row['user_name'];
        $_SESSION['full_name'] = $row['full_name'];
        $_SESSION['user_email'] = $row['user_email'];
        $_SESSION['user_course'] = $row['user_course'];
        $_SESSION['role'] = $row['role'];

        if ($remember) {
            setcookie('remember_user', $user_name, time() + (86400 * 30), "/");
            setcookie('remember_pass', $user_password, time() + (86400 * 30), "/");
        }

        header('Location: index.php');
        exit;
    }
}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>
   <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
   <link href="style.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
   <style>
       .login-page {
           margin-top: 10%;
           margin-bottom: 10%;
           position: relative;
       }
       .login-page::before {
           content: '';
           position: fixed;
           top: 0;
           left: 0;
           width: 100%;
           height: 100%;
           background-image: url('olivarez-college-tagaytay-logo.png');
           background-repeat: no-repeat;
           background-position: center;
           background-size: contain;
           opacity: 0.1;
           z-index: -1;
       }
       .bg-white {
           background-color: rgba(255, 255, 255, 0.95) !important;
       }
   </style>
</head>
<body style="background-color: #f1f2f7;">

<div class="login-page">
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="col-lg-8">
                <div class="bg-white shadow rounded">
                    <div class="d-flex justify-content-center">
                        <div class="col-md-7 pe-0">
                            <div class="form-left h-100 py-5 px-5">
                                <form class="row g-4" method="POST">
                                    <?= $alert_msg; ?>
                                    <div class="text-center mb-3">
                                        <h1>Laboratory Inventory Management System Using QR Codes</h1>
                                        <p>Sign in to access the laboratories.</p>
                                    </div>

                                    <div class="col-12">
                                        <label>Student ID</label>
                                        <input type="text" name="student_id" class="form-control" placeholder="Enter Student ID">
                                    </div>

                                    <div class="col-12">
                                        <label>Username<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-text"><i class="bi bi-person-fill"></i></div>
                                            <input type="text" class="form-control" name="user_name" placeholder="Enter Username" required>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label>Password<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-text"><i class="bi bi-lock-fill"></i></div>
                                            <input type="password" class="form-control" name="user_password" placeholder="Enter Password" required>
                                        </div>
                                    </div>

                                    <div class="col-6 d-flex align-items-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember_me" id="remember_me">
                                            <label class="form-check-label" for="remember_me">Remember me</label>
                                        </div>
                                    </div>

                                    <div class="col-6 text-end">
                                        <a href="forgot-password.php" class="text-primary">Forgot Password?</a>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <a href="registration.php" class="btn btn-outline-secondary w-100 mt-4">Register</a>
                                        </div>
                                        <div class="col-6">
                                            <button type="submit" name="login_submit" class="btn btn-success w-100 mt-4">Login</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div> <!-- End col-md-7 -->
                    </div>
                </div> <!-- End bg-white -->
            </div>
        </div>
    </div>
</div>

</body>
</html>
