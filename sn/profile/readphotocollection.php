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
	<div class="jumbotron">
    	<div class="row">
    		<?php
                $row = $q2->fetch(PDO::FETCH_ASSOC)
                ?>
	    	<h3 class="col-md-2 col-md-offset-5"><?php echo $row['title'];?></h3>
	    </div>
		</div>

 <div>
	<form class="col-md-2 col-md-offset-5" action="uploadphoto.php?photoCollectionId=<?php echo $photoCollectionId; ?>" method="post" enctype="multipart/form-data">
		<p class=""> Select image to upload: </p>
		<input class="" type="file" name="fileToUpload" id="fileToUpload"> <br>
		<input class= "btn btn-primary" type="submit" value="Upload Image" name="submit">
	</form>
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

    </div> <!-- /container -->
  </body>
    <?php include '../inc/footer.php'; ?>
</html>

<style type="text/css">
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
