<!-- Similar to messenger.com, left navigation displays list of users in a circle, rest of page dedicated to chat -->
<?php 
include("../inc/header.php");
include("../inc/nav-trn.php");
?>
<?php 
//no need for require database.php as we've already included header.php
  $circleId = $_GET['circleFriendsId']; //get ID for the circle chat that we need
  $getCircleMembers = "SELECT firstName, lastName, MyDB.users.email FROM MyDB.circleOfFriends INNER JOIN MyDB.userCircleRelationships ON MyDB.circleOfFriends.circleFriendsId=MyDB.userCircleRelationships.circleFriendsId INNER JOIN MyDB.users ON MyDB.userCircleRelationships.email=MyDB.users.email WHERE(circleOfFriends.circleFriendsId=" . $circleId . ")";
  //above query gets the first, last name and email for each member in the given circle by joining circle id between UserCircleRelationships with CircleOfFriends and also joining emails between userCircleRelationships with Users
  
  //echo $getCircleMembers;

  $pdo=Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  foreach($pdo->query($getCircleMembers) as $row){ //for each member
    $firstName = $row["firstName"];
    $lastName = $row["lastName"];
    $email = $row["email"];
    //debug for showing each member
    echo $firstName . " " . $lastName . ", " . $email;
    echo "<br>";
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
  <div class="container">
    <div class="row">

      <!--  List of friends in circle-->
      <div class="col-md-3">

        <div class="media">
          <div class="media-left">
              <img class="media-object" src="..." alt="Friend1 profile pic">
          </div>
          <div class="media-body">
            <h4 class="media-heading">Friend1 in circle</h4>
          </div>
        </div>

        <div class="media">
          <div class="media-left">
              <img class="media-object" src="..." alt="Friend2 profile pic">
          </div>
          <div class="media-body">
            <h4 class="media-heading">Friend2 in circle</h4>
          </div>
        </div>

      </div>

      <!--  Chat pane-->
      <div class="col-md-9">
        <form class="" action="index.html" method="post">
          <textarea name="name" rows="8" cols="80"></textarea>
          <button type="button" name="button">Send</button>
        </form>
      </div>
    </div>
    </div>
  </body>
</html>
