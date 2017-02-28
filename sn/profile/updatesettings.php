<?php
	//update settings here on settings.php submit
  	require("../session.php");

	//function to redirect - to be moved into a utils.php file later?
	function redirect($url) {
	ob_start();
	header('Location: '.$url);
	ob_end_flush();
	die();
	}

	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$photosetting = $_GET['setting1'];
	$friendsetting = $_GET['setting2'];

	$sql1 = "UPDATE MyDB.privacySettings SET privacySettingsDescription='".$photosetting."' WHERE(privacySettingsIndex=1 AND email='" . $loggedInUser. "')";
	$sql2 = "UPDATE MyDB.privacySettings SET privacySettingsDescription='".$friendsetting."' WHERE(privacySettingsIndex=2 AND email='" . $loggedInUser. "')";
	$pdo->exec($sql1);
	$pdo->exec($sql2);

	Database::disconnect();
	redirect('/sn/profile/settings.php');
?>