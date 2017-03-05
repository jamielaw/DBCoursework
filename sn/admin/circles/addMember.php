<?php
  // Import DB Auth Script
  require '../../database.php';

  //function to redirect - to be moved into a utils.php file later?
  function redirect($url) {
    ob_start();
    header('Location: '.$url);
    ob_end_flush();
    die();
  }

  // Get PK of Table
  $argument1 = htmlspecialchars($_GET['circleId']);
  $argument2 = htmlspecialchars($_GET['email']);


  // sql to delete a record
  $sql = "INSERT INTO userCircleRelationships ( email , circleFriendsId) VALUES ('$argument2' , $argument1)";
  // echo $sql;
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->exec($sql);
  Database::disconnect();


  redirect('../../admin/');

?>
