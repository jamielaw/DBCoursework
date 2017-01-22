<?php

  require("../database.php");

  $title = "Bookface Social Network";
  $description = "A far superior social network";
  include("../inc/header.php");



?>
<body>
  <!--  Navigation-->
  <?php include '../inc/nav-trn.php'; ?>
  <div class="container">
    <div class="row">
      <font size="5"> Admin Page </font>
    </div>
  <?php
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT * FROM users";
    echo "<table class='table table-stripped table-bordered'>
     <tr>
      <th> Email </th>
      <th> First name </th>
      <th> Second Name </th>
      <th> Image </th>
      <th> Action </th> ";
    foreach ($pdo->query($sql) as $row)  {
        echo "<tr>";
        echo "<td>" . $row["email"] . "</td><td> " . $row["firstName"] . "</td><td>" . $row["lastName"]  . "</td><td> <img style='height:100px;width=100px;' src='" . $row["profileImage"] . "'</td>";
        echo "<td> <a class='btn btn-success' href='editUserView.php?email=".$row["email"]."'<i class='fa fa-pencil' aria-hidden='true'></i> Edit <br>";
        echo "<a class='btn btn-danger' href='deleteUser.php?email=".$row["email"]."' <i class='fa fa-trash' aria-hidden='true'></i> Delete </td> </a>";
        echo "</tr>";
    }

    echo "</table>";
    Database::disconnect();

  ?>



</body>
<?php $conn->close(); ?>
<?php include '../inc/footer.php'; ?>
