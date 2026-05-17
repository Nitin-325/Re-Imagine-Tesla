<?php

include 'config.php';

session_start();

header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff"); 
header("X-Frame-Options: DENY");

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
   exit(); // Add exit after redirect
};

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if(isset($_GET['delete'])){
   if (!isset($_GET['csrf_token']) || $_GET['csrf_token'] !== $_SESSION['csrf_token']) {
      die('CSRF token validation failed');
   }
   if (!ctype_digit($_GET['delete'])) {
      die('Invalid input');
   }
   $delete_id = mysqli_real_escape_string($conn, $_GET['delete']);
   $stmt = mysqli_prepare($conn, "DELETE FROM `message` WHERE id = ?");
   mysqli_stmt_bind_param($stmt, "i", $delete_id);
   mysqli_stmt_execute($stmt);
   mysqli_stmt_close($stmt);
   header('location:admin_contacts.php');
   exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>messages</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="messages">

   <h1 class="title"> messages </h1>

   <div class="box-container">
   <?php
      $select_message = mysqli_prepare($conn, "SELECT * FROM `message` ORDER BY id DESC");
      if (!$select_message) {
          error_log("Database prepare failed: " . mysqli_error($conn));
          die('An error occurred while retrieving messages');
      }
      if (!mysqli_stmt_execute($select_message)) {
          error_log("Database execute failed: " . mysqli_stmt_error($select_message));
          die('An error occurred while retrieving messages');
      }
      $result = mysqli_stmt_get_result($select_message);
      if(mysqli_num_rows($result) > 0){
         while($fetch_message = mysqli_fetch_assoc($result)){
            // Escape output to prevent XSS
            $user_id = htmlspecialchars($fetch_message['user_id']);
            $name = htmlspecialchars($fetch_message['name']); 
            $number = htmlspecialchars($fetch_message['number']);
            $email = htmlspecialchars($fetch_message['email']);
            $message = htmlspecialchars($fetch_message['message']);
            $id = htmlspecialchars($fetch_message['id']);
   ?>
   <div class="box">
      <p> user id : <span><?php echo $user_id; ?></span> </p>
      <p> name : <span><?php echo $name; ?></span> </p>
      <p> number : <span><?php echo $number; ?></span> </p>
      <p> email : <span><?php echo $email; ?></span> </p>
      <p> message : <span><?php echo $message; ?></span> </p>
      <a href="admin_contacts.php?delete=<?php echo $id; ?>&csrf_token=<?php echo $_SESSION['csrf_token']; ?>" 
         onclick="return confirm('delete this message?');" 
         class="delete-btn">delete message</a>
   </div>
   <?php
      };
   }else{
      echo '<p class="empty">you have no messages!</p>';
   }
   ?>
   </div>

</section>

<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>