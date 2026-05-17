<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

if(isset($_GET['delete'])){
   // Validate delete_id
   $delete_id = filter_var($_GET['delete'], FILTER_VALIDATE_INT);
   
   if($delete_id === false) {
      die('Invalid user ID');
   }

   // Delete user using prepared statement
   $stmt = mysqli_prepare($conn, "DELETE FROM `users` WHERE id = ?");
   mysqli_stmt_bind_param($stmt, "i", $delete_id);
   mysqli_stmt_execute($stmt);
   mysqli_stmt_close($stmt);

   header('location:admin_users.php');
   exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>users</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">
   <link rel="stylesheet" href="css/admin_users.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="users">

   <h1 class="title"> user accounts </h1>

   <div class="box-container">
      <?php
         $stmt = mysqli_prepare($conn, "SELECT * FROM `users`");
         if(!$stmt){
            error_log("Database prepare failed: " . mysqli_error($conn));
            die('An error occurred while retrieving users');
         }
         if(!mysqli_stmt_execute($stmt)){
            error_log("Database execute failed: " . mysqli_stmt_error($stmt));
            die('An error occurred while retrieving users');
         }
         $result = mysqli_stmt_get_result($stmt);
         while($fetch_users = mysqli_fetch_assoc($result)){
            // Escape output to prevent XSS
            $id = htmlspecialchars($fetch_users['id']);
            $name = htmlspecialchars($fetch_users['name']);
            $email = htmlspecialchars($fetch_users['email']);
            $user_type = htmlspecialchars($fetch_users['user_type']);
      ?>
      <div class="box">
         <p> user id : <span><?php echo $id; ?></span> </p>
         <p> username : <span><?php echo $name; ?></span> </p>
         <p> email : <span><?php echo $email; ?></span> </p>
         <p> user type : <span style="color:<?php if($user_type == 'admin'){ echo 'var(--orange)'; } ?>"><?php echo $user_type; ?></span> </p>
         <a href="admin_users.php?delete=<?php echo $id; ?>&csrf_token=<?php echo $_SESSION['csrf_token']; ?>" onclick="return confirm('delete this user?');" class="delete-btn">delete user</a>
      </div>
      <?php
         }
         mysqli_stmt_close($stmt);
      ?>
   </div>

</section>

<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>