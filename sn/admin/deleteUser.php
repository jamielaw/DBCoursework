<?php

  require '../databases.php';


  //function to redirect
  function redirect($url) {
    ob_start();
    header('Location: '.$url);
    ob_end_flush();
    die();
  }

  function wrapArgument($arg){
    return "'" . $arg . "'";
  }

  function removeSpaces($arg){
    return str_replace(' ', '', $arg);
  }


  $argument1 = removeSpaces(wrapArgument($_GET['email']));

  // sql to delete a record
  // $sql = "DELETE FROM MyDB.users WHERE email=". "'" . $argument1 . "'";
  $sql = "DELETE FROM users WHERE email=?";

  // come back and fix logic for this + handle error case
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $q = $pdo->prepare($sql);
  $q->execute($argument1);
  Database::disconnect();


  //echo "here";

  redirect('http://localhost:8888/sn/admin/');


?>
