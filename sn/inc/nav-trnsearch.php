<?php 
require("../database.php");
if (isset($_REQUEST['query'])) {
	$pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = $_REQUEST['query'];
    $sql="SELECT firstName, lastName FROM MyDB.users WHERE firstName LIKE '" . $query .  "%' OR lastName LIKE '" . $query ."%'"; 
    foreach($pdo->query($sql) as $row){
                //-display the result of the array
    	//we don't want to use array() as that messes up the formatting of the output, we just want to push onto it
    	$array[]=$row["firstName"] . " " . $row["lastName"];
    }
    echo json_encode($array);
}
?>