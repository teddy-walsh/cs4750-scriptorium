<?php
class Database {

    private $pdo;

    public function __construct() {
            $username = getenv('DB_USER'); // e.g. 'your_db_user'
            $password = getenv('DB_PASS'); // e.g. 'your_db_password'
            $dbName = getenv('DB_NAME'); // e.g. 'your_db_name'
            $instanceUnixSocket = getenv('INSTANCE_UNIX_SOCKET'); // e.g. '/cloudsql/project:region:instance'

            // Connect using UNIX sockets
            $dsn = "mysql:unix_socket=$instanceUnixSocket;dbname=$dbname";

               // $username = 'root';
               // $password = 'vHdgxfiy+BLZp!8T6';
               // $host = '34.145.156.4';
               // $dbname = 'scriptorium';
               // $dsn = "mysql:host=$host;dbname=$dbname"; 
        try {
            // Connect to the database.
            $this->pdo = new PDO($dsn, $username, $password,
                // # [START_EXCLUDE]
                // // Here we set the connection timeout to five seconds and ask PDO to
                // // throw an exception if any errors occur.
                // [
                //     PDO::ATTR_TIMEOUT => 5,
                //     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                // ]
                // # [END_EXCLUDE]
            );
            echo "<p>You are connected to the database</p>";
        } catch (TypeError $e) {
            throw new RuntimeException(
                sprintf(
                    'Invalid or missing configuration! Make sure you have set ' .
                        '$username, $password, $dbName, ' .
                        'and $instanceUnixSocket (for UNIX socket mode). ' .
                        'The PHP error was %s',
                    $e->getMessage()
                ),
                (int) $e->getCode(),
                $e
            );
        } catch (PDOException $e) {
            throw new RuntimeException(
                sprintf(
                    'Could not connect to the Cloud SQL Database. Check that ' .
                        'your username and password are correct, that the Cloud SQL ' .
                        'proxy is running, and that the database exists and is ready ' .
                        'for use. For more assistance, refer to %s. The PDO error was %s',
                    'https://cloud.google.com/sql/docs/mysql/connect-external-app',
                    $e->getMessage()
                ),
                (int) $e->getCode(),
                $e
            );
        }
    }

    function add_user($email, $username, $password) {
        $query = "INSERT INTO users(email, display_name, password)
                            VALUES (:email, :username, :password)";
        echo "Making it to add_user";
        try {
            $statement = $this->pdo->prepare($query);
            $statement->bindValue(':email', $_POST["email"]);
            $statement->bindValue(':username', $_POST["username"]);
            $statement->bindValue(':password', password_hash($_POST["password"], PASSWORD_DEFAULT));
            $statement->execute();

            if ($statement->rowCount() == 0)
                echo "Failed to add a friend <br/>";
            return true;
        }
        catch (PDOException $e) {
            // echo $e->getMessage();
            // if there is a specific SQL-related error message
            //    echo "generic message (don't reveal SQL-specific message)";

            if (strpos($e->getMessage(), "Duplicate")){
                echo "Failed to add a friend <br/>";
            }
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    function get_user_id($username) {
        $query = "SELECT user_id FROM users WHERE (display_name=:username)";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        $result = $statement->fetch();
        return $result; 
    }


}