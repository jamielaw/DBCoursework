<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <script src="../js/min/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>
<!--  Navigation-->
<?php include '../inc/nav-trn.php'; ?>
<body>
    <div class="container">
    		<div class="row" id="friends">
    			<p><img src="../../images/profile/charles@ucl.ac.uk.jpg" class="rounded float-left" height="200">
    			<font size="5"> Charles Babbage </font> </p>
    		</div>

    			<br><br>
    		<ul class="nav nav-tabs">
    			<li class="active">
		        	<a  href="#1" data-toggle="tab">Profile</a>
				</li>
				<li>
					<a href="#2" data-toggle="tab">Friends</a>
				</li>
				<li>
					<a href="#3" data-toggle="tab">Messages</a>
				</li>
				<li>
					<a href="#4" data-toggle="tab">Photo Collections</a>
				</li>
		  	</ul>

			<div class="tab-content ">
				<div class="tab-pane active" id="1">
		          <h3>Standard tab panel created on bootstrap using nav-tabs</h3>
				</div>
				<div class="tab-pane" id="2">
		          	<table class="table table-striped table-bordered">
		            	<thead>
		                	<tr>
		                  		<th>First Name</th>
		                  		<th>Last Name</th>
		                  		<th>Action</th>
		                	</tr>
		              	</thead>
		              	<tbody>
		              		<?php 
					   			include '..\database.php';
					   			$pdo = Database::connect();
					   			// !!! HARDCODED STUFF -  TO BE CHANGED AFTER LOGIN IS IMPLEMENTED
					   			$sql = 'SELECT DISTINCT email, firstName, lastName FROM users JOIN friendships ON users.email = friendships.emailFrom OR users.email=friendships.emailTo WHERE (friendships.emailFrom=\'charles@ucl.ac.uk\' OR friendships.emailTo=\'charles@ucl.ac.uk\') AND users.email!=\'charles@ucl.ac.uk\' AND status=\'accepted\';';
	 				   			foreach ($pdo->query($sql) as $row) {
							   		echo '<tr>';
								   	echo '<td>'. $row['firstName'] . '</td>';
								   	echo '<td>'. $row['lastName'] . '</td>';
								   	echo '<td width=250>';
								   	echo '<a class="btn btn-info" href="readprofile.php?email='.$row['email'].'">Read</a>';
								   	echo '&nbsp;';
								   	echo '<a class="btn btn-success" href="updateprofile.php?email='.$row['email'].'">Update</a>';
								   	echo '&nbsp;';
								   	echo '<a class="btn btn-danger" href="deleteprofile.php?email='.$row['email'].'">Delete</a>';
								   	echo '</td>';
								   	echo '</tr>';
					  			}
					   			//Database::disconnect();
					  		?>
				      	</tbody>
	            	</table>
				</div>
				<div class="tab-pane" id="3">
		          <h3>Messages</h3>
				</div>
				<div class="tab-pane" id="4">
		          <h3>Photo Collections</h3>
		          <table class="table table-striped table-bordered">
		         	<thead>
		            	<tr>
		                	<th>Album</th>
		                  	<th>Action</th>
		                </tr>
		            </thead>
			        <tbody>
			          	<?php 
			          		// !!! HARDCODED STUFF - TO BE CHANGED AFTER LOGIN IS IMPLEMENTED
						   	$sql = 'SELECT * FROM photocollection WHERE createdBy = "charles@ucl.ac.uk" ORDER BY dateCreated'; 
		 				   	foreach ($pdo->query($sql) as $row) {
								echo '<td>'. $row['title'] . '</td>';
								echo '<td width=350>';
								echo '<a class="btn btn-info" href="readphotocollection.php?createdBy='.$row['createdBy'].'&photoCollectionId='.$row['photoCollectionId'].'">Read</a>';
								echo '&nbsp;';
								echo '<a data-title="'.$row['title'].'" data-description="'.$row['description'].'" data-id="'.$row['photoCollectionId'].'" class="open-update_dialog btn btn-success" data-toggle="modal" href="#update_dialog">Update</a>';
								echo '&nbsp;';
								echo '<a data-title="'.$row['title'].'" data-id="'.$row['photoCollectionId'].'" class="open-delete_dialog btn btn-danger" data-toggle="modal" href="#delete_dialog">Delete</a>';
								echo '</td>';
								echo '</tr>';
						  	}
						  	//Database::disconnect();
						?>
					</tbody>
				   </table>
				
				    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#collection_dialog">Create Collection</button>
					

					<!-- modal to create new collection -->
					<!-- the div that represents the modal dialog -->
					<div class="modal fade" id="collection_dialog" role="dialog">
					    <div class="modal-dialog">
					        <div class="modal-content">
					            <div class="modal-header">
					                <button type="button" class="close" data-dismiss="modal">&times;</button>
					                <h4 class="modal-title">Create New Collection</h4>
					            </div>
					                <div class="modal-body">
					                    <form id="collection_form" action="createcollection.php" method="POST">
					                        <input type="text" name="albumName" placeholder="Enter Album Name"><br/><br/>
					                        <input type="text" name="descriptionName" placeholder="Enter Album Description"><br/>
					                    </form>
					                </div>
					                <div class="modal-footer">
					                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					                    <button type="button" id="submitForm" class="btn btn-success	">Create</button>
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

		          <form action="uploadphoto.php" method="post" enctype="multipart/form-data">
			    	Select image to upload:
			    	<input type="file" name="fileToUpload" id="fileToUpload">
			    	<input type="submit" value="Upload Image" name="submit">
				  </form>
				</div>
		  	</div>
    </div> <!-- /container -->
  </body>
</html>


<script>
/* must apply only after HTML has loaded */
$(document).ready(function () {

	// Create Collection Button
    $("#collection_form").on("submit", function(e) {
        var postData = $(this).serializeArray();
        postData.push({name: "email", value: "charles@ucl.ac.uk"});
        var formURL = $(this).attr("action");
        $.ajax({
            url: formURL,
            type: "POST",
            data: postData,
            success: function(data, textStatus, jqXHR) {
                $('#collection_dialog .modal-header .modal-title').html("Result");
                $('#collection_dialog .modal-body').html(data);
                $("#submitForm").remove();
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

</script>
