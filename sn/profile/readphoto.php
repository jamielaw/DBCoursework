<?php

    $title = "Bookface Social Network";
    $description = "A far superior social network";
    include("../inc/header.php");
    include("../inc/nav-trn.php");
      //require '../database.php';

    $email="charles@ucl.ac.uk";
    $photoId = null;
    $photoCollectionId = null;
    $imageReference = null;
    $comment = null;
    $ok=1;
    if (!empty($_GET['photoId'])) {
        $photoId = $_REQUEST['photoId'];
    }
    if ($ok==1 && !empty($_GET['comment'])) {
        $comment = $_REQUEST['comment'];
        $ok==0;
        sendToDatabase($photoId, "charles@ucl.ac.uk", $comment);
        $comment = null;
    }
    $imageReference = $_GET['imageReference'];
    $photoCollectionId = $_GET['photoCollectionId'];
    if (null==$photoId) {
        header("Location: index.php");
    } else {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM comments WHERE photoId = ? ORDER BY dateCreated DESC";
        $q = $pdo->prepare($sql);
        $q->execute(array($photoId));
    }
    function getUserDetails($email)
    {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql2 = "SELECT * FROM users WHERE email = ?";
        $q2 = $pdo->prepare($sql2);
        $q2->execute(array($email));
        $user = $q2->fetch(PDO::FETCH_ASSOC);
        return $user;
    }
    function returnNumberOfComments($photoId)
    {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql3 = "SELECT COUNT(*) FROM comments WHERE photoId = ? ";
        $q3 = $pdo->prepare($sql3);
        $q3->execute(array($photoId));
        $result = $q3->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    function sendToDatabase($photoId, $email, $comment)
    {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql4 = "INSERT INTO comments (photoId,email,commentText) VALUES (?,?,?)";
        $q4 = $pdo->prepare($sql4);
        $q4->execute(array($photoId,$email,$comment));
        $comment=null;
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

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>


<body>
<div class="container-fluid">
  <div class="row content">
    <div id="imgtag" class="paddingImage col-sm-3 sidenav">
    	<img id="<?php echo $photoId?>" src="<?php echo $imageReference?>" width="300">
    	<div id="tagbox"></div>

		<div id="taglist"> <ol></ol> </div>
	</div>

    <div class="col-sm-9">
      <br>
      <button class="btn btn-info">Access Rights</button>
      <button data-title = <?php echo $photoId?> class="open-delete_dialog btn btn-danger" data-toggle="modal" href="#delete_dialog">Delete Photo</button>
      <h4>Leave a Comment:</h4>
      <form action="readphoto.php">
        <div class="form-group">
          <input type="hidden" name="photoId" value=<?php echo $photoId?>>
          <input type="hidden" name="imageReference" value=<?php echo $imageReference?>>
          <input type="hidden" name="photoCollectionId" value=<?php echo $photoCollectionId?>>

          <textarea name="comment" class="form-control" rows="3" required></textarea>
        </div>
        <button class="btn btn-success">Submit</button>
      </form>
      <br><br>

		<div class="modal fade" id="delete_dialog" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
				    <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal">&times;</button>
				            <h4 class="modal-title">Delete Photo</h4>
					</div>
					<div class="modal-body">
						<form id="delete_form" action="deletephoto.php" method="POST">
						    Are you sure you want to delete the photo?
						</form>
					 </div>
					 <div class="modal-footer">
					    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					    <button type="button" id="submitForm3" class="btn btn-danger">Delete</button>
					 </div>
				 </div>
			 </div>
		</div>


      <p><span class="badge"><?php echo returnNumberOfComments($photoId)['COUNT(*)'];?> </span> Comments:</p><br>

      <div class="row">
       <?php
           while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
               ?>
        <div class="col-sm-2 text-center">
         	<?php $email = $row['email']; ?>
			     <img src="<?php echo getUserDetails($email)['profileImage']; ?>" class="img-circle" height="65" width="65" alt="Avatar">
        </div>
        <div class="col-sm-10">
          <h4 href='#'><b><?php echo getUserDetails($email)['firstName']; ?> <?php echo getUserDetails($email)['lastName']; ?></b>
          	<small><?php
              date_default_timezone_set('Europe/London');
               $date1 = date('m/d/Y h:i:s a', time());
               $date2=$row['dateCreated'];
               echo date_difference($date1, $date2); ?>
      			ago</small>

          <small> <strong data-id= "<?php echo $row['commentId']; ?>" data-title= "<?php echo $row['commentText']; ?>" class="hover open-delete_dialog2 text-danger text-right" data-toggle="modal" href="#delete_dialog2">Delete</strong></small>

          </h4>
          <p><?php echo $row['commentText']; ?></p>
        <br>
        </div>
        <?php

           }
        ?>
     </div>
    </div>
    </div>
  </div>
</div>
<!-- modal to delete collection -->
<!-- the div that represents the modal dialog -->
<div class="modal fade" id="delete_dialog2" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form id="delete_form2" action="deletecomment.php" method="POST">
          Are you sure you want to delete the comment  <input type="text" name="deleteComment" id="deleteComment"> ?
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="submitForm4" class="btn btn-danger">Delete</button>
      </div>
    </div>
   </div>
  </div>
</div>

<footer class="container-fluid">
  <p>Footer Text</p>
</footer>
</body>
</html>


<script>

// Delete Photo
var deletephotoName = null;
$(document).on("click", ".open-delete_dialog", function () {
     deletephotoName = $(this).data('title');
     console.log("id ", deletephotoName);
     $(".modal-body #deletephotoName").val(deletephotoName);
     albumId = $(this).data('id');
    // Delete Collection Button
    $("#delete_form").on("submit", function(e) {
        var postData3 =  $(this).serializeArray();
        postData3.push({name: "deletephotoName", value: deletephotoName});
        var formURL = $(this).attr("action");
        $.ajax({
            url: formURL,
            type: "POST",
            data: postData3,
            success: function(data, textStatus, jqXHR) {
                $('#delete_dialog .modal-header .modal-title').html("Result");
                $('#delete_dialog .modal-body').html(data);
                $("#submitForm3").remove();
                location.href = "readphotocollection.php?createdBy=<?php echo $email?>&photoCollectionId=<?php echo $photoCollectionId ?>;";
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

// Delete Comment
var deleteComment = null;

$(document).on("click", ".open-delete_dialog2", function () {
     deleteComment = $(this).data('title');
     console.log("comment: ", deleteComment);
     $(".modal-body #deleteComment").val(deleteComment);
     commentId = $(this).data('id');

    // Delete Collection Button
    $("#delete_form2").on("submit", function(e) {
        var postData3 =  $(this).serializeArray();
        postData3.push({name: "commentId", value: commentId});
        var formURL = $(this).attr("action");
        $.ajax({
            url: formURL,
            type: "POST",
            data: postData3,
            success: function(data, textStatus, jqXHR) {
                $('#delete_dialog2 .modal-header .modal-title').html("Result");
                $('#delete_dialog2 .modal-body').html(data);
                $("#submitForm4").remove();
            },
            error: function(jqXHR, status, error) {
                console.log(status + ": " + error);
            }
        });
        e.preventDefault();
    });

    $("#submitForm4").on('click', function() {
        $("#delete_form2").submit();
    });
});


 $(document).ready(function(){
    var counter = 0;
    var mouseX = 0;
    var mouseY = 0;

    $("#imgtag img").click(function(e) { // make sure the image is click
      var imgtag = $(this).parent(); // get the div to append the tagging list
      mouseX = ( e.pageX - $(imgtag).offset().left ) - 50; // x and y axis
      mouseY = ( e.pageY - $(imgtag).offset().top ) - 50;
      $( '#tagit' ).remove( ); // remove any tagit div first
      $( imgtag ).append( '<div id="tagit"><div class="box"></div><div class="name"><div class="text">Type any name or tag</div><input type="text" name="txtname" id="tagname" /><input type="button" name="btnsave" value="Save" id="btnsave" /><input type="button" name="btncancel" value="Cancel" id="btncancel" /></div></div>' );
      $( '#tagit' ).css({ top:mouseY, left:mouseX });

      $('#tagname').focus();
    });

	// Save button click - save tags
    $( document ).on( 'click',  '#tagit #btnsave', function(){
      	name = $('#tagname').val();
		var img = $('#imgtag').find( 'img' );
		var id = $( img ).attr( 'id' );
      $.ajax({
        type: "POST",
        url: "savetag.php",
        data: "pic_id=" + id + "&name=" + name + "&pic_x=" + mouseX + "&pic_y=" + mouseY + "&type=insert",
        cache: true,
        success: function(data){
          console.log(">>>>> ", id, name, mouseX, mouseY);
          viewtag( id );
          $('#tagit').fadeOut();
        }
      });

    });

	// Cancel the tag box.
    $( document ).on( 'click', '#tagit #btncancel', function() {
      $('#tagit').fadeOut();
    });

	// mouseover the taglist
	$('#taglist').on( 'mouseover', 'li', function( ) {
      id = $(this).attr("id");
      $('#view_' + id).css({ opacity: 1.0 });
    }).on( 'mouseout', 'li', function( ) {
        $('#view_' + id).css({ opacity: 0.0 });
    });

	// mouseover the tagboxes that is already there but opacity is 0.
	$( '#tagbox' ).on( 'mouseover', '.tagview', function( ) {
		var pos = $( this ).position();
		$(this).css({ opacity: 1.0 }); // div appears when opacity is set to 1.
	}).on( 'mouseout', '.tagview', function( ) {
		$(this).css({ opacity: 0.0 }); // hide the div by setting opacity to 0.
	});

	// Remove tags.
    $( '#taglist' ).on('click', '.remove', function() {
      console.log("<<<<", id);
      id = $(this).parent().attr("id");
      // Remove the tag
	  $.ajax({
        type: "POST",
        url: "savetag.php",
        data: "tag_id=" + id + "&type=remove",
        success: function(data) {
			var img = $('#imgtag').find( 'img' );
			var id = $( img ).attr( 'id' );
			//get tags if present
			viewtag( id );
        }
      });
    });

	// load the tags for the image when page loads.
    var img = $('#imgtag').find( 'img' );
	var id = $( img ).attr( 'id' );

	viewtag( id ); // view all tags available on page load

    function viewtag( pic_id )
    {
       console.log("I am in view tag!", pic_id);
      // get the tag list with action remove and tag boxes and place it on the image.
	  $.post( "taglist.php" ,  "pic_id=" + pic_id, function( data ) {
	  	$('#taglist ol').html(data.lists);
		 $('#tagbox').html(data.boxes);
	  }, "json");

    }


  });
</script>

  <style>
  .hover {
    color: red;
    text-decoration: underline;
    cursor: pointer;
  }
	#container
	{
		display: block;
		margin: 0 auto;
	}
	#imgtag
	{
		position: relative;
		cursor: crosshair;
	}

	.tagview
	{
		border: 1px solid #F10303;
		width: 100px;
		height: 100px;
		position: absolute;
	/*display:none;*/
		opacity: 0;
		color: #FFFFFF;
		text-align: center;
	}
	.square
	{
		display: block;
		height: 79px;
	}
	.person
	{
		background: #282828;
		border-top: 1px solid #F10303;
	}

	#tagit
	{
		position: absolute;
		top: 0;
		left: 0;
		width: 240px;
		border: 1px solid #D7C7C7;
	}
	#tagit .box
	{
		border: 1px solid #F10303;
		width: 100px;
		height: 100px;
		float: left;
	}
	#tagit .name
	{
		float: left;
		background-color: #FFF;
		width: 127px;
		height: 92px;
		padding: 5px;
		font-size: 10pt;
	}
	#tagit DIV.text
	{
		margin-bottom: 5px;
	}
	#tagit INPUT[type=text]
	{
		margin-bottom: 5px;
	}
	#tagit #tagname
	{
		width: 110px;
	}
	#taglist
	{
		width: 300px;
		min-height: 200px;
		height: 200px;
		float: left;
		color: #000;
	}
	#taglist OL
	{
		cursor: pointer;
	}
	#taglist OL A
	{
	}
	#taglist OL A:hover
	{
		text-decoration: underline;
	}
	.tagtitle
	{
		font-size: 14px;
		text-align: center;
		width: 100%;
		float: left;
	}

 	.paddingImage {padding-left: 50px; padding-top: 20px}
    /* Set height of the grid so .sidenav can be 100% (adjust if needed) */
    .row.content {height: 1500px}

    /* Set gray background color and 100% height */
    .sidenav {
      background-color: #f1f1f1;
      height: 100%;
    }

    /* Set black background color, white text and some padding */
    footer {
      background-color: #555;
      color: white;
      padding: 15px;
    }

    /* On small screens, set height to 'auto' for sidenav and grid */
    @media screen and (max-width: 767px) {
      .sidenav {
        height: auto;
        padding: 15px;
      }
      .row.content {height: auto;}
    }
  </style>
