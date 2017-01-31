<!DOCTYPE html>

<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand">BookFace</a>
    </div>
    <div>
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
          <?php
          require("../database.php");
          $pdo = Database::connect();
          $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          //update this later when we have info on who logged in user is
          $loggedInUser = "vicky@ucl.ac.uk";
          $sql="SELECT firstName, lastName, profileImage FROM MyDB.users WHERE email='" . $loggedInUser . "'"; //change this to be for logged-in user
          foreach($pdo->query($sql) as $row){
            echo "<img style='height:20px;width:20px;border-radius:2px;' src='" . $row["profileImage"] . "'> ";
            echo $row["firstName"] . " " . $row["lastName"];
          }
          Database::disconnect();
          ?>
          <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="../profile/index.php"><i class="fa fa-user"></i> My Profile</a></li>
            <li><a href="../profile/settings.php"><i class="fa fa-cog"></i> Settings</a></li>
            <li><a href="../logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
          </ul>
        </li>
        <li><a href="../profile/myfriends.php"><i class="fa fa-users"></i></a></li>
        <li><a href="../profile/messages.php"><i class="fa fa-comments"></i></a></li>
        <li><a href="../profile/index.php#4"><i class="fa fa-picture-o"></i></a></li>
        <li><a href="../photos/index.php">Photos</a></li>
        <li><a href="../circles/index.php">Circles</a></li>
        <li><a href="../blog/index.php">Blog</a></li>
        <li><a href="../explore/index.php">Explore</a></li>
        <li><a href="../admin/index.php">Admin</a></li>
      </ul>

      <!--search bar-->
      <form class="navbar-form navbar-left" action="../search/searchresult.php?go" method="get" id="searchform" style="padding-top:2px;">
          <input type="text" class="submit" placeholder="Search for friends" name="submit" id="submit" autocomplete="off" style="vertical-align:none;">
        <button type="submit" class="btn btn-default" style="padding:3px 5px; vertical-align:top;"><i class="glyphicon glyphicon-search"></i></button>
      </form>

    </div>
  </div>
</nav>


<style type="text/css">
  body { padding-top: 50px; }
</style>
