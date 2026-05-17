<?php

include 'config.php';

if(isset($_POST['submit'])){

   // Validate and sanitize inputs
   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
   if(!$email) {
      $message[] = 'Please enter a valid email address';
   } else {
      $email = mysqli_real_escape_string($conn, $email);
      $pass = mysqli_real_escape_string($conn, md5($_POST['password'])); // Use md5 to match login
      $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword'])); // Hash confirm password too
      $user_type = filter_var($_POST['user_type'], FILTER_SANITIZE_STRING);

      // Check if user type is admin and validate admin key
      if ($user_type == 'admin') {
         $admin_key = filter_var($_POST['admin_key'], FILTER_SANITIZE_STRING);
         if ($admin_key !== '3220051532005') {
            $message[] = 'Invalid admin key!';
         }
      }

      if (empty($message)) {
         // Use prepared statement to prevent SQL injection
         $stmt = mysqli_prepare($conn, "SELECT * FROM `users` WHERE email = ?");
         mysqli_stmt_bind_param($stmt, "s", $email);
         mysqli_stmt_execute($stmt);
         $result = mysqli_stmt_get_result($stmt);

         if(mysqli_num_rows($result) > 0){
            $message[] = 'User already exists!';
         } else {
            if($pass != $cpass){
               $message[] = 'Confirm password does not match!';
            } else {
               // Insert new user with prepared statement
               $insert_stmt = mysqli_prepare($conn, "INSERT INTO `users`(name, email, password, user_type) VALUES(?, ?, ?, ?)");
               mysqli_stmt_bind_param($insert_stmt, "ssss", $name, $email, $pass, $user_type);
               
               if(mysqli_stmt_execute($insert_stmt)){
                  $message[] = 'Registered successfully!';
                  header('location:login.php');
                  exit(); // Add exit after redirect
               } else {
                  $message[] = 'Registration failed! Please try again.';
               }
               mysqli_stmt_close($insert_stmt);
            }
         }
         mysqli_stmt_close($stmt);
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/register.css">

   <script>
      function toggleAdminKey() {
         var userType = document.querySelector('select[name="user_type"]').value;
         var adminKeyField = document.getElementById('admin-key-field');
         var formContainer = document.querySelector('.form-container');
         if (userType === 'admin') {
            adminKeyField.style.display = 'block';
            formContainer.style.height = 'auto'; // Adjust height to fit content
         } else {
            adminKeyField.style.display = 'none';
            formContainer.style.height = 'auto'; // Adjust height to fit content
         }
      }
   </script>
</head>
<body>

<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.htmlspecialchars($message).'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>
 
<div class="form-container">

   <form action="" method="post">
      <h3>register now</h3>
      <input type="text" name="name" placeholder="enter your name" required class="box">
      <input type="email" name="email" placeholder="enter your email" required class="box">
      <input type="password" name="password" placeholder="enter your password" required class="box">
      <input type="password" name="cpassword" placeholder="confirm your password" required class="box">
      <select name="user_type" class="box" onchange="toggleAdminKey()">
         <option value="user">user</option>
         <option value="admin">admin</option>
      </select>
      <div id="admin-key-field" style="display: none;">
         <input type="text" name="admin_key" placeholder="enter admin key" class="box">
      </div>
      <input type="submit" name="submit" value="register now" class="btn">
      <p>already have an account?<br><a href="login.php" style="color: royalblue;">login now</a></p>
   </form>

</div>

</body>
</html>