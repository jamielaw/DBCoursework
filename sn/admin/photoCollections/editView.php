<?php
  //require '../database.php';
  $title = "Bookface Social Network";
  $description = "A far superior social network";
  include("../../inc/header.php");
  INCLUDE("../../inc/nav-trn.php");



  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $argument1 = $_GET['pcId'];

  $sql = "SELECT * FROM photoCollection WHERE photoCollectionId=" .$argument1;
  //echo $sql;
  $q= $pdo->prepare($sql);
  $q->execute();
  $row = $q->fetch(PDO::FETCH_ASSOC);

?>

<body>
  <div class="container">
    <div class="">
      <div class="row">
        <h1 style="margin-bottom: 15px;"> Update Photo Collections</h1>

      <form class="form-horizontal" method="POST" action="edit.php">
        <input type="hidden" name="photoCollectionId" value="<?php echo $argument1;?>">
        <div id="blogTitleBar">
          <div class="control-group">
            <label class="control-label">Photo Collection Name:</label>
            <div class="controls">
              <input type="text" name="albumName" style="width: 30vw;"value="<?php echo $row['title'];?>"> <br>
            </div>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Photo Collection Description:  </label><br>
          <input type="text" name="albumDescription" style="width: 30vw;"value="<?php echo $row['description'];?>"> <br>

        </div>
        <button style="margin-top:10px;" class="btn btn-success" type="submit">Save</button>
      </form>
      </div>
    </div>
    </div>

<?php Database::disconnect(); ?>
</body>
