<?php

/**
 * times.php
 * 
 * by Alex Spivakovsky
*/

require_once('../model/model.php');

if (isset($_POST["times"]))
{
    $data = getTimes($_POST["times"]);

    print json_encode($data);
} 

?>
