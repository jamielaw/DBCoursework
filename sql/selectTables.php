<?php
$servername = "localhost:3306";
$username = "root";
$password = "root";

// create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}else{
  echo "Connection established";
}


//select roles table
$selectRolesTable = "SELECT * FROM MyDB.roles";

//select users table
$selectUsersTable = "SELECT * FROM MyDB.users";

//select rights table
$selectRightsTable = "SELECT * FROM MyDB.rights";

// select table friendships
$selectFriendshipsTable = "SELECT * FROM MyDB.friendships";


//select blogs table
$selectBlogsTable = "SELECT * FROM MyDB.blogs";


//select posts table
$selectPostsTable = "SELECT * FROM MyDB.posts";

// select table for annotations
$selectAnnotationsTable = "SELECT * FROM MyDB.annotations";


// select table for Photos
$selectPhotosTable = "SELECT * FROM MyDB.photos";

// select table for comments (on photos)
$selectCommentsTable = "SELECT * FROM MyDB.comments";

// select table for Access Rights
$selectAccessRightsTable = "SELECT * FROM MyDB.accessRights";

// select table for Photo Collections
$selectPhotoCollectionsTable = "SELECT * FROM MyDB.photoCollection";


// select table for user circle relationships
$selectUserCircleRelationshipsTable = "SELECT * FROM MyDB.userCircleRelationships";


// select table for Circle of friends
$selectCircleOfFriendsTable = "SELECT * FROM MyDB.circleOfFriends";

// select table for messages
$selectMessagesTable = "SELECT * FROM MyDB.messages";

// select table for privacy settings
$selectPrivacySettingsTable = "SELECT * FROM MyDB.privacySettings";



$selectingTables = [ 
    $selectRolesTable,
    $selectUsersTable,
    $selectRightsTable,
    $selectFriendshipsTable,
    $selectBlogsTable,
    $selectPostsTable,
    $selectPrivacySettingsTable,
    $selectCircleOfFriendsTable,
    $selectMessagesTable,
    $selectUserCircleRelationshipsTable,
    $selectPhotoCollectionsTable,
    $selectPhotosTable,
    $selectCommentsTable,
    $selectAnnotationsTable,
    $selectAccessRightsTable
];


foreach ($selectingTables as $sqlquery){
  echo nl2br("\n"); //Line break in HTML conversion
  echo "<b>Executing SQL statement: </b>";
  echo $sqlquery; //Dispay statement being executed
  echo nl2br("\n");
  $result=$conn->query($sqlquery);
  if ($result->num_rows > 0) {
        // output data of each row. create a table.
        $numberofresults = 0;

        while($row = $result->fetch_assoc()) {
            $numberofresults++;
        }

        echo $numberofresults . " results";

    } else {
        echo "0 results";
    }
  }

$conn->close();
?>