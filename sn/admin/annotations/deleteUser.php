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
  $argument1 = htmlspecialchars($_GET['email']);
  // sql to delete a record
  $sql = "DELETE FROM users WHERE email=". "'" . $argument1 . "'";
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->exec($sql);
  Database::disconnect();

  //Redirect to /sn/admin page to create "refresh "
  // URL TO BE MADE RELATIVE LATER
  redirect('http://localhost:8888/sn/admin/');


?>
