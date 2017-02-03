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
?>

<div class="paddingTop container">
    <div class="row">
    <!-- Left Pannel -->
    <div class="col-md-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <span class="glyphicon glyphicon-user"></span> People
                <div class="btn-group pull-right">
                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                        <span class="glyphicon glyphicon-chevron-down"></span>
                    </button>
                        <ul class="dropdown-menu slidedown">
                            <li><a href="http://www.jquery2dotnet.com"><span class="glyphicon glyphicon-refresh">
                            </span>Refresh</a></li>
                            <li><a href="http://www.jquery2dotnet.com"><span class="glyphicon glyphicon-ok-sign">
                            </span>Available</a></li>
                            <li><a href="http://www.jquery2dotnet.com"><span class="glyphicon glyphicon-remove">
                            </span>Busy</a></li>
                            <li><a href="http://www.jquery2dotnet.com"><span class="glyphicon glyphicon-time"></span>
                                Away</a></li>
                            <li class="divider"></li>
                            <li><a href="http://www.jquery2dotnet.com"><span class="glyphicon glyphicon-off"></span>
                                Sign Out</a></li>
                        </ul>
                    </div>
                </div>
                <div class="panel-body">
                    <ul id="left-panel" class="chat">
                      <?php
                      $pdo = Database::connect();
                      $sql = 'SELECT DISTINCT emailTo AS email FROM messages WHERE emailFrom = ? AND emailTo NOT REGEXP \'^[0-9]+$\' UNION SELECT DISTINCT emailFrom AS email FROM messages WHERE emailTo = ? AND emailFrom NOT REGEXP \'^[0-9]+$\';';
                      $q1 = $pdo->prepare($sql);
                      $q1->execute(array($email,$email));
                      foreach ($q1->fetchAll() as $row) {
                          $profileImage = getProfilePicture($row['email'])['profileImage'];
                          date_default_timezone_set('Europe/London');
                          $date1 = date('m/d/Y h:i:s a', time());
                          $date2 = getMessageDate($row['email'])['dateCreated'];
                          echo '<li onclick="getMessagesUser(\''.$row['email'].'\',\''.$email.'\')" class="left clearfix"><span class="chat-img pull-left">
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
                      $sql = 'SELECT c.circleOfFriendsName, c.circleFriendsId FROM circleoffriends c INNER JOIN usercirclerelationships u ON c.circleFriendsId = u.circleFriendsId WHERE u.email = ?;';
                      $q1 = $pdo->prepare($sql);
                      $q1->execute(array($email));
                      foreach ($q1->fetchAll() as $row) {
                          date_default_timezone_set('Europe/London');
                          $date1 = date('m/d/Y h:i:s a', time());
                          $date2 = getMessageDate($row['circleFriendsId'])['dateCreated'];
                          echo '<li onclick="getMessagesCircle('.$row['circleFriendsId'].')" class="left clearfix"><span class="chat-img pull-left">
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
                                 ?>
                    </ul>
                </div>
                <div class="panel-footer">
                    <div class="input-group">
                        <input id="btn-input" type="text" class="form-control input-sm" placeholder="Search messages here..." />
                        <span class="input-group-btn">
                            <button class="btn btn-warning btn-sm" id="btn-chat">
                                Search</button>
                        </span>
                    </div>
                </div>
            </div>
    </div>

    <!--Right Pannel -->
        <div class="col-md-8">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-comment"></span> Chat
                    <div class="btn-group pull-right">
                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                            <span class="glyphicon glyphicon-chevron-down"></span>
                        </button>
                        <ul class="dropdown-menu slidedown">
                            <li><a href="http://www.jquery2dotnet.com"><span class="glyphicon glyphicon-refresh">
                            </span>Refresh</a></li>
                            <li><a href="http://www.jquery2dotnet.com"><span class="glyphicon glyphicon-ok-sign">
                            </span>Available</a></li>
                            <li><a href="http://www.jquery2dotnet.com"><span class="glyphicon glyphicon-remove">
                            </span>Busy</a></li>
                            <li><a href="http://www.jquery2dotnet.com"><span class="glyphicon glyphicon-time"></span>
                                Away</a></li>
                            <li class="divider"></li>
                            <li><a href="http://www.jquery2dotnet.com"><span class="glyphicon glyphicon-off"></span>
                                Sign Out</a></li>
                        </ul>
                    </div>
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
</script>

<style type="text/css">
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
