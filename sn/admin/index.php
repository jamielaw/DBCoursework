<?php

  require("../database.php");

  $title = "Bookface Social Network";
  $description = "A far superior social network";
  include("../inc/header.php");

  // $servername = "localhost:3306";
  // $username = "root";
  // $password = "admin";
  //
  // //Create connection
  // $conn = new mysqli($servername, $username, $password);
  // // Check connection
  // if ($conn->connect_error) {
  //     die("Connection failed: " . $conn->connect_error);
  // }else{
  //     echo "Connection established";
  // }





?>
<body>
  <h1> Admin page </h1>

  <h2> Something that the admin will be able to see. For now, just show all the data! </h2>


  <?php
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT * FROM users";
  // $result = $pdo->query($sql);
  // if ($result->num_rows > 0) {
    echo "<table style='width:100%'> <tr> <th> email </th> <th> First name </th> <th> Second Name </th> <th> Image </th> <th> Action </th> ";
    foreach ($pdo->query($sql) as $row)  {
        echo "<tr>";
        echo "<td>" . $row["email"] . "</td><td> " . $row["firstName"] . "</td><td>" . $row["lastName"]  . "</td><td> <img style='height:100px;width=100px;' src='" . $row["profileImage"] . "'</td>";
        echo "<td> <a href='editUserView.php?email=".$row["email"]."'<i class='fa fa-pencil' aria-hidden='true'></i> Edit <br>";
        echo "<a href='deleteUser.php?email=".$row["email"]."' <i class='fa fa-trash' aria-hidden='true'></i> Delete </td> </a>";
        echo "</tr>";
    }

    echo "</table>";
    Database::disconnect();

  ?>



</body>
<?php $conn->close(); ?>
<?php include '../inc/footer.php'; ?>
