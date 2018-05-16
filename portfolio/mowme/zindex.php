<?php

ini_set('display_errors', 1);

	session_start();

	$error = "";

	if (array_key_exists("logout", $_GET)) {
		// log user out
		unset($_SESSION);
		setcookie("id", "", time() - 60*60);
		$_COOKIE["id"] = "";

	} else if ((array_key_exists("id", $_SESSION) AND $_SESSION['id']) OR (array_key_exists("id", $_COOKIE) and $_COOKIE['id'])) {
			//logged in already
		header("Location: loggedinpage.php");
	}

	if (array_key_exists("submit", $_POST)) {

		$link = mysqli_connect("localhost", "nerdypil_user1", "password47", "nerdypil_mowme");

		if (mysqli_connect_error()) {
			die("Database connection error.");
		} 
		
		if ($_POST["signup"] == "0") {
			// user is signing in, not registering
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

			//no errors
			if ($_POST['signup'] == "1") {
				// sign up form used!

			$query = "SELECT id from `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";

			$result = mysqli_query($link, $query);

			if (mysqli_num_rows($result) > 0) {
				$error = "That email address is taken";
			} else {
				$query = "INSERT into `users` (`name`, `phone`, `email`, `password`) VALUES (
				'".mysqli_real_escape_string($link, $_POST['name'])."', 
				'".mysqli_real_escape_string($link, $_POST['phone'])."', 
				'".mysqli_real_escape_string($link, $_POST['email'])."',
				'".mysqli_real_escape_string($link, $_POST['password'])."')";

				if (!mysqli_query($link, $query)) {
					$error = "<p>Could not sign you up - Please try again.</p>";
			} else {
				// encrypt password to hashed
				$query = "UPDATE `users` SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = ".mysqli_insert_id($link)." LIMIT 1";

				mysqli_query($link, $query);

				$_SESSION['id'] = mysqli_insert_id($link);

				if ($_POST['stayloggedin'] == '1') {
					setcookie("id", mysqli_insert_id($link), time() + 60*60*24*365);
				}

				header("Location: loggedinpage.php");
			}
		}
		//add email validation!!
	} else {
		// user already registered - authenticate user
		
		$query = "SELECT * from `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_array($result);
		if (isset($row)) {

			$hashedPassword = md5(md5($row['id']).$_POST['password']);
			
			if ($hashedPassword == $row['password']) {
				// user authenticates
			 	$_SESSION['id'] = $row['id'];
			 	if ($_POST['stayloggedin'] == '1') {
			 		setcookie("id", $row['id'], time() + 60*60*24*365);
			 	}
				
			 	header("Location: loggedinpage.php");

			}  else {

				$error = "Invalid email/password combination.";
			}

		} else {

				$error = "Username could not be found.";

			}
	}
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
    		width: 400px;
    		text-align: center;
    		margin: 100px auto 0 auto;
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

    </style>
  </head>
  <body>
 
<nav class="navbar fixed-top nav-border-user" id="navbar">
  <a class="navbar-text" id="contractor-text">Contractor? <span id="contractor-signup">Sign Up</span> / <span id="contractor-login">Sign In </span>Here</a>
  <span class="navbar-text right" id="user-login">Already registered? <a class="toggleUser">Log In!</a></span>
  <span class="navbar-text right" id="user-signup">Need to register? <a class="toggleUser">Sign Up!</a></span>
</nav>




  	<div class="container" id="main-view">
  		<h1>Mow Me!</h1>
		
		<div id="error"><?php echo $error; ?></div>

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
					<input type="checkbox" name="stayLoggedIn" value=1>
					Stay logged in
				</label>
			</div>
			<div class="form-group">
				<input type="hidden" name="signup" value="1">
				<input type="submit" class="btn btn-success" name="submit" value="Sign Up!">
			</div>
			<!-- <p>Already registered? <a class="toggleForms">Log In</a></p> -->
		</form>

		<!-- log in form -->
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
				<input type="checkbox" name="stayLoggedIn" value=0>
				Stay logged in
			</label>
		</div>
			<div class="form-group">
			<input type="hidden" name="signup" value="0">
			<input type="submit" class="btn btn-success" name="submit" value="Log In!">
		</div>
		<!-- <p>Not registered? <a class="toggleForms">Sign Up</a></p> -->
		</form>
	

<!-- Contractor Sign Up Form -->
<form method = "post" class="forms contractor-divs" id="contractor-signup-form">
			<h3>Contractor Sign Up Form</h3>
			<div class="form-group">
				<input type="text" name="company_name" class="form-control midsize" placeholder="Company Name">
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
					<input type="checkbox" name="stayLoggedIn" value=1>
					Stay logged in
				</label>
			</div>
			<div class="form-group">
				<input type="hidden" name="signup" value="3">
				<input type="submit" class="btn btn-success" name="submit" value="Sign Up!">
			</div>
		</form>

<!-- Contractor Sign In Form -->
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
				<input type="checkbox" name="stayLoggedIn" value=4>
				Stay logged in
			</label>
		</div>
			<div class="form-group">
			<input type="hidden" name="signup" value="0">
			<input type="submit" class="btn btn-success" name="submit" value="Log In!">
		</div>
		<!-- <p>Not registered? <a class="toggleForms">Sign Up</a></p> -->
		</form>
</div>
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










