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

if (isset($_POST['order'])) {

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $method = $_POST['method'];
    $method = filter_var($method, FILTER_SANITIZE_STRING);
    $address_type = $_POST['address_type'];
    $address_type = filter_var($address_type, FILTER_SANITIZE_STRING);
    $address = 'flat no. ' . $_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['state'] . ' - ' . $_POST['pin_code'];
    $address = filter_var($address, FILTER_SANITIZE_STRING);
    $total_products = $_POST['total_products'];
    $total_price = $_POST['total_price'];

    $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $check_cart->execute([$user_id]);

    if ($check_cart->rowCount() > 0) {

        $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address_type,address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?,?)");
        $insert_order->execute([$user_id, $name, $number, $email, $method, $address_type, $address, $total_products, $total_price]);

        $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
        $delete_cart->execute([$user_id]);


        if ($method === 'cash on delivery') {

            echo "<script>alert('Order Placed Successfully !')</script>";
            echo "<script>window.location.href='orders.php';</script>";
            exit();

        } elseif ($method === 'payment gateway') {
            // Redirect to payscript.php with necessary data
            header('location: payscript.php?user_id=' . $user_id . '&name=' . $name . '&email=' . $email . '&method=' . $method . '&address_type=' . $address_type . '&total_products=' . $total_products . '&total_price=' . $total_price);
            exit();
        } else {
            $message[] = 'your cart is empty';

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>checkout</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">
    <style type="text/css">
        .btn {
            background-color: rgb(46, 46, 224);

            /*   background-color: var(--orange);*/
        }

        .heading {
            font-size: 30px;
        }

        .checkout .row {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            align-items: flex-end;
            flex-wrap: wrap-reverse;
        }

        .checkout .row form {
            flex: 1 1 50rem;
            background-color: var(--white);
            box-shadow: var(--box-shodow);
            padding: 2rem;
            border-radius: .5rem;
            border: var(--border);
        }

        .checkout .row form h3 {
            font-size: 2.5rem;
            color: var(--black);
            padding-bottom: 1rem;
            text-align: center;
            text-transform: capitalize;
        }

        .checkout .row form .input {
            width: 100%;
            background-color: var(--light-bg);
            border-radius: .5rem;
            padding: 1.4rem;
            color: var(--black);
            font-size: 1.8rem;
            margin: 1rem 0;
        }

        .checkout .row form .flex {
            display: flex;
            column-gap: 1.5rem;
            flex-wrap: wrap;
        }

        .checkout .row form .flex .box {
            flex: 1 1 30rem;
        }

        .checkout .row form p {
            padding-top: 1rem;
            font-size: 1.6rem;
            color: var(--light-color);
        }

        .checkout .row form p span {
            color: var(--red);
        }

        .checkout .row form textarea {
            height: 15rem;
            resize: none;
        }

        .checkout .row .summary {
            background-color: var(--white);
            box-shadow: var(--box-shodow);
            padding: 2rem;
            border-radius: .5rem;
            border: var(--border);
            flex: 1 1 30rem;
        }

        .checkout .row .summary .title {
            font-size: 2.2rem;
            color: var(--black);
            border-bottom: var(--border);
            margin-bottom: .5rem;
            text-transform: capitalize;
            padding-bottom: 1.5rem;
        }

        .checkout .row .summary .flex {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding: 1rem 0;
        }

        .checkout .row .summary .flex img {
            height: 6rem;
            width: 6rem;
            object-fit: contain;
        }


        .checkout .row .summary .flex .name {
            font-size: 1.8rem;
            color: var(--black);
            padding-bottom: .5rem;
        }

        .checkout .row .summary .flex .price {
            font-size: 1.6rem;
            color: var(--red);
        }

        .checkout .row .summary .grand-total {
            background-color: var(--light-bg);
            border-radius: .5rem;
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            flex-wrap: wrap;
            font-size: 2rem;
            margin-top: 1.5rem;
        }

        .checkout .row .summary .grand-total span {
            color: var(--light-color);
        }

        .checkout .row .summary .grand-total p {
            color: var(--red);
        }
    </style>
    <script>

    </script>

</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <section class="checkout">
        <h1 class="heading">checkout summary</h1>
        <div class="row">
            <form action="checkout.php" method="POST">
                <h3>Billing Details </h3>
                <div class="flex">
                    <div class="box">
                        <?php
                        $grand_total = 0;
                        $cart_items[] = '';
                        $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                        $select_cart->execute([$user_id]);
                        if ($select_cart->rowCount() > 0) {
                            while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                                $cart_items[] = $fetch_cart['name'] . ' (' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity'] . ') - ';
                                $total_products = implode($cart_items);
                                $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
                                ?>
                                <!-- <p>
                                    <?= $fetch_cart['name']; ?> <span>(
                                        <?= '₹' . $fetch_cart['price'] . '/- x ' . $fetch_cart['quantity']; ?>)
                                    </span>
                                </p> -->
                                <?php
                            }
                        } else {
                            echo '<p class="empty">your cart is empty!</p>';
                        }
                        ?>
                        <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">

                        <input type="hidden" name="total_products" value="<?= $total_products; ?>">
                        <input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">

                        <p>Your Name <span>*</span></p>
                        <input type="text" name="name" required placeholder="enter your username" maxlength="20"
                            class="input" value="<?= $fetch_profile["name"]; ?>">
                        <p>Your Email <span>*</span></p>
                        <input type="email" name="email" required placeholder="Enter your email" min="10" maxlength="50"
                            class="input" oninput="this.value = this.value.replace(/\s/g, '')"
                            value="<?= $fetch_profile["email"]; ?>">
                        <p>Mobile Number<span>*</span></p>
                        <input type="number" name="number" placeholder="Enter your mobile number" class="input" min="0"
                            max="9999999999" onkeypress="if(this.value.length == 10) return false;" required>
                        <p>Payment Method <span>*</span></p>
                        <select name="method" class="input" required>
                            <option value="cash on delivery">Cash on delivery</option>
                            <option value="payment gateway">Payment gateway</option>

                        </select>
                        <p>Address Type <span>*</span></p>
                        <select name="address_type" class="input" required>
                            <option value="home">Home</option>
                            <option value="office">Office</option>
                        </select>
                    </div>
                    <div class="box">
                        <p>Address Line 01 <span>*</span></p>
                        <input type="text" name="flat" placeholder="flat number" class="input" maxlength="50" required>
                        <p>Address Line 02 <span>*</span></p>
                        <input type="text" name="street" placeholder="street name" class="input" maxlength="50"
                            required>

                        <p>City <span>*</span></p>
                        <input type="text" name="city" placeholder="e.g. kannur" class="input" maxlength="50" required>

                        <p>State <span>*</span></p>
                        <input type="text" name="state" placeholder="e.g. kerala" class="input" maxlength="50" required>

                        <p>Pin Code <span>*</span>
                            <input type="number" name="pin_code" placeholder="e.g. 123456" min="0" max="999999"
                                onkeypress="if(this.value.length == 6) return false;" class="input" required>
                    </div>
                </div>

                <input type="submit" name="order" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>"
                    value="place order">



            </form>
            <div class="summary">
                <h3 class="title">cart items</h3>
                <?php
                $grand_total = 0;
                $cart_items[] = '';
                $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                $select_cart->execute([$user_id]);
                if ($select_cart->rowCount() > 0) {
                    while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                        $cart_items[] = $fetch_cart['name'] . ' (' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity'] . ') - ';
                        $total_products = implode($cart_items);
                        $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
                        ?>
                        <input type="hidden" name="total_products" value="<?= $total_products; ?>">
                        <input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">
                        <div class="flex">
                            <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="">
                            <div>
                                <h3 class="name">
                                    <?= $fetch_cart['name']; ?>
                                </h3>

                                <p class="price">
                                    <?= '₹' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity']; ?>
                                </p>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                    $select_cart->execute([$user_id]);
                    if ($select_cart->rowCount() > 0) {
                        while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                            $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
                            $select_products->execute([$fetch_cart['product_id']]);
                            $fetch_product = $select_products->fetch(PDO::FETCH_ASSOC);
                            $sub_total = ($fetch_cart['qty'] * $fetch_product['price']);

                            $grand_total += $sub_total;
                            echo '<p class="empty">your cart is empty!</p>';
                        }
                    }
                }

                ?>

                <div class="grand-total"><span>Grand total :</span>
                    <p><i class="fas fa-indian-rupee-sign"></i>
                        <?= $grand_total; ?>
                    </p>
                </div>
            </div>

        </div>

    </section>
    <?php include 'components/footer.php'; ?>

    <script src="js/script.js"></script>

</body>

</html>