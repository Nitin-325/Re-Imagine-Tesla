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
   <title>search page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/search_page.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>search page</h3>
   <p> <a href="home.php">Home</a> / Search </p>
</div>

<section class="search-form">
   <form action="" method="post">
      <input type="text" name="search" placeholder="search products..." class="box">
      <input type="submit" name="submit" value="search" class="btn">
   </form>
</section>

<section class="products" style="padding-top: 0;">

   <div class="box-container">
   <?php
      if(isset($_POST['submit'])){
         $search_item = mysqli_real_escape_string($conn, $_POST['search']);
         // Use prepared statement for search
         $stmt = mysqli_prepare($conn, "SELECT * FROM `products` WHERE name LIKE ?");
         $search_pattern = "%$search_item%";
         mysqli_stmt_bind_param($stmt, "s", $search_pattern);
         mysqli_stmt_execute($stmt);
         $select_products = mysqli_stmt_get_result($stmt);

         if(mysqli_num_rows($select_products) > 0){
            while($fetch_product = mysqli_fetch_assoc($select_products)){
   ?>
   <form action="" method="post" class="box">
      <img src="uploaded_img/<?php echo htmlspecialchars($fetch_product['image']); ?>" alt="" class="image">
      <div class="name"><?php echo htmlspecialchars($fetch_product['name']); ?></div>
      <div class="price">$<?php echo htmlspecialchars($fetch_product['price']); ?>/-</div>
      <input type="number" class="qty" name="product_quantity" min="1" value="1">
      <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($fetch_product['name']); ?>">
      <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($fetch_product['price']); ?>">
      <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($fetch_product['image']); ?>">
      <input type="submit" class="btn" value="add to cart" name="add_to_cart">
   </form>
   <?php
            }
            mysqli_stmt_close($stmt);
         }else{
            echo '<p class="empty">no result found!</p>';
         }
      }else{
         echo '<p class="empty">search something!</p>';
      }
   ?>
   </div>

</section>

<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>