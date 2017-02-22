<?php 
require("../session.php");
if (isset($_REQUEST['query'])) {
	$pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = $_REQUEST['query'];

    //firstly, we get the friends of the logged-in user and prepare it for use in the main sql search query
    $getFriends="SELECT email FROM MyDB.users WHERE(email IN (SELECT emailTo FROM MyDB.friendships WHERE (emailFrom='" . $loggedInUser . "' AND status='accepted')) OR email IN (SELECT emailFrom FROM MyDB.friendships WHERE ( emailTo='". $loggedInUser . "' AND status='accepted')))";

    $countFriends="SELECT COUNT(email) FROM MyDB.users WHERE(email IN (SELECT emailTo FROM MyDB.friendships WHERE (emailFrom='" . $loggedInUser . "' AND status='accepted')) OR email IN (SELECT emailFrom FROM MyDB.friendships WHERE ( emailTo='". $loggedInUser . "' AND status='accepted')))";

    $q = $pdo->query($countFriends);
    $countFriendsResult = $q->fetch(PDO::FETCH_ASSOC);
    $countF = $countFriendsResult["COUNT(email)"];

    $currentFriend = 0; //keep track of how many friends there are to keep the correct formatting
    $friends[] .= "("; //this friends array stores the list of friends to the logged-in-user in a format which is easy to use in the sql query

    foreach($pdo->query($getFriends) as $row){
      $currentFriend += 1;
      if($currentFriend==$countF){ //last friend
        $friends[] .= "'" . $row["email"] . "'";
      }else{
        $friends[] .= "'" . $row["email"] . "',";
      }
    }
    $friends[] .= ")";

    $friendStr = implode($friends);

    $sql="SELECT firstName, lastName FROM MyDB.users WHERE ((firstName LIKE '" . $query .  "%' OR lastName LIKE '" . $query ."%') AND (email IN (SELECT emailTo FROM MyDB.friendships WHERE (emailFrom='" . $loggedInUser . "' AND status='accepted')) OR email IN (SELECT emailFrom FROM MyDB.friendships WHERE ( emailTo='". $loggedInUser . "' AND status='accepted')) OR email IN (SELECT emailTo FROM MyDB.friendships WHERE (emailFrom IN " .$friendStr . " AND status='accepted') OR email IN (SELECT emailFrom FROM MyDB.friendships WHERE(emailTo IN " . $friendStr . " AND status='accepted')))))";
    foreach($pdo->query($sql) as $row){
                //-display the result of the array
    	//we don't want to use array() as that messes up the formatting of the output, we just want to push onto it
    	$array[]=$row["firstName"] . " " . $row["lastName"];
    }
    echo json_encode($array);
}
?>