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

  $friendsArray = array();
  $friends = "SELECT * FROM users JOIN friendships ON
  users.email = friendships.emailFrom OR
  users.email=friendships.emailTo WHERE
  (friendships.emailFrom='". $loggedInUser.
  "' OR friendships.emailTo='". $loggedInUser .
   "' ) AND users.email!= '". $loggedInUser.
   "' AND status='accepted'";

   foreach($pdo->query($friends) as $row){
     $friendsArray[$row['email']] = 1;
     //echo $row['email'];
   }

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
          <?php foreach($pdo->query($friendPostsQuery) as $friendPostsResults){
            //echo $friendPostsResults['email'];
            $checkPrivacy = "SELECT privacyType FROM privacySettings WHERE email = '" . $friendPostsResults['email'] . "' AND privacyTitleId = 3";
            $y = $pdo->prepare($checkPrivacy );
            $y->execute();
            $privacyResults =$y->fetch(PDO::FETCH_ASSOC);
            if( $privacyResults['privacyType'] == "None") continue;
          ?>
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

        <p>
          Public blog posts from around the world
        </p>

        <div class="blog-section">

          <?php
            $allOpenBlogAccounts = "SELECT email FROM privacySettings WHERE privacySettings.privacyTitleId=3 AND privacySettings.privacyType = 'Anyone'";
            foreach($pdo->query($allOpenBlogAccounts) as $openBlog){
              //echo $openBlog['email'];
              if($openBlog['email'] == $loggedInUser) continue;

              if($friendsArray[$openBlog['email']] == 1 ) continue;

              $blog= "SELECT * FROM users JOIN blogs ON users.email = blogs.email WHERE blogs.email = '" . $openBlog['email'] ."'" ; //. "' AND privacyTitleId = 3";
              //echo $blog;
              $y = $pdo->prepare($blog );
              $y->execute();
              $blogData =$y->fetch(PDO::FETCH_ASSOC);
              //echo $blogData['email'];

              if($blogData == null) continue;

          ?>
          <a href="viewPost.php?blogId=<?php echo $blogData["blogId"]; ?>" class="col-md-6 col-sm-12 col-lg-3 blog-section friend-post-container">
            <div class="author-box">
              <img class="author-picture" src="<?php echo $blogData['profileImage']; ?>"> <?php echo $blogData['firstName'];  ?> wrote
            </div>
            <div class="blog-title">
              <?php echo $blogData['blogTitle']; ?>
            </div>
          </a>
          <?php } ?>
        </div>

      </div>






    </div>





  </div>

</body>
    <?php include '../inc/footer.php'; ?>
<?php Database::disconnect(); ?>
