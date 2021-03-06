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
      
            function addMarkers(stations) {
		var number_of_markers = stations.length/8;
		var station_name = "";
		var station_abbr = "";
		var latitude = 0;
		var longitude = 0;
		
		for (var index = 0; index < stations.length; index++) {
		    
		    station_name = stations[index];
		    station_abbr = stations[++index];
		    latitude = parseFloat(stations[++index]);
		    longitude = parseFloat(stations[++index]);

                    marker = new google.maps.Marker({
                        position: new google.maps.LatLng(latitude, longitude),
		        title: station_name,
		        cursor: station_abbr
            	    });

          	    marker.setMap(map);

		    addInfoWindow(marker);

		    index = index + 4;
		}
            }

      
            function addPolylines(departing, destination) {
		var polylines = new Array();		
		var addPolyline = false;
		var j = 0;

		for (var i = 0; i < stations.length; i++) {

		    if (departing == stations[i]) {
			addPolyline = true;
		    }

		    if (destination == stations[i]) {
			addPolyline = false;
			i = i + 2;
			polylines[j++] = stations[i];
			polylines[j] = stations[++i];
			
			break;
		    }

		    if (addPolyline == true) {
			i = i + 2;
			polylines[j++] = stations[i];
			polylines[j++] = stations[++i];
			i = i+4;
		    }
		}
		
                var polylineCoordinates = new Array();

		var m = 0;
		for (var k = 0; k <= j; k++) {
                    polylineCoordinates[m] = new google.maps.LatLng(polylines[k], polylines[++k]);

		    m++;
		}	
            
                var polylinePath = new google.maps.Polyline({
                    path: polylineCoordinates,
                    strokeColor: "#FF0000",
                    strokeOpacity: 1.0,
                    strokeWeight: 2
                }); 
        
                polylinePath.setMap(map);
            }
    
            function addInfoWindow(marker) {
		 var station_info = new Array();
		$.ajax({
        	    type: 'POST',
        	    url: "times.php",
        	    data: { times: marker.cursor },
        	    success: function (response) { station_info = response;
						   station_info = station_info.replace(/[\[\]\\\""{}\,]+/g, '');

						   var infowindow = new google.maps.InfoWindow({
                    				   content: station_info,
                    				   position: new google.maps.LatLng(marker.getPosition().lat(), marker.getPosition().lng()), 
                				   });

                				   google.maps.event.addListener(marker, 'click', function() {
                    				   infowindow.open(map,marker);
                				   }); 
						 }
		});
		
            }

	    function getStationInfo() {
    		$.ajax({
        	    type: 'POST',
        	    url: "ajax.php",
        	    data: { station_names:'yes' },
        	    success: function (response) {
						     stations = response; 
						     stations = stations.replace(/[\[\]\\\""{}]+/g, '');
						     stations = stations.split(',');

						     addMarkers(stations);

						     for (var x = 0; x < stations.length;) {

							 if ((x!=0) && (x%8!=0)) {
							     x = x + 7;
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

		addPolylines(checked[0], checked[1]);
	    }

        </script>
    </head>
    <body onload="initialize();getStationInfo();addPolylines();" onsubmit="onSubmit();">
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
