<?php 
session_start();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Makes sure the data tables exist.
require "setup.php";

// Register the autoloader
spl_autoload_register(function($classname) {
    include "classes/$classname.php";
});

// Parse the query string for command
$command = "home";
if (isset($_GET["command"]))
    $command = $_GET["command"];

// Instantiate the controller and run
$scriptorium = new ScriptController($command);
$scriptorium->run();

echo "Hellooooooo";
