<?php
	//update settings here on settings.php submit
	require '../database.php';
	$loggedInUser = 'charles@ucl.ac.uk';

	//function to redirect - to be moved into a utils.php file later?
	function redirect($url) {
	ob_start();
	header('Location: '.$url);
	ob_end_flush();
	die();
	}

	//make everything false to begin with, then make the ones that were checked true
	$falsify = "UPDATE MyDB.privacySettings Set Status=FALSE WHERE(email='" . $loggedInUser . "')";
	// echo $falsify;
	// echo "<br>";
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->exec($falsify);

	foreach($_GET['setting'] as $setting){
		$sql = "UPDATE MyDB.privacySettings SET Status=TRUE WHERE(privacySettingsId=" . $setting . " AND email='" . $loggedInUser . "')";
	    // echo $sql;
	    // echo "<br>";
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->exec($sql);
	}

	Database::disconnect();
	redirect('/sn/profile/settings.php');
?>