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
  $argument1 = htmlspecialchars($_GET['circle']);
  $pc = htmlspecialchars($_GET['pcId']);

    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO `accessRights` ( `photoCollectionId`, `circleFriendsId`) VALUES ($pc, $argument1)";
    //echo $sql;
    $pdo->exec($sql);
    Database::disconnect();


?>
