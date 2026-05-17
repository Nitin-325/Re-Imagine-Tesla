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
   <title>about</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/about.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="head">
   <h3>about us</h3>
   <p> <a href="home.php">Home</a> / About </p>
</div>

<section class="about">

   <div class="flex">

      <div class="image">
         <img src="New_Images/HomeAboutImage" alt="">
      </div>

      <div class="content">
         <h3>why choose us?</h3>
         <p>DAILY COMMUNITY: Look for a car with sufficient range and comfort for regular drives.</p>
         <p>PERFORMANCE: Choose a model with higher acceleration and top speed if you value performance.</p>
         <p>FAMILY USE: Consider models with more seating and storage capacity.</p>
         <p>BUDGET-FRIENDLY: Opt for an entry-level Tesla if you're price-conscious.</p>
         <a href="contact.php" class="btn">contact us</a>
      </div>
   </div>
   <h1>MODELS DETAILS</h1>
   <div class="info">
      <div class="part-1">
         <h3>Tesla Model S</h3>
         <p>Who it's for: Luxury and performance enthusiasts.<br>Features: Long range (over 400 miles), ultra-fast acceleration, premium interior.<br>Highlight: Plaid version offers a supercar-level experience.</p>
         <h3 id="up">Tesla Model X</h3>
         <p id="down">Who it's for: Families needing more space and unique styling.<br>Features: SUV with up to 7 seats, Falcon Wing doors, excellent range and performance.<br>Highlight: Luxury SUV experience with a futuristic touch.</p>
         <h3>Tesla Cybertruck</h3>
         <p>Who it's for: Adventurers or those needing a durable, high-performance truck.<br>Features: Robust design, high towing capacity, all-electric powertrain.<br>Highlight: Unique design and rugged capabilities.</p>
      </div>
      <div class="part-2">
         <h3>Tesla Model 3</h3>
         <p>Who it's for: Budget-conscious buyers seeking a compact, high-tech EV.<br>Features: Good range (over 300 miles), affordability, minimalistic design.<br>Highlight: Popular choice for first-time Tesla owners.</p>
         <h3 id="up">Tesla Model Y</h3>
         <p id="down">Who it's for: Families or individuals looking for a compact SUV.<br>Features: Spacious interior, up to 7 seats, versatile storage.<br>Highlight: Combines features of Model 3 and Model X at a more affordable price.</p>
         <h3>Tesla Roadster (Upcoming)</h3>
         <p>Who it's for: Sports car enthusiasts.<br>Features: Exceptional speed, cutting-edge design, extreme performance.<br>Highlight: Designed to be the fastest Tesla ever.</p>
      </div>
   </div>

</section>
<h1>OTHER DETAILS</h1>
<section class="other">
   <div class="two">
      <div class="part-1">
         <h3>Budget and Financing</h3>
         <p>Price Range: Tesla cars range from relatively affordable (Model 3) to premium (Model S and Model X).<br>Incentives: Check for government incentives or tax credits for EV purchases in your region.<br>Leasing vs. Buying: Decide whether you want to lease or own your Tesla.</p>
         <h3>Test Drive</h3>
         <p>Visit a Tesla showroom or schedule a test drive to experience the car firsthand.<br>Pay attention to the comfort, driving dynamics, and technology features.</p>
         </div>
      <div class="part-2">
         <h3>Features and Upgrades</h3>
         <p>Autopilot and Full Self-Driving (FSD): Advanced driver-assistance systems for semi-autonomous driving.<br>Interior Options: Premium interiors, larger touchscreens, and customizable designs.<br>Performance Upgrades: Opt for performance models with higher acceleration and better handling.</p>
         <h3>Availability and Delivery</h3>
         <p>Tesla cars may have a waiting period for delivery based on demand and location.<br>Customize your Tesla online and check estimated delivery timelines.</p>
      </div>
   </div>
   <div class="one">
      <h3>Driving Range and Charging</h3>
      <p>Driving Range: Choose a model that matches your driving needs. For long trips, prioritize models with a longer range.<br>Charging: Consider how accessible Tesla's Supercharger network is in your area or if you plan to charge at home.</p>
   </div>
</section>

<section class="reviews">

   <h1 class="title">client's reviews</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/pic-1.png" alt="">
         <p>Excellent range, performance and handling. Comfortable seating and ample passenger and cargo space. Access to Tesla's expansive Supercharger fast-charging stations.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>John Deo</h3>
      </div>

      <div class="box">
         <img src="images/pic-2.png" alt="">
         <p>I bought my first EV, the dual-motor Model 3, in March of 2020.  I already knew enough about this car because a friend had one.  But five minutes into the test drive and I was sold.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Alina Singh</h3>
      </div>

      <div class="box">
         <img src="images/pic-3.png" alt="">
         <p>We've had it for almost 2 months and 3,000 miles. It was a touch overwhelming at first. There is a learning curve. Nothing a few minutes with the owners manual can't fix.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Traver Fillips</h3>
      </div>

      <div class="box">
         <img src="images/pic-4.png" alt="">
         <p>The Good. It's a great car to drive. Acceleration is the best. We took it on a long distance trip and the superchargers worked as planned.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Eliza Bell</h3>
      </div>

      <div class="box">
         <img src="images/pic-5.png" alt="">
         <p>The standard 2023 model 3 is the worst auto purchase (actually lease) I have ever made. The online buying experience was a nightmare.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Mickel Decenta</h3>
      </div>

      <div class="box">
         <img src="images/pic-6.png" alt="">
         <p>The car's performance and feel are amazing. I love driving this car. Had it for 16 months and still look forward to getting into it.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Rosa Suzin</h3>
      </div>

   </div>

</section>

<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>