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

    <!--Link to creating friendship circles  -->
    <a href="createcircleview.php">
      <button type="button" name="button">Create Circle</button>
    </a>


    <?php
      require("../database.php");
      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT * FROM circleOfFriends";
      // $result = $pdo->query($sql);
      // if ($result->num_rows > 0) {
        echo "<table style='width:100%'> <tr> <th> Circle of Friends ID </th> <th> Circle of Friends Name </th> <th> Date created </th> <th> Action </th> ";
        foreach ($pdo->query($sql) as $row)  {
            echo "<tr>";
            echo "<td>" . $row["circleFriendsId"] . "</td><td> " . $row["circleOfFriendsName"] . "</td><td>" . $row["dateCreated"]  . "</td><td> test </td>";
            echo "</tr>";
        }

        echo "</table>";
        Database::disconnect();

      ?>

    <!-- Footer  -->
    <?php include '../inc/footer.php'; ?>
