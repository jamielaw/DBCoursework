<?php

  require '../database.php';

  function wrapArgument($arg){
    return "'" . $arg . "'";
  }

  function removeSpaces($arg){
    return str_replace(' ', '', $arg);
  }

  function redirect($url) {
    ob_start();
    header('Location: '.$url);
    ob_end_flush();
    die();
  }


  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


  $sql = "UPDATE MyDB.users SET
  email=" . removeSpaces(wrapArgument($_POST['email'])) .  ","
  . "firstName=" . removeSpaces(wrapArgument($_POST['firstName'])) .  ","
  . "lastName=" . removeSpaces(wrapArgument($_POST['lastName'])) .  ","
  . "profileImage =" . removeSpaces(wrapArgument($_POST['profileImage'])) .  ","
  . "profileDescription =" . wrapArgument($_POST['profileDescription'])
  . " WHERE email='" . removeSpaces($_POST['argument1']) . "'";

  //echo $sql;

  $pdo->exec($sql);

  redirect('http://localhost:8888/sn/admin/');

  Database::disconnect();



?>
