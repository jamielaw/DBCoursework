<?php
  //require '../database.php';
  $title = "Bookface Social Network";
  $description = "A far superior social network";
  include("../inc/header.php");
  INCLUDE("../inc/nav-trn.php");



  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $argument1 = $_GET['blogId'];



  $sql = "SELECT * FROM blogs WHERE blogId=" .$argument1;

  $q= $pdo->prepare($sql);
  $q->execute();
  $row = $q->fetch(PDO::FETCH_ASSOC);


?>

<body>
  <div class="container">
    <div class="span10 offset1">
      <div class="row">
        <font size="5">Update Blog Post</font>
      </div>

      <form class="form-horizontal" method="POST" action="editPost.php">
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

        <button type="submit">Update!</button>
      </form>
    </div>
    </div>

<?php Database::disconnect(); ?>


</body>
    <?php include '../inc/footer.php'; ?>
