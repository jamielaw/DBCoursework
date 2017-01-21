<?php
$servername = "localhost:3306";
$username = "root";
$password = "admin";

// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}else{
  echo "Connection established";
}

// Drop database if necessary
$dropDatabase = "DROP DATABASE IF EXISTS MyDB";


  echo nl2br("\n"); //Line break in HTML conversion
  echo "<b>Executing SQL statement: </b>";
  echo $dropDatabase; //Dispay statement being executed
  echo nl2br("\n");
  if ($conn->query($dropDatabase) === TRUE) {
      echo "<b><font color='green'>SQL statement performed correctly</b></font>";
  } else {
      echo "<b><font color='red'>Error executing statement: </b></font>" . $conn->error;
  }

$conn->close();
?>
