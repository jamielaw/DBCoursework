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

  // sql to delete a record
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $foreignKey = $_GET['pcId'];

  $pcData = "SELECT * FROM photos WHERE photoCollectionId=$foreignKey";

  foreach($pdo->query($pcData) as $row){

    $comments = "DELETE FROM comments WHERE photoId=".  $row['photoId'];
    $annotations = "DELETE FROM annotations WHERE photoId=". $row['photoId'];
    $sql = "DELETE FROM photos WHERE photoId=".  $row['photoId'];

    $pdo->exec($comments);
    $pdo->exec($annotations);
    $pdo->exec($sql);
  }


  $sql = "DELETE FROM photoCollection WHERE photoCollectionId=".   $_GET['pcId'];
  //echo $sql;

  $pdo->exec($sql);
  Database::disconnect();


  redirect('../../admin/');



?>
