<?php 
session_start();

// Register the autoloader
spl_autoload_register(function($classname) {
    include "classes/$classname.php";
});

// Parse the query string for command
$command = "home";
if (isset($_GET["command"]))
    $command = $_GET["command"];


// Instantiate the controller and run
$scripto = new ScriptController($command);
$scripto->run();
