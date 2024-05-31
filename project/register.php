<?php
include 'config.php';

if(isset($_POST['submit'])){
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
   $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));

   $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email = '$email' AND password = '$pass'") or die('query failed');

   if(mysqli_num_rows($select) > 0){
      $message[] = 'User already exists!';
   }else{
      mysqli_query($conn, "INSERT INTO `user_form`(name, email, password) VALUES('$name', '$email', '$pass')") or die('query failed');
      $message[] = 'Registered successfully!';
      header('location:login.php');
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register</title>
   <link rel="stylesheet" href="css/style.css">
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
   <form name="registerForm" action="" method="post" onsubmit="return validateRegistrationForm(event)">
      <h3>Register Now</h3>
      <input type="text" name="name" required placeholder="Enter username" class="box">
      <input type="email" name="email" required placeholder="Enter email" class="box">
      <input type="password" name="password" required placeholder="Enter password" class="box">
      <input type="password" name="cpassword" required placeholder="Confirm password" class="box">
      <input type="submit" name="submit" class="btn" value="Register Now">
      <p>Already have an account? <a href="login.php">Login Now</a></p>
   </form>
</div>
</body>
</html>
