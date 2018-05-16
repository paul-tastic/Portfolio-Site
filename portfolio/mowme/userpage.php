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
	    <title>User Landing Page</title>
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
    	#user-display {
    		
    	}

    	#right-actions {
    		text-align: center;
    	}

    	#history-pane {
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
		#user-alert {
    		width: 100%;
    		height: 50px;
    		visibility: hidden;
    		padding: 0;
    	}

</style>
  </head>
  <body>
  	<nav class="navbar fixed-top nav-border-user bg-light" id="navbar">
  		<span class="navbar-text right" id="user-login">
  			<?php 
  			echo "<i class='fa fa-user user-icon'></i>".$user['name'];
  			?>
  		</span>
  		<span id="navbarMessage"><?php echo $navbarMessage ?></span>
  		<form method="POST">
  			<button class="btn btn-outline-primary my-2 my-sm-0" name="logout" type="submit">Log Out</button>
  		</form>
	</nav>


<div class="container-fluid" id="main-window">
	<div class="row align-items-start">
		<div class="col-md-5">
			<!-- user details -->
			<form id="user-display">
				<h4>Your Profile Details</h4>
			  <div class="form-group row">
			    <label for="staticName" class="col-sm-2 col-form-label right">Name: </label>
			    <div class="col-sm-10">
			    	<?php echo '<input type="text" readonly class="form-control" id="staticName" placeholder="'.$user["name"].'"
			       readonly>' ?>
			    </div>
			  </div>
			  <div class="form-group row">
			    <label for="staticEmail" class="col-sm-2 col-form-label right">Email: </label>
			    <div class="col-sm-10">
			    	<?php echo 
			      '<input type="password" class="form-control" id="staticEmail" placeholder="'.$user["email"].'" readonly>' ?>
			    </div>
			  </div>
			  <div class="form-group row">
			    <label for="staticPhone" class="col-sm-2 col-form-label right">Phone: </label>
			    <div class="col-sm-10">
			    	<?php echo 
			      '<input type="text" readonly class="form-control" id="staticPhone" placeholder="'.$user['phone'].'" readonly>' ?>
			    </div>
			  </div>
			  <div class="form-group row">
			    <label for="inputPassword" class="col-sm-2 col-form-label right">Password: </label>
			    <div class="col-sm-10">
			    	<input type="text" readonly class="form-control" id="inputPassword" placeholder="*********">
			    	<button type="button" class="btn-info btn" id="edit-acct-button">edit account details</button>
			    </div>
			  </div>
			</form>

		</div>
		<div class="row col-md-7" id="user-actions-pane">
			<div class="alert alert-warning" id="user-alert" role="alert">
  				Hidden placeholder to show messages when needed.
			</div>
			<div id="user-buttons">
				<a href="addnewjob.php" class="btn btn-success btn-lg btn-block user-button">Request mowing service</a>
			</div>
		</div>
	</div>
	<h4>Current Job Requests</h4>
	<div class="eighty"><hr></div>

<div class="row" id="history-pane">

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
		  	$query = "SELECT * FROM requests WHERE user_id = ".$user['id'].";";
		  	$result = mysqli_query($link, $query);
		  	 while($row = mysqli_fetch_array($result)) {
		  		$requestStatus = $row['status'];
		  	 	$jobID = $row['id'];

		  	 	// check if job is 'active' and has bids, output under status column if it does
		  	 	$queryBidAmt = "SELECT * from bids WHERE request_id = '".$row['id']."';";
		  	 	$resultBidAmt = mysqli_query($link, $queryBidAmt);
		  	 	if ((mysqli_num_rows($resultBidAmt) > 0) AND $requestStatus == 'awaiting bids') {
		  	 		$requestStatus = mysqli_num_rows($resultBidAmt). " bid(s) received";
		  	 	}
		  	 	
		  		echo "<tr>
		  			<td>".$row['id']."</td>
		  			<td>".$row['address']."</td>
		  			<td>".$row['zip']."</td>
		  			<td>".$row['rough_size']."</td>
		  			<td>".$requestStatus."</td>
		  			<td><a href='jobpage.php?ref=$jobID' class='btn btn-outline-primary'>view</a></td>
		  			</tr>";
		  	 }
		  	echo "</tbody>";
		  	echo "</table>";


		  ?>

	</div>


</div>


</div>


    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
  </body>
</html>