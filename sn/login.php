<?php
  session_start();
  require('database.php');
  $email = $_POST['email'];
  $user_password = $_POST['pwd'];


  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT * FROM MyDB.users WHERE email=?";
  $q = $pdo->prepare($sql);
  $q->execute(array($email));
  $loginAttemptResult = $q->fetch();

  // echo $loginAttemptResult["user_password"];

  // Surely this
  // echo "<br>";
  // password_verify($user_password, PASSWORD_DEFAULT);
  // echo '<br/>';
  // and this should match?

  // echo '<br/>';

  // Anyway let's check to see if they do...
  $answer = password_verify($user_password, $loginAttemptResult["user_password"]);
  // echo $answer == TRUE ? 'TRUE' : 'FALSE'; // we want TRUE, atm not getting it I think....
  // echo '<br/>';
  // user password_verfiy on the returned user_password from DB against the enterered $user_password
  if( $email == $loginAttemptResult["email"]) {
    // echo "Success on email";
    // echo '<br/>';
     if ( password_verify($user_password, $loginAttemptResult["user_password"])) {
      //  echo "Your password is correct";
       $_SESSION['loggedInUserEmail'] = $email;
       redirect("profile/index.php");
     } else {
      //  echo "Your password is incorrect, or something is wrong.";
     }

  } else {
    // echo "Your username incorrect!";
  }

  // if (!$row = mysqli_fetch_assoc($result)) {
  //   echo "Your username or password is incorrect!";
  // } else {
  //   //Uses id as a session variable
  //   $_SESSION['id'] = $row['id'];
  // }
  function redirect($url) {
    ob_start();
    header('Location: '.$url);
    ob_end_flush();
    die();
  }



 ?>
