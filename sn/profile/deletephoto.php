<?php

    require '../database.php';

    $deletephotoName = null;

    if ( !empty($_POST['deletephotoName'])) {
        $deletephotoName = $_POST['deletephotoName'];
    }
   
    if (null!=$deletephotoName) {

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "DELETE FROM photos WHERE photoId = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($deletephotoName));
        Database::disconnect();
        echo "The photo has been deleted";
    } else {
        echo "There was an error in your system! We appologise for the inconvenience.";
    }
   
?>
