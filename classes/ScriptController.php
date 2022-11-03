<?php

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
            case "script-post":
                $this->scriptPost();
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

        $list_of_scripts = $this->db->get_all_scripts();
    
        include "templates/home.php";
    }


    // Manage the page for the login page
    public function login() {
        if (isset($_POST["username"])) {
            $authenticate = $this->db->user_login($_POST["username"], $_POST["password"]);
            if ($authenticate) {
                echo "Welcome";
            } else {
                $message = "<div class='alert alert-danger'>Unable to authenticate.</div>";
            }
        }
    
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
                $insert = $this->db->add_user($_POST["email"], $_POST["username"], 
                                    password_hash($_POST["password"], PASSWORD_DEFAULT));

                if ($insert === false) {
                    $message = "<div class='alert alert-danger'>Error inserting new user.</div>";
                } else { // user successfully created
                    $new_id = $this->db->get_user_id($_POST["username"]);
                    $_SESSION["username"] = $_POST["username"];
                    $_SESSION["id"] = $new_id["id"];
                    header("Location: ?command=home");
                }
            }
        }
        include "templates/account-create.php";
    }

    public function scriptPost() {
        // echo "<pre>";
        //     print_r($_POST);
        // echo "</pre>";
        if (isset($_POST["script"])) { 

            $insert = $this->db->post_new_script($_POST["title"], $_POST["description"], 
                $_POST["script"], $_POST["genre"], $_SESSION["id"]);

            // Checks if something went wrong adding the script.
            if ($insert === false) {
                    $message = "<div class='alert alert-danger'>Error inserting new user.</div>";
                }
            else {
                // all went well
                echo "Script accepted!";
            }
        } 

        include "templates/script_post.php";
    }



}