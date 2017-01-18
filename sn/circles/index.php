<!-- Displays friend circles, each friend circle is linked to a chat page,
also has link to creating friend circles -->
<?php
$title = "Friendship Circles";
$description = "";
include("inc/header.php");
 ?>
  <body>
    <!--  Navigation-->
    <?php include 'inc/nav-trn.php'; ?>

    <!--Link to creating friendship circles  -->
    <a href="createcircle.php">
      <button type="button" name="button">Create Circle</button>
    </a>


    <div class="row">

      <!--  Repeat for $Y number of friendship circles-->
      <a href="circlechat.php">
        <div class="col-sm-6 col-md-4">
          <div class="thumbnail">
            <img src="..." alt="...">
            <div class="caption">
              <h3>Thumbnail label</h3>
              <p>...</p>
              <p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
            </div>
          </div>
        </div>
      </a>

    </div>

    <!-- Footer  -->
    <?php include 'inc/footer.php'; ?>
