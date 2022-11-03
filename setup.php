<?php
// Register the autoloader
spl_autoload_register(function($classname) {
    	include "classes/$classname.php";
	});