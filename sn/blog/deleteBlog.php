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

  //echo $_GET['blogId'];

  // Get PK of Table
  // $argument1 = $htmlspecialchars($_GET['blogId']);
  // sql to delete a record
  $argument1 = $_GET['blogId'];

  $sql = "DELETE FROM blogs WHERE blogId= $argument1" ;
  //echo $sql;
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $y = $pdo->prepare($sql);
  $y->execute();

  Database::disconnect();

  redirect('../../sn/blog/');


?>
