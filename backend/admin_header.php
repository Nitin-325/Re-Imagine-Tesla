<?php
// Escape output to prevent XSS
if(isset($message)){
   foreach($message as $message){
      $escaped_message = htmlspecialchars($message);
      echo '
      <div class="message">
         <span>'.$escaped_message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])){
   header('location:login.php');
   exit();
}
?>

<link rel="stylesheet" href="css/admin_header.css">
<header class="header">

   <div class="flex">

      <a href="admin_page.php" class="logo">Admin<span>Panel</span></a>

      <nav class="navbar">
         <a href="admin_page.php">Home</a>
         <a href="admin_products.php">Models</a>
         <a href="admin_orders.php">Orders</a>
         <a href="admin_users.php">Users</a>
         <a href="admin_contacts.php">Messages</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="account-box">
         <p>username : <span><?php echo htmlspecialchars($_SESSION['admin_name']); ?></span></p>
         <p>email : <span><?php echo htmlspecialchars($_SESSION['admin_email']); ?></span></p>
         <a href="logout.php" class="delete-btn">logout</a>
         <div>new <a href="login.php">login</a> | <a href="register.php">register</a></div>
      </div>

   </div>

</header>