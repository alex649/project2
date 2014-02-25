<?php
/*********************
 * index.php
 *
 * CSCI S-75
 * Project 2
 * Alex Spivakovsky
 *
 * Dispatcher for MVC
 *********************/
session_start();

if (isset($_GET["page"]))
{
    $page = $_GET["page"];
}
else
{
    $page = "index";
}

$path = __DIR__ . '/../controller/' . $page . '.php';

if (file_exists($path))
{
    require($path);
}

?>
