<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit;
}

if (!isset($_SESSION['confirmation']) || !isset($_SESSION['cart_items']) || !isset($_SESSION['grand_total'])) {
    header('location:index.php');
    exit;
}

$cart_items = $_SESSION['cart_items'];
$grand_total = $_SESSION['grand_total'];

// Clear the session variables after displaying the confirmation
unset($_SESSION['confirmation']);
unset($_SESSION['cart_items']);
unset($_SESSION['grand_total']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .confirmation-container {
            max-width: 800px;
            margin: 50px auto;
            text-align: center;
            padding: 20px;
            background: #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 5px;
        }

        .confirmation-container h1 {
            margin-bottom: 20px;
            color: #333;
        }

        .confirmation-container img {
            width: 200px; /* Adjust the width of the image as needed */
            margin-bottom: 20px;
        }

        .confirmation-container p {
            margin-bottom: 20px;
            color: #666;
        }

        .confirmation-container .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #333;
            color: #fff;
            text-transform: capitalize;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="confirmation-container">
        <img src="images/check.gif" alt="Payment Successful">
        <h1>Payment Confirmation</h1>
        <h2>Thank you for your purchase!</h2>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($cart_items)) : ?>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><img src="images/<?php echo $item['image']; ?>" height="50" alt=""></td>
                            <td><?php echo $item['name']; ?></td>
                            <td>R<?php echo $item['price']; ?> </td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>R<?php echo $item['price'] * $item['quantity']; ?> </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5">No items in the cart.</td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td colspan="4" style="text-align:right;"><strong>Grand Total:</strong></td>
                    <td>R<?php echo $grand_total ?? ''; ?> </td>
                </tr>
            </tbody>
        </table>
        <a href="index.php" class="btn">Return to Home</a>
    </div>
</body>
</html>
