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


    $blogsInfo = "SELECT * FROM friendships";
    echo "<h2> Friendships </h2>";
    echo "<table class='table table-stripped table-bordered'>
       <tr>
        <th> From </th>
        <th> To </th>
        <th> Status </th>
        <th> Action </th> ";

    foreach ($pdo->query($blogsInfo) as $row)  {

            $userQuery = "SELECT * FROM users where email='". $row["email"] . "'";

            $y = $pdo->prepare($userQuery);
            $y->execute();
            $userQueryResult = $y->fetch(PDO::FETCH_ASSOC);

            echo "<tr>";
            echo "<td>" . $row['emailFrom'] . "</td>";
            echo "<td>" . $row['emailTo'] . "</td>";
            echo "<td>" . $row['status'] .  "</td>";
            echo "<td>";
            if($row['status'] == "denied"){
              echo "<a class='table-btn btn btn-success' href='friendships/editFriendship.php?friendshipId=".$row["friendshipID"]."&action=accepted'><i class='fa fa-check' aria-hidden='true'></i> Accept </a><br>";

            }else{
              echo " <a class='table-btn btn btn-warning' href='friendships/editFriendship.php?friendshipId=".$row["friendshipID"]."&action=denied'><i class='fa fa-times' aria-hidden='true'></i> Decline </a><br>";

            }
            echo "<a class='table-btn btn btn-danger' href='friendships/delete.php?friendshipId=".$row["friendshipID"]."'> <i class='fa fa-trash' aria-hidden='true'></i> Delete </td> </a>";
            echo "</tr>";
    }

    echo "</table>";

    $photoCollectionQuery = "SELECT * FROM photoCollection";
    echo "<h2> Photo Collections </h2>";
    echo "<table class='table table-stripped table-bordered'>
       <tr>
        <th> Title  </th>
        <th> Description </th>
        <th> Owner </th>
        <th> Action </th> ";

    foreach ($pdo->query($photoCollectionQuery) as $row)  {

            $userQuery = "SELECT * FROM users where email='". $row["createdBy"] . "'";

            $y = $pdo->prepare($userQuery);
            $y->execute();
            $userQueryResult = $y->fetch(PDO::FETCH_ASSOC);

            echo "<tr>";
            echo "<td>" . $row['title'] .  "</td>";
            echo "<td>" . $row['description'] .  "</td>";
            echo "<td>" . $userQueryResultName["firstName"] . " " . $userQueryResult["lastName"]  . "</td>";

            echo "<td> <a class='table-btn btn btn-success' href='photoCollections/editView.php?pcId=".$row["photoCollectionId"]."'><i class='fa fa-pencil' aria-hidden='true'></i> Edit </a><br>";
            echo "<a class='table-btn btn btn-danger' href='photoCollections/delete.php?pcId=".$row["photoCollectionId"]."'> <i class='fa fa-trash' aria-hidden='true'></i> Delete </td> </a>";
            echo "</tr>";
    }

    echo "</table>";

    $photoQuery = "SELECT * FROM photos";
    echo "<h2> Photo  </h2>";
    echo "<table class='table table-stripped table-bordered'>
       <tr>
        <th> Image </th>

        <th> Action </th> ";

    foreach ($pdo->query($photoQuery) as $row)  {

            $userQuery = "SELECT * FROM users where email='". $row["createdBy"] . "'";

            $y = $pdo->prepare($userQuery);
            $y->execute();
            $userQueryResult = $y->fetch(PDO::FETCH_ASSOC);

            echo "<tr>";
            echo "<td><img style='width:100px;' src='" . $row['imageReference'] .  "'</td>";

            echo "<td> <a class='table-btn btn btn-success' href='photos/editView.php?photoId=".$row["photoId"]."'><i class='fa fa-pencil' aria-hidden='true'></i> Edit </a><br>";
            echo "<a class='table-btn btn btn-danger' href='photos/delete.php?photoId=".$row["photoId"]."'> <i class='fa fa-trash' aria-hidden='true'></i> Delete </td> </a>";
            echo "</tr>";
    }

    echo "</table>";
/// REEMBER TO DELETE STUFF FIRST
    $circlesQuery = "SELECT * FROM circleOfFriends";
    echo "<h2> Circles </h2>";
    echo "<table class='table table-stripped table-bordered'>
       <tr>
        <th> Name </th>
        <th> Members </th>
        <th> Action </th> </tr>";

    foreach ($pdo->query($circlesQuery) as $row)  {

            echo "<tr>";
            echo "<td>" . $row['circleOfFriendsName'] .  "</td>";

            // start loop to find members

            echo "<td>";
            $circleRelations = "SELECT * FROM userCircleRelationships where circleFriendsId =". $row['circleFriendsId'];
            foreach ($pdo->query($circleRelations) as $members) {
              $userQuery = "SELECT * FROM users where email='". $members["email"] . "'";

              $y = $pdo->prepare($userQuery);
              $y->execute();
              $userQueryResult = $y->fetch(PDO::FETCH_ASSOC);

              echo "<a href='/sn/profile/readprofile.php?email=". $userQueryResult ['email'] . "'>" . $userQueryResult['firstName'] . "  " . $userQueryResult['lastName'] . "</a><br>";

            }

            echo "</td>";
            echo "<td> <a class='table-btn btn btn-success' href='circles/editView.php?circleId=".$row["circleFriendsId"]."'><i class='fa fa-pencil' aria-hidden='true'></i> Edit </a><br>";
            echo "<a class='table-btn btn btn-danger' href='circles/delete.php?circleId=".$row["circleFriendsId"]."'> <i class='fa fa-trash' aria-hidden='true'></i> Delete </td> </a>";
            echo "</tr>";
    }

    echo "</table>";


    echo "<h2> Messages </h2>";
    echo "<table class='table table-stripped table-bordered'>
       <tr>
        <th> From  </th>
        <th> To </th>
        <th> Message </th>

        <th> Action </th></tr> ";

    $messagesQuery = "SELECT * FROM messages";
    foreach ($pdo->query($messagesQuery) as $row)  {
            if(strlen($row['emailTo']) < 3 ) continue;
            $userTo= "SELECT * FROM users where email='". $row["emailTo"] . "'";

            $y = $pdo->prepare($userTo);
            $y->execute();
            $userToQueryResult = $y->fetch(PDO::FETCH_ASSOC);

            $userFrom= "SELECT * FROM users where email='". $row["emailFrom"] . "'";

            $y = $pdo->prepare($userFrom);
            $y->execute();
            $userFromQueryResult = $y->fetch(PDO::FETCH_ASSOC);


            echo "<tr>";
            echo "<td><a href='/sn/profile/readprofile.php?email=". $userFromQueryResult['email'] . "'>" .$userFromQueryResult['firstName'] . "  " . $userFromQueryResult['lastName'] . "</a></td><br>";
            echo "<td><a href='/sn/profile/readprofile.php?email=". $userToQueryResult['email'] . "'>" . $userToQueryResult['firstName'] . "  " .$userToQueryResult['lastName'] . "</a></td><br>";

            echo "<td>" . $row['messageText'] .  "</td>";
            echo "<td> ";
            echo "<a class='table-btn btn btn-danger' href='messages/delete.php?messageId=".$row["messageId"]."'> <i class='fa fa-trash' aria-hidden='true'></i> Delete </td> </a>";
            echo "</tr>";
    }

    echo "</table>";
    Database::disconnect();

  ?>



</body>
<?php $conn->close(); ?>
<?php include '../inc/footer.php'; ?>
