<?php
session_start();
require 'database.php';

// $loggedInUser = 'ada@ucl.ac.uk';
if (isset($_SESSION['loggedInUserEmail'])) {
  $loggedInUser= $_SESSION['loggedInUserEmail'];
} else {
  //echo "You are not logged in";
}
//$loggedInUser='charles@ucl.ac.uk';

//Getting user data
$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT * FROM users WHERE email=?";
$q = $pdo->prepare($sql);
$q->execute(array($loggedInUser));
$data = $q->fetch(PDO::FETCH_ASSOC);

$firstName = $data['firstName'];
$lastName = $data['lastName'];
$photo = $data['profileImage'];
$isAdmin = false;
if($data['roleID'] == 1 ) $isAdmin = true;

Database::disconnect(); // remember to disconnect otherwise this causes bugs
?>
