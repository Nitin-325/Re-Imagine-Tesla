<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
   exit();
};

if(isset($_POST['add_product'])){
   // Validate and sanitize inputs
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   // Validate price
   if($price === false || $price < 0) {
      $message[] = 'Invalid price value';
   } else {
      // Check for duplicate product name using prepared statement
      $stmt = mysqli_prepare($conn, "SELECT name FROM `products` WHERE name = ?");
      mysqli_stmt_bind_param($stmt, "s", $name);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);

      if(mysqli_stmt_num_rows($stmt) > 0){
         $message[] = 'product name already added';
      } else {
         // Validate image
         $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
         if(!in_array($_FILES['image']['type'], $allowed_types)) {
            $message[] = 'Invalid image type. Only JPG, JPEG and PNG allowed.';
         } else if($image_size > 2000000){
            $message[] = 'image size is too large';
         } else {
            // Insert product using prepared statement
            $stmt = mysqli_prepare($conn, "INSERT INTO `products`(name, price, image) VALUES(?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sds", $name, $price, $image);
            
            if(mysqli_stmt_execute($stmt)){
               if(move_uploaded_file($image_tmp_name, $image_folder)){
                  $message[] = 'product added successfully!';
               } else {
                  $message[] = 'Could not upload image!';
               }
            } else {
               $message[] = 'product could not be added!';
            }
         }
      }
      mysqli_stmt_close($stmt);
   }
}

if(isset($_GET['delete'])){
   // Validate delete_id
   $delete_id = filter_var($_GET['delete'], FILTER_VALIDATE_INT);
   
   if($delete_id === false) {
      die('Invalid product ID');
   }

   // Get image filename using prepared statement
   $stmt = mysqli_prepare($conn, "SELECT image FROM `products` WHERE id = ?");
   mysqli_stmt_bind_param($stmt, "i", $delete_id);
   mysqli_stmt_execute($stmt);
   $result = mysqli_stmt_get_result($stmt);
   $fetch_delete_image = mysqli_fetch_assoc($result);
   
   if($fetch_delete_image) {
      $image_path = 'uploaded_img/'.$fetch_delete_image['image'];
      if(file_exists($image_path)) {
         unlink($image_path);
      }
   }

   // Delete product using prepared statement
   $stmt = mysqli_prepare($conn, "DELETE FROM `products` WHERE id = ?");
   mysqli_stmt_bind_param($stmt, "i", $delete_id);
   mysqli_stmt_execute($stmt);
   mysqli_stmt_close($stmt);

   header('location:admin_products.php');
   exit();
}

if(isset($_POST['update_product'])){
   // Validate inputs
   $update_p_id = filter_var($_POST['update_p_id'], FILTER_VALIDATE_INT);
   $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
   $update_price = filter_var($_POST['update_price'], FILTER_VALIDATE_FLOAT);

   if($update_p_id === false || $update_price === false || $update_price < 0) {
      $message[] = 'Invalid input values';
   } else {
      // Update product using prepared statement
      $stmt = mysqli_prepare($conn, "UPDATE `products` SET name = ?, price = ? WHERE id = ?");
      mysqli_stmt_bind_param($stmt, "sdi", $update_name, $update_price, $update_p_id);
      mysqli_stmt_execute($stmt);

      $update_image = $_FILES['update_image']['name'];
      $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
      $update_image_size = $_FILES['update_image']['size'];
      $update_folder = 'uploaded_img/'.$update_image;
      $update_old_image = $_POST['update_old_image'];

      if(!empty($update_image)){
         $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
         if(!in_array($_FILES['update_image']['type'], $allowed_types)) {
            $message[] = 'Invalid image type. Only JPG, JPEG and PNG allowed.';
         } else if($update_image_size > 2000000){
            $message[] = 'image file size is too large';
         } else {
            $stmt = mysqli_prepare($conn, "UPDATE `products` SET image = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "si", $update_image, $update_p_id);
            
            if(mysqli_stmt_execute($stmt)){
               if(move_uploaded_file($update_image_tmp_name, $update_folder)){
                  if(file_exists('uploaded_img/'.$update_old_image)) {
                     unlink('uploaded_img/'.$update_old_image);
                  }
               }
            }
         }
      }
      mysqli_stmt_close($stmt);

      header('location:admin_products.php');
      exit();
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">
   <link rel="stylesheet" href="css/admin_product.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<!-- product CRUD section starts  -->

<section class="add-products">

   <h1 class="title">Car_Showroom</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <h3>add models</h3>
      <input type="text" name="name" class="box" placeholder="enter model name" required>
      <input type="number" min="0" step="0.01" name="price" class="box" placeholder="enter model price" required>
      <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
      <input type="submit" value="add model" name="add_product" class="btn">
   </form>

</section>

<!-- product CRUD section ends -->

<!-- show products  -->

<section class="show-products">

   <div class="box-container">

      <?php
         $stmt = mysqli_prepare($conn, "SELECT * FROM `products`");
         mysqli_stmt_execute($stmt);
         $result = mysqli_stmt_get_result($stmt);
         
         if(mysqli_num_rows($result) > 0){
            while($fetch_products = mysqli_fetch_assoc($result)){
      ?>
      <div class="box">
         <img src="uploaded_img/<?php echo htmlspecialchars($fetch_products['image']); ?>" alt="">
         <div class="name"><?php echo htmlspecialchars($fetch_products['name']); ?></div>
         <div class="price">$<?php echo htmlspecialchars($fetch_products['price']); ?>/-</div>
         <a href="admin_products.php?update=<?php echo htmlspecialchars($fetch_products['id']); ?>" class="option-btn">update</a>
         <a href="admin_products.php?delete=<?php echo htmlspecialchars($fetch_products['id']); ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
      mysqli_stmt_close($stmt);
      ?>
   </div>

</section>

<section class="edit-product-form">

   <?php
      if(isset($_GET['update'])){
         $update_id = filter_var($_GET['update'], FILTER_VALIDATE_INT);
         
         if($update_id === false) {
            die('Invalid product ID');
         }

         $stmt = mysqli_prepare($conn, "SELECT * FROM `products` WHERE id = ?");
         mysqli_stmt_bind_param($stmt, "i", $update_id);
         mysqli_stmt_execute($stmt);
         $result = mysqli_stmt_get_result($stmt);
         
         if(mysqli_num_rows($result) > 0){
            while($fetch_update = mysqli_fetch_assoc($result)){
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="update_p_id" value="<?php echo htmlspecialchars($fetch_update['id']); ?>">
      <input type="hidden" name="update_old_image" value="<?php echo htmlspecialchars($fetch_update['image']); ?>">
      <img src="uploaded_img/<?php echo htmlspecialchars($fetch_update['image']); ?>" alt="">
      <input type="text" name="update_name" value="<?php echo htmlspecialchars($fetch_update['name']); ?>" class="box" required placeholder="enter product name">
      <input type="number" name="update_price" value="<?php echo htmlspecialchars($fetch_update['price']); ?>" min="0" step="0.01" class="box" required placeholder="enter product price">
      <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
      <input type="submit" value="update" name="update_product" class="btn">
      <input type="reset" value="cancel" id="close-update" class="option-btn">
   </form>
   <?php
         }
      }
      mysqli_stmt_close($stmt);
      }else{
         echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
      }
   ?>

</section>

<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>