<?php


function redirect($url) {
  ob_start();
  header('Location: '.$url);
  ob_end_flush();
  die();
}


  $argument1 = $_GET['email'];
  //echo $argument1;

  echo $argument1;
  require '../database.php';

  // sql to delete a record
  $sql = "DELETE FROM MyDB.users WHERE email=". "'" . $argument1 . "'";
  //echo $sql;

  if ($conn->query($sql) === TRUE) {
      //echo "Record deleted successfully";
  } else {
      //echo "Error deleting record: " . $conn->error;
  }

  $conn->close();



  //echo "here";

  redirect('http://localhost:8888/sn/admin/');


?>
