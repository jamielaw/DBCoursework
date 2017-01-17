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

//debugging statement in case of altering table parameters
// $sql = "DROP DATABASE IF EXISTS MyDB";

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS MyDB";

//Create roles table
$sql = "CREATE TABLE IF NOT EXISTS MyDB.roles(roleID INT NOT NULL, roleTitle VARCHAR(255), PRIMARY KEY(roleID)
	)";

//Create users table
$sql = "CREATE TABLE IF NOT EXISTS MyDB.users(email VARCHAR(255) NOT NULL, roleID INT NOT NULL, password VARCHAR(255) NOT NULL, firstName VARCHAR(255) NOT NULL, lastName VARCHAR(255) NOT NULL, profileImage VARCHAR(255) NOT NULL, profileDescription VARCHAR(255) NOT NULL, PRIMARY KEY(email), FOREIGN KEY (roleID) REFERENCES MyDB.roles(roleID)
	)";

//Create rights table
$sql = "CREATE TABLE IF NOT EXISTS MyDB.rights(rightID INT NOT NULL, roleID INT NOT NULL, rightTitle VARCHAR(255) NOT NULL, rightDescription VARCHAR(255) NOT NULL, PRIMARY KEY(rightID), FOREIGN KEY(roleID) REFERENCES MyDB.roles(roleID)
	)";

if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

$conn->close();
?>
