<!-- Displays friend circles, each friend circle is linked to a chat page,
also has link to creating friend circles -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Circles</title>
    <?php 
    include("../inc/header.php");
    include("../inc/nav-trn.php"); 
    ?>
</head>
  <body>
    <div class="container">
    <div class="blog-container">
      <div class="row">
        <font size="10"> Circles </font> <br>
        <font size="3"> You can view your circles and your friends' circles here. Hover over the 'members' link on a circle to see the list of members. </font>
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

        //echo "<table style='width:100%; text-align:center;'> <tr><th><center> Circle of Friends Name </center></th> <th><center> Members </center></th> <th><center> Date created </center></th> <th><center> Action </center></th></tr> ";
        $numberOfCircles=0;
        foreach ($pdo->query($personalCirclesQuery) as $row) {
            $numberOfCircles+=1;
            $id = $row["circleFriendsId"];

            //count members in circle
            $countMembers = "SELECT COUNT(email) FROM MyDB.circleOfFriends INNER JOIN MyDB.userCircleRelationships ON MyDB.circleOfFriends.circleFriendsId=MyDB.userCircleRelationships.circleFriendsId WHERE MyDB.circleOfFriends.circleFriendsId=" . $id;
            //echo $countMembers;
            $y = $pdo->query($countMembers);
            $countResults = $y->fetch(PDO::FETCH_ASSOC);

            //get members names in circle
            $memberList = array();
            $getMembers = "SELECT firstName, lastName, users.email FROM MyDB.users INNER JOIN MyDB.userCircleRelationships ON MyDB.users.email=MyDB.userCircleRelationships.email WHERE MyDB.userCircleRelationships.circleFriendsId=" . $id;
            $currentMember = 0;
            foreach ($pdo->query($getMembers) as $eachMember){
                //we know how many members there are in the circle already with the variable $countResults
                $currentMember++;
                $memberList[] = $eachMember["firstName"] . " " . $eachMember["lastName"];
                if($eachMember["email"]==$loggedInUser){
                    $memberList[] .= " (You)";
                }
                if($currentMember!=$countResults["COUNT(email)"]){ //non-final member in member list, need to append comma
                    $memberList[] .= ", ";
                }
            }
            
            //echo "<tr>";
            echo "<div class=\"col-md-6 col-sm-12 col-lg-3 blog-section friend-post-container\">";
            echo "<div class=\"blog-title\" style=\"font-style:normal;\">";
            echo "<b>" . $row["circleOfFriendsName"] . "</b>";
            echo "<font size=1>";
            echo "<br>";
            echo "<a href=\"#\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"". implode($memberList) . "\" id=\"circle". $id . "\">Members: " . $countResults["COUNT(email)"]; //implode converts array to string
            //echo "Members: " . $countResults["COUNT(email)"];
            echo "<br>";
            echo "</font>";
                    //<a title=\"Add friends\" href=\"/sn/circles/invite.php?circleFriendsId=" . $id . "\"<i class=\"fa fa-user-plus\"></i></a> &nbsp; removed this option as it wasn't in the specs
                    echo "<a title=\"Circle chat\" href=\"../profile/messages.php\"<i class=\"fa fa-comments\"></i></a>  &nbsp; <a onClick=\"javascript: return confirm('Are you sure you want to leave this circle? If you are the last person in this circle, the circle will also be deleted.');\" title=\"Leave circle\" href=\"/sn/circles/leavecircle.php?circleFriendsId=" . $id . "\"<i class=\"fa fa-sign-out\"></i></a>";
            echo "</div>";
            echo "</div>";
            //echo "<td>" . $row["circleOfFriendsName"] . "</td><td>" . $countResults["COUNT(email)"] . "</td><td>". $row["dateCreated"]  . "</td><td> <i class=\"fa fa-comments\"></i> Message / <i class=\"fa fa-user-plus\"></i> Invite / <i class=\"fa fa-sign-out\"></i> Leave </td>";
            //echo "</tr>";
        }
        if ($numberOfCircles==0) {
            echo "You're not part of any circles";
        }

        //echo "</table>";
        Database::disconnect();
        ?>
        </div>

        <p>
          Other circles that your friends are in (and you are not a part of):
        </p>
        <div class="blog-section">
          <?php
            $pdo=Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $numberOfCircles=0;
            $impersonalCirclesQuery= "SELECT * FROM MyDB.circleOfFriends INNER JOIN MyDB.userCircleRelationships ON MyDB.circleOfFriends.circleFriendsId=MyDB.userCircleRelationships.circleFriendsId WHERE((email IN (SELECT emailTo FROM MyDB.friendships WHERE (emailFrom='" . $loggedInUser . "' AND status='accepted')) OR email IN (SELECT emailFrom FROM MyDB.friendships WHERE ( emailTo='". $loggedInUser . "' AND status='accepted'))) AND MyDB.circleOfFriends.circleFriendsId NOT IN (SELECT circleOfFriends.circleFriendsId FROM MyDB.circleOfFriends INNER JOIN MyDB.userCircleRelationships ON MyDB.circleOfFriends.circleFriendsId=MyDB.userCircleRelationships.circleFriendsID WHERE email='" . $loggedInUser . "')) GROUP BY MyDB.circleOfFriends.circleFriendsId";

            //echo $impersonalCirclesQuery;

            /*lets run through the above query as it's quite complex to understand:
            firstly, we join the circle of friends, and user circle relationships together by their ID in order to get the list of all members of each circle
            we then filter it by making sure the email of the member is a FRIEND of the logged in user (this could be a friend request that the logged in user sent to the user, OR vice versa)
            we then filter it again by making sure that the id of these circles are not part of the circles that the logged in user is part of
            finally, we group it by ID to avoid duplicate entries of circles if there are multiple friends in one circle
            */

            //echo "<table style='width:100%'> <tr> <th><center> Circle of Friends Name </center></th> <th><center> Members </center></th> <th><center> Date created </center></th> <th><center> Action </center></th> ";
            foreach ($pdo->query($impersonalCirclesQuery) as $row) {
                $numberOfCircles+=1;
                $id = $row["circleFriendsId"];
                //count members in circle
                $countMembers = "SELECT COUNT(email) FROM MyDB.circleOfFriends INNER JOIN MyDB.userCircleRelationships ON MyDB.circleOfFriends.circleFriendsId=MyDB.userCircleRelationships.circleFriendsId WHERE MyDB.circleOfFriends.circleFriendsId=" . $id;
                $y = $pdo->query($countMembers);
                $countResults = $y->fetch(PDO::FETCH_ASSOC);
                //echo $countMembers;

                //get members names in circle
                $memberList = array();
                $getMembers = "SELECT firstName, lastName FROM MyDB.users INNER JOIN MyDB.userCircleRelationships ON MyDB.users.email=MyDB.userCircleRelationships.email WHERE MyDB.userCircleRelationships.circleFriendsId=" . $id;
                $currentMember = 0;
                foreach ($pdo->query($getMembers) as $eachMember){
                    //we know how many members there are in the circle already with the variable $countResults
                    $currentMember++;
                    if($currentMember==$countResults["COUNT(email)"]){ //final member in member list
                        $memberList[] = $eachMember["firstName"] . " " . $eachMember["lastName"];
                    }else{ //non-final member in member list
                        $memberList[] = $eachMember["firstName"] . " " . $eachMember["lastName"] . ", ";
                    }
                }

    
                //echo "<tr>";
                echo "<div class=\"col-md-6 col-sm-12 col-lg-3 blog-section friend-post-container\">";
                echo "<div class=\"blog-title\" style=\"font-style:normal;\">";
                echo "<b>" . $row["circleOfFriendsName"] . "</b>";
                echo "<font size=1>";
                echo "<br>";
                echo "<a href=\"#\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"". implode($memberList) . "\" id=\"circle". $id . "\">Members: " . $countResults["COUNT(email)"];
                //echo "Members: " . $countResults["COUNT(email)"];
                echo "<br>";
                echo "</font>";
                echo "<a title=\"Join circle\" href=\"/sn/circles/joincircle.php?circleFriendsId=" . $id . "\"<i class=\"fa fa-sign-in\"></i></a>";
                echo "</div>";
                echo "</div>";
                //echo "<td>" . $row["circleOfFriendsName"] . "</td><td>" . $countResults["COUNT(email)"] . "</td><td>". $row["dateCreated"]  . "</td><td> <i class=\"fa fa-sign-in\"></i> Join </td>";
                //echo "</tr>";
            }
            if ($numberOfCircles==0) {
                echo "None of your friends are part of a circle that you aren't in";
            }

            //echo "</table>";
            Database::disconnect();
          ?>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

    <!-- Footer  -->
    <?php include '../inc/footer.php'; ?>
<script>
$(document).ready(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>
