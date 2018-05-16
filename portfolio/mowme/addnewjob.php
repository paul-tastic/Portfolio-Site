<?php

	session_start();
	$navbarMessage = "Site under development - some features may not work at times.";
	
	if (array_key_exists("logout", $_POST)) {
		//unset($_SESSION);
		setcookie("user", "", time() - 60*60);
		//$_COOKIE["user"] = "";
		header("Location: index.php");
	}

	$error = "";
	$link = mysqli_connect("localhost", "nerdypil_user1", "password47", "nerdypil_mowme");
	$query = "SELECT * from users WHERE email = '".$_SESSION["email"]."' LIMIT 1";
	$result = mysqli_query($link, $query);
	$user = mysqli_fetch_array($result);

	

?>

<!doctype html>
<html lang="en">
  <head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css' rel='stylesheet'/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">


    <link rel="stylesheet" type="text/css" href="mowme-styles.css">
   
    <title>New Job Request Page</title>
	<style type="text/css">
	    body {
    		background: url(images/grass-50.png) no-repeat center center fixed; 
  			-webkit-background-size: cover;
  			-moz-background-size: cover;
  			-o-background-size: cover;
  			background-size: cover;
    	}
    	#main-window {
    		margin-top: 70px;
    	}
    	#job-display {
    		
    	}
    	#right-actions {
    		text-align: center;
    	}
    	.right {
    		float: right;
    		text-align: right;
    	}
    	#user-actions-pane {
    		justify-content: center;
    		padding: 5px 20px 0 20px;
    	}
    	#user-alert {
    		color: grey;
    		display: none;
    	}
    	#job-table {
    		width: 100%;
    	}
    	#job-table-div {
    		padding: 0px;
    	}
    	.user-button {
    		margin: 20px auto;
    	}
    	#change-password {
    		float: left;
    	}
    	::-webkit-input-placeholder { /* Chrome/Opera/Safari */
  			text-align: left;
		}
		::-moz-placeholder { /* Firefox 19+ */
		  	text-align: left;
		}
		:-ms-input-placeholder { /* IE 10+ */
		  	text-align: left;
		}
		:-moz-placeholder { /* Firefox 18- */
		  	text-align: left;
		}
		.eighty {
			width: 80%;
			margin: 0 auto;
		}
		#property-details {
			clear: left;
			float: left;
			text-align: left;
			
			margin-bottom: 25px;
		}
		.form-control {
			text-align: left;
		}
		#google-maps-insert {
			margin: 15px auto;
			width: 450px;
			height: 450px;
			background-color: rgba(255, 255, 255, 0.3);
			border: 1px solid gray;
			
		}
		#exit-option-row {
			display: inline-block;
			text-align: center;
		}
		#exit-option-row .btn {
			margin: 5px;
		}
		#logo-link {
			color: black;
		}

		#renderMapButton {
			display: inline;
			float: right;
			padding-left: 10px;
			margin-left: 5px;
			
		}



</style>

</head>

  <body>
  	<nav class="navbar fixed-top nav-border-user bg-light" id="navbar">
  		<a href="userpage.php" id="logo-link">
	  		<span class="navbar-text right" id="user-login">
	  			<?php 
	  			echo "<i class='fa fa-user user-icon'></i>".$user['name'];
	  			?>
	  		</span>
  		</a>
  		<span id="navbarMessage"><?php echo $navbarMessage ?></span>
  		<form method="POST">
  			<button class="btn btn-outline-primary my-2 my-sm-0" name="logout" type="submit">Log Out</button>
  		</form>
	</nav>

	<div class="container-fluid" id="main-window">

		
		<div class="row" id="main-row">

<!-- LEFT SIDE -->
		  <div class="col-md-6" id="property-details">
			<h4>Job Details</h4>
			<p>Use this form to enter specific property details, use notes entry for specific requests.</p>
		
			<!-- user details -->
			<form method="POST" name="jobDetails">
				<div class="form-row">
			  		<div class="form-group col-md-12">
			    		<label for="inputAddress">Address</label>
			    		<input type="text" class="form-control" name="address" id="inputAddress" placeholder="1234 Main St">
			  		</div>
			  	</div>
			  <div class="form-row">
			    <div class="form-group col-md-6">
			      <label for="inputCity">City</label>
			      <input type="text" name="city" class="form-control" id="inputCity">
			    </div>
			    <div class="form-group col-md-4">
			      <label for="inputState">State</label>
			      <select id="inputState" name="state" class="form-control">
			        <option selected>Choose...</option>
			        <option>Texas</option>
			      </select>
			    </div>
			    <div class="form-group col-md-2">
			      <label for="inputZip">Zip</label>
			      <input type="text" name="zip" class="form-control" id="inputZip">
			    </div>
			  </div>

			<div class="form-row">
				<div class="form-group col-md-6">
			      <label for="inputPropertyType">Property Type</label>
			      <select id="inputPropertyType" name="prop_type" class="form-control">
			        <option selected>Choose...</option>
			        <option>Single Family Residence</option>
			        <option>Multi Family Residence</option>
			        <option>Vacant Lot</option>
			        <option>Commercial Property</option>
			      </select>
			    </div>
			    <div class="form-group col-md-6">
			      <label for="inputPropertySize">Rough Property Size</label>
			      <select id="inputPropertySize" name="rough_size" class="form-control">
			        <option selected>Choose...</option>
			        <option>Very Small - garden home</option>
			        <option>Small - small residential</option>
			        <option>Normal - normal residence size</option>
			        <option>Large - large residential</option>
			        <option>commercial/other</option>
			      </select>
			    </div>
			</div>
			<div class="row">
				<div class="form-group col-md-6">
					Service Date Requested: <input type="text" name="requested_date" id="datepicker">
				</div>
			</div>
			 <div class="form-row">

			    <div class="form-group col-md-12">
			      <label for="inputNotes">Notes</label>
			      <textarea type="textarea" name="notes" rows="2" wrap="hard" class="form-control" id="inputNotes"></textarea> 
			    </div>
			    <div class="form-group" id="propdetailsbutton">
			  		<input type="hidden" name="signup" value="1">
			  		<button type="submit" name="renderMapButton" class="btn btn-primary" id="renderMapButton">Search for Property Imagery</button>
			  	</div>
			</div>
		</form>
		</div> <!-- left side -->
		


		<!-- ************************************************************* -->

		<!-- RIGHT SIDE -->
		<div class="col-md-6" id="google-maps-pane">
			<div class="row" id="google-maps-insert">

			<?php 
			
				// when user enters new job details and hits submit button
			if (array_key_exists("renderMapButton", $_POST)) {
			

				$formAddress = $_POST['address'];
				$formZip = $_POST['zip'];
				echo "<img src='https://maps.googleapis.com/maps/api/staticmap?
								center=$formAddress,$formZip
								&zoom=20
								&size=450x450
								&markers=size:mid%7Ccolor:red%7C$formAddress,$formZip
								&maptype=satellite
								&key=AIzaSyA0ku5Cz1wzKlDseUA-JV2EW4FQUDeYUnA'>";

					if (mysqli_connect_error()) {
						die("Database connection error.");
					} 
				// assign escaped variables
				$user_id = $user['id'];
				$status = "awaiting bids";
				// connect to requests table
				$query2 = "INSERT into requests (`user_id`, `address`, `city`, `state`, `zip`, `prop_type`, `rough_size`, `requested_date`, `status`, `notes`) 
				VALUES (
				'".$user_id."', 
				'".mysqli_real_escape_string($link, $_POST['address'])."', 
				'".mysqli_real_escape_string($link, $_POST['city'])."', 
				'".mysqli_real_escape_string($link, $_POST['state'])."', 
				'".mysqli_real_escape_string($link, $_POST['zip'])."', 
				'".mysqli_real_escape_string($link, $_POST['prop_type'])."', 
				'".mysqli_real_escape_string($link, $_POST['rough_size'])."', 
				'".mysqli_real_escape_string($link, $_POST['requested_date'])."', 
				'".$status."',
				'".mysqli_real_escape_string($link, $_POST['notes'])."')";

				
				if (!mysqli_query($link, $query2)) {
						$error = "<p>Could not sign you up - Please try again.</p>";
				} else {
					// I don't know what else I should do now here... oh, I know... let's redirect to userpage!
				}
			}


			?>
		
			
	</div>
			<div class="row align-baseline">
				<div class="col-md-12" id="exit-option-row">
						<a href="userpage.php" class="btn btn-outline-danger" tabindex="-1" role="button" aria-disabled="true">
	  						Cancel Request
						</a>
						<a href="userpage.php" class="btn btn-outline-success" tabindex="-1" role="button" aria-disabled="true">
	  						Submit Request
						</a>
					
				</div>
			</div>
		</div> 	
				
	</div><!-- row -->
</div><!-- main window -->
</body>
		<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
   		<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
		<script type="text/javascript">
			'use strict';
			$(function() {
		    	$("#datepicker").datepicker();
		  	});
		</script>

    
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

    
	
  </body>
</html>
	

