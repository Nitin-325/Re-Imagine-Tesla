<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
   exit(); // Add exit after redirect
}

if(isset($_POST['update_order'])){
   // Validate and sanitize inputs
   $order_update_id = filter_var($_POST['order_id'], FILTER_VALIDATE_INT);
   $update_payment = filter_var($_POST['update_payment'], FILTER_SANITIZE_STRING);
   
   // Check if the order ID is valid
   if($order_update_id === false) {
      die('Invalid order ID');
   }

   // Use prepared statement to prevent SQL injection
   $stmt = mysqli_prepare($conn, "UPDATE `orders` SET payment_status = ? WHERE id = ?");
   mysqli_stmt_bind_param($stmt, "si", $update_payment, $order_update_id);
   
   if(!mysqli_stmt_execute($stmt)){
      die('query failed: ' . mysqli_error($conn));
   }
   mysqli_stmt_close($stmt);
   
   $message[] = 'Payment status has been updated!';
}

if(isset($_GET['delete'])){
   // Validate CSRF token
   if(!isset($_GET['csrf_token']) || $_GET['csrf_token'] !== $_SESSION['csrf_token']){
      die('Invalid CSRF token');
   }
   
   // Validate and sanitize delete ID
   $delete_id = filter_var($_GET['delete'], FILTER_VALIDATE_INT);
   
   // Check if the delete ID is valid
   if($delete_id === false) {
      die('Invalid delete ID');
   }

   // Use prepared statement
   $stmt = mysqli_prepare($conn, "DELETE FROM `orders` WHERE id = ?");
   mysqli_stmt_bind_param($stmt, "i", $delete_id);
   
   if(!mysqli_stmt_execute($stmt)){
      die('query failed: ' . mysqli_error($conn));
   }
   mysqli_stmt_close($stmt);
   
   header('location:admin_orders.php');
   exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="orders">

   <h1 class="title">placed orders</h1>

   <div class="box-container">
      <?php
      // Use prepared statement for select query with JOIN to get user details
      $select_orders = mysqli_prepare($conn, "SELECT o.*, u.name as user_name 
                                            FROM `orders` o 
                                            LEFT JOIN `users` u ON o.user_id = u.id 
                                            ORDER BY o.placed_on DESC");
      if(!mysqli_stmt_execute($select_orders)){
         die('query failed: ' . mysqli_error($conn));
      }
      
      $result = mysqli_stmt_get_result($select_orders);
      
      if(mysqli_num_rows($result) > 0){
         while($fetch_orders = mysqli_fetch_assoc($result)){
            // Escape output to prevent XSS
            $user_id = htmlspecialchars($fetch_orders['user_id']);
            $user_name = htmlspecialchars($fetch_orders['user_name']);
            $placed_on = htmlspecialchars($fetch_orders['placed_on']);
            $name = htmlspecialchars($fetch_orders['name']);
            $number = htmlspecialchars($fetch_orders['number']); 
            $email = htmlspecialchars($fetch_orders['email']);
            $address = htmlspecialchars($fetch_orders['address']);
            $total_products = htmlspecialchars($fetch_orders['total_products']);
            $total_price = htmlspecialchars($fetch_orders['total_price']);
            $method = htmlspecialchars($fetch_orders['method']);
            $payment_status = htmlspecialchars($fetch_orders['payment_status']);
            $id = htmlspecialchars($fetch_orders['id']);
      ?>
      <div class="box">
         <p> user id : <span><?php echo $user_id; ?></span> </p>
         <p> user name : <span><?php echo $user_name; ?></span> </p>
         <p> placed on : <span><?php echo $placed_on; ?></span> </p>
         <p> name : <span><?php echo $name; ?></span> </p>
         <p> number : <span><?php echo $number; ?></span> </p>
         <p> email : <span><?php echo $email; ?></span> </p>
         <p> address : <span><?php echo $address; ?></span> </p>
         <p> total products : <span><?php echo $total_products ? $total_products : 0; ?></span> </p>
         <p> total price : <span>$<?php echo $total_price; ?>/-</span> </p>
         <p> payment method : <span><?php echo $method; ?></span> </p>
         <form action="" method="post">
            <input type="hidden" name="order_id" value="<?php echo $id; ?>">
            <select name="update_payment">
               <option value="" selected disabled><?php echo $payment_status; ?></option>
               <option value="pending">pending</option>
               <option value="completed">completed</option>
            </select>
            <input type="submit" value="update" name="update_order" class="option-btn">
            <a href="admin_orders.php?delete=<?php echo $id; ?>&csrf_token=<?php echo $_SESSION['csrf_token']; ?>" onclick="return confirm('delete this order?');" class="delete-btn">delete</a>
         </form>
      </div>
      <?php
         }
         mysqli_stmt_close($select_orders);
      }else{
         echo '<p class="empty">no orders placed yet!</p>';
      }
      ?>
   </div>

</section>

<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>