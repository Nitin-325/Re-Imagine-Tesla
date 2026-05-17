<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
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
   <title>orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/order.css">
   <link rel="stylesheet" href="css/order123.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="head">
   <h3>your orders</h3>
   <p> <a href="home.php">Home</a> / Orders </p>
</div>

<section class="placed-orders">

   <h1 class="title">placed orders</h1>

   <div class="box-container">

      <?php
         // Use prepared statement to prevent SQL injection
         $stmt = mysqli_prepare($conn, "SELECT * FROM `orders` WHERE user_id = ? ORDER BY placed_on DESC") or die('query failed');
         if(!$stmt) {
            die('Prepare statement failed: ' . mysqli_error($conn));
         }
         
         mysqli_stmt_bind_param($stmt, "i", $user_id); // Changed "s" to "i" for user_id
         if(!mysqli_stmt_execute($stmt)) {
            die('Execute failed: ' . mysqli_stmt_error($stmt));
         }
         
         $order_query = mysqli_stmt_get_result($stmt);
         if(!$order_query) {
            die('Get result failed: ' . mysqli_error($conn));
         }

         if(mysqli_num_rows($order_query) > 0){
            while($fetch_orders = mysqli_fetch_assoc($order_query)){
               // Sanitize data before output
               $placed_on = htmlspecialchars($fetch_orders['placed_on']);
               $name = htmlspecialchars($fetch_orders['name']); 
               $number = htmlspecialchars($fetch_orders['number']);
               $email = htmlspecialchars($fetch_orders['email']);
               $address = htmlspecialchars($fetch_orders['address']);
               $method = htmlspecialchars($fetch_orders['method']);
               $total_products = htmlspecialchars($fetch_orders['total_products']);
               $total_price = htmlspecialchars($fetch_orders['total_price']);
               $payment_status = htmlspecialchars($fetch_orders['payment_status']);
      ?>
      <div class="box">
         <p> placed on : <span><?php echo $placed_on; ?></span> </p>
         <p> name : <span><?php echo $name; ?></span> </p>
         <p> number : <span><?php echo $number; ?></span> </p>
         <p> email : <span><?php echo $email; ?></span> </p>
         <p> address : <span><?php echo $address; ?></span> </p>
         <p> payment method : <span><?php echo $method; ?></span> </p>
         <p> your orders : <span><?php echo $total_products; ?></span> </p>
         <p> total price : <span>$<?php echo $total_price; ?>/-</span> </p>
         <p> payment status : <span style="color:<?php echo ($payment_status == 'pending') ? 'red' : 'green'; ?>"><?php echo $payment_status; ?></span> </p>
         </div>
      <?php
       }
       mysqli_stmt_close($stmt);
      }else{
         echo '<p class="empty">no orders placed yet!</p>';
      }
      ?>
   </div>

</section>

<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>