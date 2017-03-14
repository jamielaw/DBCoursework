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
  $ref=$_GET['page'];
  $emailFrom = $loggedInUser;
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $unfriendRequest = "UPDATE MyDB.friendships SET status='denied' WHERE((emailTo='" . $loggedInUser. "' AND emailFrom='" . $emailTo ."') OR (emailFrom='".$loggedInUser . "' AND emailTo='" . $emailTo."'));";
  $pdo->exec($unfriendRequest);

  // Need to handle error catching etc
  Database::disconnect();
  if($ref=="zone"){
    redirect("myfriends.php");
  }else{
    redirect("readprofile.php?email=" . $emailTo);
  }

?>
