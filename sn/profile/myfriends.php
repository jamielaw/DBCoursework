<?php

    $title = "Bookface Social Network";
    $description = "A far superior social network";
    include("../inc/header.php");
    include("../inc/nav-trn.php");

    function nrOfFriends($email) {
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql1 = 'SELECT COUNT(*) FROM users JOIN friendships ON users.email = friendships.emailFrom OR users.email=friendships.emailTo WHERE (friendships.emailFrom= ? OR friendships.emailTo= ? ) AND users.email!= ? AND status=\'accepted\';';
    $q1 = $pdo->prepare($sql1);
    $q1->execute(array($email,$email,$email));
    $nr = $q1->fetch(PDO::FETCH_ASSOC);
    return $nr;
  }

  function nrOfPhotos($email) {
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql1 = 'SELECT COUNT(*) FROM photos JOIN photocollection ON photocollection.photoCollectionId=photos.photoCollectionId WHERE photocollection.createdBy= ?;';
    $q1 = $pdo->prepare($sql1);
    $q1->execute(array($email));
    $nr = $q1->fetch(PDO::FETCH_ASSOC);
    return $nr;
  }

  function nrOfComments($email) {
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql1 = 'SELECT COUNT(*) FROM comments WHERE email = ?;';
    $q1 = $pdo->prepare($sql1);
    $q1->execute(array($email));
    $nr = $q1->fetch(PDO::FETCH_ASSOC);
    return $nr;
  }

  function compareOrder($a, $b){
    return $b['mutualFriends'] - $a['mutualFriends'];
  }

  // If you ever want to display mutual friends!
  // First get a list of your friends
  // Then get a list of the other persons friends
  // keep the ones which match
  // count them
  function nrOfMutualFriends($loggedInUser, $nonFriendedUser) {
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $getLoggedInUserFriends = "SELECT * FROM ( SELECT * FROM users JOIN
       friendships ON users.email = friendships.emailFrom OR
       users.email=friendships.emailTo WHERE(
         (friendships.emailFrom='$loggedInUser' OR
         friendships.emailTo='$loggedInUser' ) AND
          users.email!= '$loggedInUser' AND
          friendships.status = 'accepted')) AS
          T1 JOIN ( SELECT * FROM users JOIN
          friendships ON users.email =
           friendships.emailFrom OR
           users.email=friendships.emailTo
           WHERE( (friendships.emailFrom='$nonFriendedUser'
           OR friendships.emailTo='$nonFriendedUser' )
           AND users.email!= '$nonFriendedUser' AND
           friendships.status = 'accepted')) AS T2 ON T1.email
           = T2.email";

    //echo $getLoggedInUserFriends;

    $count = 0;
    foreach ($pdo->query($getLoggedInUserFriends) as $row){
      //echo $row['email'];
      $count = $count + 1;
    }
    return $count;
  }

  function getUsersUniversity($loggedInUser){
    $matches = array();
    $uni = preg_match("@(.)$", $loggedInUser, $matches, PREG_OFFSET_CAPTURE);
    //echo $matches[0][0];
    return $matches[0][0];
  }
  function getDomainFromEmail($email) {
  // Get the data after the @ sign
  $domain = substr(strrchr($email, "@"), 1);
  $uni = strtok($domain,".");
  return strtoupper($uni);
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>

<body style="padding-top:0px;">
<link href="http://getbootstrap.com/examples/jumbotron-narrow/jumbotron-narrow.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">
<div class="container bootstrap snippet">


  <div class="header padding">
    <h3 class="text-muted prj-name">
        <span class="fa fa-users fa-2x principal-title"></span>
        Friend Requests
    </h3>
  </div>

  <div class="jumbotron list-content">
    <ul class="list-group">
      <li href="#" class="list-group-item title">
        Requests sent from people!
      </li>
      <?php
      $pdo = Database::connect();
      $sql = "SELECT DISTINCT email, firstName, lastName, profileImage FROM users JOIN friendships ON users.email = friendships.emailFrom OR users.email=friendships.emailTo WHERE (friendships.emailTo= '$loggedInUser') AND users.email!= '$loggedInUser'  AND status='pending';";
      $q = $pdo->prepare($sql);
      $q->execute();
      //echo $sql;
      foreach ($q as $row) {
      $nrFriends = nrOfFriends($row['email'])['COUNT(*)'];
      $nrPhotos = nrOfPhotos($row['email'])['COUNT(*)'];
      $nrComments = nrOfComments($row['email'])['COUNT(*)'];
        echo '<li href="#" class="list-group-item text-left">
          <div class="panel-heading">
            <div class="media">
            <div class="pull-left">
              <img src='.$row['profileImage'].' alt="people" class="media-object img-circle">
            </div>
            <div class="media-body">
              <h4 class="media-heading margin-v-5"><a href="readprofile.php?email='.$row['email'].'">'.$row['firstName'].' '.$row['lastName'].'</a></h4>
              <div class="profile-icons">
              <span><i class="fa fa-users"></i> ' . $nrFriends . '  </span>
              <span><i class="fa fa-photo"></i> ' . $nrPhotos. '</span>
              <span><i class="fa fa-comments"></i> '. $nrComments .'</span>
            </div>
          </div>
          <label class="pull-right">
            <a  class="btn btn-success btn-xs glyphicon glyphicon-ok" href="handleFriendRequest.php?email='.$row['email'].'&action=accepted" title="Accept"></a>
            <a  class="btn btn-danger  btn-xs glyphicon glyphicon-remove" href="handleFriendRequest.php?email='.$row['email'].'&action=denied" title="Reject"></a>
            <a  class="btn btn-info  btn-xs glyphicon glyphicon glyphicon-comment" href="#" title="Send message"></a>
          </label>
          <div class="break"></div>
        </div>
      </div>
      </li>';
      }
      ?>
    </ul>
  </div>


<!--  FRIEND RECOMMENDATIONS SECTION!-->
  <div class="header padding">
    <h3 class="text-muted prj-name">
        <span class="fa fa-users fa-2x principal-title"></span>
        People you may know
    </h3>
  </div>

  <div class="jumbotron list-content">

    <ul class="list-group">
      <li href="#" class="list-group-item title">
        Suggestions...
      </li>
      <?php
      $sql = "SELECT DISTINCT * FROM users WHERE users.email !='$loggedInUser'
       AND ( users.email NOT IN
         ( SELECT users.email
           FROM users
           JOIN friendships ON
           users.email =
           friendships.emailFrom
           OR users.email=friendships.emailTo
           WHERE(
              (friendships.emailFrom= '$loggedInUser'
              OR friendships.emailTo= '$loggedInUser'
              OR users.email LIKE '@ucl.ac.uk')
              AND
              users.email != '$loggedInUser'  ) ));";

      //echo $sql;
      $recommendations = array();
      foreach ($pdo->query($sql) as $row) {
        $row["mutualFriends"] = nrOfMutualFriends($loggedInUser,$row['email']);
        $recommendations[] = $row;
      }

      // sort by mutual friends, so highest to the top
      usort($recommendations, 'compareOrder');

      foreach ($recommendations as $row) {
        $uni = getDomainFromEmail($row['email']);
        if($uni != getDomainFromEmail($loggedInUser) && $row['mutualFriends'] == 0) continue;

        echo '<li href="#" class="list-group-item text-left">
          <div class="panel-heading">
            <div class="media">
            <div class="pull-left">
              <img src='.$row['profileImage'].' alt="people" class="media-object img-circle">
            </div>
            <div class="media-body">
              <h4 class="media-heading margin-v-5"><a href="readprofile.php?email='.$row['email'].'">'.$row['firstName'].' '.$row['lastName'].'</a></h4>
              <div class="profile-icons">';

              if($row['mutualFriends'] != 0){
                echo $row['mutualFriends'] . " mutual friends";
              }else{
                echo "also goes to ". $uni; 

              }

         echo '</div>
          </div>
          <label class="pull-right">
            <a  class="btn btn-success btn-xs glyphicon glyphicon-plus" href="createFriendRequest.php?email='.$row['email'].'" title="Add as friend"></a>
            <a  class="btn btn-info  btn-xs glyphicon glyphicon glyphicon-comment" href="#" title="Send message"></a>
          </label>
          <div class="break"></div>
        </div>
      </div>
      </li>';



      }
      ?>
    </ul>
  </div>

  <div class="header padding">
    <h3 class="text-muted prj-name">
        <span class="fa fa-users fa-2x principal-title"></span>
        Friend zone
    </h3>
  </div>


  <div class="jumbotron list-content">
    <ul class="list-group">
      <li href="#" class="list-group-item title">
        Your friend zone
      </li>
      <?php
      $sql = 'SELECT DISTINCT email, firstName, lastName, profileImage FROM users JOIN friendships ON users.email = friendships.emailFrom OR users.email=friendships.emailTo WHERE (friendships.emailFrom=? OR friendships.emailTo=?) AND users.email!=? AND status=\'accepted\';';
      $q = $pdo->prepare($sql);
      $q->execute(array($loggedInUser,$loggedInUser,$loggedInUser));
      foreach ($q as $row) {
      $nrFriends = nrOfFriends($row['email'])['COUNT(*)'];
      $nrPhotos = nrOfPhotos($row['email'])['COUNT(*)'];
      $nrComments = nrOfComments($row['email'])['COUNT(*)'];
        echo '<li href="#" class="list-group-item text-left">
          <div class="panel-heading">
            <div class="media">
            <div class="pull-left">
              <img src='.$row['profileImage'].' alt="people" class="media-object img-circle">
            </div>
            <div class="media-body">
              <h4 class="media-heading margin-v-5"><a href="readprofile.php?email='.$row['email'].'">'.$row['firstName'].' '.$row['lastName'].'</a></h4>
              <div class="profile-icons">
              <span><i class="fa fa-users"></i> ' . $nrFriends . '  </span>
              <span><i class="fa fa-photo"></i> ' . $nrPhotos. '</span>
              <span><i class="fa fa-comments"></i> '. $nrComments .'</span>
            </div>
          </div>
          <label class="pull-right">
            <a  class="btn btn-success btn-xs glyphicon glyphicon-ok" href="readprofile.php?email='.$row['email'].'" title="View"></a>
            <a  class="btn btn-danger  btn-xs glyphicon glyphicon-trash" href="deleteprofile.php?email='.$row['email'].'" title="Delete"></a>
            <a  class="btn btn-info  btn-xs glyphicon glyphicon glyphicon-comment" href="#" title="Send message"></a>
          </label>
          <div class="break"></div>
        </div>
      </div>
      </li>';
      }
      ?>
    </ul>
  </div>
  </div>
</div>

</body>
</html>


<style type="text/css">
.list-content{
 min-height:300px;
}
.list-content .list-group .title{
  background:#5bc0de;
  border:2px solid #DDDDDD;
  font-weight:bold;
  color:#FFFFFF;
}
.list-group-item img {
    height:80px;
    width:80px;
}

.jumbotron .btn {
    padding: 5px 5px !important;
    font-size: 12px !important;
}
.prj-name {
    color:#5bc0de;
}
.break{
    width:100%;
    margin:20px;
}
.name {
    color:#5bc0de;
}
.padding {
  padding-top: 80px;
}
</style>
