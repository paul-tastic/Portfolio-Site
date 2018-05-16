<?php

ini_set('display_errors', 1);

	// check for cookie and check for logged in
	session_start();
	$error = "";
	$link = mysqli_connect("localhost", "nerdypil_user1", "password47", "nerdypil_mowme");

// check if need to log out, or if still logged in and need to redirect to appropriate account page

	if (array_key_exists("logout", $_GET)) {
		// log user out
		unset($_SESSION);
		setcookie("user", "", time() - 60*60);
		$_COOKIE["user"] = "";}

	 if (!isset($_COOKIE["user"])) {
     		// cookie not set
	 	} else {
	 		$query1 = "SELECT * from users WHERE email = '".mysqli_real_escape_string($link, $_COOKIE["user"])."' LIMIT 1";
	 		$query2 = "SELECT * from contractors WHERE email = '".mysqli_real_escape_string($link, $_COOKIE["user"])."' LIMIT 1";
	 		$result1 = mysqli_query($link, $query1);
	 		$result2 = mysqli_query($link, $query2);
	 		$row1 = mysqli_fetch_assoc($result1);
	 		$row2 = mysqli_fetch_assoc($result2);

	 		if ((mysqli_num_rows($result1) > 0)) {
	 			$landing_page = "userpage.php";
	 			$row = $row1;
	 		} else if ((mysqli_num_rows($result2) > 0)) {
	 			$landing_page = "contractorpage.php";
	 			$row = $row2;
	 		}
	 		// set session variables here before redirect!
	 		$_SESSION["phone"] = $row['phone'];
	 		$_SESSION["email"] = $row['email'];
	 		$_SESSION["name"] = $row['name'];
	 		header("Location: ".$landing_page);
	 	}

//submit button pressed, check for errors, empty fields
	if (array_key_exists("submit", $_POST)) {

		if (mysqli_connect_error()) {
			die("Database connection error.");
		} 
		
		if ($_POST["signup"] == "0" OR $_POST["signup"] == "2") {
			// user/contractor is signing in, not registering
			if (!$_POST["email"]) {
				$error .= "An email address is required.<br>";
			}
			if (!$_POST["password"]) {
				$error .= "A password is required.<br>";
			}

		} else {
			// user is registering, check all forms
			if (!$_POST["name"]) {
				$error .= "Your name is required.<br>";
			}
			if (!$_POST["email"]) {
				$error .= "An email address is required.<br>";
			}
			if (!$_POST["phone"]) {
				$error .= "A phone number is required.<br>";
			}
			if (!$_POST["password"]) {
				$error .= "A password is required.<br>";
			}
		}

		if ($error != "") {
			$error = "<p>There were error(s) in your form:</p>".$error;

		} else {
//field error check complete, no errors, now parse user/contractor, add new user info or log in
			
			if ($_POST['signup'] == "1" OR $_POST['signup'] == "3") {
			// User/Contractor - sign up form

				// select correct table and assign uncommon variables
				if ($_POST['signup'] == "1") {
					// user sign up
						$table_name = 'users';
						$field_name = "`name`";
						$landing_page = 'userpage.php';
				} else if ($_POST['signup'] == "3") {
						$table_name = 'contractors';
						$field_name = "`company_name`";
						$landing_page = 'contractorpage.php';
				}
				// assign common variables 
				$query = "SELECT id from users WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
				$query2 = "SELECT id from contractors WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";

				$result = mysqli_query($link, $query);
				$result2 = mysqli_query($link, $query2);
				if ((mysqli_num_rows($result) > 0) OR (mysqli_num_rows($result2) > 0)) { 
					$error = "That email address is taken";
				} else {
						$query = "INSERT into $table_name ($field_name, `phone`, `email`, `password`) VALUES (
						'".mysqli_real_escape_string($link, $_POST['name'])."', 
						'".mysqli_real_escape_string($link, $_POST['phone'])."', 
						'".mysqli_real_escape_string($link, $_POST['email'])."',
						'".mysqli_real_escape_string($link, $_POST['password'])."')";
						if (!mysqli_query($link, $query)) {
							$error = "<p>Could not sign you up - Please try again.</p>";
						} else {
							// encrypt password to hashed
							$query = "UPDATE $table_name SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = ".mysqli_insert_id($link)." LIMIT 1";

						if ($_POST['stayLoggedIn'] == '1') {
							// stay logged in, create cookie
							setcookie("user", $_POST['email'], time() + 60*60*24*365);
							}
						// set session variables and redirect to correct landing page

						$_SESSION["name"] = $_POST['name'];
						$_SESSION["email"] = $_POST['email'];
						$_SESSION["phone"] = $_POST['phone'];
						//$_SESSION["password"] = $_POST['password'];
						header("Location: ".$landing_page);
						}
				}
				//add email validation!!
			} else {
				// user already registered - authenticate user

				// User/Contractor - log in form

				// select correct table and assign uncommon variables
				if ($_POST['signup'] == "0") {
					// user sign up
						$table_name = 'users';
						$field_name = "`name`";
						$landing_page = 'userpage.php';
				} else {
						$table_name = 'contractors';
						$field_name = "`company_name`";
						$landing_page = 'contractorpage.php';
				}

				$query = "SELECT * from $table_name WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_array($result);
				if (isset($row)) {
					$hashedPassword = md5(md5($row['id']).$_POST['password']);
			
					if ($hashedPassword == $row['password']) {
						// user authenticates
			 			if ($_POST['stayLoggedIn'] == '1') {
			 				setcookie("user", $row['email'], time() + 60*60*24*365);
			 			}
			 			$_SESSION["name"] = $_POST['name'];
						$_SESSION["email"] = $_POST['email'];
						$_SESSION["phone"] = $_POST['phone'];
			 			header("Location: ".$landing_page);

					}  else {
						$error = "Invalid email/password combination.";
						}

					} else {
						$error = "Username could not be found.";
				}
			} // authenticate user
	} // else midway

} // top

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

    <title>Mow Me!</title>
    

    <style type="text/css">
    	#tip-box {
    		position: absolute;
    		left: 10px;
    		bottom: 10px;
    		background-color: rgba(255, 255, 255, 0.4);
    		border: 1px solid black;
    		border-radius: 10px;
    		padding: 10px;
    		width: 454px;
    	}
    	body {
    		background: url(images/grass.png) no-repeat center center fixed; 
  			-webkit-background-size: cover;
  			-moz-background-size: cover;
  			-o-background-size: cover;
  			background-size: cover;
    	}

    	.nav-border-contractor {
    		border-bottom: 2px solid red;
    	}

    	.nav-border-user {
    		border-bottom: 2px solid blue;
    	}
    	
    	#main-view {
    		
    		text-align: center;
    		margin: 80px auto 0 auto;
    	}
    	.smaller {
    		width: 200px;
    		margin: 0 auto;
    	}

    	.midsize {
    		width: 250px;
    		margin: 0 auto;
    	}

    	.forms {
    		margin: 25px auto 25px auto;
    	}

    	#user-login-form {
    		display: none;
    	}

    	.toggleUser {
    		color: blue !important;
    	}

    	input {
    		text-align: center;
    	}

    	#user-signup {
    		display: none;
    	}

    	#contractor-signup {
    		color: blue !important;
    	}

    	#contractor-login {
    		color: blue !important;
    	}

    	#contractor-signup-form {
    		display: none;
    	}

    	#contractor-login-form {
    		display: none;
    	}

    	.contractor-divs {
    		border: 1px solid red;
    		border-radius: 10px;
    	}

    	.user-divs {
    		border: 1px solid blue;
    		border-radius: 10px;
    	}

    	.forms {
    		padding: 25px;
    	}

        .user-icon {
            margin-right: 10px;
            font-size: 1.5em;
        }
        #navbarMessage {
            text-align: center;
            color: grey;
        }

        #intro-screen-words {
        	text-align: left;
        	/*border: 1px solid black;
        	border-radius: 8px;*/
        	height: 85%;
        }

    </style>
  </head>
  <body>
 
<nav class="navbar fixed-top nav-border-user bg-light" id="navbar">
  <a class="navbar-text" id="contractor-text">Contractor? <span id="contractor-signup">Sign Up</span> / <span id="contractor-login">Sign In </span>Here</a>
  <span class="navbar-text right" id="user-login">Already registered? <a class="toggleUser">Log In!</a></span>
  <span class="navbar-text right" id="user-signup">Need to register? <a class="toggleUser">Sign Up!</a></span>
</nav>




  	<div class="container-fluid" id="main-view">

  		<div id="tip-box">
			<h4>Visitors</h4>
			<p>feel free to register as a new user, or use login info below.</p>
			<p>user: nerdypilot@is-hired.com // password</p>
			<p>contractor: nerdypilot@contractor.com // password</p>
		</div>
  		<div class="row">
	  		<div class="col-md-3 offset-md-1">
	  			<form method = "post" class="forms user-divs" id="user-login-form">
					<h3>Log In Form</h3>
					<div class="form-group">
					<input type="email" name="email" class="form-control midsize" placeholder="Email address">
				</div>
				<div class="form-group">
					<input type="password" name="password" class="form-control midsize" placeholder="Password">
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" name="stayLoggedIn" value="1">
						Stay logged in
					</label>
				</div>
					<div class="form-group">
					<input type="hidden" name="signup" value="0">
					<input type="submit" class="btn btn-success" name="submit" value="Log In!">
				</div>
				<!-- <p>Not registered? <a class="toggleForms">Sign Up</a></p> -->
				</form>

				<!-- Sign up form -->
				<form method = "post" class="forms user-divs" id="user-signup-form">
					<h3>Sign Up Form</h3>
					<div class="form-group">
						<input type="text" name="name" class="form-control midsize" placeholder="Enter Full Name">
					</div>
					<div class="form-group">
						<input type="email" name="email" class="form-control midsize" placeholder="Enter your email address">
					</div>
					<div class="form-group">
						<input type="tel" name="phone" class="form-control smaller" placeholder="Phone">
					</div>
					<div class="form-group">
						<input type="password" name="password" class="form-control smaller" placeholder="Choose password">
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="stayLoggedIn" value="1">
							Stay logged in
						</label>
					</div>
					<div class="form-group">
						<input type="hidden" name="signup" value="1">
						<input type="submit" class="btn btn-success" name="submit" value="Sign Up!">
					</div>
					<!-- <p>Already registered? <a class="toggleForms">Log In</a></p> -->
				</form>

				<!-- Contractor Log In Form -->
				<form method = "post" class="forms contractor-divs" id="contractor-login-form">
					<h3>Contractor Log In Form</h3>
					<div class="form-group">
					<input type="email" name="email" class="form-control midsize" placeholder="Email address">
				</div>
				<div class="form-group">
					<input type="password" name="password" class="form-control midsize" placeholder="Password">
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" name="stayLoggedIn" value="1">
						Stay logged in
					</label>
				</div>
					<div class="form-group">
					<input type="hidden" name="signup" value="2">
					<input type="submit" class="btn btn-success" name="submit" value="Log In!">
				</div>
				<!-- <p>Not registered? <a class="toggleForms">Sign Up</a></p> -->
				</form>



				<!-- Contractor Sign Up Form -->
				<form method = "post" class="forms contractor-divs" id="contractor-signup-form">
					<h3>Contractor Sign Up Form</h3>
					<div class="form-group">
						<input type="text" name="name" class="form-control midsize" placeholder="Company Name">
					</div>
					<div class="form-group">
						<input type="email" name="email" class="form-control midsize" placeholder="Contact email address">
					</div>
					<div class="form-group">
						<input type="tel" name="phone" class="form-control smaller" placeholder="Phone">
					</div>
					<div class="form-group">
						<input type="password" name="password" class="form-control smaller" placeholder="Choose password">
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="stayLoggedIn" value="1">
							Stay logged in
						</label>
					</div>
					<div class="form-group">
						<input type="hidden" name="signup" value="3">
						<input type="submit" class="btn btn-success" name="submit" value="Sign Up!">
					</div>
				</form>
		
	  		</div>
			
	  		<div class="col-md-5 offset-md-2" id="intro-screen-words">
	  			<h1>Mow Me!</h1>
	  			<!-- User log in form -->
				<p>Have a yard that needs mowing but don't have a service set up, and not sure where to start?</p>
	  			<p>Mowme connects property owners to landscape professionals to provide quality, short-notice landscape services, on demand. No contracts, no commitments, and no-fuss!</p>
	  			<hr>
	  			<p>Simply sign up for a free, no risk account, submit a job request, and instantly be connected with numerous landscape professionals who will provide you their best price up front. Creating your account is free, and you pay nothing until you accept a bid.</p>
	  			<p>There are no hidden fees or extra costs for homeowners to use our 100% money-back guaranteed service.</p>
	  			<h3>Try it today!</h3>
	  		</div>
	  	</div> <!-- row -->
</div>

<!-- <?php 
if(!isset($_COOKIE["user"])) {
     		echo "Cookie named 'user' is not set!";
		} else {
     		echo "Cookie 'user' is set!<br>";
     		echo "Value is: " . $_COOKIE["user"];
		}
?> -->
    <!-- Optional JavaScript -->

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

    <script type="text/javascript">

		$(document).on('click', '#user-login', function() {
    		$('#user-signup-form').hide();
    		$('#user-login-form').show();
    		$('#user-signup').show();
    		$('#user-login').hide();
    		$('#navbar').removeClass('nav-border-contractor');
    		$('.contractor-divs').hide();
    		$('#navbar').removeClass('nav-border-contractor');
    		$('#navbar').addClass('nav-border-user');
		});

		$(document).on('click', '#user-signup', function() {
    		$('#user-signup-form').show();
    		$('#user-login-form').hide();
    		$('#user-signup').hide();
    		$('#user-login').show();
    		$('#navbar').removeClass('nav-border-contractor');
    		$('.contractor-divs').hide();
    		$('#navbar').removeClass('nav-border-contractor');
    		$('#navbar').addClass('nav-border-user');
		});

    	$(document).on('click', '#contractor-login', function() {
    		$('#user-signup-form').hide();
    		$('#user-login-form').hide();
    		$('#contractor-signup-form').hide();
    		$('#contractor-login-form').show();
    		$('#contractor-text').html('Contractors: Need to register? <span id="contractor-signup">Sign Up!</span>');
    		$('#navbar').removeClass('nav-border-user');
    		$('#navbar').addClass('nav-border-contractor');
    	});

		$(document).on('click', '#contractor-signup', function() {
    		$('#user-signup-form').hide();
    		$('#user-login-form').hide();
    		$('#contractor-signup-form').show();
    		$('#contractor-login-form').hide();
    		$('#contractor-text').html('Contractors: Already registered? <span id="contractor-login">Sign In!</span>');
    		$('#navbar').removeClass('nav-border-user');
    		$('#navbar').addClass('nav-border-contractor');
    	});


    </script>
  </body>
</html>










