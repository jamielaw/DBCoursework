<!DOCTYPE html>
<html>
<head>
	<title>Settings</title>
	<?php
	  	include '../inc/header.php'; 
	  	include '../inc/nav-trn.php';

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
</head>
<body>
	<div class="container">
		<h1>Your Privacy Settings</h1>
		<form class="" action="updatesettings.php" method="get" id="updateform">
			<?php //we want to get the privacy settings of the user here and echo it accordingly
				$sql = "SELECT * FROM MyDB.privacySettings WHERE email='" . $loggedInUser . "'";
				foreach($pdo->query($sql) as $row){
					$description=getPrivacyDescription($row['privacyTitleId'])['privacySettingsDescription'];
					echo "<h3>" . $description . "</h3>";
					if($description=="Who can send me friend requests?"){ //friend requests
						$selected = $row["privacyType"];
						echo '<div class="form-group">
						<select class="form-control" name="setting1" value="'.$row["privacySettingsId"].'">';
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
						$selected = $row["privacyType"];
						echo '<div class="form-group">
						<select class="form-control" name="setting2" value="'.$row["privacySettingsId"].'">';
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
						$selected = $row["privacyType"];
						echo '<div class="form-group">
						<select class="form-control" name="setting3" value="'.$row["privacySettingsId"].'">';
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
						$selected = $row["privacyType"];
						echo '<div class="form-group">
						<select class="form-control" name="setting4" value="'.$row["privacySettingsId"].'">';
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
						$selected = $row["privacyType"];
						echo '<div class="form-group">
						<select class="form-control" name="setting5" value="'.$row["privacySettingsId"].'">';
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
	</div>
</body>
<?php
	include '../inc/footer.php'; 
?>
</html>