<!--  Maintain a blog and read and search the blogs of friends.  -->
<?php
  require "../database.php";
  $title = "Blog";
  $description = "";
  include("../inc/header.php");

  // CHANGED THIS TO BE AUTHENTICATED LATER
  $loggedInUser = "charles@ucl.ac.uk";
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $personalPostsQuery = "SELECT * FROM blogs WHERE email='" . $loggedInUser . "'";
  #echo $personalPostsQuery;
  // $y = $pdo->prepare($personalPostsQuery);
  // $y->execute();
  // $personalPostsResults = $y->fetch(PDO::FETCH_ASSOC);

  $friendPostsQuery = "SELECT *
  FROM (SELECT * FROM users JOIN friendships ON
  users.email = friendships.emailFrom OR
  users.email=friendships.emailTo WHERE
  (friendships.emailFrom='". $loggedInUser.
  "' OR friendships.emailTo='". $loggedInUser .
   "' ) AND users.email!= '". $loggedInUser.
   "' AND status='accepted') AS T1
  JOIN (SELECT * from blogs) AS T2
  ON T1.email = T2.email";
  #echo $friendPostsQuery;
  $y = $pdo->prepare($friendPostsQuery );
  $y->execute();
  $friendPostsResults = $y->fetch(PDO::FETCH_ASSOC);

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
          <?php foreach($pdo->query($personalPostsQuery) as $personalPostsResults){ ?>
          <a href="viewPost.php?blogId=<?php echo $personalPostsResults["blogId"]; ?>" class="blog-section personal-post-container">
            <?php echo $personalPostsResults["blogTitle"]; ?>
          </a>
          <?php } ?>
        </div>


        <p>
          Posts your friends have written:
        </p>

        <div class="blog-section">
          <?php foreach($pdo->query($friendPostsQuery) as $friendPostsResults){ ?>
          <a href="viewPost.php?blogId=<?php echo $friendPostsResults["blogId"]; ?>" class="blog-section friend-post-container">
            <?php echo $friendPostsResults['blogTitle']; ?>
          </a>
          <?php } ?>
        </div>

      </div>






    </div>





  </div>

</body>
<?php Database::disconnect(); ?>
