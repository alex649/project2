<?php

/**
 * coordinates.php
 * 
 * by Alex Spivakovsky
*/

require_once('../model/model.php');

if (isset($_POST["get_coordinates"]))
{
    // $data = getStationCoordinates($_POST["get_coordinates"]);

    $data = $_POST["get_coordinates"];

    print $data;

    //print "station1 : ". $data["station1"]; 

    // print json_encode($data);
}  

?>
