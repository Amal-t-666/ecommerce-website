<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:user_login.php');
}
;

if (isset($_POST['delete'])) {
   $cart_id = $_POST['cart_id'];
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
   $delete_cart_item->execute([$cart_id]);
}

if (isset($_GET['delete_all'])) {
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);


   header('location:cart.php');
}

if (isset($_POST['update_qty'])) {
   $cart_id = $_POST['cart_id'];
   $qty = $_POST['qty'];
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
   $update_qty->execute([$qty, $cart_id]);

   // $success_msg[] = 'Cart quantity updated!';
   $message[] = 'Cart Quantity Updated!';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shopping cart</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <style type="text/css">
      .btn {
         background-color: var(--orange);
      }

      .option-btn {
         background-color: var(--main-color);
      }

      .delete-btn {
         background-color: var(--red);
      }

      .products .box-container .box .flex .price {
         font-size: 2rem;
         /* color: var(--black); */
         color: green;
         margin-right: auto;
         text-align: center;
         margin-left: 100px;
      }

      .products .box-container .box .flex .qty {
         width: 6rem;
         padding: 1rem;
         border: var(--border);
         font-size: 1.8rem;
         color: var(--black);
         border-radius: .5rem;
         margin-left: 3px;
      }

      .products .box-container .box {
         position: relative;
         background-color: var(--white);
         box-shadow: var(--box-shadow);
         border-radius: .5rem;
         border: var(--border);
         padding: 2rem;
         overflow: hidden;
         width: 108%;
         /* margin-left: 10px; */
         height: 460px;



      }
   </style>
</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <section class="products shopping-cart">

      <h3 class="heading">shopping cart</h3>

      <div class="box-container">

         <?php
         $grand_total = 0;
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if ($select_cart->rowCount() > 0) {
            while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
               ?>
               <form action="" method="post" class="box">
                  <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
                  <a href="quick_view.php?pid=<?= $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
                  <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="">
                  <div class="name">
                     <?= $fetch_cart['name']; ?><br>
                  </div>
                  <div class="flex">
                     <div class="price">₹
                        <?= $fetch_cart['price']; ?>
                     </div>
                     <input type="number" name="qty" class="qty" min="1" max="99"
                        onkeypress="if(this.value.length == 2) return false;" value="<?= $fetch_cart['quantity']; ?>">
                     <button type="submit" class="fas fa-edit" name="update_qty"></button>
                  </div>
                  <div style=" margin:2rem 0;font-size: 2rem;color:var(--light-color); margin-left: 50px;" class="sub-total">
                     Sub Total : <span>₹
                        <?= $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-
                     </span>
                  </div>
                  <input type="submit" value="remove item" onclick="return confirm('delete this from cart?');"
                     class="delete-btn" name="delete">
               </form>
               <?php
               $grand_total += $sub_total;
            }
         } else {
            echo '<p class="empty">your cart is empty</p>';
         }
         ?>
      </div>

      <div class="cart-total">
         <p>Grand Total : <span>₹
               <?= $grand_total; ?>/-
            </span></p>
         <a href="checkout.php" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>">place your order</a>
         <a href="shop.php" class="option-btn">continue shopping</a>
         <a href="cart.php?delete_all" class="delete-btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>"
            onclick="return confirm('delete all from cart?');">remove all item</a>

      </div>

   </section>


   <?php include 'components/footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>