<?php
  require '../database.php';
  $title = "Bookface Social Network";
  $description = "A far superior social network";
  include("../inc/header.php");



  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $argument1 = $_GET['email'];



  $sql = "SELECT * FROM users WHERE email=" . "'" . $argument1 . "'";

  $q= $pdo->prepare($sql);
  $q->execute();
  $row = $q->fetch(PDO::FETCH_ASSOC);


?>

<body>
  <!--  Navigation-->
  <?php include '../inc/nav-trn.php'; ?>
  <div class="container">
    <div class="span10 offset1">
      <div class="row">
        <font size="5">Update User</font>
      </div>

      <form class="form-horizontal" method="POST" action="editUser.php">
        <input type="hidden" name="argument1" value="<?php echo $argument1;?>">
        <div class="control-group">
          <label class="control-label">Email:</label>
          <div class="controls">
            <input type="text" name="email" value="<?php echo $row['email'];?>"> <br>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">First name:</label>
          <div class="controls">
            <input type="text" name="firstName" value="<?php echo $row['firstName'];?>"> <br>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Second Name:</label>
          <div class="controls">
            <input type="text" name="lastName" value="<?php echo $row['lastName'];?>"> <br>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Image URL:</label>
          <div class="controls">
            <input type="textfield" name="profileImage" value="<?php echo $row['profileImage']?>"> <br>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Profile Description</label>
          <div class="controls">
            <input type="text" name="profileDescription" value="<?php echo $row['profileDescription'];?>"> <br>
          </div>
        </div>
        <button type="submit">Update!</button>
      </form>
    </div>
    </div>

<?php Database::disconnect(); ?>


</body>
