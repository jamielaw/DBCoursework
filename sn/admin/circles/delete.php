<?php
  // Import DB Auth Script
  require '../../database.php';

  //function to redirect - to be moved into a utils.php file later?
  function redirect($url) {
    ob_start();
    header('Location: '.$url);
    ob_end_flush();
    die();
  }
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Get PK of Table
  $argument1 = htmlspecialchars($_GET['circleId']);
  // delete any messages with the circle id
  $deleteMessages = "DELETE FROM messages WHERE emailTo=". $argument1;
  $pdo->exec($deleteMessages);


  // delete photos
  // $pcData = "SELECT * FROM photos WHERE photoCollectionId=$foreignKey";
  //
  // foreach($pdo->query($pcData) as $row){
  //
  //   $comments = "DELETE FROM comments WHERE photoId=".  $row['photoId'];
  //   $annotations = "DELETE FROM annotations WHERE photoId=". $row['photoId'];
  //   $sql = "DELETE FROM photos WHERE photoId=".  $row['photoId'];
  //
  //   $pdo->exec($comments);
  //   $pdo->exec($annotations);
  //   $pdo->exec($sql);
  // }


  // delete comments and annotations on photos

  // delete photo collections


  // sql to delete a record


  // delete any access rights
  $deleteAccessRights = "DELETE FROM accessRights WHERE circleFriendsId=". $argument1;
  $pdo->exec($deleteAccessRights);

  // user circle relations
  $deleteCircleRelations = "DELETE FROM userCircleRelationships WHERE circleFriendsId=". $argument1;
  $pdo->exec($deleteCircleRelations);


  $sql = "DELETE FROM circleOfFriends WHERE circleFriendsId=". $argument1 ;
  $pdo->exec($sql);
  //echo $sql;


  Database::disconnect();


  redirect('../../admin/');



?>
