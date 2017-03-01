<?php

  //require("../database.php");  //I've commented this out because nav-trn already requires database.php to obtain loggedinuser info - Jamie

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
  $userInfo = "SELECT * FROM users";
  echo "<h2> User accounts </h2>";
  echo "<table class='table table-stripped table-bordered'>
     <tr>
      <th> Email </th>
      <th> First name </th>
      <th> Second Name </th>
      <th> Image </th>
      <th> Action </th> ";
    foreach ($pdo->query($userInfo) as $row)  {
        echo "<tr>";
        echo "<td>" . $row["email"] . "</td><td> " . $row["firstName"] . "</td><td>" . $row["lastName"]  . "</td><td> <img style='height:100px;width=100px;' src='" . $row["profileImage"] . "'</td>";
        echo "<td> <a class='table-btn btn btn-success' href='usercontrol/editUserView.php?email=".$row["email"]."'><i class='fa fa-pencil' aria-hidden='true'></i> Edit </a>";
        echo "<br> <a class='table-btn btn btn-danger' href='usercontrol/deleteUser.php?email=".$row["email"]."'> <i class='fa fa-trash' aria-hidden='true'></i> Delete </td> </a>";
        echo "</tr>";
    }

    echo "</table>";


    $annotationsInfo = "SELECT * FROM annotations";
    echo "<h2> Annotations </h2>";
    echo "<table class='table table-stripped table-bordered'>
       <tr>
        <th> Photo </th>
        <th> Annotator </th>
        <th> Text </th>
        <th> Action </th> ";

    foreach ($pdo->query($annotationsInfo) as $row)  {

            $photoQuery = "SELECT * FROM photos where photoId=". $row["photoId"];

            $q = $pdo->prepare($photoQuery);
            $q->execute();
            $photoResult = $q->fetch(PDO::FETCH_ASSOC);

            //$user = "SELECT * IN useres where emailId='". $row["email"] . "'";

            $userQuery = "SELECT * FROM users where email='". $row["email"] . "'";

            $y = $pdo->prepare($userQuery);
            $y->execute();
            $userQueryResult = $y->fetch(PDO::FETCH_ASSOC);

            echo "<tr>";
            echo "<td> <img style='height:100px;width=100px;' src='" . $photoResult["imageReference"] . "'</td>";
            echo "<td>" . $userQueryResult["firstName"] . " " . $userQueryResult["lastName"]  . "</td><td> "  . $row['annotationText'] . "</td>";
            echo "<td> <a class='table-btn btn btn-success' href='annotations/editAnnotationView.php?annotationsId=".$row["annotationsId"]."'><i class='fa fa-pencil' aria-hidden='true'></i> Edit </a><br>";
            echo "<a class='table-btn btn btn-danger' href='annotations/deleteAnnotation.php?annotationsId=".$row["annotationsId"]."'> <i class='fa fa-trash' aria-hidden='true'></i> Delete </td> </a>";
            echo "</tr>";
    }

    echo "</table>";

    $blogsInfo = "SELECT * FROM blogs";
    echo "<h2> Blogs </h2>";
    echo "<table class='table table-stripped table-bordered'>
       <tr>
        <th> Author </th>
        <th> Title </th>
        <th> Action </th> ";

    foreach ($pdo->query($blogsInfo) as $row)  {

            $userQuery = "SELECT * FROM users where email='". $row["email"] . "'";

            $y = $pdo->prepare($userQuery);
            $y->execute();
            $userQueryResult = $y->fetch(PDO::FETCH_ASSOC);

            echo "<tr>";
            echo "<td>" . $userQueryResult["firstName"] . " " . $userQueryResult["lastName"]  . "</td>";
            echo "<td>" . $row['blogTitle'] .  "</td>";
            echo "<td> <a class='table-btn btn btn-success' href='blogs/editView.php?blogId=".$row["blogId"]."'><i class='fa fa-pencil' aria-hidden='true'></i> Edit </a><br>";
            echo "<a class='table-btn btn btn-danger' href='blogs/delete.php?blogId=".$row["blogId"]."'> <i class='fa fa-trash' aria-hidden='true'></i> Delete </td> </a>";
            echo "</tr>";
    }

    echo "</table>";

    Database::disconnect();

  ?>



</body>
<?php $conn->close(); ?>
<?php include '../inc/footer.php'; ?>
