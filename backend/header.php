<?php
if(isset($message)){
   foreach($message as $message){ // Using same variable name in loop can cause issues
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>
<link rel="stylesheet" href="css/home.css">
<link rel="stylesheet" href="css/header.css">

<header class="header">

   <div class="header-1">
      <div class="flex">
         <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-twitter"></a>
            <a href="#" class="fab fa-instagram"></a>
            <a href="#" class="fab fa-linkedin"></a>
         </div>
         <p> new <a href="login.php">login</a> | <a href="register.php">register</a> </p>
      </div>
   </div>
   <div class="header-2">
      <div class="flex">
         <a href="home.php" class="logo">𝑹𝒆-𝒊𝒎𝒂𝒈𝒊𝒏𝒆<br>𝑻𝒆𝒔𝒍𝒂</a>

         <nav class="navbar" style="position: relative;">
            <a href="home.php">Home</a>
            <a href="about.php">About</a>
            <a href="shop.php">Shop</a>
            <a href="contact.php">Contact</a>
            <a href="orders.php">Orders</a>
         </nav>

         <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="search_page.php" class="fas fa-search"></a>
            <div id="user-btn" class="fas fa-user"></div>
            <?php
            // Get cart count from database
            $select_cart_count = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
            $cart_num_rows = mysqli_num_rows($select_cart_count);
            ?>
            <a href="cart.php"> <i style="color: black;" class="fas fa-shopping-cart"></i> <span style="color: black;">(<?php echo $cart_num_rows; ?>)</span></a>
         </div>

         <div class="user-box">
            <?php
            // Check if session variables are set before using them
            if(isset($_SESSION['user_name']) && isset($_SESSION['user_email'])) {
               echo '<p>username : <span>'.$_SESSION['user_name'].'</span></p>';
               echo '<p>email : <span>'.$_SESSION['user_email'].'</span></p>';
            }
            ?>
            <a href="logout.php" class="delete-btn">logout</a>
         </div>
      </div>
   </div>

</header>