<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <script src="../js/min/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>

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
								   	echo '<a class="btn" href="readprofile.php?email='.$row['email'].'">Read</a>';
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
								echo '<a class="btn" href="readphotocollection.php?createdBy='.$row['createdBy'].'&photoCollectionId='.$row['photoCollectionId'].'">Read</a>';
								echo '</td>';
								echo '</tr>';
						  	}
						  	Database::disconnect();
						?>
					</tbody>
				   </table>
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