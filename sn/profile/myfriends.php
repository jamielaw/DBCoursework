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
?>

<!DOCTYPE html>
<html lang="en">
<head>

<body>
<link href="http://getbootstrap.com/examples/jumbotron-narrow/jumbotron-narrow.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">
<div class="container bootstrap snippet">

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
      include '..\database.php';
      $pdo = Database::connect();
      // !!! HARDCODED STUFF -  TO BE CHANGED AFTER LOGIN IS IMPLEMENTED
      $sql = 'SELECT DISTINCT email, firstName, lastName, profileImage FROM users JOIN friendships ON users.email = friendships.emailFrom OR users.email=friendships.emailTo WHERE (friendships.emailFrom=\'charles@ucl.ac.uk\' OR friendships.emailTo=\'charles@ucl.ac.uk\') AND users.email!=\'charles@ucl.ac.uk\' AND status=\'accepted\';';
      foreach ($pdo->query($sql) as $row) {
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
              <h4 class="media-heading margin-v-5"><a href="#">'.$row['firstName'].' '.$row['lastName'].'</a></h4>
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