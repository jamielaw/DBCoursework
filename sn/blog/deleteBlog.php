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
  $argument1 = $_GET['blogId'];
  // sql to delete a record
  $sql = "DELETE FROM blogs WHERE blogId=". $argument1 ;
  #echo $sql;
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $y = $pdo->prepare($sql);
  $y->execute();

  Database::disconnect();

  //Redirect to /sn/admin page to create "refresh "
  // URL TO BE MADE RELATIVE LATER
  redirect('http://localhost:8888/sn/blog/');


?>
