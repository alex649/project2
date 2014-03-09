<?php

/**
 * ajax.php
 * 
 * by Alex Spivakovsky
*/

require_once('../model/model.php');

if (isset($_POST["station_names"]))
{
    $data = getStationData();

    print json_encode($data);
} 

?>
