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



}