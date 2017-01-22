<?php
  // DB Auth Script
  require '../database.php';

  // Given "string", returns "'string'" - useful for SQL queries
  function wrapArgument($arg){
    return "'" . $arg . "'";
  }
  // Removes spaces
  function removeSpaces($arg){
    return str_replace(' ', '', $arg);
  }
  // All functions should be moved to utils.php at some point
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


  $pdo->exec($sql);

  // Need to handle error catching etc

  Database::disconnect();

  // Direct back to sn/admin
  redirect('http://localhost:8888/sn/admin/');





?>
