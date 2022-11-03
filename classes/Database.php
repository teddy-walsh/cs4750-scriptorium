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
            $statement->bindValue(':email', $email);
            $statement->bindValue(':username', $username);
            $statement->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));
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

    function user_login($username, $password) {
        $query = "SELECT * FROM users WHERE (display_name=:username)";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        $user = $statement->fetch();

        if (!empty($user)) { // it found the user
            // echo "<pre>";
            //     print_r($user);
            // echo "</pre>";
            // echo "<pre>";
            //     print_r($password);
            // echo "</pre>";

            if (password_verify($password, $user["password"])) { // pw good?
                    $_SESSION["username"] = $user["display_name"];
                    $_SESSION["id"] = $user["user_id"];
                    header("Location: ?command=home");
            } else {
                //echo "Not good";
                return false; // gives the error message of unable to authenticate
            }
        }

    }

    // TODO Fill the foreign key table as well
    function post_new_script($title, $blurb, $script, $genre, $user_id) {
        $query = "INSERT INTO scripts(title, blurb, script_body, genre) 
                    VALUES (:title, :blurb, :script, :genre)";
        try {
            $statement = $this->pdo->prepare($query);
            $statement->bindValue(':title', $title);
            $statement->bindValue(':blurb', $blurb);
            $statement->bindValue(':script', $script);
            $statement->bindValue(':genre', $genre);
            $statement->execute();

            return true; // this gets checked on the sign-up page to then log in the new user
        }
        catch (PDOException $e) {
            // echo $e->getMessage();
            // if there is a specific SQL-related error message
            //    echo "generic message (don't reveal SQL-specific message)";

            if (strpos($e->getMessage(), "Duplicate")){
                echo "Failed to add a script <br/>";
            }
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    function get_all_scripts() {
        $query = "SELECT * FROM scripts";
        $statement = $this->pdo->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();   // fetch()
        return $result;
    }


}