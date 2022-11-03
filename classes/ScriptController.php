<?php

// include "db-connect.php";

class ScriptController {

	private $command;
	private $db;

	public function __construct($command) {
        $this->command = $command;
        $this->db = new Database();
    }

    public function run() {
        switch($this->command) {
            case "login":
                $this->login();
                break;
            case "account-create":
                $this->accountCreate();
                break;
            case "logout":
                $this->destroySession();
            case "home":
            default:
                $this->home();         
        }
    }

    // Clear the whole session
    private function destroySession() {          
        // Unset all of the session variables.
        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Finally, destroy the session.
        session_destroy();
    }

    // Manages the home page
    public function home() {
    
        include "templates/home.php";
    }


    // Manage the page for the login page
    public function login() {
    
        include "templates/login.php";
    }

    public function accountCreate() {
        if (isset($_POST["email"])) { 
            // password requirements checker
            $password = $_POST["password"];
            $uppercase = preg_match('@[A-Z]@', $password);
            $lowercase = preg_match('@[a-z]@', $password);
            $number    = preg_match('@[0-9]@', $password);

            if(!$uppercase || !$lowercase || !$number || strlen($password) < 8) {
                $message = "<div class='alert-danger'>Passwords must have at least:

                    <ul>
                    <li>One upper-case letter</li>
                    <li>One lower-case letter</li>  
                    <li>One number (0-9)</li>
                    </ul>
                    </div>";
            } else {
                // create a new user
                $query = "INSERT INTO users(email, display_name, password)
                            VALUES (:email, :username, :password)";
                $insert = $this->db->add_user($_POST["email"], $_POST["username"], 
                                    password_hash($_POST["password"], PASSWORD_DEFAULT));

                if ($insert === false) {
                    $message = "<div class='alert alert-danger'>Error inserting new user.</div>";
                } else { // user successfully created
                    $new_id = $this->db->get_user_id($_POST["username"]);
                    $_SESSION["username"] = $_POST["username"];
                    $_SESSION["id"] = $new_id[0];;
                }
            }
        }
        include "templates/account-create.php";
    }



}