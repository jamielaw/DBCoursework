<?php
    $title = "Bookface Social Network";
    $description = "A far superior social network";
    include("../inc/header.php");
    include("../inc/nav-trn.php");
    //require '../database.php';

    $createdBy = null;
    $photoCollectionId = null;
    if (!empty($_GET['createdBy'])) {
        $createdBy = $_REQUEST['createdBy'];
    }
    if (!empty($_GET['photoCollectionId'])) {
        $photoCollectionId = $_REQUEST['photoCollectionId'];
    }


    if (null==$createdBy || null==$photoCollectionId) {
        header("Location: index.php");
    } else {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM photos JOIN photocollection ON photocollection.photoCollectionId = photos.photoCollectionId WHERE photocollection.createdBy = ? AND photocollection.photoCollectionId = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($createdBy, $photoCollectionId));
        //$data = $q->fetch(PDO::FETCH_ASSOC);

        $sql2 = "SELECT * FROM photocollection WHERE photoCollectionId = ?";
        $q2 = $pdo->prepare($sql2);
        $q2->execute(array($photoCollectionId));
        Database::disconnect();
    }

    function getFriends($email)
    {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT DISTINCT email, firstName, lastName, profileImage FROM users JOIN friendships ON users.email = friendships.emailFrom OR users.email=friendships.emailTo WHERE (friendships.emailFrom=? OR friendships.emailTo=?) AND users.email!=? AND status=\'accepted\';';
        $q = $pdo->prepare($sql);
        $q->execute(array($email,$email,$email));
        return $q;
    }

    function getCircles($email)
    {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT c.circleOfFriendsName, c.circleFriendsId FROM circleoffriends c INNER JOIN usercirclerelationships u ON c.circleFriendsId = u.circleFriendsId WHERE u.email=?';
        $q = $pdo->prepare($sql);
        $q->execute(array($email));
        return $q;
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

    function getFriendsOfFriends($email)
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
        return $q;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
</head>

<body>
	<div class="jumbotron">
    	<div class="row">
    		<?php
                $row = $q2->fetch(PDO::FETCH_ASSOC)
                ?>
	    	<h3 class="col-md-2 col-md-offset-5"><?php echo $row['title'];?></h3>
	    </div>
		</div>

 <div>
	<form class="col-md-2 col-md-offset-5" action="uploadphoto.php?id=<?php echo $photoCollectionId; ?>" method="post" enctype="multipart/form-data">
		<p class=""> Select image to upload: </p>
		<input class="" type="file" name="fileToUpload" id="fileToUpload"> <br>
		<input class= "btn btn-primary" type="submit" value="Upload Image" name="submit">
	</form>
    <a data-title="<?php echo $row['title']?>" data-description="" data-id="" class="open-update_dialog btn btn-success" data-toggle="modal" href="#update_dialog">Access Rights</a>
</div>

 <div class="row"><br></div>
 <div class="row"><br></div>

	<div class="row">
    <div class="container">
	    	<div class="form-horizontal" >
			  <div class="control-group">
				  	<div class="controls"></div>
					<div class="controls">
						   <label class="checkbox">
						    	<?php
                                    while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
                                        ?>
						     	<a href="readphoto.php?photoId=<?php echo $row['photoId']?>&imageReference=<?php echo $row['imageReference']?>&photoCollectionId=<?php echo $row['photoCollectionId']; ?>" id="bottle" >
									<img src="<?php echo $row['imageReference']; ?>"  height="200" ></td></a>
						     	<?php

                                    }
                                ?>
						    </label>
					</div>
					</div>
			  </div>
		   </div>
		</div>
	</div>

        <!-- modal to update access rights -->
        <!-- the div that represents the modal dialog -->
        <div class="modal fade" id="update_dialog" role="dialog">
            <div class="modal-dialog">
               <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Update Access Rights</h4>
                    </div>
                    <div class="modal-body">
                    <p> </p>
                        <form id="update_form2" action="" method="POST">
                            <div class="tabbable">
                                <ul class="nav nav-tabs padding-18">
                                    <li class="active">
                                        <a data-toggle="tab" href="#friends2">
                                            <i class="green ace-icon fa fa-user-plus bigger-120"></i>
                                            Friends
                                        </a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#circles">
                                            <i class="blue ace-icon fa fa-users bigger-120"></i>
                                            Circles
                                        </a>
                                    </li>

                                    <li>
                                        <a data-toggle="tab" href="#friendsoffriends">
                                            <i class="pink ace-icon fa fa-user bigger-120"></i>
                                            Friends of Friends
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content no-border padding-24">
                                    <div id="friends2" class="tab-pane in active">
                                        <ul id="check-list-box" class="list-group checked-list-box">
                                         <?php
                                            foreach (getFriends($createdBy) as $row) {
                                                $flag = checkAccessRights($row['email'],1)['value'];
                                                echo '<li class="list-group-item" data-checked='.$flag.'>'.$row['firstName'].' '.$row['lastName'].'</li>';
                                            }
                                        ?>
                                        </ul>  
                                    </div>
                                    <div id="circles" class="tab-pane">
                                        <ul id="check-list-box" class="list-group checked-list-box">
                                         <?php
                                            foreach (getCircles($createdBy) as $row) {
                                                echo '<li class="list-group-item">'.$row['circleOfFriendsName'].'</li>';
                                            }
                                        ?>
                                        </ul>  
                                    </div>
                                    <div id="friendsoffriends" class="tab-pane">
                                        <ul id="check-list-box" class="list-group checked-list-box">
                                         <?php
                                            foreach (getFriendsOfFriends($createdBy) as $row) {
                                                echo '<li class="list-group-item">'.$row['firstName'].' '.$row['lastName'].'</li>';
                                            }
                                        ?>
                                        </ul>                                     
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" id="submitForm2" class="btn btn-success">Update</button>
                    </div>
                </div>
            </div>
        </div>


    </div> <!-- /container -->
  </body>
    <?php include '../inc/footer.php'; ?>
</html>

<script>
/* access right modal*/
$(function () {
    $('.list-group.checked-list-box .list-group-item').each(function () {
        
        // Settings
        var $widget = $(this),
            $checkbox = $('<input type="checkbox" class="hidden" />'),
            color = ($widget.data('color') ? $widget.data('color') : "primary"),
            style = ($widget.data('style') == "button" ? "btn-" : "list-group-item-"),
            settings = {
                on: {
                    icon: 'glyphicon glyphicon-check'
                },
                off: {
                    icon: 'glyphicon glyphicon-unchecked'
                }
            };
            
        $widget.css('cursor', 'pointer')
        $widget.append($checkbox);

        // Event Handlers
        $widget.on('click', function () {
            $checkbox.prop('checked', !$checkbox.is(':checked'));
            $checkbox.triggerHandler('change');
            updateDisplay();
        });
        $checkbox.on('change', function () {
            updateDisplay();
        });
          

        // Actions
        function updateDisplay() {
            var isChecked = $checkbox.is(':checked');

            // Set the button's state
            $widget.data('state', (isChecked) ? "on" : "off");

            // Set the button's icon
            $widget.find('.state-icon')
                .removeClass()
                .addClass('state-icon ' + settings[$widget.data('state')].icon);

            // Update the button's color
            if (isChecked) {
                $widget.addClass(style + color + ' active');
            } else {
                $widget.removeClass(style + color + ' active');
            }
        }

        // Initialization
        function init() {
            
            if ($widget.data('checked') == true) {
                $checkbox.prop('checked', !$checkbox.is(':checked'));
            }
            
            updateDisplay();

            // Inject the icon if applicable
            if ($widget.find('.state-icon').length == 0) {
                $widget.prepend('<span class="state-icon ' + settings[$widget.data('state')].icon + '"></span>');
            }
        }
        init();
    });
    
    $('#get-checked-data').on('click', function(event) {
        event.preventDefault(); 
        var checkedItems = {}, counter = 0;
        $("#check-list-box li.active").each(function(idx, li) {
            checkedItems[counter] = $(li).text();
            counter++;
        });
        $('#display-json').html(JSON.stringify(checkedItems, null, '\t'));
    });
});

// Update access rights
var clicked = null;
$(document).on("click", ".open-update_dialog", function () {
     albumName = $(this).data('title');
     $(".modal-body #albumName").val(albumName);
     albumDescription = $(this).data('description');
     $(".modal-body #albumDescription").val(albumDescription);
     clicked = $(this).data('id');

     $( "p" ).text( "Collection Title: " + albumName );
   
});
</script>

<style type="text/css">

/* CSS REQUIRED */
.state-icon {
    left: -5px;
}
.list-group-item-primary {
    color: rgb(255, 255, 255);
    background-color: rgb(66, 139, 202);
}

/* DEMO ONLY - REMOVES UNWANTED MARGIN */
.well .list-group {
    margin-bottom: 0px;
}

.jumbotron {
	text-align: center;
    padding: 0.5em 0.6em;
    h1 {
        font-size: 2em;
    }
    p {
        font-size: 1.2em;
        .btn {
            padding: 0.5em;
        }
    }
}
.padding {
	 padding-left: 80px;
	 padding-top: 10px;
}
</style>
