<?php
  // DB Auth Script
  require '../../database.php';

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


  $sql = "UPDATE MyDB.annotations SET "
  . "annotationText=" . wrapArgument($_POST['annotationText'])
  . " WHERE annotationsId=" . $_POST['argument1'];


    $q = $pdo->prepare($sql);
    $q->execute();


  //   echo $q->rowCount() . " records UPDATED successfully";
  // }
  // catch(PDOException $e)
  // {
  //   echo $sql . "<br>" . $e->getMessage();
  // }




  // Need to handle error catching etc

  Database::disconnect();

  // Direct back to sn/admin
  redirect('http://localhost:8888/sn/admin/');





?>
