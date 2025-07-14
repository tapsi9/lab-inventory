<?php
include('db-connection.php');
require_once('config.php');
$alert_msg = '';

if (isset($_POST['register_submit'])) {
    // Prepare data
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
    $user_email = mysqli_real_escape_string($conn, $_POST['user_email']);
    $user_course = mysqli_real_escape_string($conn, $_POST['user_course']);
    $user_password = md5(mysqli_real_escape_string($conn, $_POST['user_password']));
    $student_id = isset($_POST['student_id']) ? mysqli_real_escape_string($conn, $_POST['student_id']) : null;

    $role = 'user'; // Default role

    // Check for existing username/email
    $verify_sql = "SELECT user_name, user_email FROM tbl_users WHERE user_name = '$user_name' OR user_email = '$user_email'";
    $verify_result = mysqli_query($conn, $verify_sql);

    if (mysqli_num_rows($verify_result) != 0) {
        $alert_msg = '<div class="alert alert-danger" role="alert">Username or Email already exists.</div>';
    } else {
        if ($role === 'user' && empty($student_id)) {
            $alert_msg = '<div class="alert alert-danger" role="alert">Student ID is required</div>';
        } else {
            $insert_sql = "INSERT INTO tbl_users (full_name, user_name, user_course, user_email, user_password, role, student_id) 
                           VALUES ('$full_name', '$user_name', '$user_course', '$user_email', '$user_password', '$role', '$student_id')";

            if (mysqli_query($conn, $insert_sql)) {
                $alert_msg = '<div class="alert alert-success" role="alert">User has been added successfully!</div>';
            } else {
                $alert_msg = '<div class="alert alert-danger">Database error: ' . mysqli_error($conn) . '</div>';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Registration</title>
   <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
   <link href="style.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<section class="vh-100" style="background-color: #0a742c;">
    <div class="container h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-lg-12 col-xl-11">
                <div class="card text-black" style="border-radius: 25px;">
                    <div class="card-body p-md-5">
                        <div class="row justify-content-center">
                            <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                                <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Sign up</p>

                                <form class="mx-1 mx-md-4" method="POST">
                                    <?= $alert_msg; ?>

                                    <div class="form-outline mb-4">
                                        <input type="text" id="full_name" name="full_name" class="form-control" required />
                                        <label class="form-label" for="full_name">Full Name</label>
                                    </div>

                                    <div class="form-outline mb-4">
                                        <input type="text" id="user_name" name="user_name" class="form-control" required />
                                        <label class="form-label" for="user_name">Username</label>
                                    </div>

                                    <div class="form-outline mb-4">
                                        <input type="email" id="user_email" name="user_email" class="form-control" required />
                                        <label class="form-label" for="user_email">Email</label>
                                    </div>

                                    <div class="form-outline mb-4">
                                        <input type="text" id="student_id" name="student_id" class="form-control" placeholder="Enter Student ID" />
                                        <label class="form-label" for="student_id">Student ID</label>
                                    </div>

                                    <div class="mb-4">
                                        <label for="course" class="form-label">Course</label>
                                        <select id="course" class="form-control" name="user_course" required>
                                            <option value="">SELECT COURSE</option>
                                            <option value="BSIT">BS in Information Technology</option>
                                            <option value="BSN">BS in Nursing</option>
                                            <option value="BSA">BS in Accountancy</option>
                                            <option value="BSIS">BS in Accounting Information System</option>
                                            <option value="BSBA">BSBA in Marketing Management</option>
                                            <option value="BSTHM">BS in Tourism & Hospitality Management</option>
                                            <option value="BSCRIM">BS in Criminology</option>
                                        </select>
                                    </div>

                                    <div class="form-outline mb-4">
                                        <input type="password" id="user_password" name="user_password" class="form-control" required />
                                        <label class="form-label" for="user_password">Password</label>
                                    </div>

                                    <div class="form-check d-flex justify-content-center mb-4">
                                        <input class="form-check-input me-2" type="checkbox" id="termsCheckbox" required />
                                        <label class="form-check-label" for="termsCheckbox">
                                            I agree all statements in <a href="terms.php" target="_blank">Terms of Service</a>
                                        </label>
                                    </div>

                                    <div class="d-flex justify-content-center mb-4">
                                        <button type="submit" name="register_submit" class="btn btn-primary btn-lg">Register</button>
                                        &nbsp;&nbsp;
                                        <a href="login.php" class="btn btn-dark btn-lg">Login</a>
                                    </div>
                                </form>

                            </div>
                            <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">
                                <img src="olivarez-college-tagaytay-logo.png" class="img-fluid" alt="Logo">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
</section>

<script>
function validateForm() {
    const checkbox = document.getElementById('termsCheckbox');
    if (!checkbox.checked) {
        alert('You must agree to the Terms of Service.');
        return false;
    }
    return true;
}
</script>

</body>
</html>
