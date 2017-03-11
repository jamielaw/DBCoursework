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
  $argument1 = htmlspecialchars( $_GET['email']);
  // sql to delete a record
  // delete all the friends hes been involved in
  $deleteFriends = "DELETE FROM friendships WHERE emailTo = '$argument1' OR emailFrom = '$argument1'";
  $pdo->exec($deleteFriends);


  $deleteMessages = "DELETE FROM messages WHERE emailTo = '$argument1' OR emailFrom = '$argument1'";
  $pdo->exec($deleteMessages);

  //delete privact settings
  $deletePrivacy = "DELETE FROM privacySettings WHERE email= '$argument1'";
  $pdo->exec($deletePrivacy);

  //delete privact settings
  $deleteBlogs = "DELETE FROM blogs WHERE email= '$argument1'";
  $pdo->exec($deleteBlogs);

  //delete comments
  $deleteComments= "DELETE FROM comments WHERE email= '$argument1'";
  $pdo->exec($deleteComments);

  //delete annotations
  $deleteAnno= "DELETE FROM annotations WHERE email= '$argument1'";
  $pdo->exec($deleteAnno);


  $pcData = "SELECT * FROM photoCollection WHERE createdBy='$argument1'";
  foreach($pdo->query($pcData) as $row){

    $sql = "DELETE FROM photos WHERE photoCollectionId=".  $row['photoCollectionId'] ;

    $pdo->exec($sql);
  }
  $deletePC= "DELETE FROM photoCollection WHERE createdBy= '$argument1'";
  $pdo->exec($deletePC);

  //delete access rights
  $deleteAccessRights= "DELETE FROM accessRights WHERE email= '$argument1'";
  $pdo->exec($deleteAccessRights);


  $deleteCircleFriends = "DELETE FROM userCircleRelationships WHERE email= '$argument1'";
  $pdo->exec($deleteCircleFriends);
  $sql = "DELETE FROM users WHERE email=". "'" . $argument1 . "'";
  $pdo->exec($sql);


  Database::disconnect();

  //Redirect to /sn/admin page to create "refresh "
  // URL TO BE MADE RELATIVE LATER
  redirect('../../admin/');


?>
