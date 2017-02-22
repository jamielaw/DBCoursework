<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <!--  Navigation-->
    <?php include '../inc/header.php'; 
    include '../inc/nav-trn.php';
     ?>
    <title>Search Results</title>
  </head>
  <body>

      <!--php for search-->
      <?php 
      //require("../database.php");
      $name=htmlspecialchars($_GET['submit']);
      //echo "<h1>Search results for first/last name matching with: ". $name ."</h1>";
       if(isset($name)){ 
          if(preg_match("/^[  a-zA-Z]+/", $_GET['submit'])){ //check search string isn't empty
            //connect  to the database 
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //-query  the database table 
            //currently searches and matches IF it correlates correctly with a (possibly ENDING unfinished, has to start with the correct character) first or last name, see examples below
            //examples that work: "ada", "ada l", "lovelace", "love" would all return ada lovelace
            //examples that don't work: "ad lovelace", "da lovelace", "ovelace"

            //search result can only be friends to logged-in user, or friends-of-friends (mutual friends) as per the specs

            //firstly, we get the friends of the logged-in user and prepare it for use in the main sql search query
            $getFriends="SELECT email FROM MyDB.users WHERE(email IN (SELECT emailTo FROM MyDB.friendships WHERE (emailFrom='" . $loggedInUser . "' AND status='accepted')) OR email IN (SELECT emailFrom FROM MyDB.friendships WHERE ( emailTo='". $loggedInUser . "' AND status='accepted')))";

            $countFriends="SELECT COUNT(email) FROM MyDB.users WHERE(email IN (SELECT emailTo FROM MyDB.friendships WHERE (emailFrom='" . $loggedInUser . "' AND status='accepted')) OR email IN (SELECT emailFrom FROM MyDB.friendships WHERE ( emailTo='". $loggedInUser . "' AND status='accepted')))";

            $q = $pdo->query($countFriends);
            $countFriendsResult = $q->fetch(PDO::FETCH_ASSOC);
            $countF = $countFriendsResult["COUNT(email)"];

            $currentFriend = 0; //keep track of how many friends there are to keep the correct formatting
            $friends[] .= "("; //this friends array stores the list of friends to the logged-in-user in a format which is easy to use in the sql query

            foreach($pdo->query($getFriends) as $row){
              $currentFriend += 1;
              if($currentFriend==$countF){ //last friend
                $friends[] .= "'" . $row["email"] . "'";
              }else{
                $friends[] .= "'" . $row["email"] . "',";
              }
            }
            $friends[] .= ")";

            $friendStr = implode($friends);

            //echo $friendStr;

            //get number of search results, similar to search query below except we're just selecting the count. explanation of how this query works is defined below
            $countQuery = "SELECT COUNT(email) FROM MyDB.users WHERE ((firstName LIKE '" . $name .  "%' OR lastName LIKE '" . $name ."%' OR concat_ws(' ', firstName, lastName) LIKE '" . $name . "%') AND (email IN (SELECT emailTo FROM MyDB.friendships WHERE (emailFrom='" . $loggedInUser . "' AND status='accepted')) OR email IN (SELECT emailFrom FROM MyDB.friendships WHERE ( emailTo='". $loggedInUser . "' AND status='accepted')) OR email IN (SELECT emailFrom FROM MyDB.friendships WHERE ( emailTo='". $loggedInUser . "' AND status='accepted')) OR email IN (SELECT emailTo FROM MyDB.friendships WHERE (emailFrom IN " .$friendStr . ") OR email IN (SELECT emailFrom FROM MyDB.friendships WHERE(emailTo IN " . $friendStr . ")))))"; 
            $y = $pdo->query($countQuery);
            $countResults = $y->fetch(PDO::FETCH_ASSOC);
            $count = $countResults["COUNT(email)"]; //extract the integer value from results
            if($count==1){ //extra points for being finickity
              echo "<h1>" . $countResults["COUNT(email)"] . " result found for first/last name matching with: <i>" . $name . "</i></h1>";
            }else{
              echo "<h1>" . $countResults["COUNT(email)"] . " results found for first/last name matching with: <i>" . $name . "</i></h1>";
            }

            //get search results here, query works by getting a matching query, then AND-ing it with (a friend of logged in user OR a friend-of-friend of logged in user)
            $searchQuery="SELECT email, firstName, lastName, profileImage FROM MyDB.users WHERE 
            ((firstName LIKE '" . $name .  "%' OR lastName LIKE '" . $name ."%' OR concat_ws(' ', firstName, lastName) LIKE '" . $name . "%') 
            AND 
            (email IN (SELECT emailTo FROM MyDB.friendships WHERE (emailFrom='" . $loggedInUser . "' AND status='accepted')) 
            OR email IN (SELECT emailFrom FROM MyDB.friendships WHERE ( emailTo='". $loggedInUser . "' AND status='accepted')) 
            OR email IN (SELECT emailTo FROM MyDB.friendships WHERE (emailFrom IN " .$friendStr . ") 
            OR email IN (SELECT emailFrom FROM MyDB.friendships WHERE(emailTo IN " . $friendStr . ")
            ))))"; 
            //echo $searchQuery;


            if($count>0){ //only display table if 1 or more results
              echo "<table style='width:100%'> <tr> <th> Email </th> <th> First name </th> <th> Last Name </th> <th> Image </th> <th> Go to profile </th>";
              //- loop through result set 
              foreach($pdo->query($searchQuery) as $row){
                  //-display the result of the array 
                  echo "<tr>"; 
                  echo "<td>" . $row["email"] . "</td><td> " . $row["firstName"] . "</td><td>" . $row["lastName"]  . "</td><td> <img style='height:100px;width=100px;' src='" . $row["profileImage"] . "'</td>" . "<td><a href=\"../profile/readprofile.php?email=" . $row["email"] . "\">View profile</a></td>";
                  echo "</tr>"; 
                  }

              echo "</table>";
            }
          } 
          else{ 
            echo  "<p>Please enter a valid search query</p>"; //this seems to be triggered when user inputs a query like <b>test, no big deal though because a query like that is invalid as it is
          }  
        }
      Database::disconnect(); 
    ?>
  <!-- Footer  -->
  <?php include '../inc/footer.php'; ?>
  </body>
</html>
