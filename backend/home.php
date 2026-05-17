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
   $product_name = mysqli_real_escape_string($conn, trim($_POST['product_name']));
   $product_price = filter_var($_POST['product_price'], FILTER_VALIDATE_FLOAT);
   $product_image = mysqli_real_escape_string($conn, trim($_POST['product_image'])); 
   $product_quantity = filter_var($_POST['product_quantity'], FILTER_VALIDATE_INT);

   // Validate required fields and data types
   if(!$product_name || !$product_price || !$product_image || !$product_quantity) {
      $message[] = 'Invalid product data';
   } else {
      // Use prepared statement to prevent SQL injection
      $check_cart = mysqli_prepare($conn, "SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
      mysqli_stmt_bind_param($check_cart, "si", $product_name, $user_id);
      mysqli_stmt_execute($check_cart);
      $result = mysqli_stmt_get_result($check_cart);

      if(mysqli_num_rows($result) > 0){
         $message[] = 'Already added to cart!';
      } else {
         $insert_cart = mysqli_prepare($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES(?, ?, ?, ?, ?)");
         mysqli_stmt_bind_param($insert_cart, "isdis", $user_id, $product_name, $product_price, $product_quantity, $product_image);
         
         if(mysqli_stmt_execute($insert_cart)){
            $message[] = 'Product added to cart!';
         } else {
            error_log("Error adding to cart: " . mysqli_stmt_error($insert_cart));
            $message[] = 'Error adding product to cart';
         }
         mysqli_stmt_close($insert_cart);
      }
      mysqli_stmt_close($check_cart);
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/Home_latestModel.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="home">

   <div class="content">
      <h3>One Mission Sustainable Energy</h3>
      <p>Re-Imagine Tesla's electric vehicles (EVs) are designed to accelerate the world's transition to sustainable energy</p>
      <p>Re-Imagine Tesla's mission is to create a world powered by solar, enabled by battery storage, and transported by electric vehicles.</p>
      <a href="about.php" class="white-btn">discover more</a>
   </div>

</section>

<section class="models">

   <h1 class="title">Latest Models</h1>

   <div class="container">

      <?php  
         $select_products = mysqli_query($conn, "SELECT * FROM `products` LIMIT 6") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
     <form action="" method="post" id="carshop" class="box">
      <img class="image" src="uploaded_img/<?php echo htmlspecialchars($fetch_products['image']); ?>" alt="">
      <div class="flexall">
         <div class="name"><?php echo htmlspecialchars($fetch_products['name']); ?></div>
         <div class="price">$<?php echo htmlspecialchars($fetch_products['price']); ?>/-</div>
         <input type="number" min="1" name="product_quantity" value="1" class="qty">
         <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($fetch_products['name']); ?>">
         <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($fetch_products['price']); ?>">
         <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($fetch_products['image']); ?>">
         <input type="submit" value="add to cart" name="add_to_cart" class="btn">
      </div>
     </form>
      <?php
         }
      }else{
         echo '<p class="empty">no car models added yet!</p>';
      }
      ?>
   </div>

   <div class="load-more" style="margin-top: 2rem; text-align:center">
      <a href="shop.php" class="option-btn">load more</a>
   </div>

</section>

<section class="about">

   <div class="flex">

      <div class="image">
         <img src="New_Images\HomeAboutImage" alt="">
      </div>

      <div class="content">
         <h3>about us</h3>
         <p>All Re-imagine Tesla models feature a unique touchscreen that comes with an array of features including video games, streaming services, and live traffic updates.</p>
         <a href="about.php" class="btn">read more</a>
      </div>

   </div>

</section>

<section class="home-contact">

   <div class="content">
      <h3>have any questions?</h3>
      <p>If you want to say something about the car models or car design then please click on CONTACT US and say something.</p>
      <a href="contact.php" class="white-btn">contact us</a>
   </div>

</section>

<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>