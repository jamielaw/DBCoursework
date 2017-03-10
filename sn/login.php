<?php
  session_start();
  require('database.php');
  $email = $_POST['email'];
  $user_password = $_POST['pwd'];

  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT * FROM MyDB.users WHERE email=? AND user_password=?";
  $q = $pdo->prepare($sql);
  $q->execute(array($email, $user_password));
  $loginAttemptResult = $q->fetch();

  // user password_verfiy on the returned user_password from DB against the enterered $user_password
  if( $email == $loginAttemptResult["email"] && password_verify($user_password, $loginAttemptResult["user_password"])) {
    $_SESSION['loggedInUserEmail'] = $email;
    redirect("profile/index.php");
  } else {
    echo "Your username or password is incorrect!";
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
