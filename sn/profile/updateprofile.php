<?php

	require '../database.php';

	$email = null;
	if ( !empty($_GET['email'])) {
		$email = $_REQUEST['email'];
	}

	if ( null==$email ) {
		header("Location: index.php");
	}

	if ( !empty($_POST)) {
		// keep track validation errors
		$firstNameError = null;
		$emailError = null;
		$lastNameError = null;

		// keep track post values
		$firstName = $_POST['firstName'];
		$email = $_POST['email'];
		$lastName = $_POST['lastName'];

		// validate input
		$valid = true;
		if (empty($firstName)) {
			$firstNameError = 'Please enter Name';
			$valid = false;
		}

		if (empty($email)) {
			$emailError = 'Please enter Email Address';
			$valid = false;
		} else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
			$emailError = 'Please enter a valid Email Address';
			$valid = false;
		}

		if (empty($lastName)) {
			$lastNameError = 'Please enter Mobile Number';
			$valid = false;
		}

		// update data
		if ($valid) {
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "UPDATE users  set firstName = ?, lastName = ?, email = ? WHERE email = ?";
			$q = $pdo->prepare($sql);
			$q->execute(array($firstName,$lastName,$email,$email));
			Database::disconnect();
			header("Location: index.php");
		}
	} else {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM users where email = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($email));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		$firstName = $data['firstName'];
		$lastName = $data['lastName'];
		$email = $data['email'];
		Database::disconnect();
	}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="../css/bootstrap.min.css" rel="stylesheet">
    <script src="../js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">

    			<div class="span10 offset1">
    				<div class="row">
		    			<h3>Update a User</h3>
		    		</div>

	    			<form class="form-horizontal" action="updateprofile.php?email=<?php echo $email?>" method="post">
					  <div class="control-group <?php echo !empty($emailError)?'error':'';?>">
					    <label class="control-label">Email Address</label>
					    <div class="controls">
					      	<input name="email" type="text" placeholder="Email Address" value="<?php echo !empty($email)?$email:'';?>">
					      	<?php if (!empty($emailError)): ?>
					      		<span class="help-inline"><?php echo $emailError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($firstNameError)?'error':'';?>">
					    <label class="control-label">First Name</label>
					    <div class="controls">
					      	<input name="firstName" type="text"  placeholder="First Name" value="<?php echo !empty($firstName)?$firstName:'';?>">
					      	<?php if (!empty($firstNameError)): ?>
					      		<span class="help-inline"><?php echo $firstNameError;?></span>
					      	<?php endif; ?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($lastNameError)?'error':'';?>">
					    <label class="control-label">Last Name</label>
					    <div class="controls">
					      	<input name="lastName" type="text"  placeholder="Last Name" value="<?php echo !empty($lastName)?$lastName:'';?>">
					      	<?php if (!empty($lastNameError)): ?>
					      		<span class="help-inline"><?php echo $lastNameError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
					  <div class="form-actions">
						  <button type="submit" class="btn btn-success">Update</button>
						  <a class="btn" href="index.php">Back</a>
						</div>
					</form>
				</div>

    </div> <!-- /container -->
  </body>
</html>
