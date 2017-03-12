<?php
$title = "Bookface Social Network";
$description = "A far superior social network";
include("inc/header.php");
include ("inc/nav-trn.php");
session_start();
 ?>
  <body>
    <!--  No Navigation because this is a page for those who aren't authenticated -->
      <!--  LOGIN -->
      <?php
      $status = $_GET['status'];
      if($status == 1){
      ?>
        <div class="error-msg">
          You entered the wrong password
        </div>
      <?php
      }
      ?>
      <?php
      $status = $_GET['status'];
      if($status == 2){
      ?>
        <div class="error-msg">
          This email does not exist.
        </div>
      <?php
      }
      ?>
      <?php
      $status = $_GET['status'];
      if($status == 3){
      ?>
        <div class="error-msg">
          You entered the wrong username or password
        </div>
      <?php
      }
      ?>
      <div class="container">
        <h1 style="text-align:center;"> BookFace </h1>
        <div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-login">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-6">
								<a href="#" class="active" id="login-form-link">Login</a>
							</div>
							<div class="col-xs-6">
								<a href="#" id="register-form-link">Register</a>
							</div>
						</div>
						<hr>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
								<form id="login-form" action="login.php" method="post" role="form" style="display: block;">
									<div class="form-group">
										<input type="text" name="email" id="email" tabindex="1" class="form-control" placeholder="Email" value="">
									</div>
									<div class="form-group">
										<input type="password" name="pwd" id="pwd" tabindex="2" class="form-control" placeholder="Password">
									</div>
									<div class="form-group text-center">
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-login" value="Log In">
											</div>
										</div>
									</div>
								</form>
								<form id="register-form" action="signup.php" method="post" role="form" style="display: none;">
                  <div id="register-msg"></div>
                  <div class="form-group">
										<input type="email" name="email" id="signup-email" tabindex="1" class="form-control" placeholder="Email Address*" value="">
									</div>
                  <div id="register-msg-first"></div>
									<div class="form-group">
										<input type="text" name="first" id="signup-first" tabindex="1" class="form-control" placeholder="First name*" value="">
									</div>
                  <div id="register-msg-last"></div>

                  <div class="form-group">
										<input type="text" name="last" id="signup-last" tabindex="1" class="form-control" placeholder="Last name*" value="">
									</div>
                  <div id="register-msg-pwd"></div>
									<div class="form-group">
										<input type="password" name="pwd" id="signup-password" tabindex="2" class="form-control" placeholder="Password">
									</div>
									<div class="form-group">
										<input type="password" name="confirm-pwd" id="signup-confirm-password" tabindex="2" class="form-control" placeholder="Confirm Password">
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="submit" name="register-submit" id="register-submit" tabindex="4" class="form-control btn btn-register" value="Register Now">
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
      <?php
        // if (isset($_SESSION['loggedInUserEmail'])) {
        //   echo "You are logged in";
        //   echo "
        //         <form action='logout.php' method='post'>
        //           <button type='submit'>LOG OUT</button>
        //         </form>
        //         ";
        //   echo $_SESSION['loggedInUserEmail'];
        // } else {
        //   echo "You are not logged in, log in or sign up";
        //   echo "<form class='' action='login.php' method='post'>
        //     <input type='text' name='email' placeholder='email'>
        //     <br>
        //     <input type='password' name='pwd' placeholder='password'>
        //     <br>
        //     <button type='submit'>LOGIN</button>
        //   </form>";
        //   echo "
        //   <!--  SIGNUP -->
        //   <form action='signup.php' method='post'>
        //     <br>
        //     <input type='text' name='first' placeholder='first'/>
        //     <br>
        //     <input type='text' name='last' placeholder='last'/>
        //     <br>
        //     <input type='text' name='email' placeholder='email' />
        //     <br>
        //     <input type='password' name='pwd' placeholder='password'/>
        //     <br>
        //     <button type='submit' name='sign-up-submit' value='Sign Up' >SIGN UP</button>
        //   </form>
        //   ";
        // }
       ?>


    </body>
    <script>
    $(function() {

        $('#login-form-link').click(function(e) {
        	$("#login-form").delay(100).fadeIn(100);
     		$("#register-form").fadeOut(100);
    		$('#register-form-link').removeClass('active');
    		$(this).addClass('active');
    		e.preventDefault();
    	});
    	$('#register-form-link').click(function(e) {
    		$("#register-form").delay(100).fadeIn(100);
     		$("#login-form").fadeOut(100);
    		$('#login-form-link').removeClass('active');
    		$(this).addClass('active');
    		e.preventDefault();
    	});


    });


    var typingTimer;                //timer identifier
    var doneTypingInterval = 1000;  //time in ms (5 seconds)

    //on keyup, start the countdown
    $('#signup-email').keyup(function(){
        clearTimeout(typingTimer);
        if ($('#signup-email').val()) {
            typingTimer = setTimeout(doneTyping, doneTypingInterval);
        }
    });

    //user is "finished typing," do something
    function doneTyping () {
        //check if its an email
        console.log("here");
        var testEmail = $('#signup-email').val();
        if(validateEmail(testEmail)){
          // valid email
          $.get("checkEmail.php?email="+testEmail, function(data){
              data = JSON.parse(data);
              console.log(data);
              if(data['free'] == true){
                $("#register-msg").html("A valid email");
                fieldSuccess('signup-email');
              }else{
                $("#register-msg").html("This email already exists");
                fieldError('signup-email');

              }
          });
        }else{
          // invalid email
          $("#register-msg").html("Please enter a valid email address");
          fieldError('signup-email');

        }
    }

    $('#signup-email').keyup(function(){
      var testEmail = $('#signup-email').val();
      testEmail = testEmail.replace(/\s/g, "");
      if(testEmail.length == 0){
        $("#register-msg").html("Please enter a valid email address");
        fieldError('signup-email');

      }
    })


    function validateEmail(email) {
      console.log("ere" + email);
      if(email.length == 0) return false;

      var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      //console.log(email);
      return re.test(email);
    }

    function fieldError(ref){
      $("#"+ref).css("border-color","red");
    }

    function fieldSuccess(ref){
      $("#"+ref).css("border-color","green");

    }

    $("#signup-confirm-password").keyup(function(){
      // console.log("here");

      if( $("#signup-confirm-password").val() == $('#signup-password').val() ){
        fieldSuccess("signup-confirm-password");
        fieldSuccess("signup-password");

        $("#register-msg-pwd").html("Passwords okay!");
      }else{
        $("#register-msg-pwd").html("Passwords don't match!");
        fieldError("signup-password");
        fieldError("signup-confirm-password");

      }
    })

    $("#signup-password").keyup(function(){
      // console.log("here");

      if( $("#signup-confirm-password").val() == $('#signup-password').val() ){
        fieldSuccess("signup-confirm-password");
        fieldSuccess("signup-password");

        $("#register-msg-pwd").html("Passwords okay!");
      }else{
        $("#register-msg-pwd").html("Passwords don't match!");
        fieldError("signup-password");
        fieldError("signup-confirm-password");

      }
    })


  $("#signup-first").keyup(function(){


    var val = $("#signup-first").val();

    var matches = val.match(/\d+/g);

    if(matches != null ){
      fieldError("signup-first");
      $("#register-msg-first").html("Numbers not allowed!");

    }else{
      val = val.toString().replace(/\s/g, "");

      console.log(val);
      if( val.length > 0){
        fieldSuccess("signup-first");
        $("#register-msg-first").html("");

      }else{
        fieldError("signup-first");
        $("#register-msg-first").html("First name is a required field");

      }
    }


  });

  $("#signup-last").keyup(function(){
    var val = $("#signup-last").val();

    var matches = val.match(/\d+/g);

    if(matches != null ){
      fieldError("signup-last");
      $("#register-msg-last").html("Numbers not allowed!");

    }else{
      val = val.toString().replace(/\s/g, "");

      console.log(val);
      if( val.length > 0){
        fieldSuccess("signup-last");
        $("#register-msg-last").html("");

      }else{
        fieldError("signup-last");
        $("#register-msg-last").html("Last name is a required field");

      }
    }
  });






    </script>

    <!-- Footer  -->
    <?php include 'inc/footer.php'; ?>
