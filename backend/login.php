<?php

include 'config.php';
session_start();

if(isset($_POST['submit'])){

   // Validate and sanitize inputs
   $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
   if(!$email) {
      $message[] = 'Please enter a valid email address';
   } else {
      $email = mysqli_real_escape_string($conn, $email);
      // Hash password with md5 to match stored hash
      $pass = md5($_POST['password']); 

      // Use prepared statement to prevent SQL injection
      $stmt = mysqli_prepare($conn, "SELECT * FROM `users` WHERE email = ? AND password = ?");
      if(!$stmt) {
         error_log("Prepare failed: " . mysqli_error($conn));
         $message[] = 'An error occurred. Please try again later.';
      } else {
         mysqli_stmt_bind_param($stmt, "ss", $email, $pass);
         
         if(!mysqli_stmt_execute($stmt)) {
            error_log("Execute failed: " . mysqli_stmt_error($stmt));
            $message[] = 'An error occurred. Please try again later.';
         } else {
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) > 0){
               $row = mysqli_fetch_assoc($result);

               if(!$row) {
                  error_log("Fetch failed: " . mysqli_error($conn));
                  $message[] = 'An error occurred. Please try again later.';
               } else {
                  // Set session variables and redirect based on user type
                  if($row['user_type'] == 'admin'){
                     $_SESSION['admin_name'] = $row['name'];
                     $_SESSION['admin_email'] = $row['email'];
                     $_SESSION['admin_id'] = $row['id'];
                     header('location:admin_page.php');
                     exit();
                  }elseif($row['user_type'] == 'user'){
                     $_SESSION['user_name'] = $row['name'];
                     $_SESSION['user_email'] = $row['email'];
                     $_SESSION['user_id'] = $row['id'];
                     header('location:home.php');
                     exit();
                  }
               }
            }else{
               $message[] = 'Invalid email or password!';
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
   <title>login</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/Login.css">

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
      <h3>login now</h3>
      <input type="email" name="email" placeholder="enter your email" required class="box">
      <input type="password" name="password" placeholder="enter your password" required class="box">
      <input type="submit" name="submit" value="login now" class="btn">
      <p>don't have an account?<br> <a href="register.php" style="color: royalblue;">register now</a></p>
   </form>

</div>

</body>
</html>