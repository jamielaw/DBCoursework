<?php

    require '../../session.php';

    function redirect($url) {
      ob_start();
      header('Location: '.$url);
      ob_end_flush();
      die();
    }

    $photoCollectionId = null;
    $albumName = null;
    $albumDescription = null;

    if ( !empty($_POST['photoCollectionId'])) {
        $photoCollectionId = $_POST['photoCollectionId'];
    }
    if ( !empty($_POST['albumName'])) {
        $albumName = $_POST['albumName'];
    }
    if ( !empty($_POST['albumDescription'])) {
        $albumDescription = $_POST['albumDescription'];
    }

    echo $photoCollectionId;
    
    if (null!=$albumName && null!=$albumDescription) {

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE photocollection SET title = ?, description = ? WHERE photoCollectionId = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($albumName,$albumDescription,$photoCollectionId));
        Database::disconnect();

        // echo "The album name has been changed to: " . $albumName . " and the album description to: " . $albumDescription;
    }

    //redirect("../../admin");
?>
