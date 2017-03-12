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
									<div class="form-group">
										<input type="text" name="first" id="first" tabindex="1" class="form-control" placeholder="First name" value="">
									</div>
                  <div class="form-group">
										<input type="text" name="last" id="last" tabindex="1" class="form-control" placeholder="Last name" value="">
									</div>
									<div class="form-group">
										<input type="email" name="email" id="email" tabindex="1" class="form-control" placeholder="Email Address" value="">
									</div>
									<div class="form-group">
										<input type="password" name="pwd" id="password" tabindex="2" class="form-control" placeholder="Password">
									</div>
									<div class="form-group">
										<input type="password" name="confirm-pwd" id="confirm-password" tabindex="2" class="form-control" placeholder="Confirm Password">
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

    </script>

    <!-- Footer  -->
    <?php include 'inc/footer.php'; ?>
