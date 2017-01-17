<?php
$servername = "localhost:8889";
$username = "root";
$password = "root";

// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}else{
  echo "working";
}

// Create users database
$createDatabase = "CREATE DATABASE IF NOT EXISTS MyDB";

//Create roles table
$createRolesTable = "CREATE TABLE IF NOT EXISTS MyDB.roles(
  roleID INT NOT NULL, roleTitle VARCHAR(255),
  PRIMARY KEY(roleID)
)";



//Create users table
$createUsersTable = "CREATE TABLE IF NOT EXISTS MyDB.users(
  email VARCHAR(255) NOT NULL,
  roleID INT NOT NULL,
  password VARCHAR(255) NOT NULL,
  firstName VARCHAR(255) NOT NULL,
  lastName VARCHAR(255) NOT NULL,
  profileImage VARCHAR(255) NOT NULL,
  profileDescription VARCHAR(255) NOT NULL,
  PRIMARY KEY(email)
  -- FOREIGN KEY (roleID) REFERENCES MyDB.roles(roleID)
)";


//Create rights table
$createRightsTable = "CREATE TABLE IF NOT EXISTS MyDB.rights(
  rightID INT NOT NULL,
  roleID INT NOT NULL,
  rightTitle VARCHAR(255) NOT NULL,
  rightDescription VARCHAR(255) NOT NULL,
  PRIMARY KEY(rightID)
  -- FOREIGN KEY(roleID) REFERENCES MyDB.roles(roleID)
)";


// Create table for annotations
$createAnnotationsTable = "CREATE TABLE IF NOT EXISTS myDB.annotations(
  anotationsId INT NOT NULL,
  coordinateX INT NOT NULL,
  cordinateY INT NOT NULL,
  annotationText VARCHAR(255) NOT NULL,
  PRIMARY KEY(annotationsId),
  -- FOREIGN KEY(photoId),
  -- FOREIGN KEY(annotatedBy)
	)";


// Create table for Photos
$createPhotosTable = "CREATE TABLE IF NOT EXISTS myDB.photos(
  photosId INT NOT NULL,
  dateAdded DATETIME NOT NULL,
  imageReference VARCHAR(255) NOT NULL,
  PRIMARY KEY(photosId)
  -- FOREIGN KEY(photoCollectionsId)
)";

// Create table for comments
$createTableForComments = "CREATE TABLE IF NOT EXISTS myDB.comments(
  postId INT NOT NULL,
  postTitle INT NOT NULL,
  postText TEXT NOT NULL,
  dateCreated DATETIME,
  PRIMARY KEY(photosId)
)";

// Create table for Access Rights
$createAccessRightsTable = "CREATE TABLE IF NOT EXISTS myDB.accessRights(
  accessRightId INT NOT NULL,
  PRIMARY KEY(accessRightsId)
  --FOREIGN KEY(photoCollectionsId),
  -- FOREIGN KEY(email),
  -- FOREIGN KEY(circleFriendsId),
)";

// Create table for Photo Collections
$createPhotoCollectionsTable = "CREATE TABLE IF NOT EXISTS myDB.photoCollectionId(
  photoCollectionId INT NOT NULL,
  dateCreated DATETIME NOT NULL,
  description VARCHAR(255) NOT NULL,
  createdBy VARCHAR(255) NOT NULL,
  PRIMARY KEY(photoCollectionsId)
)";


// Create table for user circle relatiorships
// COME BACK AND FIX THIS
// $sql = "CREATE TABLE IF NOT EXISTS myDB.userCircleRelationships(
//   email VARCHAR(255) NOT NULL,
//   cricleFriendsId
//   PRIMARY KEY(photoCollectionsId)
// )";


// Create table for Cricle of friends
$createCircleOfFriendsTable = "CREATE TABLE IF NOT EXISTS myDB.circleOfFriends(
  circleFriendsId INT NOT NULL,
  circleOfFriendsName VARCHAR(255) DATETIME,
  dateCreated DATETIME NOT NULL,
  PRIMARY KEY(circleFriendsId)
)";

// Create table for messages
$createMessagesTable = "CREATE TABLE IF NOT EXISTS myDB.messages(
  messagesId INT NOT NULL,
  messageText VARCHAR(255) NOT NULL,
  dateCreated DATETIME NOT NULL,
  PRIMARY KEY(messagesId)
  -- FOREIGN KEY
)";


// Create table for privacy settings
$createPrivacySettingsTable = "CREATE TABLE IF NOT EXISTS myDB.privacySettings(
  privacySettingsId INT NOT NULL,
  privacySettingsTitle VARCHAR(255) NOT NULL,
  privacySettingsDescription VARCHAR NOT NULL,
  status BOOLEAN NOT NULL
  -- FK:
)";

// Create table friendships
$createFriendshipsTable = "CREATE TABLE IF NOT EXISTS myDB.friendships(
  friendshipid INT NOT NULL,
  emailFrom VARCHAR(255) NOT NULL,
  emailTo VARCHAR(255) NOT NULL,
  status BOOLEAN NOT NULL
  -- FK:
)";

$creatingTables = [
    $createDatabase,
    $createRolesTable,
    $createUsersTable,
    $createRightsTable
];


foreach ($creatingTables as $sqlquery){
  echo $sqlquery;
  if ($conn->query($sqlquery) === TRUE) {
      echo "Database created successfully";
  } else {
      echo "Error creating database: " . $conn->error;
  }
}


$conn->close();
?>
