<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script type="text/javascript" src="http://code.jquery.com/jquery-3.0.0.min.js"></script>
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script type="text/javascript" src="//netsh.pp.ua/upwork-demo/1/js/typeahead.js"></script>
  <script type ="text/javascript">
    jQuery(document).ready(function($) {
      $('input.submit').typeahead({
        name: 'submit',
        remote: {url: '/sn/inc/nav-trnsearch.php?query=%QUERY'} //absolute ref as we don't know which page is calling this
      });
    });
    // jQuery('#input').on('input', function(){ //backup plan
    //   $searchQuery = $("#input").val();
    //   window.alert($searchQuery);
    // });
  </script>
  <style>
        .tt-hint,
        .submit {
            border: 2px solid #CCCCCC;
            border-radius: 8px 8px 8px 8px;
            /*font-size: 24px; */
            height: 30px;
            line-height: 30px;
            outline: medium none;
            padding: 8px 12px;
            width: 400px;
        }

        .tt-dropdown-menu {
            width: 400px;
            margin-top: 5px;
            padding: 8px 12px;
            background-color: #fff;
            border: 1px solid #ccc;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 8px 8px 8px 8px;
            font-size: 18px;
            color: #111;
            background-color: #F1F1F1;
        }
</style>
</head>


<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">BookFace</a>
    </div>
    <div>
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">Profile
          <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="../profile/index.php">My Blog</a></li>
            <li><a href="../profile/myfriends.php">My Friends</a></li>
            <li><a href="../profile/index.php#3">Messages</a></li>
            <li><a href="../profile/index.php#4">My Photo Collections</a></li>
          </ul>
        </li>
        <li><a href="../photos/index.php">Photos</a></li>
        <li><a href="../circles/index.php">Circles</a></li>
        <li><a href="../blog/index.php">Blog</a></li>
        <li><a href="../explore/index.php">Explore</a></li>
        <li><a href="../admin/index.php">Admin</a></li>
      </ul>

      <!--search bar-->
      <form class="navbar-form navbar-left" action="../search/searchresult.php?go" method="get" id="searchform">
          <input type="text" class="submit" placeholder="Search for friends" name="submit" id="submit" autocomplete="off" style="vertical-align:none;">
        <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i></button>
      </form>

    </div>
  </div>
</nav>
</html>

<style type="text/css">
  body { padding-top: 50px; }
</style>
