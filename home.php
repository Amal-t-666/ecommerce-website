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
   <title>home</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <style type="text/css">
      .home-bg .home .slide {
         display: flex;
         /*   align-items: center;*/
         /*   flex-wrap: wrap;*/
         gap: 1.5rem;
         padding-bottom: 6rem;
         padding-top: 2rem;
         user-select: none;
      }

      .home-bg .home .slide .image {
         /*   flex:1 1 40rem;*/
         margin-left: 200px;




      }

      .home-bg .home .slide .image img {
         height: 45rem;
         width: 100%;
         object-fit: contain;
         margin-left: 0px;
         margin-right: px;
         align-items: flex-start;
         align-content: start;

      }

      .home-bg .home .slide .content {
         flex: 1 1 40rem;
         margin-left: 114px;


      }

      .home-bg .home .slide .content span {
         font-size: 2rem;
         color: var(--white);
         margin-left: px;
      }

      .home-bg .home .slide .content h3 {
         margin-top: 1rem;
         font-size: 4rem;
         color: var(--white);
         text-transform: uppercase;

      }

      .home-bg .home .slide .content .btn {
         display: inline-block;
         width: auto;
      }

      .products .box-container {
         display: grid;
         grid-template-columns: repeat(auto-fit, 33rem);
         gap: 1.5rem;
         justify-content: center;
         align-items: flex-start;
      }

      .products .box-container .box {
         position: relative;
         background-color: var(--white);
         box-shadow: var(--box-shadow);
         border-radius: .5rem;
         border: var(--border);
         padding: 2rem;
         overflow: hidden;
         height: 400px;
         text-align: center;

      }

      .products .box-container .box .name {
         font-size: 2rem;
         color: var(--black);
         text-align: center;
      }

      .products .box-container .box .flex .price {
         font-size: 2rem;
         color: var(--red);
         margin-right: auto;
         margin-left: 1px;
         text-align: center;
      }
   </style>
</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <div class="home-bg">

      <section class="home">

         <div class="swiper home-slider">

            <div class="swiper-wrapper">

               <div class="swiper-slide slide">
                  <div class="image">
                     <img src="images/iphone.png" alt="">
                  </div>
                  <div class="content">
                     <span>upto 50% off</span>
                     <h3>latest smartphones</h3>
                     <a href="shop.php" class="btn">shop now</a>

                  </div>
               </div>

               <div class="swiper-slide slide">
                  <div class="image">
                     <img src="images/camera.png" alt="">
                  </div>
                  <div class="content">
                     <span>upto 50% off</span>
                     <h3>latest Cameras</h3>
                     <a href="shop.php" class="btn">shop now</a>
                  </div>
               </div>

               <div class="swiper-slide slide">
                  <div class="image">
                     <img src="images/home-img-3.png" alt="">
                  </div>
                  <div class="content">
                     <span>upto 50% off</span>
                     <h3>latest headsets</h3>
                     <a href="shop.php" class="btn">shop now</a>
                  </div>
               </div>

            </div>

            <div class="swiper-pagination"></div>

         </div>

      </section>

   </div>

   <section class="category">

      <h1 class="heading">shop by category</h1>

      <div class="swiper category-slider">

         <div class="swiper-wrapper">

            <a href="category.php?category=smartphone" class="swiper-slide slide">
               <img src="images/icon-7.png" alt="">
               <h3>Smartphones</h3>
            </a>

            <a href="category.php?category=laptop" class="swiper-slide slide">
               <img src="images/icon-1.png" alt="">
               <h3>Laptops</h3>
            </a>

            <a href="category.php?category=camera" class="swiper-slide slide">
               <img src="images/icon-3.png" alt="">
               <h3>Cameras</h3>
            </a>
            <a href="category.php?category=tv" class="swiper-slide slide">
               <img src="images/icon-2.png" alt="">
               <h3>TV</h3>
            </a>
            <a href="category.php?category=watch" class="swiper-slide slide">
               <img src="images/icon-8.png" alt="">
               <h3>Watches</h3>
            </a>



            <a href="category.php?category=mouse" class="swiper-slide slide">
               <img src="images/icon-4.png" alt="">
               <h3>mouse</h3>
            </a>

            <a href="category.php?category=fridge" class="swiper-slide slide">
               <img src="images/icon-5.png" alt="">
               <h3>fridge</h3>
            </a>

            <a href="category.php?category=washing" class="swiper-slide slide">
               <img src="images/icon-6.png" alt="">
               <h3>washing machine</h3>
            </a>



         </div>

         <div class="swiper-pagination"></div>

      </div>

   </section>

   <section class="home-products">

      <h1 class="heading">latest products</h1>

      <div class="swiper products-slider">

         <div class="swiper-wrapper">

            <?php
            $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6");
            $select_products->execute();
            if ($select_products->rowCount() > 0) {
               while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
                  ?>
                  <form action="" method="post" class="swiper-slide slide">
                     <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
                     <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
                     <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
                     <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
                     <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
                     <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
                     <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
                     <div class="name">
                        <?= $fetch_product['name']; ?>
                     </div>
                     <div class="flex">
                        <div class="price"><span>₹</span>
                           <?= $fetch_product['price']; ?><span>/-</span>
                        </div>
                        <input type="number" name="qty" class="qty" min="1" max="99"
                           onkeypress="if(this.value.length == 2) return false;" value="1">
                     </div>
                     <input type="submit" value="add to cart" class="btn" name="add_to_cart">
                  </form>
                  <?php
               }
            } else {
               echo '<p class="empty">no products added yet!</p>';
            }
            ?>

         </div>

         <div class="swiper-pagination"></div>

      </div>

   </section>

   <?php include 'components/footer.php'; ?>

   <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

   <script src="js/script.js"></script>

   <script>

      var swiper = new Swiper(".home-slider", {
         loop: true,
         spaceBetween: 20,
         pagination: {
            el: ".swiper-pagination",
            clickable: true,
         },
      });

      var swiper = new Swiper(".category-slider", {
         loop: true,
         spaceBetween: 20,
         pagination: {
            el: ".swiper-pagination",
            clickable: true,
         },
         breakpoints: {
            0: {
               slidesPerView: 2,
            },
            650: {
               slidesPerView: 3,
            },
            768: {
               slidesPerView: 4,
            },
            1024: {
               slidesPerView: 5,
            },
         },
      });

      var swiper = new Swiper(".products-slider", {
         loop: true,
         spaceBetween: 20,
         pagination: {
            el: ".swiper-pagination",
            clickable: true,
         },
         breakpoints: {
            550: {
               slidesPerView: 2,
            },
            768: {
               slidesPerView: 2,
            },
            1024: {
               slidesPerView: 3,
            },
         },
      });

   </script>

</body>

</html>