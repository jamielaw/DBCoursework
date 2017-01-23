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

  // Get PK of Table
  $circlename = $_GET['circlename'];
  // sql to delete a record
  $sql = "INSERT INTO MyDB.circleOfFriends (circleOfFriendsName) VALUES (\"" . $circlename . "\")";
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->exec($sql);
  Database::disconnect();

  //Redirect to /sn/circle/createcircleview page to create "refresh "
  // URL TO BE MADE RELATIVE LATER
  redirect('http://localhost:8888/sn/circles/index.php');


?>