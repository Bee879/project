<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Dummy payment processing delay
sleep(3);

// Retrieve order details from the cart
$cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
$grand_total = 0;
$cart_items = [];

if (mysqli_num_rows($cart_query) > 0) {
    while ($fetch_cart = mysqli_fetch_assoc($cart_query)) {
        $cart_items[] = $fetch_cart;
        $grand_total += $fetch_cart['price'] * $fetch_cart['quantity'];
    }
} else {
    echo '<script>alert("No items in cart to confirm!"); window.location.href="index.php";</script>';
    exit;
}

// Clear the cart after payment processing
mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');

// Store cart details in session to display on confirmation page
$_SESSION['cart_items'] = $cart_items;
$_SESSION['grand_total'] = $grand_total;
$_SESSION['confirmation'] = true;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing Payment</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .processing-container {
            max-width: 600px;
            margin: 50px auto;
            text-align: center;
            padding: 20px;
            background: #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 5px;
        }

        .processing-container h1 {
            margin-bottom: 20px;
            color: #333;
        }

        .processing-container p {
            margin-bottom: 20px;
            color: #666;
        }
    </style>
    <script>
        setTimeout(function() {
            window.location.href = 'confirmation.php';
        }, 5000);
    </script>
</head>
<body>
    <div class="processing-container">
        <h1>Processing Your Payment</h1>
        <p>Please wait while we process your payment. You will be redirected to the confirmation page shortly.</p>
    </div>
</body>
</html>
