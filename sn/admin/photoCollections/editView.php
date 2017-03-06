<?php
  //require '../database.php';
  $title = "Bookface Social Network";
  $description = "A far superior social network";
  include("../../inc/header.php");
  INCLUDE("../../inc/nav-trn.php");



  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $argument1 = $_GET['pcId'];

  $sql = "SELECT * FROM photoCollection WHERE photoCollectionId=" .$argument1;
  //echo $sql;
  $q= $pdo->prepare($sql);
  $q->execute();
  $row = $q->fetch(PDO::FETCH_ASSOC);

?>

<body>
  <div class="container">

      <div class="row">
      <h1 style="margin-bottom: 15px;"> Update Photo Collections</h1>
      <h2> General </h2>
      <form class="form-horizontal" method="POST" action="edit.php">
        <input type="hidden" name="photoCollectionId" value="<?php echo $argument1;?>">
        <div id="blogTitleBar">
          <div class="control-group">
            <label class="control-label">Photo Collection Name:</label>
            <div class="controls">
              <input type="text" name="albumName" style="width: 30vw;"value="<?php echo $row['title'];?>"> <br>
            </div>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Photo Collection Description:  </label><br>
          <input type="text" name="albumDescription" style="width: 30vw;"value="<?php echo $row['description'];?>"> <br>

        </div>
        <button style="margin-top:10px;" class="btn btn-success" type="submit">Save</button>
      </form>

    <h2> Privacy Settings </h2>
      <label class="control-label"> belongs to .. </label><br>

        <table class="table table-stripped table-bordered">
          <tr>
            <th>Circle</th>
            <th style="text-align:right;">Action</th>
          </tr>

        <?php
          $accessRightsQuery = "SELECT * FROM circleOfFriends JOIN accessRights ON circleOfFriends.circleFriendsId = accessRights.circleFriendsId WHERE photoCollectionId =" .$argument1;
          // echo $accessRightsQuery;
          $count = 0;
          foreach($pdo->query($accessRightsQuery) as $circlesJoined){
            $circleId = $circlesJoined['circleFriendsId'];

            echo "<tr>";
            echo "<td>" .$circlesJoined['circleOfFriendsName'] . "</td>";
            echo "<td style='text-align:right;'> <a class='table-btn btn btn-danger' href='../accessRights/deassociateCircle.php?pcId=$argument1&circleId=$circleId' > <i class='fa fa-pencil' aria-hidden='true'></i> Deassociate   </a> </td>";
            echo "</tr>";

            $count++;
          }
        ?>
        </table>

      <label class="control-label"> doesn't belong to .. </label><br>

        <table class="table table-stripped table-bordered">
          <tr>
            <th>Circle</th>
            <th style='text-align:right;'>Action</th>
          </tr>

        <?php
        $accessRightsQuery = "SELECT * FROM circleOfFriends WHERE circleFriendsId NOT IN (SELECT circleOfFriends.circleFriendsId FROM circleOfFriends JOIN accessRights ON circleOfFriends.circleFriendsId = accessRights.circleFriendsId WHERE photoCollectionId =" .$argument1 . ")";
  // echo $accessRightsQuery;
          $count = 0;
          foreach($pdo->query($accessRightsQuery) as $circlesJoined){

            $circleId = $circlesJoined['circleFriendsId'];

            echo "<tr>";
            echo "<td>" .$circlesJoined['circleOfFriendsName'] . "</td>";
            echo "<td style='text-align:right;'> <a class='table-btn btn btn-info' href='../accessRights/associateCircle.php?pcId=$argument1&circleId=$circleId'> <i class='fa fa-pencil' aria-hidden='true'></i> Associate </a> </td>";
            echo "</tr>";

            $count++;
          }
        ?>
        </table>

      <label class="control-label"> Users that can add to collection .. </label><br>
        <table class="table table-stripped table-bordered">
          <tr>
            <th>User</th>
            <th style='text-align:right;'>Action</th>
          </tr>

        <?php
        $accessRightsQuery = "SELECT * FROM users JOIN accessRights ON users.email = accessRights.email WHERE photoCollectionId =" .$argument1;
          // echo $accessRightsQuery;
          $count = 0;
          foreach($pdo->query($accessRightsQuery) as $pplJoined){
            $userQuery = "SELECT * FROM users where email='". $pplJoined["email"] . "'";

            $y = $pdo->prepare($userQuery);
            $y->execute();
            $userQueryResult = $y->fetch(PDO::FETCH_ASSOC);

            $email = $userQueryResult['email'];
            echo "<tr>";
            echo "<td>" .$userQueryResult['firstName'] . " " . $userQueryResult['lastName'] . "</td>";
            echo "<td style='text-align:right;' > <a class='table-btn btn btn-danger' href='../accessRights/deassociatePerson.php?pcId=$argument1&email=$email' > <i class='fa fa-pencil' aria-hidden='true'></i> Deassociate   </a> </td>";
            echo "</tr>";

            $count++;
          }
        ?>
        </table>

      <label class="control-label"> Users that can add to collection .. </label><br>
        <table class="table table-stripped table-bordered">
          <tr>
            <th>User</th>
            <th style='text-align:right;'>Action</th>
          </tr>

        <?php
        $accessRightsQuery = "SELECT * FROM USERS WHERE email NOT IN (SELECT users.email FROM users JOIN accessRights ON users.email = accessRights.email WHERE photoCollectionId =" .$argument1 . ")";
          $count = 0;
          foreach($pdo->query($accessRightsQuery) as $pplJoined){
            $userQuery = "SELECT * FROM users where email='". $pplJoined["email"] . "'";

            $y = $pdo->prepare($userQuery);
            $y->execute();
            $userQueryResult = $y->fetch(PDO::FETCH_ASSOC);


            echo "<tr>";
            echo "<td>" .$userQueryResult['firstName'] . " " . $userQueryResult['lastName'] . "</td>";
            echo "<td style='text-align:right;' > <a class='table-btn btn btn-info' href='../accessRights/associatePerson.php?pcId=".$argument1. "&email=" . $userQueryResult['email'] ."'> <i class='fa fa-pencil' aria-hidden='true'></i> Associate </a> </td>";
            echo "</tr>";

            $count++;
          }
        ?>
        </table>





    </div>

<?php Database::disconnect(); ?>
</body>
