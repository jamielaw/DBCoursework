<?php

    $title = "Bookface Social Network";
    $description = "A far superior social network";
    include("../inc/header.php");
    include("../inc/nav-trn.php");

    // Fetches the latest comments and annotations of the user's friends
    function getLatestNews($loggedInUser)
    {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT \'annotated\' as activity, email, photoId as id, dateCreated FROM annotations WHERE photoId IN 
        (SELECT photoId FROM photos WHERE photoCollectionId IN (SELECT photoCollectionId FROM photoCollection WHERE createdBy=?) 
        	AND email!=?) 
		UNION SELECT \'commented\' as activity, email, photoId as id, dateCreated FROM comments WHERE photoId IN 
		(SELECT photoId FROM photos WHERE photoCollectionId IN (SELECT photoCollectionId FROM photoCollection WHERE createdBy=?) 
			AND email!=?)
		ORDER BY dateCreated DESC;';
        $q = $pdo->prepare($sql);
        $q->execute(array($loggedInUser,$loggedInUser,$loggedInUser,$loggedInUser));
        return $q;
    }   

    function getUserInfo($email)
    {
    	$pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT * FROM users WHERE email=?';
        $q = $pdo->prepare($sql);
        $q->execute(array($email));
        $value = $q->fetch(PDO::FETCH_ASSOC);
        return $value;
    }

    // Fetches the latest posted photos of the user's friends
    function getPhotos($loggedInUser)
    {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT * FROM photos WHERE photoId IN 
				(SELECT photoId FROM photos WHERE photoCollectionId IN 
					(SELECT photoCollectionId FROM photoCollection WHERE createdBy IN 
                         (SELECT emailFrom as email FROM friendships WHERE emailTo= ? AND status=\'accepted\' 
                         	UNION 
                         SELECT emailTo as email FROM friendships WHERE emailFrom= ? AND status=\'accepted\' )))
                ORDER BY dateAdded DESC LIMIT 5';
        $q = $pdo->prepare($sql);
        $q->execute(array($loggedInUser,$loggedInUser,));
        return $q;
    }  

    // Fetches the latest posted blogs of the user's friends
    function getBlogs($loggedInUser)
    {
    	$pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT * FROM blogs WHERE email IN (SELECT emailFrom as email FROM friendships WHERE emailTo= ? AND status=\'accepted\' 
        	UNION 
        	SELECT emailTo as email FROM friendships WHERE emailFrom= ? AND status=\'accepted\')
			ORDER BY dateCreated DESC LIMIT 5;';
        $q = $pdo->prepare($sql);
        $q->execute(array($loggedInUser,$loggedInUser));
        return $q;
    }

    function getCollectionOwner($photoCollectionId)
    {
    	$pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT * FROM users WHERE email IN 
			(SELECT createdBy FROM photocollection WHERE photoCollectionId = ?)';
        $q = $pdo->prepare($sql);
        $q->execute(array($photoCollectionId));
        $value = $q->fetch(PDO::FETCH_ASSOC);
        return $value;
    }

    function getCollectionName($id)
    {
    	$pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT * FROM photoCollection WHERE photoCollectionId = ?';
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $value = $q->fetch(PDO::FETCH_ASSOC);
        return $value;
    }

    function getPhotoInfo($id)
    {
    	$pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT * FROM photos WHERE photoId = ?';
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $value = $q->fetch(PDO::FETCH_ASSOC);
        return $value;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div class="container">
   <div id="blog" class="row"> 
   <div class="col-sm-2 paddingTop20">
        <nav class="nav-sidebar">
            <ul class="nav">
                <li class="active"><a href="javascript:;"><span class="glyphicon glyphicon-star"></span> Your Latest News </a></li>
					<div class="activity-feed">
					<?php
						foreach (getLatestNews($loggedInUser) as $row) {
							$newdate = date( 'F j, Y, g:i a', strtotime($row['dateCreated']));
							echo '
							<div class="feed-item">
							    <div class="date">'.$newdate.'</div>
							    <div class="text"><a href="/sn/profile/readprofile.php?email='.$row['email'].'">'.getUserInfo($row['email'])['firstName'].' '.getUserInfo($row['email'])['lastName'].'</a> '. $row['activity'].'<a href="/sn/profile/readphoto.php?photoId='.$row['id'].'&imageReference='.getPhotoInfo($row['id'])['imageReference'].'&photoCollectionId='.getPhotoInfo($row['id'])['photoCollectionId'].'"> your photo.</a></div>
							</div>';
					    }
					?>
					</div>
                <li class="nav-divider"></li>
            </ul>
        </nav>
            <div><h2 class="add">Place for your add!</h2></div>
    </div>
    <div class="col-md-10 blogShort">
    	<?php
	    	foreach (getPhotos($loggedInUser) as $row) {
				$newdate = date( 'F j, Y, g:i a', strtotime($row['dateAdded']));
				echo '
				<h3>'.$newdate.'</h3>
                
                <article><p>
            		Your friend, <a href="/sn/profile/readprofile.php?email='.getCollectionOwner($row['photoCollectionId'])['email'].'"> '.getCollectionOwner($row['photoCollectionId'])['firstName'].' </a>, has added a new photo to the collection  <a href="/sn/profile/readphotocollection.php?createdBy='.getCollectionOwner($row['photoCollectionId'])['email'].'&photoCollectionId='.$row['photoCollectionId'].'">'.getCollectionName($row['photoCollectionId'])['title'].'</a>.
            	</p></article>
            	<img src='.$row['imageReference'].' alt="post img" class="img-thumbnail"  style="max-height:500px" >
            	<hr>
				';
			}
			foreach (getBlogs($loggedInUser) as $row) {
				$newdate = date( 'F j, Y, g:i a', strtotime($row['dateCreated']));
				echo '
				<h3>'.$newdate.'</h3>
                
                <article><p>
                	<img src='.getUserInfo($row['email'])['profileImage'].' alt="post img" class="pull-left img-responsive img-rounded"  style="max-height:50px" >
            		Your friend, <a href="/sn/profile/readprofile.php?email='.$row['email'].'">'.getUserInfo($row['email'])['firstName'].'</a>, has written a new blog post called <a href="/sn/blog/viewPost.php?blogId='.$row['blogId'].'">'.$row['blogTitle'].'</a>.
            		<br><br>
            	</p></article>
            	<hr>
				';
			}
		?>
    </div>  
</div>
</body>
</html>

<style>

@import url(http://fonts.googleapis.com/css?family=Open+Sans);
/* apply a natural box layout model to all elements, but allowing components to change */


.activity-feed {
  padding: 15px;
  max-height: 350px;
  overflow-y:scroll; 
}
.activity-feed .feed-item {
  position: relative;
  padding-bottom: 20px;
  padding-left: 30px;
  border-left: 2px solid #e4e8eb;
}
.activity-feed .feed-item:last-child {
  border-color: transparent;
}
.activity-feed .feed-item:after {
  content: "";
  display: block;
  position: absolute;
  top: 0;
  left: -6px;
  width: 10px;
  height: 10px;
  border-radius: 6px;
  background: #fff;
  border: 1px solid #f37167;
}
.activity-feed .feed-item .date {
  position: relative;
  top: -5px;
  color: #8c96a3;
  text-transform: uppercase;
  font-size: 13px;
}
.activity-feed .feed-item .text {
  position: relative;
  top: -3px;
}
  .blogShort{ border-bottom:1px solid #ddd;}
.add{background: #333; padding: 10%; height: 300px;}

.nav-sidebar { 
    width: 100%;
    padding: 8px 0; 
    border-right: 1px solid #ddd;
}
.nav-sidebar a {
    color: #333;
    -webkit-transition: all 0.08s linear;
    -moz-transition: all 0.08s linear;
    -o-transition: all 0.08s linear;
    transition: all 0.08s linear;
}
.nav-sidebar .active a { 
    cursor: default;
    background-color: #34ca78; 
    color: #fff; 
}
.nav-sidebar .active a:hover {
    background-color: #37D980;   
}
.nav-sidebar .text-overflow a,
.nav-sidebar .text-overflow .media-body {
    white-space: nowrap;
    overflow: hidden;
    -o-text-overflow: ellipsis;
    text-overflow: ellipsis; 
}

.btn-blog {
    color: #ffffff;
    background-color: #37d980;
    border-color: #37d980;
    border-radius:0;
    margin-bottom:10px
}
.btn-blog:hover,
.btn-blog:focus,
.btn-blog:active,
.btn-blog.active,
.open .dropdown-toggle.btn-blog {
    color: white;
    background-color:#34ca78;
    border-color: #34ca78;
}
 h2{color:#34ca78;}
 .margin10{margin-bottom:10px; margin-right:10px;}
</style>