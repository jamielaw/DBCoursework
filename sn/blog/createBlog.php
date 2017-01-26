<?php
  require '../database.php';
  $title = "Bookface Social Network";
  $description = "A far superior social network";
  include("../inc/header.php");



  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>

<body>
  <!--  Navigation-->
  <?php include '../inc/nav-trn.php'; ?>
  <div class="container">
    <div class="span10 offset1">
      <div class="row">
        <font size="5">Create a blog post</font>
      </div>

      <form class="form-horizontal" method="POST" action="createBlogHandler.php">
        <input type="hidden" name="argument1" value="<?php echo $argument1;?>">
        <div class="control-group">
          <label class="control-label">Blog Title:</label>
          <div class="controls">
            <input type="text" name="blogTitle" value="<?php echo $row['blogTitle'];?>"> <br>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Content:</label>
          <div class="controls">
            <textarea style="width:100%" name="blogDescription" ><?php echo $row['blogDescription'];?></textarea> <br>
          </div>
        </div>

        <button type="submit">Create!</button>
      </form>
    </div>
    </div>

<?php Database::disconnect(); ?>


</body>
