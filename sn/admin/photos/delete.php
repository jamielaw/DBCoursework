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
  $argument1 = htmlspecialchars($_GET['photoId']);
  $pdo = Database::connect();
  // sql to delete a record
  $comments = "DELETE FROM comments WHERE photoId=".  $argument1;
  $annotations = "DELETE FROM annotations WHERE photoId=".  $argument1;
  $sql = "DELETE FROM photos WHERE photoId=".  $argument1;


  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->exec($comments);
  $pdo->exec($annotations);
  $pdo->exec($sql);

  Database::disconnect();

  redirect('../../admin/');



?>
