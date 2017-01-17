<?php

return $conn = mysqli_connect('localhost', 'root', '', 'phplessons');

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
