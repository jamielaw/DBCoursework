<!--  Shows photo, friends will be able to leave comments and
other annotations on photos (n.b images, videos?), browseable by other friends.-->

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Photo</title>
  </head>
  <body>
    <div class="row">

      <!--  Photo on Left-->
      <div class="col-md-6">
        <img src="http://placehold.it/350x150" alt="Photo">
      </div>

      <!--  Comments on right, comment form first, followed by list of comments -->
      <div class="col-md-6">

        <!--  Comment Form-->
        <form class="" action="index.html" method="post">
          <textarea name="name" rows="8" cols="80"></textarea>
          <button type="button" name="button">Submit Comment</button>
        </form>


        <!--  Repeat for $Z comments-->
        <div class="media">
          <div class="media-left">
            <a href="#">
              <img class="media-object" src="..." alt="Commenter Photo">
            </a>
          </div>
          <div class="media-body">
            <a href="/commenterprofile.php">
              <h4 class="media-heading">Commenter Name</h4>
            </a>
            <p>Comment text</p>
          </div>
        </div>

      </div>
    </div>
  </body>
</html>
