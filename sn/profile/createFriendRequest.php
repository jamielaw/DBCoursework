<?php
// Import DB Auth Script
  require '../database.php';

  //function to redirect - to be moved into a utils.php file later?
  function redirect($url) {
    ob_start();
    header('Location: '.$url);
    ob_end_flush();
    die();
  }

  $emailTo = $_GET['email'];
  // CHANGE TO SOMETHING THATS NOT HARDCODED!
  $loggedInUser = "vicky@ucl.ac.uk";
  $emailFrom = $loggedInUser;
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $friendshipRequest = "INSERT INTO
  `friendships` ( `emailFrom`, `emailTo`, `status`)
  VALUES  ('$emailFrom', '$emailTo', 'pending');";

  $pdo->exec($friendshipRequest);

  // Need to handle error catching etc
  Database::disconnect();

  redirect("http://localhost:8888/sn/profile/myfriends.php");

?>
