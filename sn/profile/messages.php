<?php

    $title = "Bookface Social Network";
    $description = "A far superior social network";
    include("../inc/header.php");
    include("../inc/nav-trn.php");

    $email = 'charles@ucl.ac.uk';

    function getProfilePicture($email)
    {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT profileImage from users WHERE email = ? ;';
        $q1 = $pdo->prepare($sql);
        $q1->execute(array($email));
        $value = $q1->fetch(PDO::FETCH_ASSOC);
        return $value;
    }

    function getMessageDate($email)
    {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT dateCreated FROM messages WHERE emailTo = ? OR emailFrom = ? ORDER BY dateCreated DESC LIMIT 1;';
        $q1 = $pdo->prepare($sql);
        $q1->execute(array($email,$email));
        $value = $q1->fetch(PDO::FETCH_ASSOC);
        return $value;
    }

    function getFriendsMessages($email)
    {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT DISTINCT emailTo AS email FROM messages WHERE emailFrom = ? AND emailTo NOT REGEXP \'^[0-9]+$\' UNION SELECT DISTINCT emailFrom AS email FROM messages WHERE emailTo = ? AND emailFrom NOT REGEXP \'^[0-9]+$\';';
        $q1 = $pdo->prepare($sql);
        $q1->execute(array($email,$email));
        return $q1;
    }

    function getCircleMessages($email)
    {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT * FROM (SELECT c.circleOfFriendsName, c.circleFriendsId, m.dateCreated as date FROM circleoffriends c 
            INNER JOIN usercirclerelationships u ON c.circleFriendsId = u.circleFriendsId 
            INNER JOIN messages m ON m.emailTo=c.circleFriendsId WHERE u.email = ? AND (m.emailTo REGEXP \'^[0-9]+$\' OR m.emailFrom REGEXP \'^[0-9]+$\')) as a
            UNION 
            SELECT * FROM (SELECT c.circleOfFriendsName, c.circleFriendsId, str_to_date(\'01,01,2000\',\'%d,%m,%Y\') as date FROM circleoffriends c 
            INNER JOIN usercirclerelationships u ON c.circleFriendsId = u.circleFriendsId 
            WHERE u.email = ? AND c.circleFriendsId NOT IN 

            (SELECT c.circleFriendsId FROM circleoffriends c 
            INNER JOIN usercirclerelationships u ON c.circleFriendsId = u.circleFriendsId 
            INNER JOIN messages m ON m.emailTo=c.circleFriendsId WHERE u.email = ? AND (m.emailTo REGEXP \'^[0-9]+$\' OR m.emailFrom REGEXP \'^[0-9]+$\'))) as b
            ORDER BY date DESC';

         $q1 = $pdo->prepare($sql);
         $q1->execute(array($email,$email,$email));
         return $q1;
    }

    function getFriendsWithoutMessages($email)
    {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT DISTINCT email, firstName, lastName, profileImage FROM users JOIN friendships ON users.email = friendships.emailFrom OR users.email=friendships.emailTo WHERE (friendships.emailFrom=? OR friendships.emailTo=?) AND users.email!=? AND status=\'accepted\' AND email NOT IN (SELECT DISTINCT emailTo AS email FROM messages WHERE emailFrom = ? AND emailTo NOT REGEXP \'^[0-9]+$\' UNION SELECT DISTINCT emailFrom AS email FROM messages WHERE emailTo = ? AND emailFrom NOT REGEXP \'^[0-9]+$\');';
        $q1 = $pdo->prepare($sql);
        $q1->execute(array($email,$email,$email,$email,$email));
        return $q1;
    }

    function countMembersInCircle($id)
    {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $countMembers = "SELECT COUNT(email) FROM MyDB.circleOfFriends INNER JOIN MyDB.userCircleRelationships ON MyDB.circleOfFriends.circleFriendsId=MyDB.userCircleRelationships.circleFriendsId WHERE MyDB.circleOfFriends.circleFriendsId=" . $id;
        $y = $pdo->query($countMembers);
        $value = $y->fetch(PDO::FETCH_ASSOC);
        return $value;
    }

    function getMembersNames($id)
    {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT firstName, lastName, users.email FROM MyDB.users INNER JOIN MyDB.userCircleRelationships ON MyDB.users.email=MyDB.userCircleRelationships.email WHERE MyDB.userCircleRelationships.circleFriendsId=" . $id;
        $q1 = $pdo->prepare($sql);
        $q1->execute(array($id));        
        return $q1;
    }

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
            $output .= "";
        }
        return $output;
    }
?>

<div class="container-fluid">
    <div class="row content">
    <!-- Left Pannel -->
    <div class="col-sm-3 sidenav">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <span class="glyphicon glyphicon-user"></span> People
                </div>
                <div class="panel-body">
                    <ul id="left-panel" class="chat">
                      <?php
                      foreach (getFriendsMessages($email) as $row) {
                          $profileImage = getProfilePicture($row['email'])['profileImage'];
                          date_default_timezone_set('Europe/London');
                          $date1 = date('m/d/Y h:i:s a', time());
                          $date2 = getMessageDate($row['email'])['dateCreated'];
                          echo '<li style="cursor:pointer;" id="'.$email.'" onclick="getMessagesUser(\''.$row['email'].'\',\''.$email.'\')" class="left clearfix"><span class="chat-img pull-left">
                         <img width=50 src=' . $profileImage . ' alt="User Avatar" class="img-circle" />
                         </span>
                            <div class="chat-body clearfix">
                                <div class="header">
                                    <strong class="primary-font">'.$row['email'].'</strong> <small class="pull-right text-muted">
                                        <span class="glyphicon glyphicon-time"></span>'.date_difference($date1, $date2).'</small>
                                </div>
                            </div>
                        </li>';
                      }

                      foreach (getCircleMessages($email) as $row) {
                          date_default_timezone_set('Europe/London');
                          $id = $row['circleFriendsId'];
                          $date1 = date('m/d/Y h:i:s a', time());
                          $date2 = getMessageDate($id)['dateCreated'];

                        //QUERIES FOR TOOLTIPS:  
                        $countResults = countMembersInCircle($id);
                        //get members names in circle
                        $memberList = array();
                        $currentMember = 0;
                        foreach (getMembersNames($id) as $eachMember){
                            //we know how many members there are in the circle already with the variable $countResults
                            $currentMember++;
                            $memberList[] = $eachMember["firstName"] . " " . $eachMember["lastName"];
                            if($eachMember["email"]==$email){
                                $memberList[] .= " (You)";
                            }
                            if($currentMember!=$countResults["COUNT(email)"]){ //non-final member in member list, need to append comma
                                $memberList[] .= ", ";
                            }
                        }
                        //END QUERIES FOR TOOLTIPS
                         echo '<li style="cursor:pointer;" data-toggle="tooltip" data-placement="right" title="' . implode($memberList) . '" onclick="getMessagesCircle('.$row['circleFriendsId'].')" class="left clearfix"><span class="chat-img pull-left">
                         <img src="http://placehold.it/50/55C1E7/fff&text=U" alt="User Avatar" class="img-circle" />
                         </span>
                            <div class="chat-body clearfix">
                                <div class="header">
                                    <strong class="primary-font">'.$row['circleOfFriendsName'].'</strong> <small class="pull-right text-muted">
                                        <span class="glyphicon glyphicon-time"></span>'.date_difference($date1, $date2).'</small>
                                </div>
                            </div>
                        </li>';
                      }
                      
                      foreach (getFriendsWithoutMessages($email) as $row) {
                          $profileImage = getProfilePicture($row['email'])['profileImage'];
                          date_default_timezone_set('Europe/London');
                          $date1 = date('m/d/Y h:i:s a', time());
                          $date2 = getMessageDate($row['email'])['dateCreated'];
                          echo '<li style="cursor:pointer;" onclick="getMessagesUser(\''.$row['email'].'\',\''.$email.'\')" class="left clearfix"><span class="chat-img pull-left">
                         <img width=50 src=' . $profileImage . ' alt="User Avatar" class="img-circle" />
                         </span>
                            <div class="chat-body clearfix">
                                <div class="header">
                                    <strong class="primary-font">'.$row['email'].'</strong> <small class="pull-right text-muted">
                                        <span class="glyphicon glyphicon-time"></span>'.date_difference($date1, $date2).'</small>
                                </div>
                            </div>
                        </li>';
                      }
                    ?>
                    </ul>
                </div>
                <div class="panel-footer">
                    <div class="input-group">
                    <p><br></p>
                    </div>
                </div>
            </div>
    </div>

    <!--Right Pannel -->
        <div class="col-sm-9">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-comment"></span> Chat
                </div>
                <div class="panel-body">
                    <ul class="chat">
                      	<div id="messageList"> <li></li> </div>
                    </ul>
                </div>
                <div class="panel-footer">
                    <div class="input-group">
                        <input id="myMessage" type="text" class="form-control input-sm" placeholder="Type your message here..." />
                        <span class="input-group-btn">
                          <?php
                            echo '<button onclick="sendMessage(\''.$email.'\')" class="btn btn-warning btn-sm" id="btn-chat">
                                Send</button>'
                          ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

var clicked = null;
var inWhich = null; 
function sendMessage($email) {
    console.log("here");
    $(this).addClass('active');

    var loggedInUser = $email;
    var messageText = document.getElementById('myMessage').value;
    if (messageText == '' || clicked==null)
     alert('Input message is empty or you have not selected any circle or friend.');
    else {
      $.post( "sendMessage.php", "emailTo=" + clicked + "&emailFrom=" + $email + "&messageText=" + messageText,  function( data ) {
      });
      if (inWhich == 0)
        getMessagesUser(clicked,$email);
      if (inWhich == 1)
        getMessagesCircle(clicked,$email);
    }
}

function getMessagesUser(id, email){

  inWhich = 0;
  var postData =  $(this).serializeArray();
  var loggedInUser = email;
  clicked = id;
  $.post( "readcomments.php", "user=" + id + "&loggedInUser=" + loggedInUser,  function( data ) {
    $('#messageList li').html(data.lists);
  }, "json");

}

function getMessagesCircle(id, email){
  inWhich = 1;
  var postData =  $(this).serializeArray();
  var loggedInUser = email;
  clicked = id;
  $.post( "readcirclecomments.php", "id=" + id + "&loggedInUser=" + loggedInUser,  function( data ) {
    $('#messageList li').html(data.lists);
  }, "json");
}

$(document).ready(function () { //for tooltips
  $('[data-toggle="tooltip"]').tooltip()
})
</script>

<style type="text/css">
.active {
  background-color: green !important;
}
.padding
{
    padding-left: 10px;
}
.paddingTop
{
	padding-top: 30px;
}
.chat
{
    list-style: none;
    margin: 0;
    padding: 0;
}

.chat li
{
    margin-bottom: 10px;
    padding-bottom: 5px;
    border-bottom: 1px dotted #B3A9A9;
}

.chat li.left .chat-body
{
    margin-left: 60px;
}

.chat li.right .chat-body
{
    margin-right: 60px;
}


.chat li .chat-body p
{
    margin: 0;
    color: #777777;
}

.panel .slidedown .glyphicon, .chat .glyphicon
{
    margin-right: 5px;
}

.panel-body
{
    overflow-y: scroll;
    height: 500px;
}

::-webkit-scrollbar-track
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    background-color: #F5F5F5;
}

::-webkit-scrollbar
{
    width: 12px;
    background-color: #F5F5F5;
}

::-webkit-scrollbar-thumb
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
    background-color: #555;
}

</style>
