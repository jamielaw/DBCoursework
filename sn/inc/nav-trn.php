<!DOCTYPE html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
@media (max-width: 1260px) {
  .navbar-header {
      float: none;
  }
  .navbar-left,.navbar-right {
      float: none !important;
  }
  .navbar-toggle {
      display: block;
  }
  .navbar-collapse {
      border-top: 1px solid transparent;
      box-shadow: inset 0 1px 0 rgba(255,255,255,0.1);
  }
  .navbar-fixed-top {
      top: 0;
      border-width: 0 0 1px;
  }
  .navbar-collapse.collapse {
      display: none!important;
  }
  .navbar-nav {
      float: none!important;
      margin-top: 7.5px;
  }
  .navbar-nav>li {
      float: none;
  }
  .navbar-nav>li>a {
      padding-top: 10px;
      padding-bottom: 10px;
  }
  .collapse.in{
      display:block !important;
  }
}
</style>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button class="navbar-toggle" aria-controls="navbar" aria-expanded="true" data-target=".navbar-collapse" data-toggle="collapse" type="button">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand">BookFace</a>
    </div>
    <div class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
          <?php
          require("$root/sn/database.php");
          $pdo = Database::connect();
          $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          //update this later when we have info on who logged in user is
          $loggedInUser = "vicky@ucl.ac.uk";
          $sql="SELECT firstName, lastName, profileImage FROM MyDB.users WHERE email='" . $loggedInUser . "'"; //change this to be for logged-in user
          foreach ($pdo->query($sql) as $row) {
              echo "<img style='height:20px;width:20px;border-radius:2px;' src='" . $row["profileImage"] . "'> ";
              echo $row["firstName"] . " " . $row["lastName"];
          }
          Database::disconnect();
          ?>
          <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="/sn/profile/index.php"><i class="fa fa-user"></i> My Profile</a></li>
            <li><a href="/sn/profile/settings.php"><i class="fa fa-cog"></i> Settings</a></li>
            <li><a href="/sn/logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
          </ul>
        </li>
        <!--  Check for new friend requests! -->
        <?php
        $friendRequestCount = 'SELECT COUNT( DISTINCT email, firstName, lastName, profileImage )FROM users JOIN friendships ON users.email = friendships.emailFrom OR users.email=friendships.emailTo WHERE (friendships.emailTo=\'charles@ucl.ac.uk\') AND users.email!=\'charles@ucl.ac.uk\' AND status=\'pending\';';
        $q = $pdo->prepare($friendRequestCount);
        $q->execute();

        $row = $q->fetchColumn();

        if($row == 0 || $row == NULL){
          echo '<li><a href="/sn/profile/myfriends.php"><i class="fa fa-users"></i></a></li>';
        }else{
          // you have a friend request!
          echo '<li><a href="/sn/profile/myfriends.php"><i style="color: #ff304d;" class="fa fa-users"></i></a></li>';
        }
        ?>
        <!--  -->
        <li><a href="/sn/profile/messages.php"><i class="fa fa-comments"></i></a></li>
        <li><a href="/sn/profile/index.php#4"><i class="fa fa-picture-o"></i></a></li>
        <li><a href="/sn/photos/index.php">Photos</a></li>
        <li><a href="/sn/circles/index.php">Circles</a></li>
        <li><a href="/sn/blog/index.php">Blog</a></li>
        <li><a href="/sn/explore/index.php">Explore</a></li>
        <?php
        //Check if logged in user is administrator
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT roleTitle FROM MyDB.roles INNER JOIN MyDB.users ON MyDB.users.roleID=MyDB.roles.roleID WHERE(users.email='" . $loggedInUser . "')";
        //echo $sql;
        $res=$pdo->query($sql);
        $row = $res->fetch(PDO::FETCH_ASSOC);
        if ($row["roleTitle"]=="administrator") {
            echo "<li><a href=\"../admin/index.php\">Admin</a></li>";
        }
        Database::disconnect();
        ?>
      </ul>

      <!--search bar-->
      <form class="navbar-form navbar-left" action="/sn/search/searchresult.php?go" method="get" id="searchform" style="padding-top:2px;">
          <input type="text" class="submit" placeholder="Search for friends" name="submit" id="submit" autocomplete="off" style="vertical-align:none;">
        <button type="submit" class="btn btn-default" style="padding:3px 5px; vertical-align:top;"><i class="glyphicon glyphicon-search"></i></button>
      </form>
      </div>
    </div>
</nav>
