<?php

  // Todo:
  // Checking to make sure the username doesn’t already exist in the database when registering.
  // Enforcing a minimum length for the password, and perhaps a mix of numbers and letters.

  require('database.php');
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if (isset($_POST['sign-up-submit'])) {

    // Create an errors array
    $errors = array();

    // very basic validation to make sure that form fields aren’t blank,
    // mysqli_real_escape_string to ensure no injection is entered!

    if (empty($_POST['first'])){
      $errors[] = "Please enter a first name.";
      echo "Loser";
    } else {
      $firstName = mysqli_real_escape_string($_POST['first']);
      echo $firstName; // DOESN'T PRINT
    }

    if(empty($_POST['last'])){
      $errors[] = "Please enter a last name.";
      echo "Loser2";
    } else {
     $lastName = mysqli_real_escape_string($_POST['last']);
    }
    //
    // Check to make sure the fields are not empty
    if (empty($_POST['email'])){
      $errors[] = "Please enter a email";
      echo "Loser3";
    } else {
     $email = mysqli_real_escape_string($_POST['email']);
    }
    //
    if (empty($_POST['pwd'])){
      $errors[] = "Please enter a pwd name.";
      echo "Loser4";
    } else {
      $user_password = mysqli_real_escape_string($_POST['pwd']);
    }


    // This was working
    // if (empty($errors)) {
    //   $sql = "INSERT INTO MyDB.users (email, roleID, user_password, firstName, lastName, profileImage) VALUES (?, ?, ?, ?, ?, ? )";
    //   $q = $pdo->prepare($sql);
    //   $q->execute(array($email, 2, $user_password, $firstName, $lastName, "/images/profile/ada@ucl.ac.uk.jpg" ));
    //   Database::disconnect();
    //   echo $email . " user has been created! You can now login.";
    //   header("Location: index.php");
    // } else {
    //   //Display an error in $errors[]
    //   echo "There's been an error";
    // }
  }




 ?>
