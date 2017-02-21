<?php

    require '../session.php';

    $photoCollectionId = null;

    if ( !empty($_POST['photoCollectionId'])) {
        $photoCollectionId = $_POST['photoCollectionId'];
    }
   
    if (null!=$photoCollectionId) {

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "DELETE FROM photos WHERE photoCollectionId = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($photoCollectionId));

        $sql = "DELETE FROM accessrights WHERE photoCollectionId = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($photoCollectionId));

        $sql = "DELETE FROM photocollection WHERE photoCollectionId = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($photoCollectionId));
        Database::disconnect();
        echo "The album has been deleted";

        
    } else {
        echo "There was an error in your system! We appologise for the inconvenience.";
    }
   
?>
