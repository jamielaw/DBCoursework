<?php 
require("../database.php");
if (isset($_REQUEST['query'])) {
	$pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = $_REQUEST['query'];
    $loggedInUser = "vicky@ucl.ac.uk"; //remember to update this
    $sql="SELECT firstName, lastName FROM MyDB.users WHERE ((firstName LIKE '" . $query .  "%' OR lastName LIKE '" . $query ."%') AND (email IN (SELECT emailTo FROM MyDB.friendships WHERE (emailFrom='" . $loggedInUser . "' AND status='accepted')) OR email IN (SELECT emailFrom FROM MyDB.friendships WHERE ( emailTo='". $loggedInUser . "' AND status='accepted'))))"; ;
    foreach($pdo->query($sql) as $row){
                //-display the result of the array
    	//we don't want to use array() as that messes up the formatting of the output, we just want to push onto it
    	$array[]=$row["firstName"] . " " . $row["lastName"];
    }
    echo json_encode($array);
}
?>