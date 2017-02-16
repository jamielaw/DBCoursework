<?php 
	require '../database.php';
	$email = 0;

	if ( !empty($_GET['email'])) {
		$id = $_REQUEST['email'];
	}
	
	if ( !empty($_POST)) {
		// keep track post values
		$email = $_POST['email'];
		
		// delete data
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "DELETE FROM users  WHERE email = ?";
		$q = $pdo->prepare($sql);
		$response = $q->execute(array($email));
		Database::disconnect();
		header("Location: index.php");
		
	} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="../css/bootstrap.min.css" rel="stylesheet">
    <script src="../js/min/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">
    
    			<div class="span10 offset1">
    				<div class="row">
		    			<h3>Delete a Customer</h3>
		    		</div>
		    		
	    			<form class="form-horizontal" action="delete.php" method="post">
	    			  <input type="hidden" name="email" value="<?php echo $email;?>"/>
					  <p class="alert alert-error">Are you sure you want to delete  ?</p>
					  <div class="form-actions">
						  <button type="submit" class="btn btn-danger">Yes</button>
						  <a class="btn" href="index.php">No</a>
						</div>
					</form>
				</div>
				
    </div> <!-- /container -->
  </body>
    <?php include '../inc/footer.php'; ?>
</html>