<?php
  // Import DB Auth Script
  require("../session.php");

  //function to redirect - to be moved into a utils.php file later?
  function redirect($url) {
    ob_start();
    header('Location: '.$url);
    ob_end_flush();
    die();
  }

  // Get PK of Table
  $circlename = htmlspecialchars($_GET['circlename']);
  // sql to delete a record
  $sql = "INSERT INTO MyDB.circleOfFriends (circleOfFriendsName) VALUES (\"" . $circlename . "\")";
  $getId = "SELECT circleFriendsId FROM MyDB.circleOfFriends WHERE(circleOfFriendsName='" . $circlename . "') ORDER BY circleFriendsId DESC LIMIT 1"; //get ID of the circle we just created as it is autoincrement


  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->exec($sql);
  //echo $getId;
  $y = $pdo->query($getId);
  $circleId = $y->fetch(PDO::FETCH_ASSOC);
  //echo $circleId["circleFriendsId"];
  $sqluser = "INSERT INTO MyDB.userCircleRelationships(email, circleFriendsId) VALUES ('" . $loggedInUser . "', " . $circleId["circleFriendsId"] . ")";
  //echo $sqluser;
  $pdo->exec($sqluser);
  Database::disconnect();

  //Redirect to /sn/circles/index page to create "refresh "
  redirect('/sn/circles/index.php');


?>