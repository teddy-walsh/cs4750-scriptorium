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
                $this->newScriptPost();
                break;
            case "fullscript":
                $this->fullscript();
                break;
            case "test":
                $this->test();
                break;
            case "userpage":
                $this->userpage();
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
        $page = 1;
        $sortby = "script_id";
        $order = "ASC";
        $is_more = true;
        if(!empty($_GET['page'])) {
            $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
            if(false === $page) {
                $page = 1;
            }
        }
        if(!empty($_GET['sortby'])) {
            if(false === $page) {
                $sortby = "script_id";
            } else {
              $sortby = $_GET['sortby'];
            }
        }
        if(!empty($_GET['order'])) {
            if(false === $page) {
                $order = "ASC";
            } else {
              $order = $_GET['order'];
            }
        }

        // Right now it just shows the scripts in the submission order.
        $list_of_scripts = $this->db->get_paged_scripts($page, $sortby, $order);
        if (count($list_of_scripts) < 10) {
            $is_more = false;
        } else {
            $is_more = true;
        }

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

    // Lets users create accounts
    // TODO NEED TO ADD THE NAME STUFF
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
                $insert = $this->db->add_user($_POST["email"], $_POST["username"],$_POST["password"]);

                if ($insert === false) {
                    $message = "<div class='alert alert-danger'>Error inserting new user.</div>";
                } else { // user successfully created
                    $new_id = $this->db->get_user_id($_POST["username"]);
                    // var_dump($new_id);
                    $_SESSION["username"] = $_POST["username"];
                    $_SESSION["id"] = $new_id["user_id"];
                    header("Location: ?command=home");
                }
            }
        }
        include "templates/account-create.php";
    }

    // New script post page
    public function newScriptPost() {
        // echo "<pre>";
        //     print_r($_POST);
        // echo "</pre>";
        if (isset($_POST["script"])) { 

            $insert = $this->db->post_new_script($_POST["title"], $_POST["description"], 
                $_POST["script"], $_POST["genre"], $_SESSION["id"]);

            // Checks if something went wrong adding the script. Currently since it's POST
            // it will delete all the input if something goes wrong. Less than ideal.
            if ($insert === false) {
                    $message = "<div class='alert alert-danger'>Error inserting new script.</div>";
                }
            else {
                // all went well
                $message = "<div class='alert alert-success'>Script successfully inserted.</div>";
            }
        } 

        include "templates/script_post.php";
    }

    public function userpage() {
        //check to see if they are properly accessing a userpage
        //should probably check to make sure user is an int
        $info = [];
        if (isset($_GET["user"])) { 
            $user_details = $this->db->get_userpage_info($_GET["user"]);
            if (!empty($user_details)) { // it found the user in userpage
                $info = $user_details;
            }

            // populate their list of scripts
            $list_of_scripts = $this->db->get_all_scripts_by_user($_GET["user"]);

        } else { //GET not set
            header("Location: ?command=home");
        }


    
        include "templates/userpage.php";
    }

    public function fullscript() {
        // gets and displays the script;
        $script = [];
        $owner = "disabled";
        if(!empty($_GET['script'])) {
            $script_id = filter_input(INPUT_GET, 'script', FILTER_VALIDATE_INT);
            if(false === $script_id) {
                header("Location: ?command=home");
            } else {
                $script = $this->db->get_script_by_id($script_id);

                if (intval($script["user_id"]) == $_SESSION["id"]) {
                    $owner = "enabled";
                } else {
                    $owner = "disabled";
                }
            }
        }

        // Handles if the user wants to update or delete their script
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
              // Something posted

              if (isset($_POST['btnDelete'])) {
                // btnDelete 
                //UNTESTED
                //$delete_success = $this->db->delete_script($script["script_id"]);
              } else {
                // Assume btnSubmit
                $message = "<div class='alert alert-success'>Script updated.</div>"; 
              }
            }
    
        include "templates/fullscript.php";
    }

    // template for testing stuff
    public function test() {
    
        include "templates/test.php";
    }



}