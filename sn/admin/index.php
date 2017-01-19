<?php
  $title = "Bookface Social Network";
  $description = "A far superior social network";
  include("inc/header.php");

  $servername = "localhost:3306";
  $username = "root";
  $password = "root";

  //Create connection
  $conn = new mysqli($servername, $username, $password);
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }else{
      echo "Connection established";
  }

?>
<body>
  <h1> Admin page </h1>

  <h2> Something that the admin will be able to see. For now, just show all the data! </h2>


  <?php

  $sql = "SELECT * FROM MyDB.users";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
      // output data of each row. create a table!

      echo "<table style='width:100%'> <tr> <th> email </th> <th> First name </th> <th> Second Name </th>";

      while($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . $row["email"] . "</td><td> " . $row["firstName"] . "</td><td>" . $row["lastName"]  . "</td>";
          echo "</tr>";
      }

      echo "</table>";
  } else {
      echo "0 results";
  }



  ?>



</body>
<?php $conn->close(); ?>
<?php include 'inc/footer.php'; ?>
