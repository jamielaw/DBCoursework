<?php

    require '../database.php';

    $commentId = null;

    if ( !empty($_POST['commentId'])) {
        $commentId = $_POST['commentId'];
    }
   
    if (null!=$commentId) {

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "DELETE FROM comments WHERE commentId = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($commentId));
        Database::disconnect();
        echo "The comment has been deleted";
    } else {
        echo "There was an error in your system! We appologise for the inconvenience.";
    }
   
?>
