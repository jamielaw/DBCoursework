<?php

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

  $servername = "localhost:3306";
  $username = "root";
  $password = "root";


  $conn = new mysqli($servername, $username, $password);

  if ($conn->connect_error) {
      //die("Connection failed: " . $conn->connect_error);
  }else{
      //echo "Connection established";
  }


  $sql = "UPDATE MyDB.users SET
  email=" . removeSpaces(wrapArgument($_POST['email'])) .  ","
  . "firstName=" . removeSpaces(wrapArgument($_POST['firstName'])) .  ","
  . "lastName=" . removeSpaces(wrapArgument($_POST['lastName'])) .  ","
  . "profileImage =" . removeSpaces(wrapArgument($_POST['profileImage'])) .  ","
  . "profileDescription =" . wrapArgument($_POST['profileDescription'])
  . " WHERE email='" . removeSpaces($_POST['argument1']) . "'";

  //echo $sql;

  if($conn->query($sql) == TRUE){
    $conn->close();
    redirect('http://localhost:8888/sn/admin/');
  }




?>
