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

            //get number of search results
            $countQuery = "SELECT COUNT(email) FROM MyDB.users WHERE ((firstName LIKE '" . $name .  "%' OR lastName LIKE '" . $name ."%' OR concat_ws(' ', firstName, lastName) LIKE '" . $name . "%') AND (email IN (SELECT emailTo FROM MyDB.friendships WHERE (emailFrom='" . $loggedInUser . "' AND status='accepted')) OR email IN (SELECT emailFrom FROM MyDB.friendships WHERE ( emailTo='". $loggedInUser . "' AND status='accepted'))))"; 
            $y = $pdo->query($countQuery);
            $countResults = $y->fetch(PDO::FETCH_ASSOC);
            $count = $countResults["COUNT(email)"]; //extract the integer value from results
            if($count==1){ //extra points for being finickity
              echo "<h1>" . $countResults["COUNT(email)"] . " result found for first/last name matching with: <i>" . $name . "</i></h1>";
            }else{
              echo "<h1>" . $countResults["COUNT(email)"] . " results found for first/last name matching with: <i>" . $name . "</i></h1>";
            }

            $searchQuery="SELECT email, firstName, lastName, profileImage FROM MyDB.users WHERE ((firstName LIKE '" . $name .  "%' OR lastName LIKE '" . $name ."%' OR concat_ws(' ', firstName, lastName) LIKE '" . $name . "%') AND (email IN (SELECT emailTo FROM MyDB.friendships WHERE (emailFrom='" . $loggedInUser . "' AND status='accepted')) OR email IN (SELECT emailFrom FROM MyDB.friendships WHERE ( emailTo='". $loggedInUser . "' AND status='accepted'))))"; 

            //echo $searchQuery;
            //query works by getting a matching query, then AND-ing it with a friend of logged in user

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
