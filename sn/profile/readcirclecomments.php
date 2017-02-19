<?php
require '../database.php';

// fetch all tags
$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$id = $_POST['id'];
$loggedInUser = $_POST['loggedInUser'];
$sql = "SELECT * FROM messages WHERE emailTo = ? OR emailFrom = ? ORDER BY dateCreated";
$q = $pdo->prepare($sql);
$q->execute(array($id,$id));
$data['lists'] = '';

function date_difference($date_1, $date_2)
{
    $val_1 = new DateTime($date_1);
    $val_2 = new DateTime($date_2);
    $interval = $val_1->diff($val_2);
    $year     = $interval->y;
    $month    = $interval->m;
    $day      = $interval->d;
    $hour      = $interval->h;
    $minute   = $interval->i;
    $second   = $interval->s;
    $output   = '';
    $ok = 0;
    if ($year > 0) {
        if ($year > 1) {
            $output .= $year." years ";
        } else {
            $output .= $year." year ";
        }
        $ok=1;
    }
    if ($month > 0) {
        if ($month > 1) {
            $output .= $month." months ";
        } else {
            $output .= $month." month ";
        }
    }
    if ($day > 0) {
        if ($day > 1) {
            $output .= $day." days ";
        } else {
            $output .= $day." day ";
        }
        $ok=1;
    }
    if ($hour > 0) {
        if ($hour > 1) {
            $output .= $hour." hours ";
        } else {
            $output .= $hour." hour ";
        }
        $ok=1;
    }
    if ($minute > 0) {
        if ($minute > 1) {
            $output .= $minute." minutes ";
        } else {
            $output .= $minute." minute ";
        }
        $ok=1;
    }
    if ($ok==0) {
        $output .= $second." seconds ";
    }
    return $output;
}

function getUserData($email)
{
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = 'SELECT * FROM users WHERE email = ?';
    $q = $pdo->prepare($sql);
    $q->execute(array($email));
    $result = $q->fetch(PDO::FETCH_ASSOC);

    return $result;
}

function getCircleName($id)
{
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = 'SELECT * FROM circleoffriends  WHERE circleFriendsId = ?';
    $q = $pdo->prepare($sql);
    $q->execute(array($id));
    $result = $q->fetch(PDO::FETCH_ASSOC);

    return $result;
}

$data['lists'] = '<p style="color:white; background-color:#337AB7;" class="text-center">Your messages with ' .getCircleName($id)['circleOfFriendsName']. '</p>';

foreach ($q as $row) {
    $firstName = getUserData($row['emailFrom'])['firstName'];
    $lastName = getUserData($row['emailFrom'])['lastName'];
    $profilePicture = getUserData($row['emailFrom'])['profileImage'];
    $email = getUserData($row['emailFrom'])['email'];
    $date1 = date('m/d/Y h:i:s a', time());
    $date2 = $row['dateCreated'];
    $data['lists'] .= '
    <li class="left clearfix">
      <span class="chat-img pull-left">
        <img width="40" src=" ' . $profilePicture . ' " alt="User Avatar" class="img-circle" />
      </span>
      <div class="chat-body clearfix">
        <div class="header">
        <a href="readprofile.php?email='.$email.'">
          <strong class="primary-font">'.$firstName.' '.$lastName.'</strong> <small class="pull-right text-muted">
        </a>
            <span class="glyphicon glyphicon-time"></span>'.date_difference($date1, $date2).'</small>
        </div>
        <p>' . $row['messageText'] . '</p>
      </div>
    </li>';
}

echo json_encode($data);
