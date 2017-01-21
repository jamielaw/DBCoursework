<?php 
	require '../database.php';
	$photoId = null;
	$photoCollectionId = null;
	$imageReference = null;
	if ( !empty($_GET['photoId'])) {
		$photoId = $_REQUEST['photoId'];
	}
	$imageReference = $_GET['imageReference'];
	$photoCollectionId = $_GET['photoCollectionId'];
	if ( null==$photoId) {
		header("Location: index.php");
	} else {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM comments WHERE photoId = ? ORDER BY dateCreated DESC";
		$q = $pdo->prepare($sql);
		$q->execute(array($photoId));
	}
	function getUserDetails($email) {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql2 = "SELECT * FROM users WHERE email = ?";
		$q2 = $pdo->prepare($sql2);
		$q2->execute(array($email));
		$user = $q2->fetch(PDO::FETCH_ASSOC);
		return $user;
	}
	function returnNumberOfComments($photoId) {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql3 = "SELECT COUNT(*) FROM comments WHERE photoId = ? ";
		$q3 = $pdo->prepare($sql3);
		$q3->execute(array($photoId));
		$result = $q3->fetch(PDO::FETCH_ASSOC);
		return $result;
	}
	function date_difference ($date_1, $date_2) {   
    $val_1 = new DateTime($date_1);
    $val_2 = new DateTime($date_2);
    $interval = $val_1->diff($val_2);
    $year     = $interval->y;
    $month    = $interval->m;
    $day      = $interval->d;
    $hour	  = $interval->h;
    $minute   = $interval->i;
    $second   = $interval->s;
    $output   = '';
    $ok = 0;
    if($year > 0){
        if ($year > 1){
            $output .= $year." years ";     
        } else {
            $output .= $year." year ";
        }
        $ok=1;
    }
    if($month > 0){
        if ($month > 1){
            $output .= $month." months ";       
        } else {
            $output .= $month." month ";
        }
    }
    if($day > 0){
        if ($day > 1){
            $output .= $day." days ";       
        } else {
            $output .= $day." day ";
        }
        $ok=1;
    }
    if($hour > 0){
        if ($hour > 1){
            $output .= $hour." hours ";       
        } else {
            $output .= $hour." hour ";
        }
        $ok=1;
    }
    if($minute > 0){
        if ($minute > 1){
            $output .= $minute." minutes ";       
        } else {
            $output .= $minute." minute ";
        }
        $ok=1;
    }
    
    if ($ok==0){
    	if($second > 0){
        	if ($second > 1){
            	$output .= $second." minutes ";       
        	} else {
            	$output .= $second." minute ";
        	}
    	}
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
  <style>
 	
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
</head>
<body>

<div class="container-fluid">
  <div class="row content">
    <div class="paddingImage col-sm-3 sidenav">
    	<img src="<?php echo $imageReference?>" width="300">
    </div>

    <div class="col-sm-9">
      <br>


      <h4>Leave a Comment:</h4>
      <form role="form">
        <div class="form-group">
          <textarea class="form-control" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Submit</button>
      </form>
      <br><br>
      
      <p><span class="badge"><?php echo returnNumberOfComments($photoId)['COUNT(*)'];?> </span> Comments:</p><br>
      
      <div class="row">
       <?php
       	while ($row = $q->fetch(PDO::FETCH_ASSOC)){
		?>
        <div class="col-sm-2 text-center">
         	<?php $email = $row['email']; ?>
			<img src="<?php echo getUserDetails($email)['profileImage'];?>" class="img-circle" height="65" width="65" alt="Avatar">
        </div>
        <div class="col-sm-10">
          <h4><b><?php echo getUserDetails($email)['firstName'];?> <?php echo getUserDetails($email)['lastName'];?></b> 
          	<small><?php
          		$date1=date("Y-m-d h:i:s");
			    $date2=$row['dateCreated'];
			    echo date_difference($date1,$date2);
			    ?>
			ago</small>
          </h4>
          <p><?php echo $row['commentText'];?></p>
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

<footer class="container-fluid">
  <p>Footer Text</p>
</footer>

</body>
</html>
