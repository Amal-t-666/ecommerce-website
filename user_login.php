<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
   $select_user->execute([$email, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $_SESSION['user_id'] = $row['id'];
      echo "<script>alert('Login successful. Welcome, " . $row['name'] . "!');</script>";
      echo "<script>window.location.href='home.php';</script>";

   }else{
      echo "<script>alert('incorrect email or password!')</script>";

}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <style type="text/css">
      .body{
            background-color:floralwhite;
            
         }
         .ln{
            margin-bottom: 20px;
         }
      .btn:hover{
         background-color: var(--black);
      }
      .btn{
         background-color:limegreen;
         

      }
      .form-container form{
         /* background-color: var(--black); */
         background-color: lightblue;
         padding:2rem;
         border-radius: .5rem;
         border:var(--border);
         box-shadow: var(--box-shadow);
         text-align: center;
         margin:0 auto;
         max-width: 40rem;
         margin-bottom:0% ;
         height: 450px;
         margin-top: 3%;
      }
      .form-container form h3{
   font-size: 2.5rem;
   text-transform: uppercase;
   color:var(--black);
}

.form-container form p{
   font-size: 2rem;
   color:var(--light-color);
   margin:1.5rem 0;
}
.form-container form .box{
   margin:1rem 0;
   background-color: var(--light-bg);
   padding:1.4rem;
   font-size: 1.8rem;
   color:var(--black);  
   width: 100%;
   border-radius: .5rem
}

   </style>

</head>
   <body class="body" >
   
<?php include 'components/user_header.php'; ?>

<section class="form-container">

   <form action="" method="post">
      <h3 class="ln">login now</h3>
      <input type="email" name="email" required placeholder="Enter your email" maxlength="50"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Enter your password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="login" class="btn " name="submit">
      <p>Don't have an account?</p>
      <a href="user_register.php" class="option-btn">register </a>
   </form>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>