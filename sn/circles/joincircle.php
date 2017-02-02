<?php
  // Import DB Auth Script
  require '../database.php';
  $loggedInUser = 'charles@ucl.ac.uk';

  //function to redirect - to be moved into a utils.php file later?
  function redirect($url) {
    ob_start();
    header('Location: '.$url);
    ob_end_flush();
    die();
  }

  // Get PK of Table
  $circleId = $_GET['circleFriendsId'];
  // sql to delete a record
  $sql = "INSERT INTO MyDB.userCircleRelationships VALUES('" .$loggedInUser . "'," . $circleId .")";

  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->exec($sql);

  Database::disconnect();

  //Redirect to /sn/circles/index page to create "refresh "
  redirect('/sn/circles/index.php');


?>