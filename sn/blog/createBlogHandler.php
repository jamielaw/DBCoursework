<?php
  // DB Auth Script
  require '../session.php';

  // Given "string", returns "'string'" - useful for SQL queries
  function wrapArgument($arg){
    $arg = htmlspecialchars($arg);
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


  $sql = "INSERT INTO blogs (blogTitle, blogDescription, email) VALUES ("
  . wrapArgument($_POST['blogTitle']) .  ","
  . wrapArgument($_POST['blogDescription']) . ","
  . "'$loggedInUser')";

  //echo $sql;

  #echo $sql;
  $pdo->exec($sql);

  // Need to handle error catching etc
  Database::disconnect();

  // Direct back to sn/admin
  redirect('../../sn/blog/');





?>
