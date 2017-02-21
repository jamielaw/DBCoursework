<?php

  require("$root/sn/session.php");


  function redirect($url) {
    ob_start();
    header('Location: '.$url);
    ob_end_flush();
    die();
  }

  $requestingUser = "vicky@ucl.ac.uk"
  // CHANGE TO SOMETHING THATS NOT HARDCODED!
  $decidingUser = "charles@ucl.ac.uk";

  $friendsRecommendation = "SELECT *
  FROM (
    SELECT *
    FROM users
    JOIN friendships
    ON users.email = friendships.emailFrom OR users.email=friendships.emailTo
    WHERE
  (friendships.emailFrom='". $loggedInUser.
  "' OR friendships.emailTo='". $loggedInUser .
   "' ) AND users.email!= '". $loggedInUser.
   "' AND status='accepted') AS T1
  JOIN (SELECT * from blogs) AS T2
  ON T1.email = T2.email";

  $friendsRecommendation = " SELECT * FROM users JOIN friendship+s"
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>


(friendships.emailFrom="vicky@ucl.ac.uk"
OR friendships.emailTo="vicky@ucl.ac.uk"
) AND users.email!= "vicky@ucl.ac.uk"

// this gets ur friends
SELECT * FROM ( SELECT * FROM users JOIN friendships ON users.email = friendships.emailFrom OR users.email=friendships.emailTo WHERE( (friendships.emailFrom="vicky@ucl.ac.uk" OR friendships.emailTo="vicky@ucl.ac.uk" ) AND users.email!= "vicky@ucl.ac.uk" AND friendships.status = "accepted")) AS T1


// this negation of ur friendships
SELECT DISTINCT * FROM users WHERE users.email NOT IN ( SELECT users.email FROM users JOIN friendships ON users.email = friendships.emailFrom OR users.email=friendships.emailTo WHERE( (friendships.emailFrom="vicky@ucl.ac.uk" OR friendships.emailTo="vicky@ucl.ac.uk" ) AND users.email!= "vicky@ucl.ac.uk" ))
