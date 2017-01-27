<!-- Displays friend circles, each friend circle is linked to a chat page,
also has link to creating friend circles -->
<?php
$title = "Friendship Circles";
$description = "";
//CHANGE THIS TO BE AUTHENTICATED LATER
$loggedInUser="charles@ucl.ac.uk";
include("../inc/header.php");
 ?>
  <body>
    <!-- to do: split circles that currently logged in user is in, and others (as well as join/leave options). 
    implement messaging
    add friends to circle in create circle -->
    <!--  Navigation-->
    <?php include '../inc/nav-trn.php'; ?>
    <div class="container">
    <div class="blog-container">
      <div class="row">
        <font size="10"> Circles </font> <br>
        <font size="3"> You can view your circles and other circles here. </font>
      </div>

      <div class="row">

        <div class="blog-section create-blog">
          <a href="createcircleview.php"> <i class="glyphicon glyphicon-plus"></i> Create a new circle </a>
        </div>

        <p>
          Your Circles:
        </p>

        <div class="blog-section">
        <?php 
        $pdo=Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $personalCirclesQuery="SELECT * FROM MyDB.circleOfFriends INNER JOIN MyDB.userCircleRelationships ON MyDB.circleOfFriends.circleFriendsId=MyDB.userCircleRelationships.circleFriendsId WHERE(email='". $loggedInUser . "')";

        echo "<table style='width:100%; text-align:center;'> <tr><th><center> Circle of Friends ID </center></th> <th><center> Circle of Friends Name </center></th> <th><center> Date created </center></th> <th><center> Action </center></th></tr> ";
        foreach ($pdo->query($personalCirclesQuery) as $row)  {
            echo "<tr>";
            echo "<td>" . $row["circleFriendsId"] . "</td><td> " . $row["circleOfFriendsName"] . "</td><td>" . $row["dateCreated"]  . "</td><td> Leave </td>";
            echo "</tr>";
        }

        echo "</table>";
        Database::disconnect();
        ?>
        </div>
        <p>
          Other Circles:
        </p>
        <div class="blog-section">
          <?php 
            $pdo=Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $impersonalCirclesQuery= "SELECT * FROM MyDB.circleOfFriends INNER JOIN MyDB.userCircleRelationships ON MyDB.circleOfFriends.circleFriendsId=MyDB.userCircleRelationships.circleFriendsId WHERE(email!='". $loggedInUser . "')";

            echo "<table style='width:100%'> <tr> <th><center> Circle of Friends ID </center></th> <th><center> Circle of Friends Name </center></th> <th><center> Date created </center></th> <th><center> Action </center></th> ";
            foreach ($pdo->query($impersonalCirclesQuery) as $row)  {
                echo "<tr>";
                echo "<td>" . $row["circleFriendsId"] . "</td><td> " . $row["circleOfFriendsName"] . "</td><td>" . $row["dateCreated"]  . "</td><td> join </td>";
                echo "</tr>";
            }

            echo "</table>";
            Database::disconnect();
          ?>
        </div>
      </div>
    </div>
  </div>



    <!-- Footer  -->
    <?php include '../inc/footer.php'; ?>
