<?php
include 'config.php';
session_start();

if(isset($_POST['submit'])){
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));

   $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email = '$email' AND password = '$pass'") or die('query failed');

   if(mysqli_num_rows($select) > 0){
      $row = mysqli_fetch_assoc($select);
      $_SESSION['user_id'] = $row['id'];
      header('location:index.php');
   }else{
      $message[] = 'Incorrect email or password!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>
   <link rel="stylesheet" href="css/style.css">
   <script>
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
   </script>
</head>
<body>

<?php
if (isset($message)) {
   foreach ($message as $message) {
      echo '<div class="message" onclick="this.remove();">' . $message . '</div>';
   }
}
?>

<div class="form-container">
   <img src="images/logo.jpg" alt="Company Logo" class="logo">
   <form name="loginForm" action="" method="post" onsubmit="return validateLoginForm(event)">
      <h3>Login Now</h3>
      <input type="email" name="email" required placeholder="Enter email" class="box">
      <input type="password" name="password" required placeholder="Enter password" class="box">
      <input type="submit" name="submit" class="btn" value="Login Now">
      <p>Don't have an account? <a href="register.php">Register Now</a></p>
   </form>
</div>
</body>
</html>
