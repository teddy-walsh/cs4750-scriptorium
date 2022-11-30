<?php
class Database {

    private $db;

    public function __construct() {

            // LocalHost
            // $username = "root";
            // $password = "";
            // $dsn = "mysql:dbname=scriptorium;host=127.0.0.1";

            //GCP
            $username = getenv('DB_USER');
            $password = getenv('DB_PASS');
            $socket = getenv('DB_SOCKET');
            $dbname = getenv('DB_NAME');
            $dsn = "mysql:unix_socket=$socket;dbname=$dbname";

       try {
            // Connect to the database.
            $this->db = new PDO($dsn, $username, $password);

            // Sets warning messages to ON
            $this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

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
    function add_user($email, $username, $password, $fname, $mname, $lname) {
        // var_dump($_POST);
        $query = "INSERT INTO users(email, display_name, password) 
                    VALUES (:email, :username, :password)";
        try {
            $statement = $this->db->prepare($query);
            $statement->bindValue(':email', $email);
            $statement->bindValue(':username', $username);
            $statement->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));
            $statement->execute();

            $userID = $this->db->lastInsertId();
            $success = $this->fullName($userID, $fname, $mname, $lname);
            if ($success)
                $this->create_default_user_page($userID);
                return true;
            //echo "Successfully added new user";

            // if ($statement->rowCount() == 0) {
            //     echo "Failed to add a friend <br/>";
            // }

            //return true; // this gets checked on the sign-up page to then log in the new user
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

    function fullName($u, $f, $m, $l) {
        $query = "INSERT INTO user_full_names(user_id, first_name, middle_name, last_name) 
        VALUES (:u, :f, :m, :l)";
try {
$statement = $this->db->prepare($query);
$statement->bindValue(':u', $u);
$statement->bindValue(':f', $f);
$statement->bindValue(':m', $m);
$statement->bindValue(':l', $l);
$statement->execute();

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
        $statement = $this->db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        $result = $statement->fetch();
        return $result; 
    }

    // runs user login authentication
    function user_login($username, $password) {
        $query = "SELECT * FROM users WHERE (display_name=:username)";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        $user = $statement->fetch();

        if (!empty($user)) { // it found the user

            if (password_verify($password, $user["password"])) { // pw good?
                    $_SESSION["username"] = $user["display_name"];
                    $_SESSION["id"] = $user["user_id"];
                    header("Location: ?command=home");
            } else {
                return false; // gives the error message of unable to authenticate
            }
        }

    }

    // Handles the submission of new scripts
    function post_new_script($title, $blurb, $script, $genre, $user_id) {
        $query = "INSERT INTO scripts(title, blurb, script_body, genre) 
                    VALUES (:title, :blurb, :script, :genre)";
        try {
            $statement = $this->db->prepare($query);
            $statement->bindValue(':title', $title);
            $statement->bindValue(':blurb', $blurb);
            $statement->bindValue(':script', $script);
            $statement->bindValue(':genre', $genre);
            $statement->execute();
            $new_script_id = $this->db->lastInsertId();

            //since the script insert was successful, try to update the user_created table, too
            $update_results = $this->update_user_created($user_id, $new_script_id);

            // Let the controller know if the script was inserted AND the user_created table updated
            if ($update_results) {
                return $new_script_id;
            } else {
                return false;
            }
            
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

    // adds the appropriate tuple when new scripts are created
    function update_user_created($user_id, $new_script_id) {
        $query = "INSERT INTO user_created(user_id, script_id) 
                    VALUES (:user_id, :new_script_id)";
        try {
            $statement = $this->db->prepare($query);
            $statement->bindValue(':user_id', $user_id);
            $statement->bindValue(':new_script_id', $new_script_id);
            $statement->execute();
            // will report back to post_new_scripts to confirm the whole process worked
            return true; 
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

    function get_paged_scripts($page, $ordered_by, $direction) {
        
        $items_per_page = 10;
        $offset = ($page - 1) * $items_per_page;
        if($ordered_by == "rating"){

        $query = "SELECT user_id, display_name, title, blurb, genre, datetime, script_id, COALESCE(rating,0) FROM scripts LEFT JOIN (SELECT votes_on_scripts.script_id as script_id1, COALESCE(SUM(direction),0) as rating FROM (votes_on_scripts INNER JOIN votes ON votes_on_scripts.vote_id = votes.vote_id) GROUP BY script_id) as tmp ON scripts.script_id = tmp.script_id1             NATURAL JOIN user_created NATURAL JOIN users
        ORDER BY " . "COALESCE(rating,0)" . " " . $direction . " LIMIT " . $offset . "," . $items_per_page;
        $statement = $this->db->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();   // fetch()
        return $result;

        }
        

        $query = "SELECT user_id, display_name, title, blurb, genre, datetime, script_id 
            FROM scripts 
            NATURAL JOIN user_created 
            NATURAL JOIN users
            ORDER BY " . $ordered_by . " " . $direction . " LIMIT " . $offset . "," . $items_per_page;
        $statement = $this->db->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();   // fetch()
        return $result;
    }

//     function get_paged_scripts_by_rating($page, $direction) {


// SELECT script_id, SUM(direction), title FROM (votes_on_scripts NATURAL JOIN votes NATURAL JOIN scripts) GROUP BY script_id ORDER BY SUM(direction) DESC; 

//     }

    function get_all_scripts_by_user($user_id) {
        $query = "SELECT * FROM scripts NATURAL JOIN user_created WHERE (user_id=:uid)";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':uid', $user_id);
        $statement->execute();
        $scripts = $statement->fetchAll();

        if (!empty($scripts)) { // it found the user's scripts
            return $scripts;
        }
    }

    function get_script_by_id($script) {
        $query = "SELECT * FROM scripts NATURAL JOIN user_created 
            WHERE (script_id=:sid)";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':sid', $script);
        $statement->execute();
        $scriptfull = $statement->fetch();
        if (!empty($scriptfull)) { // it found the script
            return $scriptfull;
        } 
    }

    function get_all_users() {
        $query = "SELECT user_id, display_name FROM users";
        $statement = $this->db->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();   // fetch()
        return $result;
    }

    function get_all_user_created() {
        $query = "SELECT * FROM user_created";
        $statement = $this->db->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();   // fetch()
        return $result;
    }

    function get_userpage_info($user_id) {
        $query = "SELECT user_id, display_name, bio, URL FROM userpage NATURAL JOIN users WHERE (user_id=:uid)";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':uid', $user_id);
        $statement->execute();
        $user = $statement->fetch();

        if (!empty($user)) { // it found the user
            return $user;
        } 
    }

    function get_user_displayname($user_id) {
        $query = "SELECT display_name FROM users WHERE (user_id=:uid)";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':uid', $user_id);
        $statement->execute();
        $user = $statement->fetch();
        if (!empty($user)) { // it found the user
            return $user["display_name"];
        } 
    }

    function get_comment_votes($comment_id) {
        $query = "SELECT COALESCE(SUM(direction),0) as count FROM (votes_on_comments INNER JOIN votes ON votes_on_comments.vote_id = votes.vote_id) WHERE comment_id =:cid";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':cid', $comment_id);
        $statement->execute();
        $count = $statement->fetch();
        if (!empty($count)) { // it found the user
            return $count["count"];
        } 
    }
    function get_script_votes($script_id){
        // SET @p0='2'; CALL `count_script_votes`(@p0, @p1); SELECT @p1 AS `score`;

        $query = "SELECT COALESCE(SUM(direction),0) as score FROM (votes_on_scripts INNER JOIN votes ON votes_on_scripts.vote_id = votes.vote_id) WHERE script_id =:cid";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':cid', $script_id);
        $statement->execute();
        $count = $statement->fetch();
        //print_r($count) ;
        if (!empty($count)) { // it found the user
            return $count["score"];
        } 
        else{
            return 0;
        }
    }
    // //Determines how a user can vote.
    // function get_user_vote_on_comment($user_id,$comment_id){
    //     $query = "SELECT * FROM (votes_on_comments INNER JOIN votes ON votes_on_comments.vote_id = votes.vote_id) WHERE comment_id =:cid and user_id =:uid";
    //     $statement = $this->db->prepare($query);
    //     $statement->bindValue(':uid', $user_id);
    //     $statement->bindValue(':cid', $comment_id);
    //     $info = $statement->fetch();
    //     if (!empty($info)) { // it found the user
    //         return $info["direction"];
    //     } 
    //     else{
    //         return 0;
    //     }
    // }

    // //Determines how a user can vote.
    // function get_user_vote_on_script($user_id,$script_id){
    //     $query = "SELECT * FROM (votes_on_scripts INNER JOIN votes ON votes_on_scripts.vote_id = votes.vote_id) WHERE script_id =:cid and user_id =:uid";
    //     $statement = $this->db->prepare($query);
    //     $statement->bindValue(':uid', $user_id);
    //     $statement->bindValue(':cid', $script_id);
    //     $info = $statement->execute();
    //     if (!empty($info)) { // it found the user
    //         return $info["direction"];
    //     } 
    //     else{
    //         return 0;
    //     }
    // }

    function user_script_vote($user_id,$script_id,$direction){
        $query = "SELECT * FROM (votes_on_scripts INNER JOIN votes ON votes_on_scripts.vote_id = votes.vote_id) 
        WHERE votes_on_scripts.script_id =:script AND votes.user_id =:user";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':user', $user_id);
        $statement->bindValue(':script', $script_id);
        $statement->execute();
        $info = $statement->fetch();
        if (isset($info["vote_id"])) { // User already voted, update direction
            $query = "UPDATE votes
                    SET direction=:direction
                    WHERE vote_id=:vid";
            $statement = $this->db->prepare($query);
            $statement->bindValue(':direction', $direction);
            $statement->bindValue(':vid', $info["vote_id"]);
            $statement->execute();

            return true;
        } 

        else{ //user has not yet voted on this script
            $query = "INSERT INTO votes(user_id, direction)
            VALUES ( :uid, :dir)";
            $statement = $this->db->prepare($query);
            $statement->bindValue(':dir', $direction);
            $statement->bindValue(':uid', $user_id); 

            $statement->execute();
            $new_vote_id = $this->db->lastInsertId();

            $query = "INSERT INTO votes_on_scripts(vote_id, script_id)
            VALUES (:vid, :cid)";
            $statement = $this->db->prepare($query);
            $statement->bindValue(':vid', $new_vote_id);
            $statement->bindValue(':cid', $script_id); 
            $statement->execute();
            return true; 
        }
    }
    
    function user_comment_vote($user_id,$comment_id,$direction){
        $query = "SELECT * FROM (votes_on_comments INNER JOIN votes ON votes_on_comments.vote_id = votes.vote_id) 
        WHERE votes_on_comments.comment_id =:comment AND votes.user_id =:user";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':user', $user_id);
        $statement->bindValue(':comment', $comment_id);
        $statement->execute();
        $info = $statement->fetch();
        if (isset($info["vote_id"])) { // User already voted, update direction
            $query = "UPDATE votes
                    SET direction=:direction
                    WHERE vote_id=:vid";
            $statement = $this->db->prepare($query);
            $statement->bindValue(':direction', $direction);
            $statement->bindValue(':vid', $info["vote_id"]);
            $statement->execute();

            return true;
        } 

        else{ //user has not yet voted on this script
            $query = "INSERT INTO votes(user_id, direction)
            VALUES ( :uid, :dir)";
            $statement = $this->db->prepare($query);
            $statement->bindValue(':dir', $direction);
            $statement->bindValue(':uid', $user_id); 

            $statement->execute();
            $new_vote_id = $this->db->lastInsertId();

            $query = "INSERT INTO votes_on_comments(vote_id, comment_id)
            VALUES (:vid, :cid)";
            $statement = $this->db->prepare($query);
            $statement->bindValue(':vid', $new_vote_id);
            $statement->bindValue(':cid', $comment_id); 
            $statement->execute();
            return true; 
        }
    }


    function delete_script($script_id) {
        // delete the entry from user_created
        $query = "DELETE FROM user_created WHERE (script_id=:sid)";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':sid', $script_id);
        $success = $statement->execute();

        if ($success) { // it deleted from user_created; try to delete from scripts as well
            $query = "DELETE FROM scripts WHERE (script_id=:sid)";
            $statement = $this->db->prepare($query);
            $statement->bindValue(':sid', $script_id);
            $success = $statement->execute();
        }

        return $success;
    }

    function update_script($script_id, $title, $blurb, $script, $genre) {
        $query = "UPDATE scripts
                    SET title=:title, blurb=:blurb, script_body=:script, genre=:genre
                    WHERE script_id=:sid";
        try {
            $statement = $this->db->prepare($query);
            $statement->bindValue(':title', $title);
            $statement->bindValue(':blurb', $blurb);
            $statement->bindValue(':script', $script);
            $statement->bindValue(':genre', $genre);
            $statement->bindValue(':sid', $script_id);
            $statement->execute();
            return true;  
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

    function get_root_comments($script_id) {
        $query = "SELECT script_id_parent, comment_id, users.user_id as user_id,
            comments_text, time, display_name FROM script_parent 
        JOIN comments ON script_parent.comment_id_child = comments.comment_id
        JOIN users ON comments.user_id = users.user_id 
            WHERE (script_parent.script_id_parent=:sid)";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':sid', $script_id);
        $statement->execute();
        $root_comments = $statement->fetchAll();
        // echo "<pre>";
        //     print_r($parent_comments);
        // echo "</pre>";

        if (!empty($root_comments)) { // it found root comments for the script
            return $root_comments;
        } 
    }

    function get_child_comments($script_id) {
        $query = "SELECT comment_id, users.user_id as user_id, comment_parent.comment_id_parent as comment_parent, comments_text, time, display_name 
        FROM script_parent 
        JOIN comment_parent ON script_parent.comment_id_child = comment_parent.comment_id_parent
        JOIN comments ON comment_parent.comment_id_child = comments.comment_id
        JOIN users ON comments.user_id = users.user_id  
            WHERE (script_parent.script_id_parent=:sid)";

        $statement = $this->db->prepare($query);
        $statement->bindValue(':sid', $script_id);
        $statement->execute();
        $child_comments = $statement->fetchAll();
        if (!empty($child_comments)) { // it found root comments for the script
            return $child_comments;
        } 

    }

    function comment_on_script ($script_id, $user_id, $text) {
        $commentID = $this->create_comment($user_id, $text);

        if (isset($commentID)) {
            $query = "INSERT INTO script_parent(script_id_parent, comment_id_child) 
            VALUES (:script_id, :cid)";

            try {
                $statement = $this->db->prepare($query);
                $statement->bindValue(':script_id', $script_id);
                $statement->bindValue(':cid', $commentID);
                $success = $statement->execute();
                if ($success) {
                    return true;
                }
            }
            catch (PDOException $e) {
                if (strpos($e->getMessage(), "Duplicate")){
                    echo "Failed to make a comment <br/>";
                }
            }
            catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }

    function comment_on_comment ($comment_id, $user_id, $text) {
        $new_comment_id = $this->create_comment($user_id, $text);

        $query = "INSERT INTO comment_parent VALUES (:cidparent, :cidchild)";
        try {
            $statement = $this->db->prepare($query);
            $statement->bindValue(':cidparent', $comment_id);
            $statement->bindValue(':cidchild', $new_comment_id);
            $success = $statement->execute();
            if ($success) {
                return true;
            }
            
        }
        catch (PDOException $e) {
            // echo $e->getMessage();
            // if there is a specific SQL-related error message
            //    echo "generic message (don't reveal SQL-specific message)";

            if (strpos($e->getMessage(), "Duplicate")){
                echo "Failed to make a comment <br/>";
            }
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    function create_comment ($user_id, $text) {
        $query = "INSERT INTO comments(user_id, comments_text)
        VALUES (:user_id, :text)";
        try {
            $statement = $this->db->prepare($query);
            $statement->bindValue(':user_id', $user_id);
            $statement->bindValue(':text', $text);
            $statement->execute();
            $commentID = $this->db->lastInsertId();
            return $commentID;   
        }
        catch (PDOException $e) {
            // echo $e->getMessage();
            // if there is a specific SQL-related error message
            //    echo "generic message (don't reveal SQL-specific message)";

            if (strpos($e->getMessage(), "Duplicate")){
                echo "Failed to make a comment <br/>";
            }
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    function create_default_user_page ($user_id) {
        $query = "INSERT INTO userpage(user_id, bio, URL)
        VALUES (:user_id, '', '')";
        try {
            $statement = $this->db->prepare($query);
            $statement->bindValue(':user_id', $user_id);
            $statement->execute();
            return true;   
        }
        catch (PDOException $e) {
            // echo $e->getMessage();
            // if there is a specific SQL-related error message
            //    echo "generic message (don't reveal SQL-specific message)";

            if (strpos($e->getMessage(), "Duplicate")){
                echo "Failed to add user page <br/>";
            }
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    function update_userpage($id, $bio, $url) {
        $query = "UPDATE userpage
        SET bio=:bio, URL=:url
        WHERE user_id=" . $id;
    try {
        $statement = $this->db->prepare($query);
    $statement->bindValue(':bio', $bio);
    $statement->bindValue(':url', $url);
    $statement->execute();
    return true;  
    }
    catch (PDOException $e) {
    // echo $e->getMessage();
    // if there is a specific SQL-related error message
    //    echo "generic message (don't reveal SQL-specific message)";

    if (strpos($e->getMessage(), "Duplicate")){
        echo "Failed to update user page <br/>";
    }
    }
    catch (Exception $e) {
    echo $e->getMessage();
    }
    }

}