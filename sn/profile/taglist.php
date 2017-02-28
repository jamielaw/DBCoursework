<?php
require '../session.php';

// fetch all tags
$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$id = $_POST['pic_id'];
$sql = "SELECT * FROM annotations WHERE photoId= ? ";
$q = $pdo->prepare($sql);
$q->execute(array($id));

$data['boxes'] = '';
$data['lists'] = '';

foreach ($q as $row) {
    $data['boxes'] .= '<div class="tagview" style="left:' . $row['coordinateX'] . 'px;top:' . $row['coordinateY'] . 'px;" id="view_'.$row['annotationsId'].'">';
    $data['boxes'] .= '<div class="square"></div>';
    $data['boxes'] .= '<div class="person" style="left:' . $row['coordinateX'] . 'px;top:' . $row['coordinateY']  . 'px;">' . $row[ 'annotationText' ] . '</div>';
    $data['boxes'] .= '</div>';

    $data['lists'] .= '<li id="'.$row['annotationsId'].'"><a>' . $row['annotationText'] . '</a> (<a class="remove">Remove</a>)</li>';
}

echo json_encode($data);
