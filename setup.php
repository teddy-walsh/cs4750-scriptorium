<?php

spl_autoload_register(function($classname) {
    	include "classes/$classname.php";
	});

// mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// try {  
//    $username = getenv('DB_USER');
//    $password = getenv('DB_PASS');
//    $dbName = getenv('DB_NAME');
//    $instanceUnixSocket = getenv('INSTANCE_UNIX_SOCKET');

//     // Connect using UNIX sockets
//     $dsn = sprintf(
//         'mysql:dbname=%s;unix_socket=%s',
//         $dbName,
//         $instanceUnixSocket
//     );

//     // Connect to the database.
//     $db = new PDO($dsn, $username, $password,
//         # [START_EXCLUDE]
//         // Here we set the connection timeout to five seconds and ask PDO to
//         // throw an exception if any errors occur.
//         [
//             PDO::ATTR_TIMEOUT => 5,
//             PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//         ]
//         # [END_EXCLUDE]
//     );
   
//    // dispaly a message to let us know that we are connected to the database 
//    echo "<p>You are connected to the database --- host=$instanceUnixSocket</p>";
// }
// catch (PDOException $e)     // handle a PDO exception (errors thrown by the PDO library)
// {
//    // Call a method from any object, use the object's name followed by -> and then method's name
//    // All exception objects provide a getMessage() method that returns the error message 
//    $error_message = $e->getMessage();        
//    echo "<p>An error occurred while connecting to the database: $error_message </p>";
// }
// catch (Exception $e)       // handle any type of exception
// {
//    $error_message = $e->getMessage();
//    echo "<p>Error message: $error_message </p>";
// }