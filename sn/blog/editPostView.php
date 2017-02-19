<?php
  //require '../database.php';
  $title = "Bookface Social Network";
  $description = "A far superior social network";
  include("../inc/header.php");
  INCLUDE("../inc/nav-trn.php");



  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $argument1 = htmlspecialchars($_GET['blogId']);



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
      <button id="previewBtn" > Preview </button>
      <button id="editBtn" style=""> Edit </button>

      <form class="form-horizontal" method="POST" action="editPost.php">
        <input type="hidden" name="argument1" value="<?php echo $argument1;?>">
        <div class="control-group">
          <label class="control-label">Blog Title:</label>
          <div class="controls">
            <input type="text" name="blogTitle" value="<?php echo $row['blogTitle'];?>"> <br>
          </div>
        </div>
        <div class="control-group">

          <div class="controls" id="editArea">
          <label class="control-label">Content:</label>
            <textarea id="rawText" style="width:100%" name="blogDescription" ><?php echo $row['blogDescription'];?></textarea> <br>
          </div>
          <div id="MDpreview" style="width:100%;">

          </div>
        </div>
        <button type="submit">Update!</button>
      </form>
    </div>
    </div>

<?php Database::disconnect(); ?>
</body>

<script>

$(document).ready(function(){
  $("#previewBtn").click(function(){
    var converter = new showdown.Converter(),
        text      = $('#rawText')[0]['textContent'];
        html      =  converter.makeHtml(text);
    $("#MDpreview").html(html);// = html;
    $("#MDpreview").show();
    $("#editArea").hide();
    $("#editBtn").show();

  });

  $("#editBtn").click(function(){
    $("#MDpreview").hide();
    $("#editArea").show();
  });

});

</script>


    <?php include '../inc/footer.php'; ?>
