<?php
  //require '../database.php';
  $title = "Bookface Social Network";
  $description = "A far superior social network";
  include("../../inc/header.php");
?>

<body>
  <?php
    include("../../inc/nav-trn.php");
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $argument1 = htmlspecialchars($_GET['email']);
    $sql = "SELECT * FROM users WHERE email=" . "'" . $argument1 . "'";

    $q= $pdo->prepare($sql);
    $q->execute();
    $row = $q->fetch(PDO::FETCH_ASSOC);



    function getPrivacyDescription($id)
      {
          $pdo = Database::connect();
          $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $sql = 'SELECT * FROM privacyTitles WHERE privacyTitleId = ?;';
          $q = $pdo->prepare($sql);
          $q->execute(array($id));
          $data = $q->fetch(PDO::FETCH_ASSOC);
          return $data;
      }

  ?>
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

      <div class="row">
        <font size="5">Update User's Privacy Settings</font>
      </div>
      <form class="" action="../privacySettings/updateSettings.php" method="get" id="updateform">
  			<?php //we want to get the privacy settings of the user here and echo it accordingly
  				$sql = "SELECT * FROM MyDB.privacySettings WHERE email='" .$argument1. "'";
  				foreach($pdo->query($sql) as $settings){
  					$description=getPrivacyDescription($settings['privacyTitleId'])['privacySettingsDescription'];
  					echo "<label class='control-label'>" . $description . "</label>";
  					if($description=="Who can send me friend requests?"){ //friend requests
  						$selected = $settings["privacyType"];
  						echo '<div class="form-group">
  						<select class="form-control" name="setting1" value="'.$settings["privacySettingsId"].'">';
  						if($selected=="None"){
  							echo '<option selected>None</option>
  						<option>Friends of friends</option>
  						<option>Anybody</option>';
  						}else if($selected=="Friends of friends"){
  							echo '<option>None</option>
  						<option selected>Friends of friends</option>
  						<option>Anybody</option>';
  						}else{ //anybody
  							echo '<option>None</option>
  						<option>Friends of friends</option>
  						<option selected>Anybody</option>';
  						}
  						echo '</select>';
  					}

  					if($description=="Who can search me?"){
  						$selected = $settings["privacyType"];
  						echo '<div class="form-group">
  						<select class="form-control" name="setting2" value="'.$settings["privacySettingsId"].'">';
  						if($selected=="None"){
  							echo '<option selected>None</option>
  						<option>Friends of friends</option>
  						<option>Anybody</option>';
  						}else if($selected=="Friends of friends"){
  							echo '<option>None</option>
  						<option selected>Friends of friends</option>
  						<option>Anybody</option>';
  						}else{ //anybody
  							echo '<option>None</option>
  						<option>Friends of friends</option>
  						<option selected>Anybody</option>';
  						}
  						echo '</select>';
  					}

  					if($description=="Who can view my blogs?"){
  						$selected = $settings["privacyType"];
  						echo '<div class="form-group">
  						<select class="form-control" name="setting3" value="'.$settings["privacySettingsId"].'">';
  						if($selected=="None"){
  							echo '<option selected>None</option>
  						<option>Friends of friends</option>
  						<option>Anybody</option>';
  						}else if($selected=="Friends of friends"){
  							echo '<option>None</option>
  						<option selected>Friends of friends</option>
  						<option>Anybody</option>';
  						}else{ //anybody
  							echo '<option>None</option>
  						<option>Friends of friends</option>
  						<option selected>Anybody</option>';
  						}
  						echo '</select>';
  					}

  					if($description=="Who can see my photo collections?"){
  						$selected = $settings["privacyType"];
  						echo '<div class="form-group">
  						<select class="form-control" name="setting4" value="'.$settings["privacySettingsId"].'">';
  						if($selected=="None"){
  							echo '<option selected>None</option>
  						<option>Friends of friends</option>
  						<option>Anybody</option>';
  						}else if($selected=="Friends of friends"){
  							echo '<option>None</option>
  						<option selected>Friends of friends</option>
  						<option>Anybody</option>';
  						}else{ //anybody
  							echo '<option>None</option>
  						<option>Friends of friends</option>
  						<option selected>Anybody</option>';
  						}
  						echo '</select>';
  					}

  					if($description=="Who can send me messages?"){
  						$selected = $settings["privacyType"];
  						echo '<div class="form-group">
  						<select class="form-control" name="setting5" value="'.$settings["privacySettingsId"].'">';
  						if($selected=="None"){
  							echo '<option selected>None</option>
  						<option>Friends of friends</option>
  						<option>Anybody</option>';
  						}else if($selected=="Friends of friends"){
  							echo '<option>None</option>
  						<option selected>Friends of friends</option>
  						<option>Anybody</option>';
  						}else{ //anybody
  							echo '<option>None</option>
  						<option>Friends of friends</option>
  						<option selected>Anybody</option>';
  						}
  						echo '</select>';
  					}
  					echo "<br>";
  				}
  			?>
  			<button type="submit">Update Settings</button>
  		</form>
      <div class="row">
        <font size="5">Change Admin Access</font>
      </div>
      <?php
      if($row['roleID'] == 1 ){
        echo "<p> This user is an admin. </p>";
        echo "<button id='revoke' class='btn btn-danger'>Revoke</button>";
      }else if($row['roleID'] == 2){
        echo "<p> This user is not an admin. </p> ";
        echo "<button id='empower' class='btn btn-danger'>Make Admin</button>";
      }
      ?>
    </div>
    </div>

<?php Database::disconnect(); ?>


</body>
<script>
  $("#revoke").click(function(){
    $.get("../meta/changeAdmin.php?adminStatus=2&email=<?php echo  $row['email']; ?>", function(data){
      //console.log(data);
      location.reload();
    });
  });

  $("#empower").click(function(){
    $.get("../meta/changeAdmin.php?adminStatus=1&email=<?php echo $row['email']; ?>", function(){
      location.reload();
    });
  });
</script>
