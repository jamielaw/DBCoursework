<?php

    require '../database.php';

    $albumName = null;
    $descriptionName = null;
    $email = null;

    if ( !empty($_POST['albumName'])) {
        $albumName = $_POST['albumName'];
    }
    if ( !empty($_POST['descriptionName'])) {
        $descriptionName = $_POST['descriptionName'];
    }
    if ( !empty($_POST['email'])) {
        $email = $_POST['email'];
    }

    if ( null!=$albumName) {
        $albumName = $_POST['albumName'];
        $descriptionName = $_POST['descriptionName'];

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO photocollection (title, description, createdBy) VALUES (?,?,?)";
        $q = $pdo->prepare($sql);
        $q->execute(array($albumName,$descriptionName,$email));
        Database::disconnect();

        echo $albumName . " has been created! You can now add photos to your collection. Please try again!";
    } else {
        echo "You haven't given a name to your collection.";
    }
?>
