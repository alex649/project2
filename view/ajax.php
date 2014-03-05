<?php

/**
 * ajax.html
 * 
 * by Alex Spivakovsky
*/

require_once('../model/model.php');

if ($_POST["post_made"])
{
    $data = getStationNames();

    /*
    foreach ($data as $result)
    {
        print $result;
    }
    */

    print json_encode($data);

}  
?>
