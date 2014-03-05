<?php

require_once('../model/model.php');

// $data = getStationNames();

// json_encode(array("result" => $data));

$num1 = $_GET['num1'];
$num2 = $_GET['num2'];

echo json_encode(array("result" => $num1 * $num2));

/*
if ($_POST['get_station_names'])
{
    return $data;
}
*/

?>


