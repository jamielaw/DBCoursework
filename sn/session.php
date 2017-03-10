<?php
session_start();
require 'database.php';

if (isset($_SESSION['email'])) {
  $loggedInUser= $_SESSION['email'];
} else {
  echo "You are not logged in";
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

Database::disconnect(); // remember to disconnect otherwise this causes bugs
?>
