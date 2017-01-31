<!DOCTYPE html>
<head>
  <?php $root = realpath($_SERVER["DOCUMENT_ROOT"]); ?>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!--
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
-->
  <script type="text/javascript" src="http://code.jquery.com/jquery-3.0.0.min.js"></script>
  <script type="text/javascript" src="//netsh.pp.ua/upwork-demo/1/js/typeahead.js"></script>
  <script src="https://use.fontawesome.com/48a6746b39.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

  <!--  Boostrap Framework  -->
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <!--  Custom stylesheet -->
  <link href="/sn/css/custom.css" rel="stylesheet">

  <script type ="text/javascript">
    jQuery(document).ready(function($) {
      $('input.submit').typeahead({
        name: 'submit',
        remote: {url: '/sn/inc/nav-trnsearch.php?query=%QUERY'} //absolute ref as we don't know which page is calling this
      });
    });

  </script>
  <style>
        .tt-hint,
        .submit {
            border: 2px solid #CCCCCC;
            border-radius: 8px 8px 8px 8px;
            /*font-size: 24px; */
            height: 30px;
            line-height: 30px;
            outline: medium none;
            padding: 8px 12px;
            width: 400px;
        }

        .tt-dropdown-menu {
            width: 400px;
            margin-top: 5px;
            padding: 8px 12px;
            background-color: #fff;
            border: 1px solid #ccc;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 8px 8px 8px 8px;
            font-size: 18px;
            color: #111;
            background-color: #F1F1F1;
        }
</style>
</head>
