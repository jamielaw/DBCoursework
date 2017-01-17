<?php include 'dbh.php' ?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Database Coursework</title>
  </head>
  <body>
    <?php

    $sql = "SELECT * FROM posts";
    $result = mysqli_query($conn, $sql);
    $row = $result->fetch_assoc();

    echo $row['subject'];
     ?>
  </body>
</html>
