<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
   exit(); // Add exit after redirect
}

if(isset($_POST['send'])){

   // First check if message table exists, if not create it
   $create_message_table = "CREATE TABLE IF NOT EXISTS `message` (
      `id` int(100) NOT NULL AUTO_INCREMENT,
      `user_id` int(100) NOT NULL,
      `name` varchar(100) NOT NULL,
      `email` varchar(100) NOT NULL,
      `number` varchar(12) NOT NULL,
      `message` text NOT NULL,
      PRIMARY KEY (`id`)
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

   if (!mysqli_query($conn, $create_message_table)) {
      error_log("Error creating message table: " . mysqli_error($conn));
      die('Error creating message table. Please try again later.');
   }

   // Validate and sanitize inputs
   $name = mysqli_real_escape_string($conn, trim($_POST['name']));
   $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
   $number = filter_var($_POST['number'], FILTER_SANITIZE_NUMBER_INT);
   $msg = mysqli_real_escape_string($conn, trim($_POST['message']));

   // Validate required fields
   if(!$name || !$email || !$number || !$msg) {
      $message[] = 'Please fill all fields with valid information';
   } else {
      // Check message length
      if(strlen($msg) > 1000) {
         $message[] = 'Message is too long. Maximum 1000 characters allowed.';
      } else {
         $select_message = mysqli_prepare($conn, "SELECT * FROM `message` WHERE name = ? AND email = ? AND number = ? AND message = ?");
         if(!$select_message) {
            error_log("Prepare failed: " . mysqli_error($conn));
            $message[] = 'An error occurred. Please try again later.';
         } else {
            mysqli_stmt_bind_param($select_message, "ssss", $name, $email, $number, $msg);
            mysqli_stmt_execute($select_message);
            $result = mysqli_stmt_get_result($select_message);

            if(mysqli_num_rows($result) > 0){
               $message[] = 'Message sent already!';
            } else {
               $insert_message = mysqli_prepare($conn, "INSERT INTO `message`(user_id, name, email, number, message) VALUES(?, ?, ?, ?, ?)");
               if(!$insert_message) {
                  error_log("Prepare failed: " . mysqli_error($conn));
                  $message[] = 'An error occurred. Please try again later.';
               } else {
                  mysqli_stmt_bind_param($insert_message, "issss", $user_id, $name, $email, $number, $msg);
                  if(mysqli_stmt_execute($insert_message)){
                     $message[] = 'Message sent successfully!';
                  } else {
                     error_log("Execute failed: " . mysqli_stmt_error($insert_message));
                     $message[] = 'Error sending message!';
                  }
                  mysqli_stmt_close($insert_message);
               }
            }
            mysqli_stmt_close($select_message);
         }
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
   <title>Contact</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/contact.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="head">
   <h3>Contact Us</h3>
   <p><a href="home.php">Home</a> / Contact</p>
</div>

<section class="contact">
   <?php
   if(isset($message)){
      foreach($message as $msg){
         echo '<div class="message">'.$msg.'</div>';
      }
   }
   ?>
   <form action="" method="post">
      <h3>Send us a message!</h3>
      <input type="text" name="name" required placeholder="Enter your name" class="box" maxlength="100">
      <input type="email" name="email" required placeholder="Enter your email" class="box" maxlength="100">
      <input type="tel" name="number" required placeholder="Enter your phone number" class="box" pattern="[0-9]{10,12}" title="Please enter a valid phone number (10-12 digits)">
      <textarea name="message" class="box" placeholder="Enter your message" required maxlength="1000" rows="10"></textarea>
      <input type="submit" value="Send Message" name="send" class="btn">
   </form>
</section>

<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>