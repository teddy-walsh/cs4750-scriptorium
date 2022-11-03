<?php
class Database {

    private $pdo;

    public function __construct() {
            $username = getenv('DB_USER');
            $password = getenv('DB_PASS');
            $dsn = "mysql:unix_socket=/cloudsql/cs4750scriptorium:us-east4:scriptorium-home;dbname=scriptorium"; 
            
        try {
            // Connect to the database.
            $this->pdo = new PDO($dsn, $username, $password);

            // Sets warning messages to ON
            $this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

            //echo "<p>You are connected to the database</p>";
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

    // Adds new accounts via the sign-up page
    function add_user($email, $username, $password) {
        // var_dump($_POST);
        $query = "INSERT INTO users(email, display_name, password) 
                    VALUES (:email, :username, :password)";
        try {
            $statement = $this->pdo->prepare($query);
            $statement->bindValue(':email', $_POST["email"]);
            $statement->bindValue(':username', $_POST["username"]);
            $statement->bindValue(':password', password_hash($_POST["password"], PASSWORD_DEFAULT));
            $statement->execute();
            //echo "Successfully added new user";

            // if ($statement->rowCount() == 0) {
            //     echo "Failed to add a friend <br/>";
            // }

            return true; // this gets checked on the sign-up page to then log in the new user
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

    // returns the user_id for a particular username
    function get_user_id($username) {
        $query = "SELECT user_id FROM users WHERE (display_name=:username)";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        $result = $statement->fetch();
        return $result; 
    }


}