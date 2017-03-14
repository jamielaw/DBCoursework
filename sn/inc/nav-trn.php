<!DOCTYPE html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
@media (max-width: 1190px) {
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
    </div>
          <?php
          session_start();
          require("$root/sn/session.php");
          $pdo = Database::connect();
          $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          if($_SESSION['loggedInUserEmail']){ //if user is logged in, display relevant navbar
                    echo "<a class=\"navbar-brand\" href=\"\sn\profile\">BookFace</a>";
                    echo "<div class=\"collapse navbar-collapse\">
                    <ul class=\"nav navbar-nav\">
                    <li class=\"dropdown\">
                    <a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">";
                    //get user details for sprite in navbar
                    $sql="SELECT firstName, lastName, profileImage FROM MyDB.users WHERE email='" . $loggedInUser . "'";
                    $userq = $pdo->query($sql);
                    $row = $userq->fetch(PDO::FETCH_ASSOC);
                    echo "<img style='height:20px;width:20px;border-radius:2px;' src='" . $row["profileImage"] . "'> ";
                    echo $row["firstName"] . " " . $row["lastName"];
                    echo "<span class=\"caret\"></span></a>
                  <ul class=\"dropdown-menu\">
                    <li><a href=\"/sn/profile/readprofile.php?email=" . $loggedInUser . "\"><i class=\"fa fa-user\"></i> My Profile</a></li>
                    <li><a href=\"/sn/profile/settings.php\"><i class=\"fa fa-cog\"></i> Settings</a></li>
                    <li><a href=\"/sn/logout.php\"><i class=\"fa fa-sign-out\"></i> Logout</a></li>
                  </ul>";
                    echo "</li>";

                //Check for new friend requests!
                  $friendRequestCount = "SELECT COUNT( * )FROM friendships WHERE (emailTo='$loggedInUser' AND status='pending');";
                  //echo $friendRequestCount;
                  $q = $pdo->prepare($friendRequestCount);
                  $q->execute();

                  $row = $q->fetchColumn();

                  if($row == 0 || $row == NULL){
                    echo '<li><a href="/sn/profile/myfriends.php"><i class="fa fa-users" data-toggle="tooltip" data-placement="bottom" title="Friend requests"></i></a></li>';
                  }else{
                    // you have a friend request!
                    echo '<li><a href="/sn/profile/myfriends.php"><i style="color: #ff304d;" class="fa fa-users" data-toggle="tooltip" data-placement="bottom" title="You have new friend requests!"></i></a></li>';
                  }

                  echo "<li><a href=\"/sn/profile/messages.php\"><i class=\"fa fa-comments\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Your messages\"></i></a></li>

                  <li><a href=\"/sn/circles/index.php\">Circles</a></li>
                  <li><a href=\"/sn/blog/index.php\">Blog</a></li>
                  <li><a href=\"/sn/explore/index.php\">Explore</a></li>"; //<li><a href=\"/sn/profile/readprofile.php?email=".$loggedInUser."#pictures\"><i class=\"fa fa-picture-o\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Your photos\"></i></a></li>

                  //Check if logged in user is administrator
                  $sql = "SELECT roleTitle FROM MyDB.roles INNER JOIN MyDB.users ON MyDB.users.roleID=MyDB.roles.roleID WHERE(users.email='" . $loggedInUser . "')";
                  //echo $sql;
                  $res=$pdo->query($sql);
                  $row = $res->fetch(PDO::FETCH_ASSOC);
                  if ($row["roleTitle"]=="administrator") {
                      echo "<li><a href=\"/sn/admin/index.php\">Admin</a></li>";
                  }


                  //search bar
                  echo "<form class=\"navbar-form navbar-left\" action=\"/sn/search/searchresult.php?go\" method=\"get\" id=\"searchform\" style=\"padding-top:2px;\">
                      <input type=\"text\" class=\"submit\" placeholder=\"Search for friends\" name=\"submit\" id=\"submit\" autocomplete=\"off\" style=\"vertical-align:none;\">
                    <button type=\"submit\" class=\"btn btn-default\" style=\"padding:3px 5px; vertical-align:top;\"><i class=\"glyphicon glyphicon-search\"></i></button>
                  </form>
                  </div>
                </div>";
        }
        else{
          echo "<a class=\"navbar-brand\">BookFace</a>";
          echo "<div class=\"collapse navbar-collapse\">
                    <ul class=\"nav navbar-nav\">
                    <li><a href=\"/sn/index.php\">Log In</a></li>
                    <li><a href=\"/sn/index.php\">Sign Up</a></li>
                </div>";
        }
        Database::disconnect();
        ?>
      </ul>

</nav>
<script>
$(document).ready(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>
