<!--  Maintain a blog and read and search the blogs of friends.  -->
<?php include("../inc/header.php"); ?>
<body>
  <?php include '../inc/nav-trn.php'; ?>
  <?php
    //require "../database.php";
    $title = "Blog";
    $description = "";

    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $postQuery = "SELECT * FROM blogs WHERE blogId=" . htmlspecialchars($_GET['blogId']);

    $y = $pdo->prepare($postQuery);
    $y->execute();
    $postQueryResult = $y->fetch(PDO::FETCH_ASSOC);

    $usersEmail = $postQueryResult["email"];
    $userDetails = "SELECT * FROM users WHERE email='$usersEmail'";
    //echo $usersEmail;
    $d = $pdo->prepare($userDetails);
    $d->execute();
    $userDetailsResult = $d->fetch();
    //echo $userDetailsResult[0]["firstName"];
  ?>
  <div class="container">
    <div class="blog-container">
      <div class="row">
        <font size="10"> <?php echo $postQueryResult["blogTitle"]; ?> </font> <br>
        <font size="3"> Written by
        <?php  echo '<a href="/sn/profile/readprofile.php?email=' .  $usersEmail . '">' . $userDetailsResult['firstName'] . "  " . $userDetailsResult["lastName"] . "</a>"; ?>
        </font>
      <?php if( $loggedInUser == $postQueryResult["email"]){ ?>
      <div class="blog-edit-options">
        <a class='btn btn-success' href="editPostView.php?blogId=<?php echo  $postQueryResult["blogId"]; ?>"> <i class="fa fa-pencil" aria-hidden='true'> Edit</i>  </a>
        <a class='btn btn-danger' href="deleteBlog.php?blogId=<?php echo  $postQueryResult["blogId"]; ?>"> <i class="fa fa-trash" aria-hidden='true'> Delete</i>  </a>
      </div>
    <?php } ?>

    <div id="rawText" style="font-size: 14px; visibility:hidden;"><?php echo $postQueryResult["blogDescription"];  ?></div>
    <div id="viewText">

    </div>

          </div>


      </div>



    </div>





  </div>

</body>
<?php Database::disconnect(); ?>
<script>

$(document).ready(function(){
  var converter = new showdown.Converter();
  var text      = $('#rawText')[0]['innerHTML'];
  var html      =  converter.makeHtml(text);

  console.log(text);
  $("#viewText").html(html);
});


  </script>
