<!--  Maintain a blog and read and search the blogs of friends.  -->
<?php
  require "../database.php";
  $title = "Blog";
  $description = "";
  include("../inc/header.php");

  // CHANGED THIS TO BE AUTHENTICATED LATER
  $loggedInUser = "larry@ucl.ac.uk";
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $postQuery = "SELECT * FROM blogs WHERE blogId=" . $_GET["blogId"];

  $y = $pdo->prepare($postQuery);
  $y->execute();
  $postQueryResult = $y->fetch(PDO::FETCH_ASSOC);



?>
<body>
  <?php include '../inc/nav-trn.php'; ?>
  <div class="container">
    <div class="blog-container">
      <div class="row">
        <font size="10"> <?php echo $postQueryResult["blogTitle"]; ?> </font> <br>
        <font size="3"></font>
      </div>

      <p>
        <?php echo $postQueryResult["blogDescription"];  ?>
      </p>




      </div>






    </div>





  </div>

</body>
<?php Database::disconnect(); ?>
