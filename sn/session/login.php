<?php
  session_start();
  include 'dbh.php';
  $uid = $_POST['uid'];
  $pwd = $_POST['pwd'];

  $sql = "SELECT * FROM users WHERE email='$uid' AND user_password='$pwd'";
  $result = mysqli_query($conn, $sql);

  if (!$row = mysqli_fetch_assoc($result)) {
    echo "Your username or password is incorrect!";
  } else {
    //Uses email row as a session variable
    $_SESSION['id'] = $row['email'];
  }
  header("Location: index.php");
 ?>
