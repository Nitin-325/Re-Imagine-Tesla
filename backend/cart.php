<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
   exit();
}

if(isset($_POST['update_cart'])){
   $cart_id = filter_var($_POST['cart_id'], FILTER_VALIDATE_INT);
   $cart_quantity = filter_var($_POST['cart_quantity'], FILTER_VALIDATE_INT);
   
   if($cart_id === false || $cart_quantity === false || $cart_quantity < 1) {
      $message[] = 'Invalid input values';
   } else {
      $stmt = mysqli_prepare($conn, "UPDATE `cart` SET quantity = ? WHERE id = ? AND user_id = ?");
      mysqli_stmt_bind_param($stmt, "iii", $cart_quantity, $cart_id, $user_id);
      
      if(mysqli_stmt_execute($stmt)){
         $message[] = 'Cart quantity updated!';
      } else {
         $message[] = 'Failed to update cart';
      }
      mysqli_stmt_close($stmt);
   }
}

if(isset($_GET['delete'])){
   $delete_id = filter_var($_GET['delete'], FILTER_VALIDATE_INT);
   
   if($delete_id === false) {
      die('Invalid cart item ID');
   }

   $stmt = mysqli_prepare($conn, "DELETE FROM `cart` WHERE id = ? AND user_id = ?");
   mysqli_stmt_bind_param($stmt, "ii", $delete_id, $user_id);
   mysqli_stmt_execute($stmt);
   mysqli_stmt_close($stmt);
   
   header('location:cart.php');
   exit();
}

if(isset($_GET['delete_all'])){
   $stmt = mysqli_prepare($conn, "DELETE FROM `cart` WHERE user_id = ?");
   mysqli_stmt_bind_param($stmt, "i", $user_id);
   mysqli_stmt_execute($stmt);
   mysqli_stmt_close($stmt);
   
   header('location:cart.php');
   exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>cart</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/cart.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="head">
   <h3>shopping cart</h3>
   <p> <a href="home.php">Home</a> / Cart </p>
</div>

<section class="shopping" class="shopping-cart">

   <h1 class="title">modeles added</h1>

   <div class="box-container">
      <?php
         $grand_total = 0;
         $stmt = mysqli_prepare($conn, "SELECT * FROM `cart` WHERE user_id = ?");
         mysqli_stmt_bind_param($stmt, "i", $user_id);
         mysqli_stmt_execute($stmt);
         $select_cart = mysqli_stmt_get_result($stmt);
         
         if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){   
      ?>
      <div class="box">
         <a href="cart.php?delete=<?php echo htmlspecialchars($fetch_cart['id']); ?>" class="fas fa-times" onclick="return confirm('delete this from cart?');"></a>
         <img src="uploaded_img/<?php echo htmlspecialchars($fetch_cart['image']); ?>" alt="">
         <div class="name"><?php echo htmlspecialchars($fetch_cart['name']); ?></div>
         <div class="price">$<?php echo htmlspecialchars($fetch_cart['price']); ?>/-</div>
         <form action="" method="post">
            <input type="hidden" name="cart_id" value="<?php echo htmlspecialchars($fetch_cart['id']); ?>">
            <input type="number" min="1" name="cart_quantity" class="qty" value="<?php echo htmlspecialchars($fetch_cart['quantity']); ?>">
            <input type="submit" name="update_cart" value="update quantity" class="option-btn">
         </form>
         <div class="sub-total"> sub total : <span>$<?php echo $sub_total = ($fetch_cart['quantity'] * $fetch_cart['price']); ?>/-</span> </div>
      </div>
      <?php
      $grand_total += $sub_total;
         }
         mysqli_stmt_close($stmt);
      }else{
         echo '<p class="empty">your cart is empty</p>';
      }
      ?>
   </div>

   <div class="delete" style="margin-top: 2rem; text-align:center;">
      <a href="cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 1)?'':'disabled'; ?>" onclick="return confirm('delete all from cart?');">delete all</a>
   </div>

   <div class="cart-total">
      <p style="border: none;">GRAND TOTAL : <span>$<?php echo htmlspecialchars($grand_total); ?>/-</span></p>
      <div style="margin-left: -100px;" class="flex">
         <a style="margin-right: 70px;" href="shop.php" class="option-btn">continue shopping</a>
         <a href="checkout.php" class="btn <?php echo ($grand_total > 0)?'':'disabled'; ?>">proceed to checkout</a>
      </div>
   </div>

</section>

<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>