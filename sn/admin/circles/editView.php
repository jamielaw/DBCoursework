<?php
  //require '../database.php';
  $title = "Bookface Social Network";
  $description = "A far superior social network";
  include("../../inc/header.php");
  INCLUDE("../../inc/nav-trn.php");

  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $argument1 = $_GET['circleId'];

  $sql = "SELECT * FROM circleOfFriends WHERE circleFriendsId=" .$argument1;
  //echo $sql;
  $q= $pdo->prepare($sql);
  $q->execute();
  $row = $q->fetch(PDO::FETCH_ASSOC);

?>

<body>
  <div class="container">
    <div class="">
      <div class="row">
        <h1 style="margin-bottom: 15px;"> Update Circle Friends </h1>

      <form class="form-horizontal" method="POST" action="edit.php">
        <input type="hidden" name="circleFriendsId" value="<?php echo $argument1;?>">
        <div id="blogTitleBar">
          <div class="control-group">
            <label class="control-label">Circle Name:</label>
            <div class="controls">
              <input type="text" name="circleOfFriendsName" style="width: 30vw;"value="<?php echo $row['circleOfFriendsName'];?>"> <br>
            </div>
          </div>
        </div>
        <div class="">
          <label class="control-label">Members of this circle:</label>
          <br>
          <table style="width=100%;">
          <tr>
            <th> Name </th>
            <th> Delete </th>
          </tr>
<?php
          // echo $argument1;
          $circleRelations = "SELECT * FROM userCircleRelationships where circleFriendsId =". $argument1;
          foreach ($pdo->query($circleRelations) as $members) {
            $userQuery = "SELECT * FROM users where email='". $members["email"] . "'";

            $y = $pdo->prepare($userQuery);
            $y->execute();
            $userQueryResult = $y->fetch(PDO::FETCH_ASSOC);

            echo "<tr>";
            echo "<td>";
            echo "<a href='/sn/profile/readprofile.php?email=". $userQueryResult ['email'] . "'>" . $userQueryResult['firstName'] . "  " . $userQueryResult['lastName'] . "</a><br>";
            echo "</td>";
            echo "<td> <a class='table-btn btn btn-danger' href='deleteMember.php?circleId=".$row["circleFriendsId"]. "&email=" . $userQueryResult['email'] ."'> <i class='fa fa-trash' aria-hidden='true'></i> Delete  </a></td>";
            echo "</tr>";
          }
?>
          </table>

          <label class="control-label">Members of this circle:</label>
          <br>
          <table style="width=100%;">
          <tr>
            <th> Name </th>
            <th> Add </th>
          </tr>
<?php
          // echo $argument1;
          //$circleRelations = "SELECT * FROM userCircleRelationships where circleFriendsId =". $argument1;
          $nonMembers = "SELECT * FROM USERS WHERE email NOT IN ( SELECT email from userCircleRelationships WHERE userCircleRelationships.circleFriendsId = $argument1) ";
          //echo $nonMembers;
          foreach ($pdo->query($nonMembers) as $members) {
            $userQuery = "SELECT * FROM users where email='". $members["email"] . "'";

            $y = $pdo->prepare($userQuery);
            $y->execute();
            $userQueryResult = $y->fetch(PDO::FETCH_ASSOC);

            echo "<tr>";
            echo "<td>";
            echo "<a href='/sn/profile/readprofile.php?email=". $userQueryResult ['email'] . "'>" . $userQueryResult['firstName'] . "  " . $userQueryResult['lastName'] . "</a><br>";
            echo "</td>";
            echo "<td> <a class='table-btn btn btn-info' href='addMember.php?circleId=".$row["circleFriendsId"]. "&email=" . $userQueryResult['email'] ."'> <i class='fa fa-trash' aria-hidden='true'></i> Add  </a></td>";
            echo "</tr>";
          }
?>
          </table>

        </div>
        <button style="margin-top:10px;" class="btn btn-success" type="submit">Save</button>
      </form>
      </div>
    </div>
    </div>

<?php Database::disconnect(); ?>
</body>
