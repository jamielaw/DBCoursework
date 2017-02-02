<!--  Maintain a blog and read and search the blogs of friends.  -->
<?php
  $title = "Blogs";
  include("../inc/header.php");
  include ("../inc/nav-trn.php"); ?>
<body>
  <?php
  //require "../database.php"; //I've commented this out because nav-trn already requires database.php to obtain loggedinuser info - Jamie
  $title = "Blog";
  $description = "";

  // CHANGED THIS TO BE AUTHENTICATED LATER
  $loggedInUser = "ada@ucl.ac.uk";
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
  <div class="container">
    <div class="blog-container">
      <div class="row">
        <font size="10"> Blogs </font> <br>
        <font size="3"> You can write blogposts or view your friends. </font>
      </div>

      <div class="row">

        <div class="blog-section create-blog">
          <a href="createBlog.php"> <i class="glyphicon glyphicon-plus"></i> Create a new blog </a>
        </div>

        <p>
          Posts you've written:
        </p>

        <div class="blog-section">
          <?php foreach($pdo->query($personalPostsQuery) as $personalPostsResults){ ?>
          <a href="viewPost.php?blogId=<?php echo $personalPostsResults["blogId"]; ?>" class="col-md-6 col-sm-12 col-lg-3 blog-section personal-post-container">
            <?php echo $personalPostsResults["blogTitle"]; ?>
          </a>
          <?php } ?>
        </div>


        <p>
          Posts your friends have written:
        </p>

        <div class="blog-section">
          <?php foreach($pdo->query($friendPostsQuery) as $friendPostsResults){ ?>
            <a href="viewPost.php?blogId=<?php echo $friendPostsResults["blogId"]; ?>" class="col-md-6 col-sm-12 col-lg-3 blog-section friend-post-container">
              <div class="author-box">
                <img class="author-picture" src="<?php echo $friendPostsResults['profileImage']; ?>"> <?php echo $friendPostsResults['firstName'];  ?> wrote
              </div>
              <div class="blog-title">
                <?php echo $friendPostsResults['blogTitle']; ?>
              </div>
            </a>
          <?php } ?>
        </div>

      </div>






    </div>





  </div>

</body>
<?php Database::disconnect(); ?>
