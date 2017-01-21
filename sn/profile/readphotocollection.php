<?php 
	require '../database.php';
	$createdBy = null;
	$photoCollectionId = null;
	if ( !empty($_GET['createdBy'])) {
		$createdBy = $_REQUEST['createdBy'];
	}
	if ( !empty($_GET['photoCollectionId'])) {
		$photoCollectionId = $_REQUEST['photoCollectionId'];
	}
	
	
	if ( null==$createdBy || null==$photoCollectionId) {
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <script src="../js/min/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">
    
    			<div class="span10 offset1">
    				<div class="row">
    					<?php 
    						$row = $q2->fetch(PDO::FETCH_ASSOC)
    						?> 
		    				<h3 class="text-center"><?php echo $row['title'];?></h3>
		    				<h5 class="text-center"><?php echo $row['description'];?></h5>
		    		</div>
		    		<div class="form-actions">
						<a class="btn btn-info" href="index.php">Back</a>
					</div><br>
	    			<div class="form-horizontal" >
					  <div class="control-group">
					  <div class="controls">
					    </div>
					    <div class="controls">
						    <label class="checkbox">
						     	<?php 
						     	 while ($row = $q->fetch(PDO::FETCH_ASSOC)){
						     	 	?>
						     	 	<a href="readphoto.php?photoId=<?php echo $row['photoId']?>&imageReference=<?php echo $row['imageReference'];?>" id="bottle" >
										<img src="<?php echo $row['imageReference'];?>"  height="200" ></td></a>
						     	 	<?php
						     	 }
						     	?>
						    </label>
					    </div>
					  </div>
					</div>
				</div>
				
    </div> <!-- /container -->
  </body>
</html>