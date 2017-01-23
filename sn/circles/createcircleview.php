<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Create Friendship Circle</title>
    <?php include("../inc/header.php");
    include("../inc/nav-trn.php"); 
    ?>
  </head>
  <body>
    <form class="" action="createcircle.php" method="get" id="createform">
    <!-- Name of circle -->
    <br>
     <div class="input-group">
        <span class="input-group-addon">Name of circle</span>
        <input id="circlename" type="text" class="form-control" name="circlename" placeholder="Circle name" style="width: 50%;">
     </div>

      <!--  Requires multi select friends, use http://davidstutz.github.io/bootstrap-multiselect/#further-examples-->

      <button type="submit">Create Circle</button>
    </form>
  </body>
</html>
