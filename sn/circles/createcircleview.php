<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Create Friendship Circle</title>
    <?php 
    include("../inc/header.php");
    include("../inc/nav-trn.php"); 
    ?>
  </head>
  <body>
  <div class="container">
  <row><font size="5">Create new circle</font></row>
    <form class="" action="createcircle.php" method="get" id="createform">
    <!-- Name of circle -->
    <br>
     <div class="input-group">
        <span class="input-group-addon">Name of circle</span>
        <input id="circlename" type="text" class="form-control" name="circlename" placeholder="Circle name" style="width: 50%;">
     </div>

      <!--  Requires multi select friends, use http://davidstutz.github.io/bootstrap-multiselect/#further-examples-->
      <br>
      <button type="submit" onclick="return empty()">Create Circle</button>
    </form>
    <br>
    <button onclick="window.location='/sn/circles/index.php'">Go back</button>
    </div>
  </body>
</html>
<script>
function empty() {
    var x;
    x = document.getElementById("circlename").value;
    if (x == "") {
        alert("Enter a circle name");
        return false;
    };
}
</script>