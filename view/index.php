<!-- 
index.html
Project 2 sampling by Alain Ibrahim 
-->

<!DOCTYPE html>
<html>
    <head>
    <h1>Sun Francisco Bay Transit Map</h1>
        </br>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
        <style type="text/css">
            html { height: 100% }
            body { height: 100%; margin-left: 300px; padding: 0; background-color: #66FF66; }
            #map_canvas { height: 100% }
      
            #select_route {width:20%;height:200px;}
        </style>
        <script type="text/javascript"
            src="http://maps.googleapis.com/maps/api/js?sensor=false">
        </script>
        <script type="text/javascript">
            var map = "";
            var marker = "";
	    var stations = [];

            // draw menu
            function initialize() {

		// clear the former content of a given <div id="some_div"></div>
		document.getElementById('menu').innerHTML = '';

                var mapOptions = {
                    center: new google.maps.LatLng(37.775362, -122.417564),
                    zoom: 12,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                map = new google.maps.Map(document.getElementById("map_canvas"),
                mapOptions);

		// create the necessary elements
		var label= document.createElement("label");
		var description = document.createTextNode("checkbox");
		var checkbox = document.createElement("input");

		checkbox.type = "checkbox";    // make the element a checkbox
		checkbox.name = "slct[]";      // give it a name we can check on the server side
		checkbox.value = "checked";         // make its value "pair"

		label.appendChild(checkbox);   // add the box to the element
		label.appendChild(description);// add the description to the element

		// add the label element to your div
		document.getElementById('menu').appendChild(label);

		// clear the former content of a given <div id="some_div"></div>
		// document.getElementById('some_div').innerHTML = '';
            }
      
            function addMarker() {
                marker = new google.maps.Marker({
                position: new google.maps.LatLng(37.775362, -122.417564),
                title: "I am a marker!"
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
                });
            }
  
        </script>
    </head>
    <body onload="initialize();addPolyline();addMarker();addInfoWindow();">
        <div id="map_canvas" style="width:60%; height:80%"></div>
	<div id="menu"></div>
    </body>
</html>

<?php

require_once('../model/model.php');

    $error = write_station_info_to_db();

    if ($error != false)
    {
	print $error;
    }
?>    
