<?php
require '../database.php';

// fetch all tags
$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$user = $_POST['user'];
$sql = "SELECT * FROM messages WHERE emailTo = ? or emailFrom = ? ORDER BY dateCreated";
$q = $pdo->prepare($sql);
$q->execute(array($user,$user));

$data['boxes'] = '';
$data['lists'] = '';

foreach ($q as $row) {
    //  $data['lists'] .= '<li id="'.$row['messageId'].'"><a>' . $row['messageText'] . '</a> (<a class="remove">Remove</a>)</li>';
    $data['lists'] .= '<li class="left clearfix"><span class="chat-img pull-left">
        <img src="http://placehold.it/50/55C1E7/fff&text=U" alt="User Avatar" class="img-circle" />
    </span>
        <div class="chat-body clearfix">
            <div class="header">
                <strong class="primary-font">Jack Sparrow</strong> <small class="pull-right text-muted">
                    <span class="glyphicon glyphicon-time"></span>12 mins ago</small>
            </div>
            <p>
              ' . $row['messageText'] . '
            </p>
        </div>
    </li>';
}

echo json_encode($data);
