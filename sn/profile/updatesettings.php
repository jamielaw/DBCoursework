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

	$friendsettingtype = $_GET['setting1'];
	$searchsettingtype = $_GET['setting2'];
	$blogsettingtype = $_GET['setting3'];
	$photosettingtype = $_GET['setting4'];
	$messagesettingtype = $_GET['setting5'];

	$sql1 = "UPDATE MyDB.privacySettings SET privacyType='".$friendsettingtype."' WHERE(email='" . $loggedInUser. "' AND privacyTitleId='1')";
	$pdo->exec($sql1);

	$sql2 = "UPDATE MyDB.privacySettings SET privacyType='".$searchsettingtype."' WHERE(email='" . $loggedInUser. "' AND privacyTitleId='2')";
	$pdo->exec($sql2);

	$sql3 = "UPDATE MyDB.privacySettings SET privacyType='".$blogsettingtype."' WHERE(email='" . $loggedInUser. "' AND privacyTitleId='3')";
	$pdo->exec($sql3);

	$sql4 = "UPDATE MyDB.privacySettings SET privacyType='".$photosettingtype."' WHERE(email='" . $loggedInUser. "' AND privacyTitleId='4')";
	$pdo->exec($sql4);

	$sql5 = "UPDATE MyDB.privacySettings SET privacyType='".$messagesettingtype."' WHERE(email='" . $loggedInUser. "' AND privacyTitleId='5')";
	$pdo->exec($sql5);

	Database::disconnect();
	redirect('/sn/profile/settings.php');
?>