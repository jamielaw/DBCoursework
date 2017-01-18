<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Photos</title>
  </head>
  <body>
    <!--Link to creating photo collections  -->
    <a href="createphotocollection.php">
      <button type="button" name="button">Create Photo Collection</button>
    </a>

    <div class="row">

      <!--  Repeat for $Y number of photo collections-->
      <a href="photocollection.php">
        <div class="col-sm-6 col-md-4">
          <div class="thumbnail">
            <img src="..." alt="...">
            <div class="caption">
              <h3>Thumbnail label</h3>
              <p>...</p>
              <p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
            </div>
          </div>
        </div>
      </a>
  </body>
</html>
