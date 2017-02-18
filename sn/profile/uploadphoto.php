<?php
require '../database.php';

$id = null;
$loggedInUser="charles@ucl.ac.uk";

if (!empty($_GET['id'])) {
    $id = $_REQUEST['id'];
}

if(is_numeric($id))
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/images/photoCollection/';
else
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/images/profile/';
$path_parts = pathinfo($_FILES["fileToUpload"]["name"]);
$name = $path_parts['filename'].'_'.time().'.'.$path_parts['extension'];
$target_file =  $target_dir . $name;
$uploadOk = 1;
$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif") {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename($_FILES["fileToUpload"]["name"]). " has been uploaded at ". basename($target_dir) . "  ";
        if(is_numeric($id))
        {
            savePhoto($id, $name);
            $URL="readphotocollection.php?createdBy=".$loggedInUser."&photoCollectionId=".$id;
            header("Location: " . $URL);
        }
        else
        {
            updateProfile($id, $name);
            $URL="http://localhost/sn/profile/readprofile.php?email=".$loggedInUser;
            header("Location: " . $URL);    
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

function savePhoto($id, $name)
{
    $imageReference = '../../images/photoCollection/' . $name;

    echo $id;
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO photos (photoCollectionId,imageReference) VALUES (?,?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($id, $imageReference));
}

function updateProfile($id, $name)
{
    $imageReference = '../../images/profile/' . $name;

    echo $id;
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE users SET profileImage=? WHERE email=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($imageReference,$id));
}