<?php
   session_start();
   require_once('config.php');
   include('db-connection.php');
   if (isset($_SESSION['id'])) {
    header('Location: '.BASE_URL);
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
       }


   </style>
</head>
<body>


   <?php


       $alert_msg = '';
       if ( isset( $_POST['login_submit'] ) ) {


           //Prepare data
           $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
           $user_password = mysqli_real_escape_string($conn, $_POST['user_password']);
           $user_password = md5($user_password);
          
           //5. Make SQL Query to insert data into database
           $sql = "SELECT * FROM `tbl_users` WHERE `user_name` = '$user_name' AND `user_password` = '$user_password'";
           $result = mysqli_query($conn, $sql);
           if ( $result ) {
               $row = mysqli_fetch_assoc($result); 


               if ( $row ) {

                $_SESSION['id'] = $row['id'];
                $_SESSION['user_name'] = $row['user_name'];
                $_SESSION['user_email'] = $row['user_email'];
                $_SESSION['full_name'] = $row['full_name']; 
                $_SESSION['user_course'] = $row['user_course'];  
                   header('Location: '.BASE_URL);
               } else {
                   $alert_msg = '<div class="alert alert-danger" role="alert">Username and email are incorrect.</div>';
               }
           }




       }
  
   ?>
    <div class="login-page">
       <div class="container">
           <div class="row">
               <div class="col-lg-8 offset-lg-2">
                   <div class="bg-white shadow rounded">
                       <div class="row">
                           <div class="col-md-7 pe-0">
                               <div class="form-left h-100 py-5 px-5">
                                   <form class="row g-4" method="POST">
                                           <?=$alert_msg;?>
                                           <div class="col-12">
                                               <label>Username<span class="text-danger">*</span></label>
                                               <div class="input-group">
                                                   <div class="input-group-text"><i class="bi bi-person-fill"></i></div>
                                                   <input type="text" class="form-control" name="user_name" placeholder="Enter Username">
                                               </div>
                                           </div>


                                           <div class="col-12">
                                               <label>Password<span class="text-danger">*</span></label>
                                               <div class="input-group">
                                                   <div class="input-group-text"><i class="bi bi-lock-fill"></i></div>
                                                   <input type="password" class="form-control" name="user_password" placeholder="Enter Password">
                                               </div>
                                           </div>


                                           <div class="col-sm-6">
                                               <div class="form-check">
                                                   <input class="form-check-input" type="checkbox" id="inlineFormCheck">
                                                   <label class="form-check-label" for="inlineFormCheck">Remember me</label>
                                               </div>
                                           </div>


                                           <div class="col-sm-6">
                                               <a href="#" class="float-end text-primary">Forgot Password?</a>
                                           </div>


                                           <div class="col-12">
                                               <button type="submit" name="login_submit" class="btn btn-primary px-4 float-end mt-4">Login</button>
                                           </div>
                                   </form>
                               </div>
                           </div>
                           <div class="col-md-5 ps-0 d-none d-md-block">
                               <div class="form-right h-100 bg-primary text-white text-center pt-5">
                                   <i class="bi bi-bootstrap" style="font-size: 100px;"></i>
                                   <h2 class="fs-1">Welcome Back!!!</h2>
                                   <a href="registration.php" class="btn btn-dark btn-block">Register</a>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </div>


</body>
</html>


