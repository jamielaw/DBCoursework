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




  $action = $_GET['action'];
  $requestingUser = $_GET['email'];
  $decidingUser = $loggedInUser;



  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if($action == "accepted" || $action == "denied"){
    // update with appropiate action
    $sql = "UPDATE friendships SET status='$action' WHERE ( emailFrom='$requestingUser'
    AND emailTo='$decidingUser') OR ( emailFrom='$decidingUser'
    AND emailTo='$requestingUser') ";

    $q = $pdo->prepare($sql);
    $q->execute();

    //echo $sql;
  }else{
    // dont do anything because the action is wrong!
  }

    redirect("myfriends.php");

  // completed

?>
