<?php
// Import DB Auth Script
  require '../session.php';

  //function to redirect - to be moved into a utils.php file later?
  function redirect($url) {
    ob_start();
    header('Location: '.$url);
    ob_end_flush();
    die();
  }

  $emailTo = $_GET['email'];
  $emailFrom = $loggedInUser;
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $friendshipRequest = "INSERT INTO
  `friendships` ( `emailFrom`, `emailTo`, `status`)
  VALUES  ('$emailFrom', '$emailTo', 'pending');";

  $pdo->exec($friendshipRequest);

  // Need to handle error catching etc
  Database::disconnect();

  redirect("readprofile.php?email=" . $emailTo);

?>
