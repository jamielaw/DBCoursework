<?php
$title = "Bookface Social Network";
$description = "A far superior social network";
include("inc/header.php");
include ("inc/nav-trn.php");
session_start();
 ?>
  <body>
    <!--  No Navigation because this is a page for those who aren't authenticated -->
      <!--  LOGIN -->
      <?php
        if (isset($_SESSION['loggedInUserEmail'])) {
          echo "You are logged in";
          echo "
                <form action='logout.php' method='post'>
                  <button type='submit'>LOG OUT</button>
                </form>
                ";
          echo $_SESSION['loggedInUserEmail'];
        } else {
          echo "You are not logged in, log in or sign up";
          echo "<form class='' action='login.php' method='post'>
            <input type='text' name='email' placeholder='email'>
            <br>
            <input type='password' name='pwd' placeholder='password'>
            <br>
            <button type='submit'>LOGIN</button>
          </form>";
          echo "
          <!--  SIGNUP -->
          <form action='signup.php' method='post'>
            <br>
            <input type='text' name='first' placeholder='first'/>
            <br>
            <input type='text' name='last' placeholder='last'/>
            <br>
            <input type='text' name='email' placeholder='email' />
            <br>
            <input type='password' name='pwd' placeholder='password'/>
            <br>
            <button type='submit' name='sign-up-submit' value='Sign Up' >SIGN UP</button>
          </form>
          ";
        }
       ?>

      <br/>
      <br/>
      <br/>



      <br/>
      <br/>
      <br/>

    </body>

    <!-- Footer  -->
    <?php include 'inc/footer.php'; ?>
