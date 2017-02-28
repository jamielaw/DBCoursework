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
  $circleId = $_GET['circleFriendsId'];
  // sql to delete a record
  $sql = "DELETE FROM MyDB.userCircleRelationships WHERE(email='" .$loggedInUser . "' AND circleFriendsId=" . $circleId .")";

  $countMembers = "SELECT COUNT(email) FROM MyDB.circleOfFriends INNER JOIN MyDB.userCircleRelationships ON MyDB.circleOfFriends.circleFriendsId=MyDB.userCircleRelationships.circleFriendsId WHERE MyDB.circleOfFriends.circleFriendsId=" . $circleId;


  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->exec($sql);

  //count members to see if there are 0 remaining, if so we delete the circle
  $y = $pdo->query($countMembers);
  $count = $y->fetch(PDO::FETCH_ASSOC);

  if($count["COUNT(email)"]==0){
    $deleteCircle = "DELETE FROM MyDB.circleOfFriends WHERE(circleFriendsId=" . $circleId . ")";
    //echo $deleteCircle;
    $pdo->exec($deleteCircle);
  }

  Database::disconnect();

  //Redirect to /sn/circles/index page to create "refresh "
  redirect('/sn/circles/index.php');


?>