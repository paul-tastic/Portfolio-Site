<?php


	session_start();
	$navbarMessage = "Site under development - some features may not work at times.";
	

	if (array_key_exists("logout", $_POST)) {
		//unset($_SESSION);
		setcookie("user", "", time() - 60*60);
		//$_COOKIE["user"] = "";
		header("Location: index.php");
	}

	$jobID = $_GET['ref'];

	$error = "";
	$link = mysqli_connect("localhost", "nerdypil_user1", "password47", "nerdypil_mowme");

	// connect up with database and get user information
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
    <link rel="stylesheet" type="text/css" href="mowme-styles.css">
    <title>Job Detail Page</title>
<style type="text/css">

	    body {
    		background: url(images/grass-50.png) no-repeat center center fixed; 
  			-webkit-background-size: cover;
  			-moz-background-size: cover;
  			-o-background-size: cover;
  			background-size: cover;
    	}
    	#main-window {
    		margin-top: 100px;
    		text-align: center;
    	}
    	#job-display {
    		
    	}

    	#right-actions {
    		text-align: center;
    	}

    	#job-pane {
    		background-color: lightyellow;
    		
    		width: 90%;
    		margin: 0 auto;
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


		#map-image-pane {
			margin: 20px auto;
			width: 250px;
			height: 250px;
		
		}

		.choices {
			width: 90%;
			margin: 15px;
		}

		#screen-tip {
			width: 100%;
			text-align: center;
			margin-top: 50px;
		}
		#logo-link {
			color: black;
		}

		textarea {
			resize: none;
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
	<div class="row align-items-start">
		<div class="col-md-6">
			<!-- user details -->
			<form id="job-display">
				<h4>Job Request Details</h4>

<?php

	$query = "SELECT * from requests WHERE id = '".$jobID."' LIMIT 1";
	$result = mysqli_query($link, $query);
	$job = mysqli_fetch_array($result);
	// error check connection

	if(isset($_POST['cancelJob'])) {
			// get id of bid to delete
			$delID = $job['id'];
			// delete bid
			$query = "DELETE FROM requests WHERE id = '".$delID."' LIMIT 1";
			if(mysqli_query($link, $query)) {
				echo "request deleted successfully";
			} else {
				echo "error deleting request ".mysqli_error($link);
				}
				header('Location: userpage.php');
			}

?>


			  <div class="form-group row">
			    <div class="col-sm-7">
			    	<?php echo '<input type="text" readonly class="form-control" id="staticMowAddress1" placeholder="'.$job["address"].'"
			       readonly>' ?>
			    </div>
			    <div class="col-md-5">
					    <div>
					    	<?php echo 
					      '<input type="password" class="form-control" id="staticPropertyType" placeholder="'.$job["prop_type"].'" readonly>' ?>
					    </div>
				  </div>
			  </div>
			  <div class="form-row">
				  <div class="form-group col-md-6">
					    <div>
					    	<?php echo 
					      '<input type="password" class="form-control" id="staticMowCity" placeholder="'.$job["city"].'" readonly>' ?>
					    </div>
				  </div>
			  	<div class="form-group col-md-3">
				    <div>
				    	<?php echo 
				      '<input type="password" class="form-control" id="staticMowState" placeholder="'.$job["state"].'" readonly>' ?>
				    </div>
			  </div>
			  <div class="form-group col-md-3">
				    <div>
				    	<?php echo 
				      '<input type="password" class="form-control" id="staticMowZip" placeholder="'.$job["zip"].'" readonly>' ?>
				    </div>
			  </div>
			</div>
			  <div class="form-row">
				  
			  	<div class="form-group col-md-6">
				    <div>
				    	<?php echo 
				      '<input type="password" class="form-control" id="staticRoughSize" placeholder="'.$job["rough_size"].'" readonly>' ?>
				    </div>
			  </div>
			  <div class="form-group col-md-3">
				    <div>
				    	<?php echo 
				      '<input type="password" class="form-control" id="staticSizeOfLot" placeholder="'.$job["size_of_lot"].'" readonly>' ?>
				    </div>
			  </div>
			  <div class="form-group col-md-3">
				    <div>
				    	<?php echo 
				      '<input type="password" class="form-control" id="staticDayRequested" placeholder="'.$job["requested_date"].'" readonly>' ?>
				    </div>
			  </div>
			</div>
			<div class="form-row">
				  <div class="form-group col-md-12">
					    <div>
					    	<?php echo 
					      '<textarea type="password" rows="2" class="form-control" id="staticNotes" placeholder="'.$job["notes"].'" readonly></textarea>' ?>
					    </div>
				  </div>
				</div>
			</form>
		</div>

		<!-- ************************************* -->
		<!-- RIGHT SIDE -->
		
		<div class="col-md-6" id="image-side">
			<div class="row">

			<div class="col-md-5" id="map-image-pane">
				<?php 
				$address = $job['address'];
				$zip = $job['zip'];
				echo "<img src='https://maps.googleapis.com/maps/api/staticmap?
								center=$address,$zip
								&zoom=19
								&size=250x250
								&markers=size:mid%7Ccolor:red%7C$address,$zip
								&maptype=satellite
								&key=AIzaSyA0ku5Cz1wzKlDseUA-JV2EW4FQUDeYUnA'>";
				?>
				<div class="row" id="map-insert-placeholder-text">
					
				</div>
			</div> 
			<div class="col-md-5">
				
					<a href="userpage.php" class="btn btn-outline-primary choices">
  						Back to Dashboard
					</a>
					<form method="POST">
						<button name="cancelJob" class="btn btn-outline-danger choices">
  						Cancel Job Request
						</button>
					</form>
			</div>
		</div>
	</div>
</div>
	<h4>Bids (see all)</h4>
	<div class="eighty"><hr></div>
<div class="row" id="job-pane">

	<div class="col-md-12" id="job-table-div">

		<table class="table table-bordered table-hover" id="job-table">
		  <thead>
		    <tr>
		      <th scope="col">Company</th>
		      <th scope="col">Rating</th>
		      <th scope="col">Proposed Date</th>
		      <th scope="col">Price</th>
		      <th scope="col">Offer Expires</th>
		      <th scope="col">Notes</th>
		    </tr>
		  </thead>
		  <tbody>


		 <?php 

		 	if(isset($_POST['decline'])) {
		 		$decID = $_POST['decline'];
		 		if (!$link) {
		 			die("database connection failure, please refresh the page.");
		 		}
		 		$queryDecline = "UPDATE bids SET status='declined'  WHERE id = '".$decID."';";
		 		$resultDecline = mysqli_query($link, $queryDecline);
		 		if (mysqli_connect_error()) {
					die("Database connection error.");
				}
				// close DB connection
		 	}

		 	if(isset($_POST['accept'])) {
		 		$accID = $_POST['accept'];
		 		if (!$link) {
		 			die("database connection failure, please refresh the page.");
		 		}
		 		$queryAccept = "UPDATE bids SET status = 'bid accepted'  WHERE id = '".$accID."';";
		 		$resultAccept = mysqli_query($link, $queryAccept);
		 		if (mysqli_connect_error()) {
					die("Database connection error.");
					}

				// update request status to scheduled
				$queryBid = "SELECT request_id from bids WHERE id = '".$accID."';";

				$resultBid = mysqli_query($link, $queryBid); 
				$request = mysqli_fetch_assoc($resultBid);
				$reqID = $request['request_id'];

				$queryRequest = "UPDATE requests SET status = 'scheduled' WHERE id = '".$reqID."';";
				$resultRequest = mysqli_query($link, $queryRequest);

				if (mysqli_connect_error()) {
					die("Database connection error.");
				}
		 	}

		  	$query = "SELECT * FROM bids WHERE request_id = '".$jobID."';";
		  	$result = mysqli_query($link, $query);
			//$bid = mysqli_fetch_array($result);
				
		  	 while($bid = mysqli_fetch_array($result)) {
		  	 	//if not declined status, display row
		  	 	if ($bid['status'] != 'declined') {
		  	 		// if job accepted, display highlighted button
		  	 		$viewButtonStyle = 'btn btn-outline-success';
		  	 		$declineButtonStyle = 'btn btn-outline-danger';
		  	 		$disabled = "";
		  	 		if ($bid['status'] == 'bid accepted') {
		  	 			$viewButtonStyle = 'btn btn-success';
		  	 			$declineButtonStyle = 'btn btn-outline-light';
		  	 			$disabled = "disabled";
		  	 		}

		  		 	$query2 = "SELECT * FROM contractors WHERE id = '".$bid['contractor_id']."' LIMIT 1;";
		  			$result2 = mysqli_query($link, $query2);
		  			$contractor_details = mysqli_fetch_array($result2);
		  			$bidID = $bid['id'];
		  	
			  		echo "<tr>
			  			<td>".$contractor_details['company_name']."</td>
			  			<td>".$contractor_details['rating']."</td>
			  			<td>".$bid['day_offered']."</td>
			  			<td>$ ".$bid['price']."</td>
			  			<td>".$bid['expires']."</td>
			  			<td>".$bid['notes']."</td>
			  			<td>
			  				<form method='POST'>
			  				<button type='submit' name='accept' value='$bidID' class='$viewButtonStyle'>accept</button>
			  				<button type='submit' name='decline' value='$bidID' class='$declineButtonStyle' $disabled>decline</button>
			  				</form>
			  			</td>

			  			</tr>";
		  		}
		  	 }
		  	echo "</tbody>";
		  	echo "</table>";
		?>

	</div>
</div>


</div> <!-- Container -->


    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
  </body>
</html>


