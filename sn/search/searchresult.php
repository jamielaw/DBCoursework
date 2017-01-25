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
      require("../database.php");
      echo "<h1>Search results for first/last name matching with: ". $_GET['submit'] ."</h1>";
       if(isset($_GET['submit'])){ 
          if(preg_match("/^[  a-zA-Z]+/", $_GET['submit'])){ //check search string isn't empty
            $name=$_GET['submit']; 
            //connect  to the database 
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //-query  the database table 
            //currently searches to see if the first or last name contains the search string at any location
            $sql="SELECT email, firstName, lastName, profileImage FROM MyDB.users WHERE firstName LIKE '" . $name .  "%' OR lastName LIKE '" . $name ."%' OR concat_ws(' ', firstName, lastName) LIKE '" . $name . "%'"; 

            //echo $sql;

            echo "<table style='width:100%'> <tr> <th> Email </th> <th> First name </th> <th> Last Name </th> <th> Image </th> <th> Go to profile </th>";
            //- loop through result set 
            foreach($pdo->query($sql) as $row){
                //-display the result of the array 
                echo "<tr>"; 
                echo "<td>" . $row["email"] . "</td><td> " . $row["firstName"] . "</td><td>" . $row["lastName"]  . "</td><td> <img style='height:100px;width=100px;' src='" . $row["profileImage"] . "'</td>" . "<td><a href=\"../profile/readprofile.php?email='" . $row["email"] . "'\">View profile</a></td>";
                echo "</tr>"; 

            }

            echo "</table>";
          } 
          else{ 
            echo  "<p>Please enter a search query</p>" .$_GET['submit']; 
          }  
        }
      Database::disconnect(); 
    ?>
  <!-- Footer  -->
  <?php include '../inc/footer.php'; ?>
  </body>
</html>
