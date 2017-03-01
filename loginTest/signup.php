<?php
  session_start();
  include 'dbh.php';
  $first = $_POST['first'];
  $last = $_POST['last'];
  $uid = $_POST['uid'];
  $pwd = $_POST['pwd'];
  $email = "henry@scrub.ac.uk"; //pls add this xx, as well as profile image? either that or create a placeholder image and refer to that

  $sql = "INSERT INTO user (first, last, uid, pwd) VALUES ('$first', '$last', '$uid', '$pwd')";
  //is this sql statement above correct? shouldn't it be MyDB.users. the formatting is also incorrect, it should follow the format
  //(email,roleID,user_password,firstName,lastName,profileImage) - Jamie

  $defaultPrivacy = "INSERT INTO MyDB.privacySettings (email, privacySettingsTitle, privacySettingsDescription) VALUES
(\"".$email."\", \"Who can send me friend requests?\", \"Anyone\")";
//execute this above statement too please (I'm not sure how to execute it without using PDO)
//echo $defaultPrivacy;

  //$result = mysqli_query($conn, $sql);

  //header("Location: index.php");
 ?>
