<?php


	session_start();
	$navbarMessage = "Site under development - some features may not work at times.";


	if (array_key_exists("logout", $_POST)) {
		//unset($_SESSION);
		setcookie("user", "", time() - 60*60);
		//$_COOKIE["user"] = "";
		header("Location: index.php");
	}

	//pull job ID from get request
	$bidID = $_GET['ref'];

	$error = "";
	$link = mysqli_connect("localhost", "nerdypil_user1", "password47", "nerdypil_mowme");

	// extra security level, must be logged in as contractor ID and will cross reference with bid # to make sure bid displayed is that contractor's
	$query = "SELECT * from contractors WHERE email = '".$_SESSION["email"]."' LIMIT 1";
	$result = mysqli_query($link, $query);
	$contractor = mysqli_fetch_array($result);
	$contractorID = $contractor['id'];

	//get bid record
	$query = "SELECT * FROM bids where id = '".$bidID."' LIMIT 1"; // add AND contractor ID matches
	$result = mysqli_query($link, $query);
	$bid = mysqli_fetch_array($result);
	$requestID = $bid['request_id'];

	// check to make sure bid's contractor == contractor that is signed in
	if ($contractorID == $bid['contractor_id']) {
		//get job request from bid
		$query = "SELECT * FROM requests WHERE id = '".$requestID."' LIMIT 1";
		$result = mysqli_query($link, $query);
		$job = mysqli_fetch_array($result);
} else {
		$navbarMessage = "contractor ID: ".$contractorID.". Bid Contractor: ".$bid['contractor_id'];
	}
	
	
	// have retrived 




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
    <title>Review Bid Page</title>
	
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

    	#bid-pane {
    		
    		height: 100px;
    		
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

    	#bid-table-div {
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

		#staticNotes {
			height: 75px;
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

		#bid-row {
			
		}

		#submit-bid-button {
			width: 80%;
			margin: 15px auto;
			height: 55px;
		}

		#top-div {
			margin-bottom: 25px;
		}
		#logo-link {
			color: black;
		}

</style>


  </head>
  <body>
  	<nav class="navbar fixed-top nav-border-contractor bg-light" id="navbar">
  		<a href="contractorpage.php" id="logo-link">
	  		<span class="navbar-text right" id="user-login">
  				<?php 
  				echo "<i class='fa fa-user user-icon'></i>".$contractor['company_name'];
  				?>
  			</span>
  		</a>
  		<span id="navbarMessage"><?php echo $navbarMessage ?></span>
  		<form method="POST">
  			<button class="btn btn-outline-danger my-2 my-sm-0" name="logout" type="submit">Log Out</button>
  		</form>
	</nav>


<div class="container-fluid" id="main-window">
	<div class="row align-items-start" id="top-div">
		<div class="col-md-6">
			<!-- user details -->
			<form id="job-display">
				<h4>Job Request Details</h4>

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
					      '<input type="password" class="form-control" id="staticNotes" placeholder="'.$job["notes"].'" readonly>' ?>
					    </div>
				  </div>
				</div>
			</form>
		</div>

		<!-- ************************************* -->
		<!-- RIGHT SIDE -->
		
		<div class="col-md-6" id="image-side">
			<div class="row">

			<div class="col-md-6" id="map-image-pane">

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
			</div> 
			<div class="col-md-6">
				<div class="col-md-12" id="exit-option-row">
					<a href="contractorpage.php" class="btn btn-outline-primary choices">
  						Back to Home Page
					</a>
				</div>
				
			</div>
		</div>
	</div>
</div>
	<h4>Your bid details</h4>
	<div class="eighty"><hr></div>

<div id="bid-row">
	<div class="col-md-12" id="bid-pane">
			<form class="row" id="bidForm" method="POST">
				<div class="form-group col-md-1 offset-md-1 mb-3">
					<label for="price">Your Price: </label>
			      	<input type="text" class="form-control" name="price" id="price" <?php echo 'placeholder="$ '.$bid["price"].'";' ?> readonly>
			    </div>
			    <div class="form-group col-md-2 mb-3">
			    	<label for="serviceDate2">Service Date: </label>
					<input type="text" class="form-control" name="day_offered" id="serviceDate2" <?php echo 'placeholder="'.$bid["day_offered"].'";' ?> readonly>
				</div>
				<div class="form-group col-md-2 mb-3">
			    	<label for="expireDate2">Bid Expires: </label>
					<input type="text" class="form-control" name="expires" id="expireDate2" <?php echo 'placeholder="'.$bid["expires"].'";' ?> readonly>
				</div>
				<div class="form-group col-md-5 mb-3">
				    <label for="bidnotes">Remarks:</label>
				   	<textarea type="textarea" rows="2" wrap="hard" name="notes" class="form-control" id="inputNotes" <?php echo 'placeholder="'.$bid["notes"].'";' ?> readonly></textarea>
				</div>
				
			</form>
		</div>
	
	</div>

	 <!-- data-toggle="modal" data-target="#submitBidButton" id="submit-bid-button" -->


<div class="modal fade" id="submitBidButton" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Bid Submitted</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>This bid is submitted! You can monitor status on your dashboard.</p>
        <p>We will redirect you to your account page</p>
      </div>
      <div class="modal-footer">
        <a href="contractorpage.php" class="btn btn-primary">Got it!</a>
      </div>
    </div>
  </div>
</div>

</div> <!-- Container -->
	<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
		<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
	<script type="text/javascript">
		'use strict';
		$(function() {
	    	$("#serviceDate").datepicker();
	    	$("#expireDate").datepicker();
	  	});
	</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
  </body>
</html>


