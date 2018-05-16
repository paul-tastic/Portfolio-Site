<?php

	session_start();
	$navbarMessage = "Site under development - some features may not work at times.";

	if (array_key_exists("logout", $_POST)) {
		//unset($_SESSION);
		setcookie("user", "", time() - 60*60);
		//$_COOKIE["user"] = "";
		header("Location: index.php");
	}

	// $error = "";
	// $link = mysqli_connect("localhost", "nerdypil_user1", "password47", "nerdypil_mowme");
	// // connect up with database and get user information
	// $query = "SELECT * from contractors WHERE email = '".$_SESSION["email"]."' LIMIT 1";
	// $result = mysqli_query($link, $query);
	// $contractor = mysqli_fetch_array($result);

?>

<!doctype html>
<html lang="en">
  <head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css' rel='stylesheet'/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="mowme-styles.css">
    <title>Search for Jobs Page</title>
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
		#inputPassword {
			width: 240px;
			display: inline;
			float: left;
		}

		#change-password {
			float: right;
		}

		#search-row {
			width: 100%;
			margin: 0 auto;
		}

		#search-button {
			margin-right: 25px;
			float: left;
			width: 35%;
		}

		#logo-link {
			color: black;
		}

		#buttons {
			width: 90%;
		}

		#back-home-button {
			width: 35%;
		}
		

</style>
  </head>
  <body>
  	<nav class="navbar fixed-top nav-border-contractor bg-light" id="navbar">
  		<a href="contractorpage.php" id="logo-link">
	  		<span class="navbar-text right" id="user-login">
	  			<?php 
	  			//echo "<i class='fa fa-user user-icon'></i>".$contractor['company_name'];
	  			?>
	  		</span>
  		</a>
<!--   		<span id="navbarMessage"><?php //echo $navbarMessage ?></span> -->
  		<form method="POST">
  			<button class="btn btn-outline-danger my-2 my-sm-0" name="logout" type="submit">Log Out</button>
  		</form>
	</nav>


<div class="container-fluid" id="main-window">
	<div class="row align-items-start">
		<div class="col-md-12">
			<!-- user details -->
			<form id="search-display">
				<h4>Filter Job Requests</h4>
				<!-- insert inline form with search fields -->
				<div class="col-md-12">
				  <div class="form-row" id="search-row">
					    <div class="col-md-1">
					      <input type="text" class="form-control" name="zip" placeholder="Zip Code">
					    </div>
					    <div class="col-md-2">
					      <select id="inputPropertySize" name="propSize" class="form-control">
					        <option selected>Rough Property Size</option>
					        <option>Very Small - garden home</option>
					        <option>Small - small residential</option>
					        <option>Normal - normal residence size</option>
					        <option>Large - large residential</option>
					        <option>commercial/other</option>
					      </select>
					    </div>
					    <div class="col-md-2">
					      <select id="inputPropertyType" name="propType" class="form-control">
					        <option selected>Property Type</option>
					        <option>Single Family Residence</option>
					        <option>Multi Family Residence</option>
					        <option>Vacant Lot</option>
					        <option>Commercial Property</option>
					      </select>
					    </div>
					    <div class="col-md-2">
					      <select id="inputDateRequested" name="dateWanted" class="form-control">
					        <option selected>Date Requested</option>
					        <option>Jobs for today</option>
					        <option>Jobs for next 7 days</option>
					        <option>Jobs over 7 days away</option>
					      </select>
					    </div>
					    <div class="col" id="buttons">
					      <button type="submit" class="btn btn-outline-success" id="search-button">Search</button>
					      <a href="contractorpage.php" class="btn btn-outline-primary" id="back-home-button">Back to Home Page</a>
					    </div>
					</div>
				  </div>
			</form>
<!-- 			<?php 
			// conduct search results
			//if(isset($_POST['search-button'])) {
			//	$search_zip = $_POST['zip'];
			//	$navbarMessage = .$search_zip;


				}


			?> -->




		</div>
	</div>


	<div class="eighty"><hr></div>
	<h4>Available Jobs</h4>
	<div class="eighty"><hr></div>

	<div class="row" id="job-pane">
		<div class="col-md-12" id="job-table-div">
			<table class="table table-bordered table-hover" id="job-table">
			  <thead>
			    <tr>
			      <th scope="col">job ref #</th>
			      <th scope="col">Address</th>
			      <th scope="col">Zip</th>
			      <th scope="col">Lot Size</th>
			      <th scope="col">Status</th>
			      <th scope="col">Actions</th>
			    </tr>
			  </thead>
			  <tbody>

		<?php
			//$link = mysqli_connect("localhost", "nerdypil_user1", "password47", "nerdypil_mowme");

		  	// $query = "SELECT * FROM requests WHERE status = 'awaiting bids';";
		  	// $result = mysqli_query($link, $query);

		  	// // check connection error

		  	//  while($row = mysqli_fetch_array($result)) {
		  	//  	// if already submitted bid, do not display on this table
		  	//  	$queryBid = "SELECT * FROM bids WHERE request_id = '".$row['id']."' LIMIT 1;"; 
		  	//  	$resultBid = mysqli_query($link, $queryBid);
		  	//  	if (mysqli_num_rows($resultBid) < 1) {
		  	//  		// bid not found, display table row
		  	//  		$jobID = $row['id'];
		  	 	
		  	// 	echo "<tr>
		  	// 		<td>".$row['id']."</td>
		  	// 		<td>".$row['address']."</td>
		  	// 		<td>".$row['zip']."</td>
		  	// 		<td>".$row['rough_size']."</td>
		  	// 		<td>".$row['status']."</td>
		  	// 		<td><a href='viewjobrequestdetails.php?ref=$jobID' class='btn btn-outline-primary'>view</a></td>
		  	// 		</tr>";
		  	//  	}
		  	//  }

		  	 	
		  	 	
		  	// echo "</tbody>";
		  	// echo "</table>";


		  ?>




		</div>
	</div>


</div> <!-- container -->


    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
  </body>
</html>