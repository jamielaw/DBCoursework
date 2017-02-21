<?php

require '../session.php';

if( !empty( $_POST['type'] ) && $_POST['type'] == "insert" )
{
  $id = $_POST['pic_id'];  
  $name = $_POST['name'];
  $pic_x = $_POST['pic_x'];
  $pic_y = $_POST['pic_y'];

  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "INSERT INTO annotations (photoId,email,coordinateX,coordinateY,annotationText) VALUES (?,?,?,?,?)";
  $q = $pdo->prepare($sql);
  $q->execute(array($id, $loggedInUser, $pic_x, $pic_y, $name));
}

if( !empty( $_POST['type'] ) && $_POST['type'] == "remove")
{
  $tag_id = $_POST['tag_id'];
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "DELETE FROM annotations WHERE annotationsId = ?";
  $q = $pdo->prepare($sql);
  $q->execute(array($tag_id));
}

?>