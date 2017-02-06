<?php
require '../database.php';

// fetch all tags
$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$emailTo = $_POST['emailTo'];
$emailFrom = $_POST['emailFrom'];
$messageText = $_POST['messageText'];

$sql = "INSERT INTO messages (emailTo, emailFrom, messageText) VALUES (?,?,?)";
$q = $pdo->prepare($sql);
$q->execute(array($emailTo,$emailFrom,$messageText));
