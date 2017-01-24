<!--  Maintain a blog and read and search the blogs of friends.  -->
<?php
$title = "Blog";
$description = "";
include("../inc/header.php");
?>
<body>
  <?php include '../inc/nav-trn.php'; ?>
  <div class="container">
    <div class="blog-container">
      <div class="row">
        <font size="10"> Blogs </font> <br>
        <font size="3"> You can write blogposts or view your friends. </font>
      </div>

      <div class="row">
        <div class="blog-section">
          <input class="blog-search-bar" type="text" name="search" placeholder="Search posts">
        </div>

        <p>
          Posts you've written:
        </p>
        <div class="blog-section">
          <div class="blog-section personal-post-container">
            Post Title
          </div>

        </div>

        <p>
          Posts your friends have written:
        </p>

        <div class="blog-section">
          <div class="blog-section friend-post-container">
            Post Title 
          </dvi>
        </div>
      </div>






    </div>





  </div>

</body>
