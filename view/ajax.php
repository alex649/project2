<?php

/**
 * ajax.html
 * AJAX section example by Alain Ibrahim 
*/

/*
error_reporting(0);


if ($_POST["i_want_text"]) {
    print "text received with value of " . $_POST["i_want_text"];
   
}
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
