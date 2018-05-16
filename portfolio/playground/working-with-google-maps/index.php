<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

    <title>Zip Code Finder</title>
  </head>
  <body>

    <div class="container">
      <h1>Zip Code finder</h1>
      <p>Enter a partial address to get the zipcode</p>
      <div id="message"></div>

    <form>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="address">Address</label>
          <input type="text" class="form-control" id="address" placeholder="Enter partial address">
        </div>
     
      <button class="btn btn-primary" id="find-zipcode">Submit</button>
    </form>

    </div>
    
    
    <script
  src="https://code.jquery.com/jquery-3.3.1.js"
  integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
  crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

    <script type="text/javascript">

      $("#find-zipcode").click(function(e) {
          if ($("#address").val() == "") {
            // blank entry
            $("#message").html('<div class="alert alert-warning" role="alert">No entry found. Please try again.</div>');
          } else {



            e.preventDefault();
            $.ajax ({
            url: "https://maps.googleapis.com/maps/api/geocode/json?address=" + encodeURIComponent($("#address").val()) + "&key=AIzaSyAjojkxp0LX9Tm3hydhNdOWLBiTlVTrLFA",
            type: "GET",
            success: function (data) {
              console.log(data);

              if (data["status"] != "OK") {
                  // error message, bad data received
                  $("#message").html('<div class="alert alert-warning" role="alert">Zip code not found! Please try again.</div>');

              } else {



              $.each(data["results"][0]["address_components"], function (key, value) {
                  if (value["types"][0] == "postal_code") {
                    $("#message").html('<div class="alert alert-success" role="alert">Zip code found! The zip code is: ' + value["long_name"]);
                  }


              })
            }
            }
          })
          }
      })


    


    </script>
  </body>
</html>