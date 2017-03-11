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
  $argument1 = htmlspecialchars($_GET['adminStatus']);
  $email = htmlspecialchars($_GET['email']);

  if($argument1 == 1 || $argument1 == 2 ){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE users SET roleID= $argument1 WHERE email='". $email . "'";
    //echo $sql;
    $pdo->exec($sql);
    Database::disconnect();
  }

?>
