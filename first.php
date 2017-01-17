<?php
$servername = "localhost:3306";
$username = "root";
$password = "root";

// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create users database
$sql = "CREATE DATABASE IF NOT EXISTS lolz";
$sql = "CREATE TABLE IF NOT EXISTS lolz.users(email VARCHAR(50) NOT NULL, password VARCHAR(50) NOT NULL, firstName VARCHAR(50) NOT NULL, lastName VARCHAR(50) NOT NULL, profileImage VARCHAR(50) NOT NULL, profileDescription VARCHAR(50) NOT NULL, PRIMARY KEY(email)
	)";

if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

$conn->close();
?>
