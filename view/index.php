<!-- 
index.html
by Alex Spivakovsky 
-->

<!DOCTYPE html>
<html>
    <head>
        </br>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
        <style type="text/css">
            html { height: 100% }
            body { height: 100%; background-color: #66FF66; }
            #map_canvas { height: 100%; margin-left: 250px; margin-right: 200px; padding: 0; }
            #select_route {width:20%;height:200px;}
        </style>
	<?php

	    require_once('../model/model.php');

    	    $error = write_station_info_to_db();

    	    if ($error != false)
    	    {
		print $error;
    	    }

	?>    
	<script type="text/javascript" 
	    src="http://maps.googleapis.com/maps/api/js?sensor=false" type="text/javascript">
	</script>
        <script type="text/javascript"
	    src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript">
        </script>
        <script type="text/javascript" type="text/javascript">
            var map = "";
            var marker = "";
	    var stations = new Array();

            // draw menu
            function initialize() {

		// clear the former content of a given div
		document.getElementById('menu').innerHTML = '';

                var mapOptions = {
                    center: new google.maps.LatLng(37.775362, -122.417564),
                    zoom: 12,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                map = new google.maps.Map(document.getElementById("map_canvas"),
                mapOptions);
            }
      
            function addMarker() {
                marker = new google.maps.Marker({
                position: new google.maps.LatLng(37.775362, -122.417564),
                title: "I am a marker!",
		cursor: "String"
            });
          
                marker.setMap(map);
            }
      
            function addPolyline() {
                var polylineCoordinates = [
                    new google.maps.LatLng(37.775362, -122.417564),
                    new google.maps.LatLng(37.7849, -122.4522),
                    new google.maps.LatLng(37.7805, -122.4725),
                ];
            
                var polylinePath = new google.maps.Polyline({
                    path: polylineCoordinates,
                    strokeColor: "#FF0000",
                    strokeOpacity: 1.0,
                    strokeWeight: 2
                }); 
        
                polylinePath.setMap(map);
            }
    
            function addInfoWindow() {
                var infowindow = new google.maps.InfoWindow({
                    content: "<h1 style='color:blue'>I am an info window!</h1>",
                    position: new google.maps.LatLng(37.7805, -122.4725), 
                });
        
                google.maps.event.addListener(marker, 'click', function() {
                    infowindow.open(map,marker);
		    alert(marker.cursor);
                });
            }

	    function getStationNames() {
    		$.ajax({
        	    type: 'POST',
        	    url: "ajax.php",
        	    data: { station_names:'yes' },
        	    success: function (response) {
						     stations = response; 
						     stations = stations.replace(/[\[\]\\\""{}]+/g, '');
						     stations = stations.split(',');

						     //var x = 0;
						     for (var x = 0; x < stations.length;) {

							 if ((x!=0) && (x%8!=0)) {
							     x++;
							     continue;
							 }

						         // create the necessary element
	    					         var label= document.createElement("label");
	    					         var description = document.createTextNode(stations[x]);
	    					         var checkbox = document.createElement("input");

	    					         checkbox.type = "checkbox";
	    					         checkbox.name = stations[x];
	    					         checkbox.value = "checked";

	    					         label.appendChild(checkbox);
	    					         label.appendChild(description);

							 // document.getElementById(stations[x]).innerHTML = stations[x] + "Hi";

	    					         // add the label element to your div
	    					         document.getElementById('menu').appendChild(label);

							 x++;
						     }
						 },
    	        });
	    }

	    function onSubmit() {
		var checked = new Array();
		var i = 0;
		$("input").each(function () {
		    if ($(this).is(':checked')) {
			checked[i] = this.name;
			i++;
		    }
	        });

		if ((checked.length > 2) || (checked.length < 2)) {
		    alert("Please select exactly 2 stations for a drawing of an itinerary.");

		    return;
		}

		drawItinerary(checked[0], checked[1]);
	    }

	    function drawItinerary(station1, station2) {
		var stats = station1 + "," + station2;
		$.ajax({
        	    type: 'POST',
        	    url: "coordinates.php",
        	    data: { get_coordinates:stats },
        	    success: function (coordinates) {
			alert(coordinates);
		    },
	        });
	    }

        </script>
    </head>
    <body onload="initialize();addPolyline();addMarker();addInfoWindow();getStationNames();" onsubmit="onSubmit();">
	<h1 align=center>Sun Francisco Bay Transit Map</h1>
        <div id="map_canvas" style="width:60%; height:80%"></div>
	<div id="menu"></div>
	<br>
	<br>
	<div align=center onclick="onSubmit();">
	    <input type=submit name=submit value="Draw Itinerary">
	</div>
	<br>
	<br>
    </body>
</html>
