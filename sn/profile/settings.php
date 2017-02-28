<!DOCTYPE html>
<html>
<head>
	<title>Settings</title>
	<?php
	  include '../inc/header.php'; 
	  include '../inc/nav-trn.php'; 
	?>
</head>
<body>
	<div class="container">
		<h1>Your Privacy Settings</h1>
		<form class="" action="updatesettings.php" method="get" id="updateform">
			<?php //we want to get the privacy settings of the user here and echo it accordingly
				$sql = "SELECT * FROM MyDB.privacySettings WHERE email='" . $loggedInUser . "'";
				foreach($pdo->query($sql) as $row){
					echo "<h3>" . $row["privacySettingsTitle"] . "</h3>";
					// if($row["privacySettingsIndex"]==1){ //photos
					// 	$selected = $row["privacySettingsDescription"];
					// 	echo '<div class="form-group">
					// 	<select class="form-control" name="setting1" value="'.$row["PrivacySettingsId"].'">';
					// 	if($selected=="Only me"){
					// 		echo '<option selected>Only me</option>
					// 	<option>Friends</option>
					// 	<option>Anybody</option>';
					// 	}else if($selected=="Friends"){
					// 		echo '<option>Only me</option>
					// 	<option selected>Friends</option>
					// 	<option>Anybody</option>';
					// 	}else{ //anybody
					// 		echo '<option>Only me</option>
					// 	<option>Friends</option>
					// 	<option selected>Anybody</option>';
					// 	}
					// 	echo '</select>';
					// }
					if($row["privacySettingsTitle"]=="Who can send me friend requests?"){ //friend requests
						$selected = $row["privacySettingsDescription"];
						echo '<div class="form-group">
						<select class="form-control" name="setting1" value="'.$row["PrivacySettingsId"].'">';
						if($selected=="Noone"){
							echo '<option selected>Noone</option>
						<option>Friends of friends</option>
						<option>Anybody</option>';
						}else if($selected=="Friends of friends"){
							echo '<option>Noone</option>
						<option selected>Friends of friends</option>
						<option>Anybody</option>';
						}else{ //anybody
							echo '<option>Noone</option>
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
	</div>
</body>
<?php
	include '../inc/footer.php'; 
?>
</html>