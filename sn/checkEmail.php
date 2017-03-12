<?php

require('database.php');
$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$testEmail = $_GET['email'];

$sql = "SELECT COUNT(*) FROM USERS where email=?";
$q = $pdo->prepare($sql);
$q->execute(array($testEmail));

$row = $q->fetch(PDO::FETCH_ASSOC);
// echo $row['COUNT(*)'];

if($row['COUNT(*)'] == 0){
  echo json_encode(array('free'=>true));
}else{
  echo json_encode(array('free'=>false));
}
echo $row['count'];


?>
