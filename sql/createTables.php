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
  echo "Connection established";
}

// Drop database if necessary
$dropDatabase = "DROP DATABASE IF EXISTS MyDB";

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
  PRIMARY KEY(email),
  FOREIGN KEY(roleID) REFERENCES MyDB.roles(roleID)
)";

//Create rights table
$createRightsTable = "CREATE TABLE IF NOT EXISTS MyDB.rights(
  rightID INT NOT NULL,
  roleID INT NOT NULL,
  rightTitle VARCHAR(255) NOT NULL,
  rightDescription VARCHAR(255) NOT NULL,
  PRIMARY KEY(rightID),
  FOREIGN KEY(roleID) REFERENCES MyDB.roles(roleID)
)";

// Create table friendships
$createFriendshipsTable = "CREATE TABLE IF NOT EXISTS MyDB.friendships(
  friendshipID INT NOT NULL,
  emailFrom VARCHAR(255) NOT NULL,
  emailTo VARCHAR(255) NOT NULL,
  status BOOLEAN NOT NULL
)";


//Create blogs table
$createBlogsTable = "CREATE TABLE IF NOT EXISTS MyDB.blogs(
  blogId INT NOT NULL,
  email VARCHAR(255) NOT NULL,
  blogTitle VARCHAR(255) NOT NULL,
  blogDescription VARCHAR(255) NOT NULL,
  dateCreated VARCHAR(255) NOT NULL,
  PRIMARY KEY(blogId),
  FOREIGN KEY(email) REFERENCES MyDB.users(email)
)";


//Create posts table
$createPostsTable = "CREATE TABLE IF NOT EXISTS myDB.posts(
  postId INT NOT NULL,
  postTitle INT NOT NULL,
  postText TEXT NOT NULL,
  dateCreated DATETIME NOT NULL,
  PRIMARY KEY(postId)
)";

// Create table for annotations
$createAnnotationsTable = "CREATE TABLE IF NOT EXISTS myDB.annotations(
  annotationsId INT NOT NULL,
  photoId INT NOT NULL,
  email VARCHAR(255) NOT NULL,
  coordinateX INT NOT NULL,
  coordinateY INT NOT NULL,
  annotationText VARCHAR(255) NOT NULL,
  PRIMARY KEY(annotationsId),
  FOREIGN KEY(photoId) REFERENCES MyDB.photos(photoId),
  FOREIGN KEY(email) REFERENCES MyDB.users(email)
	)";


// Create table for Photos
$createPhotosTable = "CREATE TABLE IF NOT EXISTS myDB.photos(
  photoId INT NOT NULL,
  photoCollectionId INT NOT NULL,
  dateAdded DATETIME NOT NULL,
  imageReference VARCHAR(255) NOT NULL,
  PRIMARY KEY(photoId),
  FOREIGN KEY(photoCollectionId) REFERENCES MyDB.photoCollection(photoCollectionId)
)";

// Create table for comments (on photos)
$createCommentsTable = "CREATE TABLE IF NOT EXISTS myDB.comments(
  commentId INT NOT NULL,
  photoId INT NOT NULL,
  email VARCHAR(255) NOT NULL,
  commentText VARCHAR(255) NOT NULL,
  dateCreated DATETIME,
  PRIMARY KEY(commentId),
  FOREIGN KEY(photoId) REFERENCES MyDB.photos(photoId),
  FOREIGN KEY(email) REFERENCES MyDB.users(email)
)";

// Create table for Access Rights
$createAccessRightsTable = "CREATE TABLE IF NOT EXISTS myDB.accessRights(
  accessRightsId INT NOT NULL,
  photoCollectionId INT NOT NULL,
  email VARCHAR(255) NOT NULL,
  circleFriendsId INT NOT NULL,
  PRIMARY KEY(accessRightsId),
  FOREIGN KEY(photoCollectionId) REFERENCES myDB.photoCollection(photoCollectionId),
  FOREIGN KEY(email) REFERENCES myDB.users(email),
  FOREIGN KEY(circleFriendsId) REFERENCES myDB.circleOfFriends(circleFriendsId)
)";

// Create table for Photo Collections
$createPhotoCollectionsTable = "CREATE TABLE IF NOT EXISTS myDB.photoCollection(
  photoCollectionId INT NOT NULL,
  dateCreated DATETIME NOT NULL,
  description VARCHAR(255) NOT NULL,
  createdBy VARCHAR(255) NOT NULL,
  PRIMARY KEY(photoCollectionId)
)";


// Create table for user circle relationships
$createUserCircleRelationshipsTable = "CREATE TABLE IF NOT EXISTS myDB.userCircleRelationships(
  email VARCHAR(255) NOT NULL,
  circleFriendsId INT NOT NULL,
  PRIMARY KEY(email, circleFriendsID)
)";


// Create table for Circle of friends
$createCircleOfFriendsTable = "CREATE TABLE IF NOT EXISTS myDB.circleOfFriends(
  circleFriendsId INT NOT NULL,
  circleOfFriendsName VARCHAR(255),
  dateCreated DATETIME NOT NULL,
  PRIMARY KEY(circleFriendsId)
)";

// Create table for messages
$createMessagesTable = "CREATE TABLE IF NOT EXISTS myDB.messages(
  messageId INT NOT NULL,
  emailTo VARCHAR(255) NOT NULL,
  emailFrom VARCHAR(255) NOT NULL,
  messageText VARCHAR(255) NOT NULL,
  dateCreated DATETIME NOT NULL,
  PRIMARY KEY(messageId),
  FOREIGN KEY(emailTo) REFERENCES MyDB.users(email),
  FOREIGN KEY(emailFrom) REFERENCES MyDB.users(email) 
)";

// Create table for privacy settings
$createPrivacySettingsTable = "CREATE TABLE IF NOT EXISTS myDB.privacySettings(
  privacySettingsId INT NOT NULL,
  email VARCHAR(255) NOT NULL,
  privacySettingsTitle VARCHAR(255) NOT NULL,
  privacySettingsDescription VARCHAR(255) NOT NULL,
  status BOOLEAN NOT NULL,
  PRIMARY KEY(privacySettingsId),
  FOREIGN KEY(email) REFERENCES MyDB.users(email)
)";



$creatingTables = [ //make sure you create in the right order! foreign keys must refer to a primary key in an existing table
    $createDatabase,
    $createRolesTable,
    $createUsersTable,
    $createRightsTable,
    $createFriendshipsTable,
    $createPostsTable,
    $createBlogsTable,
    $createPrivacySettingsTable,
    $createCircleOfFriendsTable,
    $createMessagesTable,
    $createUserCircleRelationshipsTable,
    $createPhotoCollectionsTable,
    $createPhotosTable,
    $createCommentsTable,
    $createAnnotationsTable,
    $createAccessRightsTable
];


foreach ($creatingTables as $sqlquery){
  echo nl2br("\n"); //Line break in HTML conversion
  echo "<b>Executing SQL statement: </b>";
  echo $sqlquery; //Dispay statement being executed
  echo nl2br("\n");
  if ($conn->query($sqlquery) === TRUE) {
      echo "<b><font color='green'>SQL statement performed correctly</b></font>";
  } else {
      echo "<b><font color='red'>Error executing statement: </b></font>" . $conn->error;
  }
}


$conn->close();
?>
