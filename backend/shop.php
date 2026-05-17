<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
   exit(); // Add exit after redirect
}

if(isset($_POST['add_to_cart'])){
   // Validate and sanitize inputs
   $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
   $product_price = filter_var($_POST['product_price'], FILTER_VALIDATE_FLOAT);
   $product_image = mysqli_real_escape_string($conn, $_POST['product_image']);
   $product_quantity = filter_var($_POST['product_quantity'], FILTER_VALIDATE_INT);

   if(!$product_price || !$product_quantity || $product_quantity < 1) {
      $message[] = 'Invalid input values';
   } else {
      // Use prepared statement to prevent SQL injection
      $stmt = mysqli_prepare($conn, "SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
      mysqli_stmt_bind_param($stmt, "ss", $product_name, $user_id);
      mysqli_stmt_execute($stmt);
      $check_cart_numbers = mysqli_stmt_get_result($stmt);

      if(mysqli_num_rows($check_cart_numbers) > 0){
         $message[] = 'already added to cart!';
      } else {
         $insert_stmt = mysqli_prepare($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES(?, ?, ?, ?, ?)");
         mysqli_stmt_bind_param($insert_stmt, "ssdis", $user_id, $product_name, $product_price, $product_quantity, $product_image);
         
         if(mysqli_stmt_execute($insert_stmt)){
            $message[] = 'product added to cart!';
         } else {
            $message[] = 'Error adding product to cart';
         }
         mysqli_stmt_close($insert_stmt);
      }
      mysqli_stmt_close($stmt);
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shop</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/shop.css">
   <link rel="stylesheet" href="css/shop_head.css">
   <link rel="stylesheet" href="css/models123.css">
</head>
<body>
   
<?php include 'header.php'; ?>

<div class="head">
   <h3>our shop</h3>
   <p> <a href="home.php">Home</a> / Shop </p>
</div>

<section class="models">

   <h1 class="title">latest products</h1>

   <div class="container">

      <?php  
         // Use prepared statement for selecting products
         $stmt = mysqli_prepare($conn, "SELECT * FROM `products`");
         mysqli_stmt_execute($stmt);
         $select_products = mysqli_stmt_get_result($stmt);
         
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
     <form action="" method="post" class="box">
      <img class="image" src="uploaded_img/<?php echo htmlspecialchars($fetch_products['image']); ?>" alt="">
      <div class="name"><?php echo htmlspecialchars($fetch_products['name']); ?></div>
      <div class="price">$<?php echo htmlspecialchars($fetch_products['price']); ?>/-</div>
      <input type="number" min="1" name="product_quantity" value="1" class="qty">
      <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($fetch_products['name']); ?>">
      <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($fetch_products['price']); ?>">
      <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($fetch_products['image']); ?>">
      <input type="submit" value="add to cart" name="add_to_cart" class="btn">
     </form>
      <?php
            }
            mysqli_stmt_close($stmt);
         }else{
            echo '<p class="empty">no products added yet!</p>';
         }
      ?>
   </div>

</section>

<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>