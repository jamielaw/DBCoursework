<?php
$title = "Bookface Social Network";
$description = "A far superior social network";
include("inc/header.php");
include ("inc/nav-trn.php");

 ?>
  <body>
    <!--  No Navigation because this is a page for those who aren't authenticated -->
      <!--  LOGIN -->
      <form class="" action="login.php" method="post">
        <input type="text" name="uid" placeholder="username">
        <br>
        <input type="password" name="pwd" placeholder="password">
        <br>
        <button type="submit">LOGIN</button>
      </form>

      <?php
        if (isset($_SESSION['id'])) {
          echo $_SESSION['id'];
        } else {
          echo "You are not logged in";
        }
       ?>

      <br/>
      <br/>
      <br/>

      <!--  SIGNUP -->
      <form action="signup.php" method="post">
        <br>
        <input type="text" name="first" placeholder="first">
        <br>
        <input type="text" name="last" placeholder="last">
        <br>
        <input type="text" name="uid" placeholder="username">
        <br>
        <input type="password" name="pwd" placeholder="password">
        <br>
        <button type="submit">SIGN UP</button>
      </form>

      <br/>
      <br/>
      <br/>

      <form action="logout.php" method="post">
        <button type="submit">LOG OUT</button>
      </form>
    </body>

    <!-- Footer  -->
    <?php include 'inc/footer.php'; ?>
