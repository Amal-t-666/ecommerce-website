<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}
;

include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>quick view</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <style>
      .quick-view form .row .content .description {
         font-size: 2rem;
         color: var(--black);
         margin-top: 0px;
      }

      .quick-view form .row .content .details {
         font-size: 15px;
         color: var(--black);


      }

      .quick-view form {
         padding: 2rem;
         border-radius: .5rem;
         border: var(--border);
         background-color: var(--white);
         box-shadow: var(--box-shadow);
         margin-top: 1rem;
         width: 100%;
         height: 2000px;
      }

      .quick-view form .row {
         display: flex;
         align-items: center;
         gap: 0rem;
         flex-wrap: wrap;

      }

      .quick-view form .row .image-container {
         margin-bottom: 2rem;
         flex: 1 1 40rem;
         width: 50%;
         margin-bottom: 15%;



      }

      .quick-view form .row .image-container .main-image img {
         height: 30rem;
         width: 70%;
         object-fit: contain;
         margin-left: 85px;
         margin-top: 40px;


      }

      .quick-view form .row .image-container .sub-image {
         display: flex;
         gap: 1.5rem;
         justify-content: center;
         margin-top: 2rem;
      }

      .quick-view form .row .image-container .sub-image img {
         height: 7rem;
         width: 10rem;
         object-fit: contain;
         padding: .5rem;
         border: var(--border);
         cursor: pointer;
         transition: .2s linear;
      }

      .quick-view form .flex .image-container .sub-image img:hover {
         transform: scale(1.1);
      }

      .quick-view form img {
         width: 100%;
         height: 20rem;
         object-fit: contain;
         margin-bottom: 2rem;
      }

      .quick-view form .row .content {
         flex: 1 1 40rem;
         margin-left: px;
         margin-right: px;
         margin-bottom: 0px;
         margin-top: 20px;
         align-self: start;



      }

      .quick-view form .row .content .name {
         font-size: 2rem;
         color: var(--black);
      }

      .quick-view form .row .flex {
         display: flex;
         align-items: center;
         justify-content: space-between;
         gap: 1rem;
         margin: 1rem 0;

      }

      .quick-view form .row .flex .qty {
         width: 7rem;
         padding: 1rem;
         border: var(--border);
         font-size: 1.8rem;
         color: var(--black);
         border-radius: .5rem;
      }

      .quick-view form .row .flex .price {
         font-size: 2rem;
         color: lightseagreen;
         font-weight: bold;
      }

      .quick-view form .row .flex .discount {
         font-size: 2rem;
         color: var(--black);
         font-weight: bold;

      }


      /*.quick-view form .row .content .details{
   font-size: 1.6rem;
   color:var(--light-color);
   line-height: 2;
   }
*/
      .option-btn {
         display: block;
         width: 40%;
         margin-top: 1rem;
         border-radius: .5rem;
         padding: 1rem 3rem;
         font-size: 1.7rem;
         text-transform: capitalize;
         color: var(--white);
         cursor: pointer;
         text-align: center;
      }

      .btn:hover,
      .delete-btn:hover,
      .option-btn:hover {
         background-color: var(--black);
      }

      .btn {
         background-color: var(--orange);
         width: 40%;
         margin-left: 9%;
      }

      .option-btn {
         background-color: var(--main-color);
         margin-right: 5%;
      }


      .flex-btn {
         display: flex;
         gap: 1rem;
      }

      .h1 {
         font-size: 100px;
      }

      .spec {
         color: #312c2c;
         font-size: 24px;
         height: 30px;
         margin-top: 29px;
         margin-left: 10%;

      }

      .hr {
         color: red;
      }

      #h2 {
         font-size: 20px;
         color: #494242;
         margin-left: 10%;

      }

      .specification {
         align-items: ;
         color: #555;
         font-size: 18px;
         margin-left: 10%;
         margin-right: 0px;
         width: ;


      }
   </style>

</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <section class="quick-view">



      <?php
      $pid = $_GET['pid'];
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
      $select_products->execute([$pid]);
      if ($select_products->rowCount() > 0) {
         while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <form action="" method="post" class="box">
               <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
               <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
               <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
               <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
               <div class="row">
                  <div class="image-container">
                     <div class="main-image">
                        <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
                     </div>
                     <div class="sub-image">
                        <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
                        <img src="uploaded_img/<?= $fetch_product['image_02']; ?>" alt="">
                        <img src="uploaded_img/<?= $fetch_product['image_03']; ?>" alt="">
                        <img src="uploaded_img/<?= $fetch_product['image_04']; ?>" alt="">
                     </div>
                     <div class="flex-btn">
                        <input type="submit" value="add to cart" class="btn" name="add_to_cart">
                        <input class="option-btn" type="submit" name="add_to_wishlist" value="add to wishlist">

                     </div>
                     <div> </div>
                     <br>
                     <h1 class="spec">Specifications</h1>
                     <hr>
                     <hr class="hr" /><br>
                     <h2 id=h2>General: )</h2><br>
                     <h2>
                        <p class="specification">
                           <?= $fetch_product['specification1']; ?>
                        </p>
                     </h2><br>
                     <h2>
                        <p class="specification">
                           <?= $fetch_product['specification2']; ?>
                        </p>
                     </h2><br>
                     <h2>
                        <p class="specification">
                           <?= $fetch_product['specification3']; ?>
                        </p>
                     </h2><br>
                     <h2>
                        <p class="specification">
                           <?= $fetch_product['specification4']; ?>
                        </p>
                     </h2><br>
                     <h2>
                        <p class="specification">
                           <?= $fetch_product['specification5']; ?>
                        </p>
                     </h2><br>
                     <h2>
                        <p class="specification">
                           <?= $fetch_product['specification6']; ?>
                        </p>
                     </h2><br>
                     <h2>
                        <p class="specification">
                           <?= $fetch_product['specification7']; ?>
                        </p>
                     </h2><br>
                     <h2>
                        <p class="specification">
                           <?= $fetch_product['specification8']; ?>
                        </p>
                     </h2><br>
                     <h2>
                        <p class="specification">
                           <?= $fetch_product['specification9']; ?>
                        </p>
                     </h2><br>
                     <h2 id=h2><?= $fetch_product['spechead1']; ?></h2><br>
                     <h2>
                        <p class="specification">
                           <?= $fetch_product['specification10']; ?>
                        </p>
                     </h2><br>
                     <h2>
                        <p class="specification">
                           <?= $fetch_product['specification11']; ?>
                        </p>
                     </h2><br>
                     <h2>
                        <p class="specification">
                           <?= $fetch_product['specification12']; ?>
                        </p>
                     </h2><br>
                     <h2>
                        <p class="specification">
                           <?= $fetch_product['specification13']; ?>
                        </p>
                     </h2><br>
                     <h2>
                        <p class="specification">
                           <?= $fetch_product['specification14']; ?>
                        </p>
                     </h2><br>
                     <h2>
                        <p class="specification">
                           <?= $fetch_product['specification15']; ?>
                        </p>
                     </h2><br>
                     <h2>
                        <p class="specification">
                           <?= $fetch_product['specification16']; ?>
                        </p>
                     </h2><br>
                     <h2>
                        <p class="specification">
                           <?= $fetch_product['specification17']; ?>
                        </p>
                     </h2><br>
                     <h2 id=h2><?= $fetch_product['spechead2']; ?></h2><br>
                     <h2>
                        <p class="specification">
                           <?= $fetch_product['specification18']; ?>
                        </p>
                     </h2><br>

                     <h2 id=h2><?= $fetch_product['spechead3']; ?></h2><br>
                     <h2>
                        <p class="specification">
                           <?= $fetch_product['specification19']; ?>
                        </p>
                     </h2><br>
                     <h2 id=h2><?= $fetch_product['spechead4']; ?></h2><br>
                     <h2>
                        <p class="specification">
                           <?= $fetch_product['specification20']; ?>
                        </p>
                     </h2><br>
                     <h2>
                        <p class="specification">
                           <?= $fetch_product['specification21']; ?>
                        </p>
                     </h2><br>
                     <h2>
                        <p class="specification">
                           <?= $fetch_product['specification22']; ?>
                        </p>
                     </h2><br>
                     <h2>
                        <p class="specification">
                           <?= $fetch_product['specification23']; ?>
                        </p>
                     </h2><br>
                     <h2>
                        <p class="specification">
                           <?= $fetch_product['specification24']; ?>
                        </p>
                     </h2><br>
                     <h2>
                        <p class="specification">
                           <?= $fetch_product['specification25']; ?>
                        </p>
                     </h2><br>

                  </div>
                  <div class="content">
                     <div class="description">
                        <?= $fetch_product['description']; ?>
                     </div>
                     <div class="flex">
                        <div class="price"><span>₹</span>
                           <?= $fetch_product['price']; ?><span>/-</span>
                        </div>

                        <input type="number" name="qty" class="qty" min="1" max="99"
                           onkeypress="if(this.value.length == 2) return false;" value="1">
                     </div>
                     <div class="details">
                        <?= $fetch_product['details']; ?>
                     </div>
                     <div>
                        <br>
                     </div>
                  </div>
            </form>
            <?php
         }
      } else {
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>

   </section>

   <?php include 'components/footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>