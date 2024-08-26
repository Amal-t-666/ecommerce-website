<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}
;

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>
   <style type="text/css">
      .orders .box-container {
         display: flex;
         flex-wrap: wrap;
         gap: 1.5rem;
         align-items: flex-start;
         margin-bottom: 8%;

      }

      .orders .box-container .box {
         padding: 1rem 2rem;
         flex: 1 1 40rem;
         border: var(--border);
         background-color: var(--white);
         box-shadow: var(--box-shadow);
         border-radius: .5rem;
         width: 500px;
         height: 500px;
         /* text-align: center; */


      }

      .orders .box-container .box p {
         margin: .5rem 0;
         line-height: 1.8;
         font-size: 2rem;
         color: var(--light-color);
      }

      .orders .box-container .box p span {
         color: var(--main-color);
      }

      .title i {
         margin-right: 1rem;
         color: var(--black);
         /* margin-left: 180px; */

      }

      .title {
         text-align: center;

      }


      .user i {
         margin-right: 1rem;
         color: var(--main-color);
      }

      .user {
         padding: .7rem 0;
         font-size: 1.7rem;
         color: var(--light-color);
         line-height: 1.5;
      }
   </style>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <section class="orders">

      <h1 class="heading">your orders</h1>

      <div class="box-container">

         <?php
         if ($user_id == '') {
            echo '<p class="empty">please login to see your orders</p>';
         } else {
            $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? ORDER BY placed_on DESC");
            $select_orders->execute([$user_id]);
            if ($select_orders->rowCount() > 0) {
               while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                  ?>
                  <div class="box">
                     <p class="title"><i class="fas fa-calendar"></i>
                        <?= $fetch_orders['placed_on']; ?>
                     </p>
                     <p class="user"><i class="fas fa-user"></i>
                        <?= $fetch_orders['name']; ?>
                     </p>
                     <p class="user"><i class="fas fa-phone"></i>
                        <?= $fetch_orders['number']; ?>
                     </p>

                     <p class="user"><i class="fas fa-envelope"></i>
                        <?= $fetch_orders['email']; ?>

                        <script> echo "this is not a phone nubers"; </script>

                     </p>
                     <p class="user"><i class="fas fa-map-marker-alt"></i>
                        <?= $fetch_orders['address']; ?>
                     </p>

                     <p><span>Payment Method : </span>
                        <?= $fetch_orders['method']; ?>
                     </p>
                     <p><span>Your Orders :</span>
                        <?= $fetch_orders['total_products']; ?>
                     </p>
                     <p><span>Total Price :</span> ₹
                        <?= $fetch_orders['total_price']; ?>/-
                     </p>
                     <p> <span>Payment Status :</span> <span style="color:<?php if ($fetch_orders['payment_status'] == 'pending') {
                        echo 'red';
                     } else {
                        echo 'green';
                     }
                     ; ?>">
                           <?= $fetch_orders['payment_status']; ?>
                        </span> </p>
                  </div>
                  <?php
               }
            } else {
               echo '<p class="empty">no orders placed yet!</p>';
            }
         }
         ?>

      </div>

   </section>

   <?php include 'components/footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>