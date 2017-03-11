<?php
  require('database.php');
  $firstName = $_POST['first'];
  $lastName = $_POST['last'];
  $email = $_POST['email'];
  $user_password = $_POST["pwd"];

  echo $email;
  //pls add this xx, as well as profile image? either that or create a placeholder image and refer to that
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $encrypted_password = password_hash($user_password, PASSWORD_DEFAULT);
  $sql = "INSERT INTO MyDB.users (email, roleID, user_password, firstName, lastName, profileImage) VALUES (?, ?, ?, ?, ?, ? )";

  $q = $pdo->prepare($sql);

  $q->execute(array($email, 2, $encrypted_password, $firstName, $lastName, "/images/profile/default-profile.png" ));

  $sql1 = "INSERT INTO MyDB.privacySettings (privacyTitleId, email, privacyType)
  VALUES
  (1, '$email', \"Friends of friends\"),
  (2, '$email', \"Friends of friends\"),
  (3, '$email', \"Friends of friends\"),
  (4, '$email', \"Friends of friends\"),
  (5, '$email', \"Friends of friends\");";


  $q = $pdo->prepare($sql1);
  $q->execute();

  Database::disconnect();
  echo $email . " user has been created! You can now login.";
  //is this sql statement above correct? shouldn't it be MyDB.users. the formatting is also incorrect, it should follow the format
  //(email,roleID,user_password,firstName,lastName,profileImage) - Jamie

  //$defaultPrivacy = "INSERT INTO MyDB.privacySettings (email, privacySettingsTitle, privacySettingsDescription) VALUES (\"".$email."\", \"Who can send me friend requests?\", \"Anyone\")";
//execute this above statement too please (I'm not sure how to execute it without using PDO)
// Jamie this isn't 3NF.
//echo $defaultPrivacy;

  //$result = mysqli_query($conn, $sql);

  //header("Location: index.php");
 ?>
