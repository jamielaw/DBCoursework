<!-- Similar to messenger.com, left navigation displays list of users in a circle, rest of page dedicated to chat -->
<?php 
include("../inc/header.php");
include("../inc/nav-trn.php");
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
  <div class="container">
    <div class="row">

      <!--  List of friends in circle-->
      <div class="col-md-3">

        <div class="media">
          <div class="media-left">
              <img class="media-object" src="..." alt="Friend1 profile pic">
          </div>
          <div class="media-body">
            <h4 class="media-heading">Friend1 in circle</h4>
          </div>
        </div>

        <div class="media">
          <div class="media-left">
              <img class="media-object" src="..." alt="Friend2 profile pic">
          </div>
          <div class="media-body">
            <h4 class="media-heading">Friend2 in circle</h4>
          </div>
        </div>

      </div>

      <!--  Chat pane-->
      <div class="col-md-9">
        <form class="" action="index.html" method="post">
          <textarea name="name" rows="8" cols="80"></textarea>
          <button type="button" name="button">Send</button>
        </form>
      </div>
    </div>
    </div>
  </body>
</html>
