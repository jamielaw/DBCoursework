<?php
$servername = "localhost:8889";
$username = "root";
$password = "root";

// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// This file contains table initalisers for
// annotations , photos, access rights, comments,
// Create users database
$sql = "CREATE DATABASE IF NOT EXISTS MyDB";
// $sql = "CREATE TABLE IF NOT EXISTS myDB.users(email VARCHAR(50) NOT NULL, password VARCHAR(50) NOT NULL, firstName VARCHAR(50) NOT NULL, lastName VARCHAR(50) NOT NULL, profileImage VARCHAR(50) NOT NULL, profileDescription VARCHAR(50) NOT NULL, PRIMARY KEY(email)
// 	)";

// Create table for annotations
$sql = "CREATE TABLE IF NOT EXISTS myDB.annotations(
  anotationsId INT NOT NULL,
  coordinateX INT NOT NULL,
  cordinateY INT NOT NULL,
  annotationText VARCHAR(255) NOT NULL,
  PRIMARY KEY(annotationsId),
  -- FOREIGN KEY(photoId),
  -- FOREIGN KEY(annotatedBy)
	)";


// Create table for Photos
$sql = "CREATE TABLE IF NOT EXISTS myDB.photos(
  photosId INT NOT NULL,
  dateAdded DATETIME NOT NULL,
  imageReference VARCHAR(255) NOT NULL,
  PRIMARY KEY(photosId)
  -- FOREIGN KEY(photoCollectionsId)
)";

// Create table for Comments
$sql = "CREATE TABLE IF NOT EXISTS myDB.comments(
  postId INT NOT NULL,
  postTitle INT NOT NULL,
  postText TEXT NOT NULL,
  dateCreated DATETIME,
  PRIMARY KEY(photosId)
)";

// Create table for Access Rights
$sql = "CREATE TABLE IF NOT EXISTS myDB.accessRights(
  accessRightId INT NOT NULL,
  PRIMARY KEY(accessRightsId)
  --FOREIGN KEY(photoCollectionsId),
  -- FOREIGN KEY(email),
  -- FOREIGN KEY(circleFriendsId),
)";

// Create table for Photo Collections
$sql = "CREATE TABLE IF NOT EXISTS myDB.photoCollectionId(
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
$sql = "CREATE TABLE IF NOT EXISTS myDB.circleOfFriends(
  circleFriendsId INT NOT NULL,
  circleOfFriendsName VARCHAR(255) DATETIME,
  dateCreated DATETIME NOT NULL,
  PRIMARY KEY(circleFriendsId)
)";

// Create table for messages
$sql = "CREATE TABLE IF NOT EXISTS myDB.messages(
  messagesId INT NOT NULL,
  messageText VARCHAR NOT NULL,
  dateCreated DATETIME NOT NULL,
  PRIMARY KEY(messagesId)
  -- FOREIGN KEY
)";


// Create table for
$sql = "CREATE TABLE IF NOT EXISTS myDB.messages(
  messagesId INT NOT NULL,
  messageText VARCHAR NOT NULL,
  dateCreated DATETIME NOT NULL,
  PRIMARY KEY(messagesId)
  -- FOREIGN KEY
)";

$sql =



if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

$conn->close();
?>
