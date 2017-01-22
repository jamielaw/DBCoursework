<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">BookFace</a>
    </div>
    <div>
      <ul class="nav navbar-nav">
        <li><a href="../profile">Profile</a></li>
        <li><a href="../photos/index.php">Photos</a></li>
        <li><a href="../circles/index.php">Circles</a></li>
        <li><a href="../blog/index.php">Blog</a></li>
        <li><a href="../explore/index.php">Explore</a></li>
        <li><a href="../admin/index.php">Admin</a></li>
      </ul>

      <!--search bar-->
      <form class="navbar-form navbar-left" action="../search/searchresult.php?go" method="get" id="searchform">
          <input type="text" class="form-control" placeholder="Search for friends" name="submit">
        <button type="submit" id="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i></button>
      </form>

    </div>
  </div>
</nav>

</body>
</html>