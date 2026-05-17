<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
   exit(); // Add exit after redirect
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admin panel</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">
   <link rel="stylesheet" href="css/admin_home.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<!-- admin dashboard section starts  -->

<section class="dashboard">

   <h1 class="title">dashboard</h1>

   <div class="box-container">

      <div class="box">
         <?php
            $total_pendings = 0;
            $select_pending = mysqli_prepare($conn, "SELECT total_price FROM `orders` WHERE payment_status = 'pending'");
            if (!$select_pending) {
               error_log("Database prepare failed: " . mysqli_error($conn));
               die('An error occurred while retrieving pending orders');
            }
            if (!mysqli_stmt_execute($select_pending)) {
               error_log("Database execute failed: " . mysqli_stmt_error($select_pending));
               die('An error occurred while retrieving pending orders');
            }
            $result = mysqli_stmt_get_result($select_pending);
            if(mysqli_num_rows($result) > 0){
               while($fetch_pendings = mysqli_fetch_assoc($result)){
                  $total_price = $fetch_pendings['total_price'];
                  $total_pendings += $total_price;
               }
            }
            mysqli_stmt_close($select_pending);
         ?>
         <h3>$<?php echo htmlspecialchars($total_pendings); ?>/-</h3>
         <p>total pendings</p>
      </div>

      <div class="box">
         <?php
            $total_completed = 0;
            $select_completed = mysqli_prepare($conn, "SELECT total_price FROM `orders` WHERE payment_status = 'completed'");
            if (!$select_completed) {
               error_log("Database prepare failed: " . mysqli_error($conn));
               die('An error occurred while retrieving completed orders');
            }
            if (!mysqli_stmt_execute($select_completed)) {
               error_log("Database execute failed: " . mysqli_stmt_error($select_completed));
               die('An error occurred while retrieving completed orders');
            }
            $result = mysqli_stmt_get_result($select_completed);
            if(mysqli_num_rows($result) > 0){
               while($fetch_completed = mysqli_fetch_assoc($result)){
                  $total_price = $fetch_completed['total_price'];
                  $total_completed += $total_price;
               }
            }
            mysqli_stmt_close($select_completed);
         ?>
         <h3>$<?php echo htmlspecialchars($total_completed); ?>/-</h3>
         <p>completed payments</p>
      </div>

      <div class="box">
         <?php 
            $select_orders = mysqli_prepare($conn, "SELECT COUNT(*) as count FROM `orders`");
            if (!$select_orders) {
               error_log("Database prepare failed: " . mysqli_error($conn));
               die('An error occurred while counting orders');
            }
            if (!mysqli_stmt_execute($select_orders)) {
               error_log("Database execute failed: " . mysqli_stmt_error($select_orders));
               die('An error occurred while counting orders');
            }
            $result = mysqli_stmt_get_result($select_orders);
            $row = mysqli_fetch_assoc($result);
            $number_of_orders = $row['count'];
            mysqli_stmt_close($select_orders);
         ?>
         <h3><?php echo htmlspecialchars($number_of_orders); ?></h3>
         <p>order placed</p>
      </div>

      <div class="box">
         <?php 
            $select_products = mysqli_prepare($conn, "SELECT COUNT(*) as count FROM `products`");
            if (!$select_products) {
               error_log("Database prepare failed: " . mysqli_error($conn));
               die('An error occurred while counting products');
            }
            if (!mysqli_stmt_execute($select_products)) {
               error_log("Database execute failed: " . mysqli_stmt_error($select_products));
               die('An error occurred while counting products');
            }
            $result = mysqli_stmt_get_result($select_products);
            $row = mysqli_fetch_assoc($result);
            $number_of_products = $row['count'];
            mysqli_stmt_close($select_products);
         ?>
         <h3><?php echo htmlspecialchars($number_of_products); ?></h3>
         <p>products added</p>
      </div>

      <div class="box">
         <?php 
            $select_users = mysqli_prepare($conn, "SELECT COUNT(*) as count FROM `users` WHERE user_type = 'user'");
            if (!$select_users) {
               error_log("Database prepare failed: " . mysqli_error($conn));
               die('An error occurred while counting users');
            }
            if (!mysqli_stmt_execute($select_users)) {
               error_log("Database execute failed: " . mysqli_stmt_error($select_users));
               die('An error occurred while counting users');
            }
            $result = mysqli_stmt_get_result($select_users);
            $row = mysqli_fetch_assoc($result);
            $number_of_users = $row['count'];
            mysqli_stmt_close($select_users);
         ?>
         <h3><?php echo htmlspecialchars($number_of_users); ?></h3>
         <p>normal users</p>
      </div>

      <div class="box">
         <?php 
            $select_admins = mysqli_prepare($conn, "SELECT COUNT(*) as count FROM `users` WHERE user_type = 'admin'");
            if (!$select_admins) {
               error_log("Database prepare failed: " . mysqli_error($conn));
               die('An error occurred while counting admins');
            }
            if (!mysqli_stmt_execute($select_admins)) {
               error_log("Database execute failed: " . mysqli_stmt_error($select_admins));
               die('An error occurred while counting admins');
            }
            $result = mysqli_stmt_get_result($select_admins);
            $row = mysqli_fetch_assoc($result);
            $number_of_admins = $row['count'];
            mysqli_stmt_close($select_admins);
         ?>
         <h3><?php echo htmlspecialchars($number_of_admins); ?></h3>
         <p>admin users</p>
      </div>

      <div class="box">
         <?php 
            $select_account = mysqli_prepare($conn, "SELECT COUNT(*) as count FROM `users`");
            if (!$select_account) {
               error_log("Database prepare failed: " . mysqli_error($conn));
               die('An error occurred while counting accounts');
            }
            if (!mysqli_stmt_execute($select_account)) {
               error_log("Database execute failed: " . mysqli_stmt_error($select_account));
               die('An error occurred while counting accounts');
            }
            $result = mysqli_stmt_get_result($select_account);
            $row = mysqli_fetch_assoc($result);
            $number_of_account = $row['count'];
            mysqli_stmt_close($select_account);
         ?>
         <h3><?php echo htmlspecialchars($number_of_account); ?></h3>
         <p>total accounts</p>
      </div>

      <div class="box">
         <?php 
            $select_messages = mysqli_prepare($conn, "SELECT COUNT(*) as count FROM `message`");
            if (!$select_messages) {
               error_log("Database prepare failed: " . mysqli_error($conn));
               die('An error occurred while counting messages');
            }
            if (!mysqli_stmt_execute($select_messages)) {
               error_log("Database execute failed: " . mysqli_stmt_error($select_messages));
               die('An error occurred while counting messages');
            }
            $result = mysqli_stmt_get_result($select_messages);
            $row = mysqli_fetch_assoc($result);
            $number_of_messages = $row['count'];
            mysqli_stmt_close($select_messages);
         ?>
         <h3><?php echo htmlspecialchars($number_of_messages); ?></h3>
         <p>new messages</p>
         <a href="admin_contacts.php" class="btn">view messages</a>
      </div>
   </div>

</section>


<script src="js/admin_script.js"></script>

</body>
</html>