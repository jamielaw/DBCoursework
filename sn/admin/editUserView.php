<?php
  $title = "Bookface Social Network";
  $description = "A far superior social network";
  include("../inc/header.php");

  $servername = "localhost:3306";
  $username = "root";
  $password = "admin";

  //Create connection
  $conn = new mysqli($servername, $username, $password);
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }else{
      echo "Connection established";
  }


  $argument1 = $_GET['email'];

  echo $argument1;
  $sql = "SELECT * FROM MyDB.users WHERE email=" . "'" . $argument1 . "'";
  echo $sql;
  $result = $conn->query($sql);

  $row =  $result->fetch_assoc();

?>

<body>

  <h3> Edit a user account </h3>

  <form method="POST" action="editUser.php">
    <input type="hidden" name="argument1" value="<?php echo $argument1;?>">
    Email: <input type="text" name="email" value="<?php echo $row['email'];?>"> <br>
    First name: <input type="text" name="firstName" value="<?php echo $row['firstName'];?>"> <br>
    Last name: <input type="text" name="lastName" value="<?php echo $row['lastName']?>"> <br>
    Image url: <input type="textfield" name="profileImage" value="<?php echo $row['profileImage']?>"> <br>
    profile description: <input type="textfield" name="profileDescription" value="<?php echo $row['profileDescription']?>"> <br>


    <button type="submit">Update!</button>
  </form>



</body>
