<!--  Maintain a blog and read and search the blogs of friends.  -->
<?php include("../inc/header.php"); ?>
<body>
  <?php include '../inc/nav-trn.php'; ?>
  <?php
    //require "../database.php";
    $title = "Blog";
    $description = "";


    // CHANGED THIS TO BE AUTHENTICATED LATER
    $loggedInUser = "larry@ucl.ac.uk";
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $postQuery = "SELECT * FROM blogs WHERE blogId=" . $_GET["blogId"];

    $y = $pdo->prepare($postQuery);
    $y->execute();
    $postQueryResult = $y->fetch(PDO::FETCH_ASSOC);



  ?>
  <div class="container">
    <div class="blog-container">
      <div class="row">
        <font size="10"> <?php echo $postQueryResult["blogTitle"]; ?> </font> <br>
        <font size="3"></font>
      </div>
      <div class="blog-edit-options">
        <a class='btn btn-success' href="editPostView.php?blogId=<?php echo  $postQueryResult["blogId"]; ?>"> <i class="fa fa-pencil" aria-hidden='true'> Edit</i>  </a>
        <a class='btn btn-danger' href="deleteBlog.php?blogId=<?php echo  $postQueryResult["blogId"]; ?>"> <i class="fa fa-trash" aria-hidden='true'> Delete</i>  </a>

      </div>

    <div id="rawText">
        <?php echo $postQueryResult["blogDescription"];  ?>
    </div>




      </div>






    </div>





  </div>

</body>
<?php Database::disconnect(); ?>
<script>

$(document).ready(function(){
  var converter = new showdown.Converter(),
      text      = $('#rawText')[0]['textContent'];
      html      =  converter.makeHtml(text);

  console.log(text);
  console.log(html);
  $("#rawText").html(html);// = html;
});


  </script>
