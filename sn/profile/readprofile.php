<?php

    $title = "Bookface Social Network";
    $description = "A far superior social network";
    include("../inc/header.php");
    include("../inc/nav-trn.php");

    $email = null;
    if (!empty($_GET['email'])) {
        $email = $_REQUEST['email'];
    }

    //Access Rights - changing view
    $showEmail='true';
    if(strcmp($loggedInUser, $email)==0) {$userAccess='hidden'; $adminAccess='true';}else{$userAccess='true'; $adminAccess='hidden';}

    if (null==$email) {
        header("Location: index.php");
    } else {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM users WHERE email = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($email));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        Database::disconnect();
    }

    function getPhoto($photoId)
    {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT imageReference FROM photos WHERE photoCollectionId = ? LIMIT 1;';
        $q = $pdo->prepare($sql);
        $q->execute(array($photoId));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        return $data;
    }   

    function checkDescription($description)
    {
        if(strcmp($description,'null')==0)
            $description = "This person has not written anything about themselves.";
        return $description;
    }

    function checkUserIsFriendOfFriend($loggedInUser,$email)
    {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT IF 
        ((SELECT COUNT(*) as total FROM users JOIN friendships ON users.email = friendships.emailFrom OR users.email=friendships.emailTo WHERE 
        users.email = ? AND 
        (friendships.emailFrom IN
            (SELECT DISTINCT email FROM users JOIN friendships ON users.email = friendships.emailFrom OR users.email=friendships.emailTo WHERE (friendships.emailFrom= ? OR friendships.emailTo= ?) AND users.email!=? AND status=\'accepted\') 
        OR friendships.emailTo IN
            (SELECT DISTINCT email FROM users JOIN friendships ON users.email = friendships.emailFrom OR users.email=friendships.emailTo WHERE (friendships.emailFrom= ? OR friendships.emailTo= ?) AND users.email!=? AND status=\'accepted\')) 
        AND users.email NOT IN 
            (SELECT DISTINCT email FROM users JOIN friendships ON users.email = friendships.emailFrom OR users.email=friendships.emailTo WHERE (friendships.emailFrom= ? OR friendships.emailTo= ?) AND users.email!=? AND status=\'accepted\')
        AND users.email!=?),true,false) AS value';        
        $q = $pdo->prepare($sql);
        $q->execute(array($loggedInUser,$email,$email,$email,$email,$email,$email,$email,$email,$email,$email));        
        $data = $q->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    function checkUserIsFriend($loggedInUser,$email)
    {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT IF 
            ((SELECT COUNT(*) as total FROM users 
            JOIN friendships ON users.email = friendships.emailFrom OR users.email=friendships.emailTo 
            WHERE (friendships.emailFrom= ? OR friendships.emailTo= ?) 
            AND users.email!=? AND status=\'accepted\' AND users.email=?
            )>0,true,false) AS value';
        $q = $pdo->prepare($sql);
        $q->execute(array($email,$email,$email,$loggedInUser));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    function checkCollectionPrivacySettings($email)
    {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT * FROM privacysettings WHERE email = ? AND privacyTitleId=4;';
        $q = $pdo->prepare($sql);
        $q->execute(array($email));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        return $data;
    }


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>
<body>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<div id="user-profile-2" class="user-profile">
        <div class="tabbable">
            <ul class="nav nav-tabs padding-18">
                <li class="active">
                    <a data-toggle="tab" href="#home">
                        <i class="green ace-icon fa fa-user bigger-120"></i>
                        Profile
                    </a>
                </li>

                <li>
                    <a data-toggle="tab" href="#friends">
                        <i class="blue ace-icon fa fa-users bigger-120"></i>
                        Friends
                    </a>
                </li>

                <li>
                    <a data-toggle="tab" href="#pictures">
                        <i class="pink ace-icon fa fa-picture-o bigger-120"></i>
                        Pictures
                    </a>
                </li>
            </ul>

            <div class="tab-content no-border padding-24">
                <div id="home" class="tab-pane in active">
                    <div class="row">
                        <div class="col-xs-12 col-sm-3 center">
                            <span class="profile-picture">
                            <a class="open-update_photo" width="300" data-toggle="modal" href="#update_photo" data-rel="colorbox">
                                <img height="300" src=<?php echo $data['profileImage'];?>>
                            </a>

                            <div class="tools tools-bottom">
                                <a class="open-update_photo" data-toggle="modal" href="#update_photo">
                                    <i class="ace-icon fa fa-pencil"></i>
                                </a>
                            </div>
                            </span>


                            <div class="space space-4"></div>
                            <?php //check friend request privacy of user!
                                if($email!=$loggedInUser){ //only show add as friend or send message button if the profile that is being viewed is not the currently logged in user
                                    
                                    //TODO: check if user is already a friend, if so, change the button to show unfriend option? otherwise execute below code

                                    $getFriendshipPrivacy = "SELECT privacyType FROM MyDB.privacySettings WHERE(email='".$email."' AND privacyTitleId IN (SELECT privacyTitleId FROM privacyTitles WHERE privacySettingsDescription='Who can send me friend requests?'))";
                                    $pdo = Database::connect();
                                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                    $stmt = $pdo->prepare($getFriendshipPrivacy); 
                                    $stmt->execute(); 
                                    $row = $stmt->fetch();
                                    $privacy = $row['privacyType'];
                                    if ($privacy=='Anyone'){
                                        echo '<a href="#" class="btn btn-sm btn-block btn-success">
                                            <i class="ace-icon fa fa-plus-circle bigger-120"></i>
                                            <span class="bigger-110" >Add as a friend</span>
                                        </a>';
                                    }elseif($privacy=='Friends of friends'){
                                        //*******BEGIN LOTS OF QUERYING CODE TO FIND FRIENDS OF FRIENDS*******
                                        //firstly, we get the friends of the logged-in user and prepare it for use in the main sql search query
                                        $getFriends="SELECT email FROM MyDB.users WHERE(email IN (SELECT emailTo FROM MyDB.friendships WHERE (emailFrom='" . $loggedInUser . "' AND status='accepted')) OR email IN (SELECT emailFrom FROM MyDB.friendships WHERE ( emailTo='". $loggedInUser . "' AND status='accepted')))";

                                        $countFriends="SELECT COUNT(email) FROM MyDB.users WHERE(email IN (SELECT emailTo FROM MyDB.friendships WHERE (emailFrom='" . $loggedInUser . "' AND status='accepted')) OR email IN (SELECT emailFrom FROM MyDB.friendships WHERE ( emailTo='". $loggedInUser . "' AND status='accepted')))";

                                        $q = $pdo->query($countFriends);
                                        $countFriendsResult = $q->fetch(PDO::FETCH_ASSOC);
                                        $countF = $countFriendsResult["COUNT(email)"];

                                        $currentFriend = 0; //keep track of how many friends there are to keep the correct formatting
                                        $friends[] .= "("; //this friends array stores the list of friends to the logged-in-user in a format which is easy to use in the sql query

                                        foreach($pdo->query($getFriends) as $row){
                                          $currentFriend += 1;
                                          if($currentFriend==$countF){ //last friend
                                            $friends[] .= "'" . $row["email"] . "'";
                                          }else{
                                            $friends[] .= "'" . $row["email"] . "',";
                                          }
                                        }
                                        $friends[] .= ")";

                                        $friendStr = implode($friends);

                                        $getFriendsOfFriends="SELECT email FROM MyDB.users WHERE ((email IN (SELECT emailTo FROM MyDB.friendships WHERE (emailFrom='" . $email . "' AND status='accepted')) OR email IN (SELECT emailFrom FROM MyDB.friendships WHERE ( emailTo='". $email . "' AND status='accepted')) OR email IN (SELECT emailTo FROM MyDB.friendships WHERE (emailFrom IN " .$friendStr . " AND status='accepted') OR email IN (SELECT emailFrom FROM MyDB.friendships WHERE(emailTo IN " . $friendStr . " AND status='accepted')))) AND email!='" . $email . "')"; //get friends/friends of friends of the profile we're looking at
                                        $sharedMutualFriends=false;
                                        foreach($pdo->query($getFriendsOfFriends) as $row){
                                            if($row["email"]==$loggedInUser){
                                                $sharedMutualFriends=true;
                                            }
                                        }
                                        //*******END LOTS OF QUERYING CODE TO FIND FRIENDS OF     FRIENDS*******                                        

                                        if($sharedMutualFriends){
                                            echo '<a href="#" class="btn btn-sm btn-block btn-success">
                                                <i class="ace-icon fa fa-plus-circle bigger-120"></i>
                                                <span class="bigger-110" >Add as a friend</span>
                                            </a>';
                                        }else{
                                            echo '<a href="#" class="btn btn-sm btn-block btn-success disabled">
                                                <i class="ace-icon fa fa-plus-circle bigger-120"></i>
                                                <span class="bigger-110" >Can\'t add this person as a friend</span>
                                            </a>';
                                        }
                                    }elseif($privacy=='Noone'){
                                        echo '<a href="#" class="btn btn-sm btn-block btn-success disabled">
                                            <i class="ace-icon fa fa-plus-circle bigger-120"></i>
                                            <span class="bigger-110" >Can\'t add this person as a friend</span>
                                        </a>';
                                    }

                               
                                    echo '<a href="messages.php" class="btn btn-sm btn-block btn-primary">
                                        <i class="ace-icon fa fa-envelope-o bigger-110"></i>
                                        <span class="bigger-110">Send a message</span>
                                        </a>';
                                }
                            ?>

                            <a style="visibility: <?php echo $adminAccess ?>" data-toggle="modal" data-target="#export_dialog" class="btn btn-sm btn-block btn-primary">
                                <i class="ace-icon fa fa-download bigger-110"></i>
                                <span class="bigger-110">Export Profile</span>
                            </a>

                            <a style="visibility: <?php echo $adminAccess ?>" data-toggle="modal" data-target="#import_dialog" class="btn btn-sm btn-block btn-primary">
                                <i class="ace-icon fa fa-upload bigger-110"></i>
                                <span class="bigger-110">Import Profile</span>
                            </a>

                        </div><!-- /.col -->

                        <div class="col-xs-12 col-sm-9">
                            <h4 class="blue">
                                <span class="middle"> <?php echo $data['firstName'];?> <?php echo $data['lastName'];?> </span>

                                <span class="label label-purple arrowed-in-right">
                                    <i class="ace-icon fa fa-circle smaller-80 align-middle"></i>
                                    online
                                </span>
                            </h4>

                            <div class="profile-user-info">
                                <div class="profile-info-row">
                                    <div style="visibility: <?php echo $showEmail ?>" class="profile-info-name"> Email </div>

                                    <div style="visibility: <?php echo $showEmail ?>" class="profile-info-value">
                                        <span> <?php echo $data['email'];?> </span>
                                    </div>
                                </div>

                            </div>
                        </div><!-- /.col -->

                        <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="widget-box transparent">
                                <div class="widget-header widget-header-small">
                                    <h4 class="widget-title smaller">
                                        <i class="ace-icon fa fa-check-square-o bigger-110"></i>
                                        Little About Me
                                    </h4>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <p> <?php echo checkDescription($data['profileDescription']);?> 
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div><!-- /.row -->

                    <div class="space-20"></div>


                </div><!-- /#home -->

                <div id="friends" class="tab-pane">
                    <div class="profile-users clearfix">

                        <?php
                            $pdo = Database::connect();
                            $sql = 'SELECT DISTINCT email, firstName, lastName, profileImage FROM users JOIN friendships ON users.email = friendships.emailFrom OR users.email=friendships.emailTo WHERE (friendships.emailFrom= ? OR friendships.emailTo= ?) AND users.email!= ? AND status=\'accepted\';';
                            $q1 = $pdo->prepare($sql);
                            $q1->execute(array($email,$email,$email));
                            foreach ($q1->fetchAll() as $row) {
                                echo '<div class="itemdiv memberdiv">
                                        <div class="inline pos-rel">
                                            <div class="user">
                                                <a href="readprofile.php?email='.$row['email'].'">
                                                    <img href="readprofile.php?email='.$row['email'].'" height="65" src=" ' . $row['profileImage'] . ' " alt="Bob Does avatar">
                                                </a>
                                            </div>

                                            <div class="body">
                                                <div class="name">
                                                    <a href="readprofile.php?email='.$row['email'].'">
                                                        <span class="user-status status-online"></span>
                                                            ' . $row['firstName']. ' ' . $row['lastName']. '
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
                            }
                        ?>

                    </div>
                </div><!-- /#friends -->


                <div id="pictures" class="tab-pane">

                    <button style="visibility: <?php echo $adminAccess ?>" type="button" class="btn btn-info" data-toggle="modal" data-target="#collection_dialog">Create Collection</button>

                    <?php
                        // Check the user's privacy settings for photo collections
                        $value=checkCollectionPrivacySettings($email)['privacyType'];
                        checkUserIsFriendOfFriend($loggedInUser,$email)['value'];

                        $allowAccess=0; //default to allow access
                        if($value=='Friends of friends' && checkUserIsFriendOfFriend($loggedInUser,$email)['value'])
                           $allowAccess=1;
                        if($value=='Anyone')
                            $allowAccess=1;
                        if(checkUserIsFriend($loggedInUser,$email)['value'] && $value!='None')
                            $allowAccess=1;
                        if($loggedInUser==$email)
                            $allowAccess=1;
                        
                        if($allowAccess)
                        {
                            //$pdo = Database::connect();
                            $sql = 'SELECT photoCollectionId, dateCreated, title, description FROM photocollection WHERE createdBy = ?;';
                            $q1 = $pdo->prepare($sql);
                            $q1->execute(array($email));
                            $numberofresults = 0;
                            foreach ($q1->fetchAll() as $row) {
                                $numberofresults+=1;
                                $imageReference = getPhoto($row['photoCollectionId'])['imageReference'];
                                if($imageReference==null)
                                    $imageReference="http://www.plantauthority.gov.in/images/pg1.png";
                                echo '
                                <ul class="ace-thumbnails">
                                    <li>
                                        <a href="readphotocollection.php?createdBy='.$email.'&photoCollectionId='.$row['photoCollectionId'].'" data-rel="colorbox">
                                            <img height="300" src=" ' . $imageReference . ' ">
                                            <div class="text">
                                                <div class="inner"> ' . $row['title'] . ' </div>
                                            </div>
                                        </a>

                                        <div style="visibility: '.$adminAccess.'" class="tools tools-bottom">
                                            <a data-title="'.$row['title'].'" data-description="'.$row['description'].'" data-id="'.$row['photoCollectionId'].'" class="open-update_dialog" data-toggle="modal" href="#update_dialog">
                                                <i class="ace-icon fa fa-pencil"></i>
                                            </a>

                                            <a data-title="'.$row['title'].'" data-id="'.$row['photoCollectionId'].'" class="open-delete_dialog" data-toggle="modal" href="#delete_dialog">
                                                <i class="ace-icon fa fa-times red"></i>
                                            </a>
                                        </div>
                                    </li>
                                </ul>';
                            }
                            if($numberofresults==0){
                                echo '<ul class="ace-thumbnails">No photos found</ul>';
                            }
                        } else
                        {
                            echo '<p> You don\'t have access to see the user\'s photo collections. </p>';
                        }
                        ?>
                </div><!-- /#pictures -->
            </div>
        </div>

        <!-- modal to edit profile picture -->
        <div class="modal fade" id="update_photo" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Update Profile Picture</h4>
                    </div>
                    <form class="" action="uploadphoto.php?id=<?php echo $loggedInUser ?>" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <p style="visibility: <?php echo $userAccess ?>">You don't have access to change other users' profile pictures.<p>
                        <p style="visibility: <?php echo $adminAccess ?>">Once a new picture is submitted, the old one will be removed.</p>
                            <p style="visibility: <?php echo $adminAccess ?>" class=""> Select image to upload: </p>
                            <input style="visibility: <?php echo $adminAccess ?>" class="" type="file" name="fileToUpload" id="fileToUpload"> <br>
                    </div>
                    <div style="visibility: <?php echo $adminAccess ?>" class="modal-footer">
                    <input class= "btn btn-primary" type="submit" value="Upload Image" name="submit">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- modal to export user profile -->
        <div class="modal fade" id="export_dialog" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Export your profile to XML format</h4>
                    </div>
                    <div class="modal-body">
                        <form data-title=<?php echo $loggedInUser ?> id="export_form" action="../XML/exportProfile.php" method="POST">
                            <p> Are you sure you want to export your profile? We appreciate your patience as it may take a while.</p>
                            <div id="myProgress">
                              <div id="myBar">
                                <div id="label">0%</div>
                              </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" id="submitForm4" class="btn btn-success">Export</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- modal to import user profile -->
        <div class="modal fade" id="import_dialog" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Import your profile to XML format</h4>
                    </div>
                    <div class="modal-body">
                       <form  data-title=<?php echo $loggedInUser ?> action="../XML/importProfile.php?" method="post" enctype="multipart/form-data">
                            <div class="modal-body">
                                <p> Upload your XML file! All your current data will be lost! </p>
                                <p class=""> Select XML file to upload: </p>
                                <input class="" type="file" name="fileToUpload" id="fileToUpload"> <br>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <input class= "btn btn-primary" type="submit" value="Import" name="submit">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- modal to create new collection -->
        <div class="modal fade" id="collection_dialog" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Create New Collection</h4>
                    </div>
                    <div class="modal-body">
                        <form data-title=<?php echo $loggedInUser ?> id="collection_form" action="createcollection.php" method="POST">
                            <input type="text" name="albumName" placeholder="Enter Album Name"><br/><br/>
                            <input type="text" name="descriptionName" placeholder="Enter Album Description"><br/>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" id="submitForm" class="btn btn-success">Create</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- modal to update collection -->
        <!-- the div that represents the modal dialog -->
        <div class="modal fade" id="update_dialog" role="dialog">
            <div class="modal-dialog">
               <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Update Collection</h4>
                    </div>
                    <div class="modal-body">
                        <form id="update_form" action="updatephotocollection.php" method="POST">
                            Collection Title: <input type="text" name="albumName" id="albumName" placeholder="Edit Album Name"><br/><br/>
                            Collection Description: <input type="text" name="albumDescription" id="albumDescription" placeholder="Edit Album Description"><br/>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" id="submitForm2" class="btn btn-success">Update</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- modal to delete collection -->
        <!-- the div that represents the modal dialog -->
        <div class="modal fade" id="delete_dialog" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Delete Collection</h4>
                    </div>
                    <div class="modal-body">
                        <form id="delete_form" action="deletephotocollection.php" method="POST">
                            Are you sure you want to delete the collection  <input type="text" name="deletealbumName" id="deletealbumName"> ?
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" id="submitForm3" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script type="text/javascript">

</script>
</body>
</html>

<script>
/* must apply only after HTML has loaded */
$(document).ready(function () {

    // Create Collection Button
    $("#collection_form").on("submit", function(e) {
        var postData = $(this).serializeArray();
        var email = $(this).data('title');
        postData.push({name: "email", value: email});
        var formURL = $(this).attr("action");
        $.ajax({
            url: formURL,
            type: "POST",
            data: postData,
            success: function(data, textStatus, jqXHR) {
                $('#collection_dialog .modal-header .modal-title').html("Result");
                $('#collection_dialog .modal-body').html(data);
                $("#submitForm").remove();

                location.reload();
            },
            error: function(jqXHR, status, error) {
                console.log(status + ": " + error);
            }
        });
        e.preventDefault();
    });

    $("#submitForm").on('click', function() {
        $("#collection_form").submit();
    });
});


$(document).ready(function () {

    // Export Collection Button
    $("#export_form").on("submit", function(e) {

      var elem = document.getElementById("myBar");   
      var width = 10;
      var id = setInterval(frame, 300);
      function frame() {
        if (width >= 100) {
          clearInterval(id);
        } else {
          width++; 
          elem.style.width = width + '%'; 
          document.getElementById("label").innerHTML = width * 1  + '%';
        }
      }
        var postData = $(this).serializeArray();
        var email = $(this).data('title');
        postData.push({name: "email", value: email});
        var formURL = $(this).attr("action");
        $.ajax({
            url: formURL,
            type: "POST",
            data: postData,
            success: function(data, textStatus, jqXHR) {
                $('#export_dialog .modal-header .modal-title').html("Result");
                $('#export_dialog .modal-body').html(data);
                $("#submitForm4").remove();

                //location.reload();
            },
            error: function(jqXHR, status, error) {
                console.log(status + ": " + error);
            }
        });
        e.preventDefault();
    });

    $("#submitForm4").on('click', function() {
        $("#export_form").submit();
    });
});

$(document).ready(function () {

    // Import Collection Button
    $("#import_form").on("submit", function(e) {

        var formURL = $(this).attr("action");
        $.ajax({
            url: formURL,
            type: "POST",
            data: postData,
            success: function(data, textStatus, jqXHR) {
                $('#import_dialog .modal-header .modal-title').html("Result");
                $('#import_dialog .modal-body').html(data);
                $("#submitForm5").remove();

                //location.reload();
            },
            error: function(jqXHR, status, error) {
                console.log(status + ": " + error);
            }
        });
        e.preventDefault();
    });

    $("#submitForm5").on('click', function() {
        $("#import_form").submit();
    });
});


// Update Collection
var albumName = null;
var albumDescription = null;
var albumId = null;

$(document).on("click", ".open-update_dialog", function () {
     albumName = $(this).data('title');
     $(".modal-body #albumName").val(albumName);
     albumDescription = $(this).data('description');
     $(".modal-body #albumDescription").val(albumDescription);
     albumId = $(this).data('id');

    // Update Collection Button
    $("#update_form").on("submit", function(e) {
        var postData2 =  $(this).serializeArray();
        postData2.push({name: "photoCollectionId", value: albumId});
        var formURL = $(this).attr("action");
        $.ajax({
            url: formURL,
            type: "POST",
            data: postData2,
            success: function(data, textStatus, jqXHR) {
                $('#update_dialog .modal-header .modal-title').html("Result");
                $('#update_dialog .modal-body').html(data);
                $("#submitForm2").remove();
            },
            error: function(jqXHR, status, error) {
                console.log(status + ": " + error);
            }
        });
        e.preventDefault();
    });

    $("#submitForm2").on('click', function() {
        $("#update_form").submit();
    });
});

var clicked = null;
// Delete Collection
var deletealbumName = null;

$(document).on("click", ".open-delete_dialog", function () {
     deletealbumName = $(this).data('title');
     $(".modal-body #deletealbumName").val(deletealbumName);
     albumId = $(this).data('id');

    // Delete Collection Button
    $("#delete_form").on("submit", function(e) {
        var postData3 =  $(this).serializeArray();
        postData3.push({name: "photoCollectionId", value: albumId});
        var formURL = $(this).attr("action");
        $.ajax({
            url: formURL,
            type: "POST",
            data: postData3,
            success: function(data, textStatus, jqXHR) {
                $('#delete_dialog .modal-header .modal-title').html("Result");
                $('#delete_dialog .modal-body').html(data);
                $("#submitForm3").remove();

                location.reload();
            },
            error: function(jqXHR, status, error) {
                console.log(status + ": " + error);
            }
        });
        e.preventDefault();
    });

    $("#submitForm3").on('click', function() {
        $("#delete_form").submit();
    });
});

// Update Profile Picture
var profilePicture = null;

$(document).on("click", ".open-update_photo", function () {
     albumName = $(this).data('title');
     $(".modal-body #albumName").val(albumName);
     albumDescription = $(this).data('description');
     $(".modal-body #albumDescription").val(albumDescription);
     albumId = $(this).data('id');
    
});


</script>


<style type="text/css">
/*body{margin-top:20px;}*/

#myProgress {
  width: 100%;
  background-color: #ddd;
}

#myBar {
  width: 1%;
  height: 30px;
  background-color: #4CAF50;
}

#label {
  text-align: center;
  line-height: 30px;
  color: white;
}
.align-center, .center {
    text-align: center!important;
}

.profile-user-info {
    display: table;
    width: 98%;
    width: calc(100% - 24px);
    margin: 0 auto
}

.profile-info-row {
    display: table-row
}

.profile-info-name,
.profile-info-value {
    display: table-cell;
    border-top: 1px dotted #D5E4F1
}

.profile-info-name {
    text-align: right;
    padding: 6px 10px 6px 4px;
    font-weight: 400;
    color: #667E99;
    background-color: transparent;
    width: 110px;
    vertical-align: middle
}

.profile-info-value {
    padding: 6px 4px 6px 6px
}

.profile-info-value>span+span:before {
    display: inline;
    content: ",";
    margin-left: 1px;
    margin-right: 3px;
    color: #666;
    border-bottom: 1px solid #FFF
}

.profile-info-value>span+span.editable-container:before {
    display: none
}

.profile-info-row:first-child .profile-info-name,
.profile-info-row:first-child .profile-info-value {
    border-top: none
}

.profile-user-info-striped {
    border: 1px solid #DCEBF7
}

.profile-user-info-striped .profile-info-name {
    color: #336199;
    background-color: #EDF3F4;
    border-top: 1px solid #F7FBFF
}

.profile-user-info-striped .profile-info-value {
    border-top: 1px dotted #DCEBF7;
    padding-left: 12px
}

.profile-picture {
    border: 1px solid #CCC;
    background-color: #FFF;
    padding: 4px;
    display: inline-block;
    max-width: 100%;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    box-shadow: 1px 1px 1px rgba(0, 0, 0, .15)
}

.dd-empty,
.dd-handle,
.dd-placeholder,
.dd2-content {
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box
}

.profile-activity {
    padding: 10px 4px;
    border-bottom: 1px dotted #D0D8E0;
    position: relative;
    border-left: 1px dotted #FFF;
    border-right: 1px dotted #FFF
}

.profile-activity:first-child {
    border-top: 1px dotted transparent
}

.profile-activity:first-child:hover {
    border-top-color: #D0D8E0
}

.profile-activity:hover {
    background-color: #F4F9FD;
    border-left: 1px dotted #D0D8E0;
    border-right: 1px dotted #D0D8E0
}

.profile-activity img {
    border: 2px solid #C9D6E5;
    border-radius: 100%;
    max-width: 40px;
    margin-right: 10px;
    margin-left: 0;
    box-shadow: none
}

.profile-activity .thumbicon {
    background-color: #74ABD7;
    display: inline-block;
    border-radius: 100%;
    width: 38px;
    height: 38px;
    color: #FFF;
    font-size: 18px;
    text-align: center;
    line-height: 38px;
    margin-right: 10px;
    margin-left: 0;
    text-shadow: none!important
}

.profile-activity .time {
    display: block;
    margin-top: 4px;
    color: #777
}

.profile-activity a.user {
    font-weight: 700;
    color: #9585BF
}

.profile-activity .tools {
    position: absolute;
    right: 12px;
    bottom: 8px;
    display: none
}

.profile-activity:hover .tools {
    display: block
}

.user-profile .ace-thumbnails li {
    border: 1px solid #CCC;
    padding: 3px;
    margin: 6px
}

.user-profile .ace-thumbnails li .tools {
    left: 3px;
    right: 3px
}

.user-profile .ace-thumbnails li:hover .tools {
    bottom: 3px
}

.user-title-label:hover {
    text-decoration: none
}

.user-title-label+.dropdown-menu {
    margin-left: -12px
}

.profile-contact-links {
    padding: 4px 2px 5px;
    border: 1px solid #E0E2E5;
    background-color: #F8FAFC
}

.btn-link:hover .ace-icon {
    text-decoration: none!important
}

.profile-social-links>a:hover>.ace-icon,
.profile-users .memberdiv .name a:hover .ace-icon,
.profile-users .memberdiv .tools>a:hover {
    text-decoration: none
}

.profile-social-links>a {
    text-decoration: none;
    margin: 0 1px
}

.profile-skills .progress {
    height: 26px;
    margin-bottom: 2px;
    background-color: transparent
}

.profile-skills .progress .progress-bar {
    line-height: 26px;
    font-size: 13px;
    font-weight: 700;
    font-family: "Open Sans";
    padding: 0 8px
}

.profile-users .user {
    display: block;
    position: static;
    text-align: center;
    width: auto
}

.profile-users .user img {
    padding: 2px;
    border-radius: 100%;
    border: 1px solid #AAA;
    max-width: none;
    width: 64px;
    -webkit-transition: all .1s;
    -o-transition: all .1s;
    transition: all .1s
}

.profile-users .user img:hover {
    -webkit-box-shadow: 0 0 1px 1px rgba(0, 0, 0, .33);
    box-shadow: 0 0 1px 1px rgba(0, 0, 0, .33)
}

.profile-users .memberdiv {
    background-color: #FFF;
    width: 100px;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    border: none;
    text-align: center;
    margin: 0 8px 24px
}

.profile-users .memberdiv .body {
    display: inline-block;
    margin: 8px 0 0
}

.profile-users .memberdiv .popover {
    visibility: hidden;
    min-width: 0;
    max-height: 0;
    max-width: 0;
    margin-left: 0;
    margin-right: 0;
    top: -5%;
    left: auto;
    right: auto;
    opacity: 0;
    display: none;
    position: absolute;
    -webkit-transition: opacity .2s linear 0s, visibility 0s linear .2s, max-height 0s linear .2s, max-width 0s linear .2s, min-width 0s linear .2s;
    -o-transition: opacity .2s linear 0s, visibility 0s linear .2s, max-height 0s linear .2s, max-width 0s linear .2s, min-width 0s linear .2s;
    transition: opacity .2s linear 0s, visibility 0s linear .2s, max-height 0s linear .2s, max-width 0s linear .2s, min-width 0s linear .2s
}

.profile-users .memberdiv .popover.right {
    left: 100%;
    right: auto;
    display: block
}

.profile-users .memberdiv .popover.left {
    left: auto;
    right: 100%;
    display: block
}

.profile-users .memberdiv>:first-child:hover .popover {
    visibility: visible;
    opacity: 1;
    z-index: 1060;
    max-height: 250px;
    max-width: 250px;
    min-width: 150px;
    -webkit-transition-delay: 0s;
    -moz-transition-delay: 0s;
    -o-transition-delay: 0s;
    transition-delay: 0s
}

.profile-users .memberdiv .tools {
    position: static;
    display: block;
    width: 100%;
    margin-top: 2px
}

.profile-users .memberdiv .tools>a {
    margin: 0 2px
}

.user-status {
    display: inline-block;
    width: 11px;
    height: 11px;
    background-color: #FFF;
    border: 3px solid #AAA;
    border-radius: 100%;
    vertical-align: middle;
    margin-right: 1px
}

.user-status.status-online {
    border-color: #8AC16C
}

.user-status.status-busy {
    border-color: #E07F69
}

.user-status.status-idle {
    border-color: #FFB752
}

.tab-content.profile-edit-tab-content {
    border: 1px solid #DDD;
    padding: 8px 32px 32px;
    -webkit-box-shadow: 1px 1px 0 0 rgba(0, 0, 0, .2);
    box-shadow: 1px 1px 0 0 rgba(0, 0, 0, .2);
    background-color: #FFF
}

@media only screen and (max-width:480px) {
    .profile-info-name {
        width: 80px
    }
    .profile-user-info-striped .profile-info-name {
        float: none;
        width: auto;
        text-align: left;
        padding: 6px 4px 6px 10px;
        display: block
    }
    .profile-user-info-striped .profile-info-value {
        margin-left: 10px;
        display: block
    }
    .user-profile .memberdiv {
        width: 50%;
        margin-left: 0;
        margin-right: 0
    }
}



.itemdiv {
    padding-right: 3px;
    min-height: 66px
}

.itemdiv>.user {
    display: inline-block;
    width: 42px;
    position: absolute;
    left: 0
}

.itemdiv>.user>.img,
.itemdiv>.user>img {
    border-radius: 100%;
    border: 2px solid #5293C4;
    max-width: 40px;
    position: relative
}

.itemdiv>.user>.img {
    padding: 2px
}

.itemdiv>.body {
    width: auto;
    margin-left: 50px;
    margin-right: 12px;
    position: relative
}

.itemdiv>.body>.time {
    display: block;
    font-size: 11px;
    font-weight: 700;
    color: #666;
    position: absolute;
    right: 9px;
    top: 0
}

.itemdiv>.body>.time .ace-icon {
    font-size: 14px;
    font-weight: 400
}

.itemdiv>.body>.name {
    display: block;
    color: #999
}

.itemdiv>.body>.name>b {
    color: #777
}

.itemdiv>.body>.text {
    display: block;
    position: relative;
    margin-top: 2px;
    padding-bottom: 19px;
    padding-left: 7px;
    font-size: 13px
}

.itemdiv.dialogdiv:before,
.itemdiv.dialogdiv>.body:before,
.itemdiv>.body>.text:after {
    content: "";
    position: absolute
}

.itemdiv>.body>.text:after {
    display: block;
    height: 1px;
    font-size: 0;
    overflow: hidden;
    left: 16px;
    right: -12px;
    margin-top: 9px;
    border-top: 1px solid #E4ECF3
}

.itemdiv>.body>.text>.ace-icon:first-child {
    color: #DCE3ED;
    margin-right: 4px
}

.itemdiv:last-child>.body>.text {
    border-bottom-width: 0
}

.itemdiv:last-child>.body>.text:after {
    display: none
}

.itemdiv.dialogdiv {
    padding-bottom: 14px
}

.itemdiv.dialogdiv:before {
    display: block;
    top: 0;
    bottom: 0;
    left: 19px;
    width: 3px;
    max-width: 3px;
    background-color: #E1E6ED;
    border: 1px solid #D7DBDD;
    border-width: 0 1px
}

.itemdiv.dialogdiv:last-child {
    padding-bottom: 0
}

.itemdiv.dialogdiv:last-child:before {
    display: none
}

.itemdiv.dialogdiv>.user>img {
    border-color: #C9D6E5
}

.itemdiv.dialogdiv>.body {
    border: 1px solid #DDE4ED;
    padding: 5px 8px 8px;
    border-left-width: 2px;
    margin-right: 1px
}

.itemdiv.dialogdiv>.body:before {
    display: block;
    left: -7px;
    top: 11px;
    width: 8px;
    height: 8px;
    border: 2px solid #DDE4ED;
    border-width: 2px 0 0 2px;
    background-color: #FFF;
    -webkit-box-sizing: content-box;
    -moz-box-sizing: content-box;
    box-sizing: content-box;
    -webkit-transform: rotate(-45deg);
    -ms-transform: rotate(-45deg);
    -o-transform: rotate(-45deg);
    transform: rotate(-45deg)
}

.itemdiv.dialogdiv>.body>.time {
    position: static;
    float: right
}

.itemdiv.dialogdiv>.body>.text {
    padding-left: 0;
    padding-bottom: 0
}

.itemdiv.dialogdiv>.body>.text:after {
    display: none
}

.itemdiv.dialogdiv .tooltip-inner {
    word-break: break-all
}

.itemdiv.memberdiv {
    width: 175px;
    padding: 2px;
    margin: 3px 0;
    float: left;
    border-bottom: 1px solid #E8E8E8
}

@media (min-width:992px) {
    .itemdiv.memberdiv {
        max-width: 50%
    }
}

@media (max-width:991px) {
    .itemdiv.memberdiv {
        min-width: 33.333%
    }
}

.itemdiv.memberdiv>.user>img {
    border-color: #DCE3ED
}

.itemdiv.memberdiv>.body>.time {
    position: static
}

.itemdiv.memberdiv>.body>.name {
    line-height: 18px;
    height: 18px;
    margin-bottom: 0
}

.itemdiv.memberdiv>.body>.name>a {
    display: inline-block;
    max-width: 100px;
    max-height: 18px;
    overflow: hidden;
    text-overflow: ellipsis;
    word-break: break-all
}

.itemdiv .tools {
    position: absolute;
    right: 5px;
    bottom: 10px;
    display: none
}

.item-list>li>.checkbox,
.item-list>li>label.inline,
.itemdiv:hover .tools {
    display: inline-block
}

.itemdiv .tools .btn {
    border-radius: 36px;
    margin: 1px 0
}

.itemdiv .body .tools {
    bottom: 4px
}

.itemdiv.commentdiv .tools {
    right: 9px
}

.item-list {
    margin: 0;
    padding: 0;
    list-style: none
}

.item-list>li {
    padding: 9px;
    background-color: #FFF;
    margin-top: -1px;
    position: relative
}

.item-list>li.selected {
    color: #8090A0;
    background-color: #F4F9FC
}

.item-list>li.selected .lbl,
.item-list>li.selected label {
    text-decoration: line-through;
    color: #8090A0
}

.item-list>li label {
    font-size: 13px
}

.item-list>li .percentage {
    font-size: 11px;
    font-weight: 700;
    color: #777
}


.ace-thumbnails>li,
.ace-thumbnails>li>:first-child {
    display: block;
    position: relative
}

.ace-thumbnails {
    list-style: none;
    margin: 0;
    padding: 0
}

.ace-thumbnails>li {
    float: left;
    overflow: hidden;
    margin: 2px;
    border: 2px solid #333
}

.ace-thumbnails>li>:first-child:focus {
    outline: 0
}

.ace-thumbnails>li .tags {
    display: inline-block;
    position: absolute;
    bottom: 0;
    right: 0;
    overflow: visible;
    direction: rtl;
    padding: 0;
    margin: 0;
    height: auto;
    width: auto;
    background-color: transparent;
    border-width: 0;
    vertical-align: inherit
}

.ace-thumbnails>li .tags>.label-holder {
    opacity: .92;
    filter: alpha(opacity=92);
    display: table;
    margin: 1px 0 0;
    direction: ltr;
    text-align: left
}

.ace-thumbnails>li>.tools,
.ace-thumbnails>li>:first-child>.text {
    position: absolute;
    text-align: center;
    background-color: rgba(0, 0, 0, .55)
}

.ace-thumbnails>li .tags>.label-holder:hover {
    opacity: 1;
    filter: alpha(opacity=100)
}

.ace-thumbnails>li>.tools {
    top: 0;
    bottom: 0;
    left: -30px;
    width: 24px;
    vertical-align: middle;
    -webkit-transition: all .2s ease;
    -o-transition: all .2s ease;
    transition: all .2s ease
}

.ace-thumbnails>li>.tools.tools-right {
    left: auto;
    right: -30px
}

.ace-thumbnails>li>.tools.tools-bottom {
    width: auto;
    height: 28px;
    left: 0;
    right: 0;
    top: auto;
    bottom: -30px
}

.ace-thumbnails>li>.tools.tools-top {
    width: auto;
    height: 28px;
    left: 0;
    right: 0;
    top: -30px;
    bottom: auto
}

.ace-thumbnails>li:hover>.tools {
    left: 0;
    right: 0
}

.ace-thumbnails>li:hover>.tools.tools-bottom {
    top: auto;
    bottom: 0
}

.ace-thumbnails>li:hover>.tools.tools-top {
    bottom: auto;
    top: 0
}

.ace-thumbnails>li:hover>.tools.tools-right {
    left: auto;
    right: 0
}

.ace-thumbnails>li>.in.tools {
    left: 0;
    right: 0
}

.ace-thumbnails>li>.in.tools.tools-bottom {
    top: auto;
    bottom: 0
}

.ace-thumbnails>li>.in.tools.tools-top {
    bottom: auto;
    top: 0
}

.ace-thumbnails>li>.in.tools.tools-right {
    left: auto;
    right: 0
}

.ace-thumbnails>li>.tools>a,
.ace-thumbnails>li>:first-child .inner a {
    display: inline-block;
    color: #FFF;
    font-size: 18px;
    font-weight: 400;
    padding: 0 4px
}

.ace-thumbnails>li>.tools>a:hover,
.ace-thumbnails>li>:first-child .inner a:hover {
    text-decoration: none;
    color: #C9E2EA
}

.ace-thumbnails>li .tools.tools-bottom>a,
.ace-thumbnails>li .tools.tools-top>a {
    display: inline-block
}

.ace-thumbnails>li>:first-child>.text {
    right: 0;
    left: 0;
    bottom: 0;
    top: 0;
    color: #FFF;
    opacity: 0;
    filter: alpha(opacity=0);
    -webkit-transition: all .2s ease;
    -o-transition: all .2s ease;
    transition: all .2s ease
}

.dialogs,
.itemdiv {
    position: relative
}

.ace-thumbnails>li>:first-child>.text:before {
    content: '';
    display: inline-block;
    height: 100%;
    vertical-align: middle;
    margin-right: 0
}

.ace-thumbnails>li>:first-child>.text>.inner {
    padding: 4px 0;
    margin: 0;
    display: inline-block;
    vertical-align: middle;
    max-width: 90%
}

.ace-thumbnails>li:hover>:first-child>.text {
    opacity: 1;
    filter: alpha(opacity=100)
}

@media only screen and (max-width:480px) {
    .ace-thumbnails {
        text-align: center
    }
    .ace-thumbnails>li {
        float: none;
        display: inline-block
    }
}


.tab-content {
    border: 1px solid #C5D0DC;
    padding: 16px 12px;
    position: relative
}

.tab-content.no-padding {
    padding: 0
}

.tab-content.no-border {
    border: none;
    padding: 12px
}

.tab-content.padding-32 {
    padding: 32px 24px
}

.tab-content.no-border.padding-32 {
    padding: 32px
}

.tab-content.padding-30 {
    padding: 30px 23px
}

.tab-content.no-border.padding-30 {
    padding: 30px
}

.tab-content.padding-28 {
    padding: 28px 21px
}

.tab-content.no-border.padding-28 {
    padding: 28px
}

.tab-content.padding-26 {
    padding: 26px 20px
}

.tab-content.no-border.padding-26 {
    padding: 26px
}

.tab-content.padding-24 {
    padding: 24px 18px
}

.tab-content.no-border.padding-24 {
    padding: 24px
}

.tab-content.padding-22 {
    padding: 22px 17px
}

.tab-content.no-border.padding-22 {
    padding: 22px
}

.tab-content.padding-20 {
    padding: 20px 15px
}

.tab-content.no-border.padding-20 {
    padding: 20px
}

.tab-content.padding-18 {
    padding: 18px 14px
}

.tab-content.no-border.padding-18 {
    padding: 18px
}

.tab-content.padding-16 {
    padding: 16px 12px
}

.tab-content.no-border.padding-16 {
    padding: 16px
}

.tab-content.padding-14 {
    padding: 14px 11px
}

.tab-content.no-border.padding-14 {
    padding: 14px
}

.tab-content.padding-12 {
    padding: 12px 9px
}

.tab-content.no-border.padding-12 {
    padding: 12px
}

.tab-content.padding-10 {
    padding: 10px 8px
}

.tab-content.no-border.padding-10 {
    padding: 10px
}

.tab-content.padding-8 {
    padding: 8px 6px
}

.tab-content.no-border.padding-8 {
    padding: 8px
}

.tab-content.padding-6 {
    padding: 6px 5px
}

.tab-content.no-border.padding-6 {
    padding: 6px
}

.tab-content.padding-4 {
    padding: 4px 3px
}

.tab-content.no-border.padding-4 {
    padding: 4px
}

.tab-content.no-border.padding-2,
.tab-content.padding-2 {
    padding: 2px
}

.tab-content.no-border.padding-0,
.tab-content.padding-0 {
    padding: 0
}


.nav.nav-tabs.padding-28 {
    padding-left: 28px
}

.tabs-left>.nav.nav-tabs.padding-28,
.tabs-right>.nav.nav-tabs.padding-28 {
    padding-left: 0;
    padding-top: 28px
}

.nav.nav-tabs.padding-26 {
    padding-left: 26px
}

.tabs-left>.nav.nav-tabs.padding-26,
.tabs-right>.nav.nav-tabs.padding-26 {
    padding-left: 0;
    padding-top: 26px
}

.nav.nav-tabs.padding-24 {
    padding-left: 24px
}

.tabs-left>.nav.nav-tabs.padding-24,
.tabs-right>.nav.nav-tabs.padding-24 {
    padding-left: 0;
    padding-top: 24px
}

.nav.nav-tabs.padding-22 {
    padding-left: 22px
}

.tabs-left>.nav.nav-tabs.padding-22,
.tabs-right>.nav.nav-tabs.padding-22 {
    padding-left: 0;
    padding-top: 22px
}

.nav.nav-tabs.padding-20 {
    padding-left: 20px
}

.tabs-left>.nav.nav-tabs.padding-20,
.tabs-right>.nav.nav-tabs.padding-20 {
    padding-left: 0;
    padding-top: 20px
}

.nav.nav-tabs.padding-18 {
    padding-left: 18px
}

.tabs-left>.nav.nav-tabs.padding-18,
.tabs-right>.nav.nav-tabs.padding-18 {
    padding-left: 0;
    padding-top: 18px
}

.nav.nav-tabs.padding-16 {
    padding-left: 16px
}

.tabs-left>.nav.nav-tabs.padding-16,
.tabs-right>.nav.nav-tabs.padding-16 {
    padding-left: 0;
    padding-top: 16px
}

.nav.nav-tabs.padding-14 {
    padding-left: 14px
}

.tabs-left>.nav.nav-tabs.padding-14,
.tabs-right>.nav.nav-tabs.padding-14 {
    padding-left: 0;
    padding-top: 14px
}

.nav.nav-tabs.padding-12 {
    padding-left: 12px
}

.tabs-left>.nav.nav-tabs.padding-12,
.tabs-right>.nav.nav-tabs.padding-12 {
    padding-left: 0;
    padding-top: 12px
}

.nav.nav-tabs.padding-10 {
    padding-left: 10px
}

.tabs-left>.nav.nav-tabs.padding-10,
.tabs-right>.nav.nav-tabs.padding-10 {
    padding-left: 0;
    padding-top: 10px
}

.nav.nav-tabs.padding-8 {
    padding-left: 8px
}

.tabs-left>.nav.nav-tabs.padding-8,
.tabs-right>.nav.nav-tabs.padding-8 {
    padding-left: 0;
    padding-top: 8px
}

.nav.nav-tabs.padding-6 {
    padding-left: 6px
}

.tabs-left>.nav.nav-tabs.padding-6,
.tabs-right>.nav.nav-tabs.padding-6 {
    padding-left: 0;
    padding-top: 6px
}

.nav.nav-tabs.padding-4 {
    padding-left: 4px
}

.tabs-left>.nav.nav-tabs.padding-4,
.tabs-right>.nav.nav-tabs.padding-4 {
    padding-left: 0;
    padding-top: 4px
}

.nav.nav-tabs.padding-2 {
    padding-left: 2px
}

.tabs-left>.nav.nav-tabs.padding-2,
.tabs-right>.nav.nav-tabs.padding-2 {
    padding-left: 0;
    padding-top: 2px
}

.nav-tabs {
    border-color: #C5D0DC;
    margin-bottom: 0!important;
    position: relative;
    top: 1px
}

.nav-tabs>li>a {
    padding: 7px 12px 8px
}

.nav-tabs>li>a,
.nav-tabs>li>a:focus {
    border-radius: 0!important;
    border-color: #C5D0DC;
    background-color: #F9F9F9;
    color: #999;
    margin-right: -1px;
    line-height: 18px;
    position: relative
}

.nav-tabs>li>a:hover {
    background-color: #FFF;
    color: #4C8FBD;
    border-color: #C5D0DC
}

.nav-tabs>li>a:active,
.nav-tabs>li>a:focus {
    outline: 0!important
}

.nav-tabs>li.active>a,
.nav-tabs>li.active>a:focus,
.nav-tabs>li.active>a:hover {
    color: #576373;
    border-color: #C5D0DC #C5D0DC transparent;
    border-top: 2px solid #4C8FBD;
    background-color: #FFF;
    z-index: 1;
    line-height: 18px;
    margin-top: -1px;
    box-shadow: 0 -2px 3px 0 rgba(0, 0, 0, .15)
}

.tabs-below>.nav-tabs {
    top: auto;
    margin-bottom: 0;
    margin-top: -1px;
    border-color: #C5D0DC;
    border-bottom-width: 0
}

.tabs-below>.nav-tabs>li>a,
.tabs-below>.nav-tabs>li>a:focus,
.tabs-below>.nav-tabs>li>a:hover {
    border-color: #C5D0DC
}

.tabs-below>.nav-tabs>li.active>a,
.tabs-below>.nav-tabs>li.active>a:focus,
.tabs-below>.nav-tabs>li.active>a:hover {
    border-color: transparent #C5D0DC #C5D0DC;
    border-top-width: 1px;
    border-bottom: 2px solid #4C8FBD;
    margin-top: 0;
    box-shadow: 0 2px 3px 0 rgba(0, 0, 0, .15)
}

.tabs-left>.nav-tabs>li>a,
.tabs-right>.nav-tabs>li>a {
    min-width: 60px
}

.tabs-left>.nav-tabs {
    top: auto;
    margin-bottom: 0;
    border-color: #C5D0DC;
    float: left
}

.tabs-left>.nav-tabs>li {
    float: none!important
}

.tabs-left>.nav-tabs>li>a,
.tabs-left>.nav-tabs>li>a:focus,
.tabs-left>.nav-tabs>li>a:hover {
    border-color: #C5D0DC;
    margin: 0 -1px 0 0
}

.tabs-left>.nav-tabs>li.active>a,
.tabs-left>.nav-tabs>li.active>a:focus,
.tabs-left>.nav-tabs>li.active>a:hover {
    border-color: #C5D0DC transparent #C5D0DC #C5D0DC;
    border-top-width: 1px;
    border-left: 2px solid #4C8FBD;
    margin: 0 -1px;
    -webkit-box-shadow: -2px 0 3px 0 rgba(0, 0, 0, .15)!important;
    box-shadow: -2px 0 3px 0 rgba(0, 0, 0, .15)!important
}

.tabs-right>.nav-tabs {
    top: auto;
    margin-bottom: 0;
    border-color: #C5D0DC;
    float: right
}

.tabs-right>.nav-tabs>li {
    float: none!important
}

.tabs-right>.nav-tabs>li>a,
.tabs-right>.nav-tabs>li>a:focus,
.tabs-right>.nav-tabs>li>a:hover {
    border-color: #C5D0DC;
    margin: 0 -1px
}

.tabs-right>.nav-tabs>li.active>a,
.tabs-right>.nav-tabs>li.active>a:focus,
.tabs-right>.nav-tabs>li.active>a:hover {
    border-color: #C5D0DC #C5D0DC #C5D0DC transparent;
    border-top-width: 1px;
    border-right: 2px solid #4C8FBD;
    margin: 0 -2px 0 -1px;
    -webkit-box-shadow: 2px 0 3px 0 rgba(0, 0, 0, .15);
    box-shadow: 2px 0 3px 0 rgba(0, 0, 0, .15)
}

.nav-tabs>li>a .badge {
    padding: 1px 5px;
    line-height: 15px;
    opacity: .75;
    vertical-align: initial
}

.nav-tabs>li>a .ace-icon {
    opacity: .75
}

.nav-tabs>li.active>a .ace-icon,
.nav-tabs>li.active>a .badge {
    opacity: 1
}

.nav-tabs li .ace-icon {
    width: 1.25em;
    display: inline-block;
    text-align: center
}

.nav-tabs>li.open .dropdown-toggle {
    background-color: #4F99C6;
    border-color: #4F99C6;
    color: #FFF
}

.nav-tabs>li.open .dropdown-toggle>.ace-icon {
    color: #FFF!important
}

.tabs-left .tab-content,
.tabs-right .tab-content {
    overflow: auto
}

.dark {
    color: #333!important
}

.white {
    color: #FFF!important
}

.red {
    color: #DD5A43!important
}

.red2 {
    color: #E08374!important
}

.light-red {
    color: #F77!important
}

.blue {
    color: #478FCA!important
}

.light-blue {
    color: #93CBF9!important
}

.green {
    color: #69AA46!important
}

.light-green {
    color: #B0D877!important
}

.orange {
    color: #FF892A!important
}

.orange2 {
    color: #FEB902!important
}

.light-orange {
    color: #FCAC6F!important
}

.purple {
    color: #A069C3!important
}

.pink {
    color: #C6699F!important
}

.pink2 {
    color: #D6487E!important
}

.brown {
    color: brown!important
}

.grey {
    color: #777!important
}
    </style>
