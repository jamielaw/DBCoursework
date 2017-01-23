<?php
  require '../../database.php';
  $title = "Bookface Social Network";
  $description = "A far superior social network";
  include("../../inc/header.php");


  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $argument1 = $_GET['annotationsId'];
  $sql = "SELECT * FROM annotations WHERE annotationsId=" . $argument1;

  $q= $pdo->prepare($sql);
  $q->execute();
  $row = $q->fetch(PDO::FETCH_ASSOC);


?>

<body>
  <!--  Navigation-->
  <?php include '../../inc/nav-trn.php'; ?>
  <div class="container">
    <div class="span10 offset1">
      <div class="row">
        <font size="5">Update annotations</font>
      </div>

      <form class="form-horizontal" method="POST" action="editAnnotations.php">
        <input type="hidden" name="argument1" value="<?php echo $argument1;?>">
        <div class="control-group">
          <label class="control-label">Annotator:</label>
          <div class="controls">
            <input type="text" name="email" value="<?php echo $row['email'];?>"> <br>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">CoordinateX:</label>
          <div class="controls">
            <input type="text" name="coordinateX" value="<?php echo $row['coordinateX'];?>"> <br>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">CoordinateY</label>
          <div class="controls">
            <input type="text" name="coordinateY" value="<?php echo $row['coordinateY'];?>"> <br>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Text</label>
          <div class="controls">
            <input type="text" name="annotationText" value="<?php echo $row['annotationText'];?>"> <br>
          </div>
        </div>
        <button type="submit">Update!</button>
      </form>
    </div>
    </div>

<?php Database::disconnect(); ?>


</body>
