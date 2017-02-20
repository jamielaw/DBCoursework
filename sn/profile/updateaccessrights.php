<?php

    require '../database.php';

    $photoCollectionId = null;
    $totalNumber = null;
    $value = null;
    $checked = array();  

    $email = 'charles@ucl.ac.uk';

    if (!empty($_POST['photoCollectionId'])) {
        $photoCollectionId = $_POST['photoCollectionId'];
    }

    if (!empty($_POST['totalNumber'])) {
        $totalNumber = $_POST['totalNumber'];
    }

    for ($i = 0; $i <= $totalNumber; $i++) {
        if (!empty($_POST[$i])) {
            $value = $_POST[$i];
            array_push($checked, $value);
        }
    } 

    function addAccessRightFriend($email,$photoCollectionId)
    {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'INSERT INTO accessrights (photoCollectionId, email) VALUES (?,?);';
        $q = $pdo->prepare($sql);
        $q->execute(array($photoCollectionId,$email));  
    }

    function deleteAccessRightFriend($email,$photoCollectionId)
    {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'DELETE FROM accessrights WHERE photoCollectionId = ? and email = ?;';
        $q = $pdo->prepare($sql);
        $q->execute(array($photoCollectionId,$email));  
    }

    function addAccessRightCircle($circleFriendsId,$photoCollectionId)
    {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'INSERT INTO accessrights (photoCollectionId, circleFriendsId) VALUES (?,?);';
        $q = $pdo->prepare($sql);
        $q->execute(array($photoCollectionId,$circleFriendsId));  
    }

    function deleteAccessRightCircle($circleFriendsId,$photoCollectionId)
    {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'DELETE FROM accessrights WHERE photoCollectionId = ? AND circleFriendsId = ?;';
        $q = $pdo->prepare($sql);
        $q->execute(array($photoCollectionId,$circleFriendsId));  
    }

    function checkAccessRights($email, $photoCollectionId)
    {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT IF ((SELECT COUNT(*) FROM accessrights WHERE photoCollectionId=? AND (email=? OR circleFriendsId=?)) > 0, true, false) AS value;';
        $q = $pdo->prepare($sql);
        $q->execute(array($photoCollectionId,$email,$email));  
        $data = $q->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    function getFriends($checked,$email,$photoCollectionId)
    {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT DISTINCT email, firstName, lastName, profileImage FROM users JOIN friendships ON users.email = friendships.emailFrom OR users.email=friendships.emailTo WHERE (friendships.emailFrom=? OR friendships.emailTo=?) AND users.email!=? AND status=\'accepted\';';
        $q = $pdo->prepare($sql);
        $q->execute(array($email,$email,$email));
        
        foreach ($q as $row) {
           // echo $row['email'].' '.$photoCollectionId;
            if (in_array($row['email'], $checked)) {
                echo $row['email']." has access rights </br>";
                if(checkAccessRights($row['email'], $photoCollectionId)['value']!=1)
                {
                    echo 'need to be updated';
                    addAccessRightFriend($row['email'],$photoCollectionId);
                }
            }
            else // delete if it has access right in db
            {
                if(checkAccessRights($row['email'], $photoCollectionId)['value']==1)
                {
                    deleteAccessRightFriend($row['email'],$photoCollectionId);
                }
            }
        }
    }

    function getCircles($checked,$email,$photoCollectionId)
    {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT c.circleOfFriendsName, c.circleFriendsId FROM circleoffriends c INNER JOIN usercirclerelationships u ON c.circleFriendsId = u.circleFriendsId WHERE u.email=?';
        $q = $pdo->prepare($sql);
        $q->execute(array($email));

        foreach ($q as $row) {
           // echo $row['email'].' '.$photoCollectionId;
            if (in_array($row['circleFriendsId'], $checked)) {
                echo $row['circleFriendsId']." has access rights </br>";
                if(checkAccessRights($row['circleFriendsId'], $photoCollectionId)['value']!=1)
                {
                    echo 'need to be updated';
                    addAccessRightCircle($row['circleFriendsId'],$photoCollectionId);
                }
            }
            else // delete if it has access right in db
            {
                if(checkAccessRights($row['circleFriendsId'], $photoCollectionId)['value']==1)
                {
                    deleteAccessRightCircle($row['circleFriendsId'],$photoCollectionId);
                }
            }
        }
    }

    function getFriendsOfFriends($checked, $email, $photoCollectionId)
    {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT DISTINCT email, firstName, lastName, profileImage FROM users JOIN friendships ON users.email = friendships.emailFrom OR users.email=friendships.emailTo WHERE 
        (friendships.emailFrom IN
            (SELECT DISTINCT email FROM users JOIN friendships ON users.email = friendships.emailFrom OR users.email=friendships.emailTo WHERE (friendships.emailFrom=? OR friendships.emailTo=?) AND users.email!=? AND status=\'accepted\') 
        OR friendships.emailTo IN
            (SELECT DISTINCT email FROM users JOIN friendships ON users.email = friendships.emailFrom OR users.email=friendships.emailTo WHERE (friendships.emailFrom=? OR friendships.emailTo=?) AND users.email!=? AND status=\'accepted\')) 
        AND users.email NOT IN 
            (SELECT DISTINCT email FROM users JOIN friendships ON users.email = friendships.emailFrom OR users.email=friendships.emailTo WHERE (friendships.emailFrom=? OR friendships.emailTo=?) AND users.email!=? AND status=\'accepted\')
        AND users.email!=?';
        $q = $pdo->prepare($sql);
        $q->execute(array($email,$email,$email,$email,$email,$email,$email,$email,$email,$email));
        
        foreach ($q as $row) {
           // echo $row['email'].' '.$photoCollectionId;
            if (in_array($row['email'], $checked)) {
                echo $row['email']." has access rights </br>";
                if(checkAccessRights($row['email'], $photoCollectionId)['value']!=1)
                {
                    echo 'need to be updated';
                    addAccessRightFriend($row['email'],$photoCollectionId);
                }
            }
            else // delete if it has access right in db
            {
                if(checkAccessRights($row['email'], $photoCollectionId)['value']==1)
                {
                    deleteAccessRightFriend($row['email'],$photoCollectionId);
                }
            }
        }
    }

    getFriends($checked,$email,$photoCollectionId);
    getCircles($checked,$email,$photoCollectionId);
    getFriendsOfFriends($checked, $email, $photoCollectionId);
?>
