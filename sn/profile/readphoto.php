<?php 
	require '../database.php';
	$photoId = null;
	$photoCollectionId = null;
	$imageReference = null;
	if ( !empty($_GET['photoId'])) {
		$photoId = $_REQUEST['photoId'];
	}

	$imageReference = $_GET['imageReference'];
	if ( null==$photoId) {
		header("Location: index.php");
	} else {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM comments WHERE photoId = ? ORDER BY dateCreated DESC";
		$q = $pdo->prepare($sql);
		$q->execute(array($photoId));
		Database::disconnect();
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <script src="../js/min/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">
    <div class="span10 offset1">
	    <div class="row padding">
		  <div class="col-md-4"><img src="<?php echo $imageReference?>" height="400">
		  	<div class="text-center">Tags</div>
		  </div>
		  
		  <div class="col-md-6 scroll">
		  <?php 
		  	while ($row = $q->fetch(PDO::FETCH_ASSOC)){
		  ?>
			  <div class="row inner">
			  	<div class="col-sm-8">
			  		<div class="panel panel-white post panel-shadow">
			            <div class="post-heading">
			                <div class="pull-left image">
			                    <img src="http://bootdey.com/img/Content/user_1.jpg" class="img-circle avatar" alt="user profile image">
			                </div>
			                <div class="pull-left meta">
			                    <div class="title h5">
			                        <a href="#"><b><?php echo $row['email'];?></b></a>
			                            made a post.
			                    </div>
			                    <h6 class="text-muted time">
			                    	<?php
			                    		$date1=date("Y-m-d h:i:s");
			                    		$date2=$row['dateCreated'];
			                    		echo '<h6 class="text-muted time">'.date_difference($date1,$date2);'</h6>'
			                    	?>
			                    	ago
			                    </h6>
			                </div>
			            </div> 
			            <div class="post-description"> 
			                <p><?php echo $row['commentText'];?></p>
			                <div class="stats">
			                    <a href="#" class="btn btn-default stat-item">
			                        <i class="fa fa-thumbs-up icon"></i>2
			                    </a>
			                    <a href="#" class="btn btn-default stat-item">
			                        <i class="fa fa-thumbs-down icon"></i>12
			                    </a>
			                </div>
			            </div>
			        </div>
			    </div>
			   </div>
			 <?php
				}
			?>


		  </div>
		</div>
	   	</div>
	</div>
  </body>
</html>



<style>
.scroll {
    height: 700px;
    overflow: auto;
}

.inner {
    margin: 0 auto; 
    position: center;
}
.test {background-color: lightblue;}
.test2 {background-color: yellow;}
.test3 {background-color: red;}
.padding {
    padding-top: 1.5cm;
}

.panel-shadow {
    box-shadow: rgba(0, 0, 0, 0.3) 7px 7px 7px;
}
.panel-white {
  border: 1px solid #dddddd;
}
.panel-white  .panel-heading {
  color: #333;
  background-color: #fff;
  border-color: #ddd;
}
.panel-white  .panel-footer {
  background-color: #fff;
  border-color: #ddd;
}

.post .post-heading {
  height: 95px;
  padding: 20px 15px;
}
.post .post-heading .avatar {
  width: 60px;
  height: 60px;
  display: block;
  margin-right: 15px;
}
.post .post-heading .meta .title {
  margin-bottom: 0;
}
.post .post-heading .meta .title a {
  color: black;
}
.post .post-heading .meta .title a:hover {
  color: #aaaaaa;
}
.post .post-heading .meta .time {
  margin-top: 8px;
  color: #999;
}
.post .post-image .image {
  width: 100%;
  height: auto;
}
.post .post-description {
  padding: 15px;
}
.post .post-description p {
  font-size: 14px;
}
.post .post-description .stats {
  margin-top: 20px;
}
.post .post-description .stats .stat-item {
  display: inline-block;
  margin-right: 15px;
}
.post .post-description .stats .stat-item .icon {
  margin-right: 8px;
}
.post .post-footer {
  border-top: 1px solid #ddd;
  padding: 15px;
}
.post .post-footer .input-group-addon a {
  color: #454545;
}
.post .post-footer .comments-list {
  padding: 0;
  margin-top: 20px;
  list-style-type: none;
}
.post .post-footer .comments-list .comment {
  display: block;
  width: 100%;
  margin: 20px 0;
}
.post .post-footer .comments-list .comment .avatar {
  width: 35px;
  height: 35px;
}
.post .post-footer .comments-list .comment .comment-heading {
  display: block;
  width: 100%;
}
.post .post-footer .comments-list .comment .comment-heading .user {
  font-size: 14px;
  font-weight: bold;
  display: inline;
  margin-top: 0;
  margin-right: 10px;
}
.post .post-footer .comments-list .comment .comment-heading .time {
  font-size: 12px;
  color: #aaa;
  margin-top: 0;
  display: inline;
}
.post .post-footer .comments-list .comment .comment-body {
  margin-left: 50px;
}
.post .post-footer .comments-list .comment > .comments-list {
  margin-left: 50px;
}
</style>