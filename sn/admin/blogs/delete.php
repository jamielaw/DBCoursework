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
  $deleteAccessRights = "DELETE  FROM privac WHERE photoCollectionId=". $foreignKey;
  // echo $deleteAccessRights;
  $pdo->exec($deleteAccessRights);

  // sql to delete a record
  $sql = "DELETE FROM blogs WHERE blogId=".   $_GET['blogId'];
  //echo $sql;
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->exec($sql);
  Database::disconnect();


  redirect('../../admin/');



?>
