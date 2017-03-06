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
    <div class="">
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
      <div class="control-group">
        <table style="width:100%;">
          <tr>
            <th>Circle</th>
            <th>Action</th>
          </tr>

        <?php
          $accessRightsQuery = "SELECT * FROM circleOfFriends JOIN accessRights ON circleOfFriends.circleFriendsId = accessRights.circleFriendsId WHERE photoCollectionId =" .$argument1;
          // echo $accessRightsQuery;
          $count = 0;
          foreach($pdo->query($accessRightsQuery) as $circlesJoined){

            echo "<tr>";
            echo "<td>" .$circlesJoined['circleOfFriendsName'] . "</td>";
            echo "<td> <a class='table-btn btn btn-danger' href='../accessRights/disassociate.php?circleId=".$circlesJoined["circleFriendsId"]. "&email=" . $userQueryResult['email'] ."'> <i class='fa fa-pencil' aria-hidden='true'></i> Deassociate   </a> </td>";
            echo "</tr>";

            $count++;
          }
        ?>
        </table>
      </div>
      <label class="control-label"> doesn't belong to .. </label><br>
      <div class="control-group">
        <table style="width:100%;">
          <tr>
            <th>Circle</th>
            <th>Action</th>
          </tr>

        <?php
        $accessRightsQuery = "SELECT * FROM circleOfFriends JOIN accessRights ON circleOfFriends.circleFriendsId != accessRights.circleFriendsId WHERE photoCollectionId =" .$argument1;
          // echo $accessRightsQuery;
          $count = 0;
          foreach($pdo->query($accessRightsQuery) as $circlesJoined){


            echo "<tr>";
            echo "<td>" .$circlesJoined['circleOfFriendsName'] . "</td>";
            echo "<td> <a class='table-btn btn btn-info' href='../accessRights/disassociate.php?circleId=".$circlesJoined["circleFriendsId"]. "&email=" . $userQueryResult['email'] ."'> <i class='fa fa-pencil' aria-hidden='true'></i> Associate </a> </td>";
            echo "</tr>";

            $count++;
          }
        ?>
        </table>
      </div>

      </div>



      <button style="margin-top:10px;" class="btn btn-success" type="submit">Update Access Rights</button>
    </form>
      </div>
    </div>
    </div>

<?php Database::disconnect(); ?>
</body>
