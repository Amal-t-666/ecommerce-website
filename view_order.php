<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}
;
if (isset($_GET['get_id'])) {
   $get_id = $_GET['get_id'];
} else {
   $get_id = '';
   header('location:orders.php');
}

if (isset($_POST['cancel'])) {

   $update_orders = $conn->prepare("UPDATE `orders` SET status = ? WHERE id = ?");
   $update_orders->execute(['canceled', $get_id]);
   header('location:orders.php');

}

?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>View Orders</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

   <link rel="stylesheet" href="css/style.css">
   <style>
      .order-details .box-container {
         background-color: var(--white);
         border: var(--border);
         border-radius: .5rem;
         padding: 2rem;
         box-shadow: var(--box-shodow);
      }

      .order-details .box-container .box {
         display: flex;
         gap: 1.5rem;
         flex-wrap: wrap;
         align-items: flex-start;
         overflow-x: hidden;
      }

      .order-details .box-container .box .col {
         flex: 1 1 40rem;
      }

      .order-details .box-container .box .col .image {
         height: 17rem;
         width: 100%;
         object-fit: contain;
         margin: 1rem 0;
      }

      .order-details .box-container .box .col .title {
         border-radius: .5rem;
         margin-bottom: 1rem;
         padding: 1rem 1.5rem;
         font-size: 1.7rem;
         color: var(--light-color);
         background-color: var(--light-bg);
         display: inline-block;
         text-transform: capitalize;
      }

      .order-details .box-container .box .col .title i {
         margin-right: 1rem;
         color: var(--black);
      }

      .order-details .box-container .box .col .price {
         color: var(--red);
         font-size: 1.8rem;
         padding: .5rem 0;
         margin-left: 100px;
      }

      .order-details .box-container .box .col .name {
         font-size: 2rem;
         color: var(--black);
         text-overflow: ellipsis;
         overflow-x: hidden;
         margin-top: 30px;
         margin-bottom: 20px;
      }

      .order-details .box-container .box .col .user {
         padding: .7rem 0;
         font-size: 1.7rem;
         color: var(--light-color);
         line-height: 1.5;
      }

      .order-details .box-container .box .col .user i {
         margin-right: 1rem;
         color: var(--main-color);
      }

      .order-details .box-container .box .col .grand-total {
         background-color: var(--black);
         display: flex;
         align-items: center;
         justify-content: space-between;
         gap: 1.5rem;
         flex-wrap: wrap;
         padding: 1.5rem;
         font-size: 2rem;
         color: var(--white);
         border-radius: .5rem;
         text-transform: capitalize;
         margin-top: 1.5rem;
         margin-top: 130px;
      }

      .order-details .box-container .box .col .grand-total span {
         color: var(--orange);
      }

      .order-details .box-container .box .col .status {
         font-size: 1.8rem;
         padding: .5rem 0;
      }
   </style>

</head>

<body>
   <?php include 'components/user_header.php'; ?>
   <section class="order-details">

      <h1 class="heading">order details</h1>

      <div class="box-container">
         <?php
         $grand_total = 0;
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE id = ? LIMIT 1");
         $select_orders->execute([$get_id]);
         if ($select_orders->rowCount() > 0) {
            while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
               $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
               $select_products->execute([$fetch_orders['user_id']]);
               if ($select_products->rowCount() > 0) {

                  $sub_total = ($fetch_orders['total_price']);
                  $grand_total += $sub_total;
                  ?>
                  <div class="box">
                     <div class="col">
                        <p class="title"><i class="fas fa-calendar"></i>
                           <?= $fetch_orders['placed_on']; ?>
                        </p>
                        <!-- <img src="uploaded_img/<?= $fetch_product['image']; ?>" class="image" alt=""> -->

                        <h3 class="name">
                           <?= $fetch_orders['total_products']; ?>
                        </h3>
                        <p class="price"><i class="fas fa-indian-rupee-sign"></i>
                           <?= $fetch_orders['total_price']; ?>/-

                        </p>
                        <p class="grand-total">grand total : <span><i class="fas fa-indian-rupee-sign"></i>
                              <?= $grand_total; ?>
                           </span></p>
                     </div>
                     <div class="col">
                        <p class="title">billing address</p>
                        <p class="user"><i class="fas fa-user"></i>
                           <?= $fetch_orders['name']; ?>
                        </p>
                        <p class="user"><i class="fas fa-phone"></i>
                           <?= $fetch_orders['number']; ?>
                        </p>
                        <p class="user"><i class="fas fa-envelope"></i>
                           <?= $fetch_orders['email']; ?>
                        </p>
                        <p class="user"><i class="fas fa-map-marker-alt"></i>
                           <?= $fetch_orders['address']; ?>
                        </p>
                        <p class="title">status</p>
                        <p class="status" style="color:<?php if ($fetch_orders['status'] == 'delivered') {
                           echo 'green';
                        } elseif ($fetch_orders['status'] == 'canceled') {
                           echo 'red';
                        } else {
                           echo 'orange';
                        }
                        ; ?>">
                           <?= $fetch_orders['status']; ?>
                        </p>
                        <?php if ($fetch_orders['status'] == 'canceled') { ?>
                           <a href="checkout.php?get_id=<?= $fetch_products['id']; ?>" class="btn">order again</a>
                        <?php } else { ?>
                           <form action="" method="POST">
                              <input type="submit" value="cancel order" name="cancel" class="delete-btn"
                                 onclick="return confirm('cancel this order?');">
                           </form>
                        <?php } ?>
                     </div>
                  </div>
                  <?php
               }

            }
         } else {
            echo '<p class="empty">no orders found!</p>';
         }

         ?>

      </div>

   </section>

   <?php include 'components/footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>