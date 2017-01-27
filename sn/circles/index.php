<!-- Displays friend circles, each friend circle is linked to a chat page,
also has link to creating friend circles -->

<?php
$title = "Friendship Circles";
$description = "";
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
        <font size="3"> You can view your circles and your friends' circles here. </font>
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
        //CHANGE THIS TO BE AUTHENTICATED LATER
        $loggedInUser="charles@ucl.ac.uk";
        $pdo=Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $personalCirclesQuery="SELECT * FROM MyDB.circleOfFriends INNER JOIN MyDB.userCircleRelationships ON MyDB.circleOfFriends.circleFriendsId=MyDB.userCircleRelationships.circleFriendsId WHERE(email='". $loggedInUser . "')";

        echo "<table style='width:100%; text-align:center;'> <tr><th><center> Circle of Friends Name </center></th> <th><center> Members </center></th> <th><center> Date created </center></th> <th><center> Action </center></th></tr> ";
        foreach ($pdo->query($personalCirclesQuery) as $row)  {
            $countMembers = "SELECT COUNT(email) FROM MyDB.circleOfFriends INNER JOIN MyDB.userCircleRelationships ON MyDB.circleOfFriends.circleFriendsId=MyDB.userCircleRelationships.circleFriendsId WHERE MyDB.circleOfFriends.circleFriendsId=" . $row["circleFriendsId"];
            //echo $countMembers;
            $y = $pdo->query($countMembers);
            $countResults = $y->fetch(PDO::FETCH_ASSOC);
            echo "<tr>";
            echo "<td>" . $row["circleOfFriendsName"] . "</td><td>" . $countResults["COUNT(email)"] . "</td><td>". $row["dateCreated"]  . "</td><td> <i class=\"fa fa-comments\"></i> Message / <i class=\"fa fa-user-plus\"></i> Invite / <i class=\"fa fa-sign-out\"></i> Leave </td>";
            echo "</tr>";
        }

        echo "</table>";
        Database::disconnect();
        ?>
        </div>

        <p>
          Other circles that your friends are in:
        </p>
        <div class="blog-section">
          <?php 
            $pdo=Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $impersonalCirclesQuery= "SELECT * FROM MyDB.circleOfFriends INNER JOIN MyDB.userCircleRelationships ON MyDB.circleOfFriends.circleFriendsId=MyDB.userCircleRelationships.circleFriendsId WHERE(email IN (SELECT emailTo FROM MyDB.friendships WHERE (emailFrom='" . $loggedInUser . "' AND status='accepted')) OR email IN (SELECT emailFrom FROM MyDB.friendships WHERE ( emailTo='". $loggedInUser . "' AND status='accepted'))) GROUP BY MyDB.circleOfFriends.circleFriendsId";

            //echo $impersonalCirclesQuery;

            /*lets run through the above query as it's quite complex to understand:
            firstly, we join the circle of friends, and user circle relationships together by their ID in order to get the list of all members of a circle
            we then filter it by making sure the email of the member is a FRIEND of the logged in user (this could be a friend request that the logged in user sent to the user, or vice versa)
            finally, we group it by ID to avoid duplicate entries of circles if there are multiple friends in one circle 
            */

            echo "<table style='width:100%'> <tr> <th><center> Circle of Friends Name </center></th> <th><center> Members </center></th> <th><center> Date created </center></th> <th><center> Action </center></th> ";
            foreach ($pdo->query($impersonalCirclesQuery) as $row)  {
                $countMembers = "SELECT COUNT(email) FROM MyDB.circleOfFriends INNER JOIN MyDB.userCircleRelationships ON MyDB.circleOfFriends.circleFriendsId=MyDB.userCircleRelationships.circleFriendsId WHERE MyDB.circleOfFriends.circleFriendsId=" . $row["circleFriendsId"];
                //echo $countMembers;
                $y = $pdo->query($countMembers);
                $countResults = $y->fetch(PDO::FETCH_ASSOC);
                echo "<tr>";
                echo "<td>" . $row["circleOfFriendsName"] . "</td><td>" . $countResults["COUNT(email)"] . "</td><td>". $row["dateCreated"]  . "</td><td> <i class=\"fa fa-sign-in\"></i> Join </td>";
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
