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
    <div class="">
      <div class="row">
        <h1 style="margin-bottom: 15px;"> Update Blog Post</h1>

      <button class="btn btn-info" id="previewBtn" > Preview </button>
      <button class="btn btn-warning" id="editBtn" style=""> Edit </button>

      <form class="form-horizontal" method="POST" action="editPost.php">
        <input type="hidden" name="argument1" value="<?php echo $argument1;?>">
        <div id="blogTitleBar">
          <div class="control-group">
            <label class="control-label">Blog Title:</label>
            <div class="controls">
              <input type="text" name="blogTitle" style="width: 30vw;"value="<?php echo $row['blogTitle'];?>"> <br>
            </div>
          </div>
        </div>
        <div class="control-group">
          <div class="controls" id="editArea">
          <label class="control-label">Content:</label>
            <textarea id="rawText" style="width:100%; height: 55vh;" name="blogDescription" ><?php echo $row['blogDescription'];?></textarea> <br>
          </div>
          <div id="MDpreview" style="width:100%; text-align:center;">
          </div>
        </div>
        <button class="btn btn-success" type="submit">Save</button>
      </form>
      </div>
    </div>
    </div>

<?php Database::disconnect(); ?>
</body>

<script>

$(document).ready(function(){
  $("#previewBtn").click(function(){
    var converter = new showdown.Converter(),
        text      = $('#rawText').val();
        html      =  converter.makeHtml(text);
    $("#MDpreview").html(html);
    $("#MDpreview").show();
    $("#editArea").hide();
    $("#editBtn").show();
    $("#blogTitleBar").hide();

  });

  $("#editBtn").click(function(){
    $("#MDpreview").hide();
    $("#editArea").show();
    $("#blogTitleBar").show();
  });

});

</script>
