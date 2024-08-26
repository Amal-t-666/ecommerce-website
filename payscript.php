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

$name = $_GET['name'] ?? '';
$email = $_GET['email'] ?? '';
$method = $_GET['method'] ?? '';
$address_type = $_GET['address_type'] ?? '';
$total_products = $_GET['total_products'] ?? '';
$total_price = $_GET['total_price'] ?? '';

$select_user_data = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$user_data = $select_user_data->fetch(PDO::FETCH_ASSOC);

// Use the retrieved data as needed
$customer_name = $user_data['name'] ?? '';
$customer_email = $user_data['email'] ?? '';
$customer_contact = $user_data['contact'] ?? '';

// API Key for Razorpay
$apiKey = "rzp_test_TgtVdHUvw65hGp";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title></title>
    <script>
        function trigger_btn() {
            document.getElementById('checkoutdiv').style.display = 'none';
            document.getElementById('sbt').click();
        }
    </script>
</head>

<body onload='trigger_btn()'>
    <div id='checkoutdiv'>
        <?php
        $apiKey = "rzp_test_TgtVdHUvw65hGp";
        ?>
        <form action="http://localhost/ecommerce/orders.php" method="POST">
            <script src="https://checkout.razorpay.com/v1/checkout.js" data-key="<?php echo $apiKey; ?>"
                data-amount="<?php echo $total_price * 100; ?>" data-currency="INR" data - name="MazzCart" data -
                description="My Order" data - image="https://example.com/your_logo.jpg" data -
                prefill.name="<?php echo $_POST['name']; ?>" data - prefill.email="<?php echo $_POST['email']; ?>" data
                - prefill.contact="<?php echo $_POST['number']; ?>" data - notes.shipping_order_id="3456" date -
                id="12345"></script>

            <input type="hidden" name="customer_name" value="<?php echo $_POST['name']; ?>">
            <input type="hidden" name="customer_email" value="<?php echo $_POST['email']; ?>">
            <input type="hidden" name="customer_totalamount" value="<?php echo $total_price; ?>">
            <input style="display:none;" type="submit" id='sbt' value="proceed to payment">
        </form>

    </div>
</body>

</html>