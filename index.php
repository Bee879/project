<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_GET['logout'])){
   unset($user_id);
   session_destroy();
   header('location:login.php');
}

if(isset($_POST['add_to_cart'])){
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if(mysqli_num_rows($select_cart) > 0){
      $message[] = 'Product already added to cart!';
   } else {
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, image, quantity) VALUES('$user_id', '$product_name', '$product_price', '$product_image', '$product_quantity')") or die('query failed');
      $message[] = 'Product added to cart!';
   }
}

if(isset($_POST['update_cart'])){
   $update_quantity = $_POST['cart_quantity'];
   $update_id = $_POST['cart_id'];
   mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_quantity' WHERE id = '$update_id'") or die('query failed');
   $message[] = 'Cart quantity updated successfully!';
}

if(isset($_GET['remove'])){
   $remove_id = $_GET['remove'];
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id'") or die('query failed');
   header('location:index.php');
}

if(isset($_GET['delete_all'])){
   mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   header('location:index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shopping Cart</title>
   <link rel="stylesheet" href="css/style.css">

   <style>
      .box-container {
         display: flex;
         flex-wrap: wrap;
         gap: 20px;
      }

      .box {
         flex: 1 1 calc(33.333% - 20px);
         background: #fff;
         border: 1px solid #ddd;
         border-radius: 5px;
         box-shadow: 0 5px 15px rgba(0,0,0,0.1);
         padding: 10px;
         box-sizing: border-box;
      }
   </style>

   <script>
      function validateRegistrationForm(event) {
         var form = document.forms["registerForm"];
         var name = form["name"].value;
         var email = form["email"].value;
         var password = form["password"].value;
         var confirmPassword = form["cpassword"].value;

         if (name === "" || email === "" || password === "" || confirmPassword === "") {
            alert("All fields must be filled out");
            event.preventDefault();
            return false;
         }

         if (password !== confirmPassword) {
            alert("Passwords do not match");
            event.preventDefault();
            return false;
         }

         return true;
      }

      function validateLoginForm(event) {
         var form = document.forms["loginForm"];
         var email = form["email"].value;
         var password = form["password"].value;

         if (email === "" || password === "") {
            alert("Both email and password are required");
            event.preventDefault();
            return false;
         }

         return true;
      }

      document.addEventListener("DOMContentLoaded", function() {
         document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
               e.preventDefault();
               document.querySelector(this.getAttribute('href')).scrollIntoView({
                  behavior: 'smooth'
               });
            });
         });
      });
   </script>
</head>
<body>

<?php
if(isset($message)){
   foreach($message as $message){
      echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
   }
}
?>

<div class="container">
   <img src="images/logo.jpg" alt="Company Logo" class="logo">

   <div class="user-profile">
      <?php
         $select_user = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('query failed');
         if(mysqli_num_rows($select_user) > 0){
            $fetch_user = mysqli_fetch_assoc($select_user);
         }
      ?>
      <p>Username: <span><?php echo $fetch_user['name']; ?></span></p>
      <p>Email: <span><?php echo $fetch_user['email']; ?></span></p>
      <div class="flex">
         <a href="login.php" class="btn">Login</a>
         <a href="register.php" class="option-btn">Register</a>
         <a href="index.php?logout=<?php echo $user_id; ?>" onclick="return confirm('Are you sure you want to logout?');" class="delete-btn">Logout</a>
      </div>
   </div>

   <div class="products">
      <h1 class="heading">Latest Products</h1>
      <div class="box-container">
         <?php
            $select_product = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
            if(mysqli_num_rows($select_product) > 0){
               while($fetch_product = mysqli_fetch_assoc($select_product)){
         ?>
            <form method="post" class="box" action="">
               <img src="images/<?php echo $fetch_product['image']; ?>" alt="">
               <div class="name"><?php echo $fetch_product['name']; ?></div>
               <div class="price">R<?php echo $fetch_product['price']; ?> </div>
               <input type="number" min="1" name="product_quantity" value="1">
               <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
               <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
               <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
               <input type="submit" value="add to cart" name="add_to_cart" class="btn">
            </form>
         <?php
               }
            }
         ?>
      </div>
   </div>

   <div class="shopping-cart">
      <h1 class="heading">Shopping Cart</h1>
      <table>
         <thead>
            <th>Image</th>
            <th>Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Action</th>
         </thead>
         <tbody>
         <?php
            $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
            $grand_total = 0;
            if(mysqli_num_rows($cart_query) > 0){
               while($fetch_cart = mysqli_fetch_assoc($cart_query)){
         ?>
            <tr>
               <td><img src="images/<?php echo $fetch_cart['image']; ?>" height="100" alt=""></td>
               <td><?php echo $fetch_cart['name']; ?></td>
               <td>R<?php echo $fetch_cart['price']; ?> </td>
               <td>
                  <form action="" method="post">
                     <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                     <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
                     <input type="submit" name="update_cart" value="Update" class="option-btn">
                  </form>
               </td>
               <td>R<?php echo $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?> </td>
               <td><a href="index.php?remove=<?php echo $fetch_cart['id']; ?>" class="delete-btn" onclick="return confirm('Remove item from cart?');">Remove</a></td>
            </tr>
         <?php
                  $grand_total += $sub_total;
               }
            } else {
               echo '<tr><td style="padding:20px; text-transform:capitalize;" colspan="6">No item added</td></tr>';
            }
         ?>
         <tr class="table-bottom">
            <td colspan="4">Grand Total :</td>
            <td>R<?php echo $grand_total; ?> </td>
            <td><a href="index.php?delete_all" onclick="return confirm('Delete all from cart?');" class="delete-btn <?php echo ($grand_total > 1)?'':'disabled'; ?>">Delete All</a></td>
         </tr>
         </tbody>
      </table>

      <div class="cart-btn">  
   <a href="Checkout.php?total=<?php echo $grand_total; ?>" class="btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">Proceed to Checkout</a>
</div>
   </div>
</div>

</body>
</html>
