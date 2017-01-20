<?php

  require '../database.php';

  //function to redirect
  function redirect($url) {
    ob_start();
    header('Location: '.$url);
    ob_end_flush();
    die();
  }

  //$argument1 = removeSpaces(wrapArgument($_GET['email']));
  $argument1 = $_GET['email'];
  // sql to delete a record
  $sql = "DELETE FROM users WHERE email=". "'" . $argument1 . "'";
  //$sql = "DELETE FROM users WHERE email=". $argument1;
  #echo $sql;
  //$sql = "DELETE FROM users WHERE email=?";
  // come back and fix logic for this + handle error case
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->exec($sql);
  Database::disconnect();
  //echo "here";

  redirect('http://localhost:8888/sn/admin/');


?>
