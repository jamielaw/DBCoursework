<?php
$title = "Bookface Social Network";
$description = "A far superior social network";
include("inc/header.php");
include ("inc/nav-trn.php");
session_start();
 ?>
  <body>
    <!--  No Navigation because this is a page for those who aren't authenticated -->
      <?php
        if (isset($_SESSION['loggedInUserEmail'])) {
          echo $_SESSION['loggedInUserEmail'];
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
        <input type="text" name="first" placeholder="first"/>
        <br>
        <input type="text" name="last" placeholder="last"/>
        <br>
        <input type="text" name="email" placeholder="email" />
        <br>
        <input type="password" name="pwd" placeholder="password"/>
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
