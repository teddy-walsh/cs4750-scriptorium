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
        $list_of_scripts = $this->db->get_all_scripts();
        $list_of_users = $this->db->get_all_users();
        $joiner = $this->db->get_all_user_created();

        $home_page_filler = [];
        foreach ($joiner as $item) {
            $sid = intval($item["script_id"]);
            $uid = intval($item["user_id"]);
            $temp = [];

            foreach ($list_of_users as $user) {
                if (intval($user["user_id"]) == $uid) {
                    //add to the temp.
                    $temp["display_name"] = $user["display_name"];
                    $temp["user_id"] = $user["user_id"];
                }
            }

            foreach ($list_of_scripts as $script) {
                if (intval($script["script_id"]) == $sid) {
                    // //add to the temp.
                    $temp["title"] = $script["title"];
                    $temp["blurb"] = $script["blurb"];
                    $temp["genre"] = $script["genre"];
                    $temp["datetime"] = $script["datetime"];
                    $temp["script_id"] = $script["script_id"];
                }
            }

            // add the temp to the overall array
            array_push($home_page_filler, $temp);
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
            $user_name = $this->db->get_user_displayname($_GET["user"]);
            if (!empty($user_details)) { // it found the user in userpage
                $info = $user_details;
            }
            if (!empty($user_name)) { // it found the user in users
                $info["display_name"] = $user_name;
            }

            // populate their list of scripts
            $list_of_scripts = $this->db->get_all_scripts_by_user($_GET["user"]);

        } else { //GET not set
            header("Location: ?command=home");
        }


    
        include "templates/userpage.php";
    }

    public function test() {
    
        include "templates/test.php";
    }



}