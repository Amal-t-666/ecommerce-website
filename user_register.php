<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}
;

if (isset($_POST['submit'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_user->execute([$email,]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if ($select_user->rowCount() > 0) {
      $message[] = 'email already exists!';
   } else {
      if ($pass != $cpass) {
         $message[] = 'confirm password not matched!';
      } else {
         $insert_user = $conn->prepare("INSERT INTO `users`(name, email, password) VALUES(?,?,?)");
         $insert_user->execute([$name, $email, $cpass]);
         $message[] = 'registered successfully, login now please!';
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
   <title>register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <style type="text/css">
      .reg {
         margin-bottom: 20px;
      }

      .option-btn {

         background-color: limegreen;
      }

      .btn {
         background-color: var(--main-color);
      }

      .form-container form {
         /*   background-color: var(--white);*/
         padding: 2rem;
         border-radius: .5rem;
         border: var(--border);
         box-shadow: var(--box-shadow);
         text-align: center;
         margin: 0 auto;
         max-width: 40rem;
         background-color: lightblue;

      }

      .form-container form h3 {
         font-size: 2.5rem;
         text-transform: uppercase;
         color: var(--black);
      }

      .form-container form p {
         font-size: 2rem;
         color: var(--light-color);
         margin: 1.5rem 0;
      }

      .form-container form .box {
         margin: 1rem 0;
         background-color: var(--light-bg);
         padding: 1.4rem;
         font-size: 1.8rem;
         color: var(--black);
         width: 100%;
         border-radius: .5rem;

      }
   </style>

</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <section class="form-container">

      <form action="" method="post">
         <h3 class="reg">register now</h3>
         <input type="text" name="name" required placeholder="Enter your username" maxlength="20" class="box">
         <input type="email" name="email" required placeholder="Enter your email" maxlength="50" class="box"
            oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="pass" required placeholder="Enter your password" maxlength="20" class="box"
            oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="cpass" required placeholder="Confirm your password" maxlength="20" class="box"
            oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="submit" value="register" class="btn" name="submit">
         <p>Already have an account?</p>
         <a href="user_login.php" class="option-btn">login now</a>
      </form>

   </section>


   <?php include 'components/footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>