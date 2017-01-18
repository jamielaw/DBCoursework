<?php
$servername = "localhost:3306";
$username = "root";
$password = "admin";

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
  roleID INT NOT NULL AUTO_INCREMENT, 
  roleTitle VARCHAR(50),
  PRIMARY KEY(roleID)
)";

//Create users table
$createUsersTable = "CREATE TABLE IF NOT EXISTS MyDB.users(
  email VARCHAR(50) NOT NULL,
  roleID INT NOT NULL,
  password VARCHAR(20),
  firstName VARCHAR(15),
  lastName VARCHAR(15),
  profileImage VARCHAR(255),
  profileDescription VARCHAR(255),
  PRIMARY KEY(email),
  FOREIGN KEY(roleID) REFERENCES MyDB.roles(roleID)
)";

//Create rights table
$createRightsTable = "CREATE TABLE IF NOT EXISTS MyDB.rights(
  rightID INT NOT NULL AUTO_INCREMENT,
  roleID INT NOT NULL,
  rightTitle VARCHAR(100) ,
  rightDescription VARCHAR(255) ,
  PRIMARY KEY(rightID),
  FOREIGN KEY(roleID) REFERENCES MyDB.roles(roleID)
)";

// Create table friendships
$createFriendshipsTable = "CREATE TABLE IF NOT EXISTS MyDB.friendships(
  friendshipID INT NOT NULL AUTO_INCREMENT,
  emailFrom VARCHAR(50) NOT NULL,
  emailTo VARCHAR(50) NOT NULL,
  status VARCHAR(20), -- status can take the values of {accepted, pending,denied}
  PRIMARY KEY(friendshipID)
)";


//Create blogs table
$createBlogsTable = "CREATE TABLE IF NOT EXISTS MyDB.blogs(
  blogId INT NOT NULL AUTO_INCREMENT,
  email VARCHAR(50) NOT NULL,
  blogTitle VARCHAR(255),
  blogDescription VARCHAR(255),
  dateCreated TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY(blogId),
  FOREIGN KEY(email) REFERENCES MyDB.users(email)
)";


//Create posts table
$createPostsTable = "CREATE TABLE IF NOT EXISTS myDB.posts(
  postId INT NOT NULL AUTO_INCREMENT,
  blogId INT NOT NULL,
  postTitle VARCHAR(255),
  postText LONGTEXT,
  dateCreated TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY(postId),
  FOREIGN KEY(blogId) REFERENCES MyDB.blogs(blogId)
)";

// Create table for annotations
$createAnnotationsTable = "CREATE TABLE IF NOT EXISTS myDB.annotations(
  annotationsId INT NOT NULL AUTO_INCREMENT,
  photoId INT,
  email VARCHAR(50) NOT NULL,
  coordinateX INT,
  coordinateY INT,
  annotationText VARCHAR(255) ,
  PRIMARY KEY(annotationsId),
  FOREIGN KEY(photoId) REFERENCES MyDB.photos(photoId),
  FOREIGN KEY(email) REFERENCES MyDB.users(email)
	)";


// Create table for Photos
$createPhotosTable = "CREATE TABLE IF NOT EXISTS myDB.photos(
  photoId INT NOT NULL AUTO_INCREMENT,
  photoCollectionId INT NOT NULL,
  dateAdded TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  imageReference VARCHAR(255) ,
  PRIMARY KEY(photoId),
  FOREIGN KEY(photoCollectionId) REFERENCES MyDB.photoCollection(photoCollectionId)
)";

// Create table for comments (on photos)
$createCommentsTable = "CREATE TABLE IF NOT EXISTS myDB.comments(
  commentId INT NOT NULL AUTO_INCREMENT,
  photoId INT NOT NULL,
  email VARCHAR(50) ,
  commentText VARCHAR(255) ,
  dateCreated TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY(commentId),
  FOREIGN KEY(photoId) REFERENCES MyDB.photos(photoId),
  FOREIGN KEY(email) REFERENCES MyDB.users(email)
)";

// Create table for Access Rights
$createAccessRightsTable = "CREATE TABLE IF NOT EXISTS myDB.accessRights(
  accessRightsId INT NOT NULL AUTO_INCREMENT,
  photoCollectionId INT NOT NULL,
  email VARCHAR(50) NOT NULL,
  circleFriendsId INT NOT NULL,
  PRIMARY KEY(accessRightsId),
  FOREIGN KEY(photoCollectionId) REFERENCES myDB.photoCollection(photoCollectionId),
  FOREIGN KEY(email) REFERENCES myDB.users(email),
  FOREIGN KEY(circleFriendsId) REFERENCES myDB.circleOfFriends(circleFriendsId)
)";

// Create table for Photo Collections
$createPhotoCollectionsTable = "CREATE TABLE IF NOT EXISTS myDB.photoCollection(
  photoCollectionId INT NOT NULL AUTO_INCREMENT,
  dateCreated TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  description VARCHAR(255) ,
  createdBy VARCHAR(255) NOT NULL,
  PRIMARY KEY(photoCollectionId)
)";


// Create table for user circle relationships
$createUserCircleRelationshipsTable = "CREATE TABLE IF NOT EXISTS myDB.userCircleRelationships(
  email VARCHAR(50) NOT NULL,
  circleFriendsId INT NOT NULL,
  PRIMARY KEY(email, circleFriendsID),
  FOREIGN KEY(email) REFERENCES myDB.users(email),
  FOREIGN KEY(circleFriendsID) REFERENCES myDB.circleOfFriends(circleFriendsId)
)";


// Create table for Circle of friends
$createCircleOfFriendsTable = "CREATE TABLE IF NOT EXISTS myDB.circleOfFriends(
  circleFriendsId INT NOT NULL AUTO_INCREMENT,
  circleOfFriendsName VARCHAR(50),
  dateCreated TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY(circleFriendsId)
)";

// Create table for messages
$createMessagesTable = "CREATE TABLE IF NOT EXISTS myDB.messages(
  messageId INT NOT NULL AUTO_INCREMENT,
  emailTo VARCHAR(50) NOT NULL,
  emailFrom VARCHAR(50) NOT NULL,
  messageText VARCHAR(255),
  dateCreated TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY(messageId),
  FOREIGN KEY(emailTo) REFERENCES MyDB.users(email),
  FOREIGN KEY(emailFrom) REFERENCES MyDB.users(email) 
)";

// Create table for privacy settings
$createPrivacySettingsTable = "CREATE TABLE IF NOT EXISTS myDB.privacySettings(
  privacySettingsId INT NOT NULL AUTO_INCREMENT,
  email VARCHAR(50) NOT NULL,
  privacySettingsTitle VARCHAR(255) ,
  privacySettingsDescription VARCHAR(255) ,
  status BOOLEAN DEFAULT false,
  PRIMARY KEY(privacySettingsId),
  FOREIGN KEY(email) REFERENCES MyDB.users(email)
)";



$creatingTables = [ //make sure you create in the right order! foreign keys must refer to a primary key in an existing table
    //$dropDatabase, //uncomment this if there is a wrong format in any table
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