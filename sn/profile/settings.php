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
		<form class="" action="createcircle.php" method="get" id="createform">
			<?php //we want to get the privacy settings of the user here and echo it accordingly
				$sql = "SELECT * FROM MyDB.privacySettings WHERE email='" . $loggedInUser . "'";
				foreach($pdo->query($sql) as $row){
					echo "<h3>" . $row["privacySettingsTitle"] . "</h3>";
					if($row["status"]==True){
						echo "<input type='checkbox' name='" . $row["privacySettingsId"] . "' value = '" . $row["privacySettingsTitle"] . "' checked>" . $row["privacySettingsDescription"] . "</input>";
					}
					else{
						echo "<input type='checkbox' name='" . $row["privacySettingsId"] . "' value = '" . $row["privacySettingsTitle"] . "'>" . $row["privacySettingsDescription"] . "</input>";
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