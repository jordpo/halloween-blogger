<?php
class users_controller extends base_controller {

    public function __construct() {
        parent::__construct();
        // echo "users_controller construct called<br><br>";
    } 

    public function index() {
        echo "This is the index page";
    }

    public function signup($error = NULL) {
        
        # first, set the content via a view file
        $this->template->content = View::instance('v_users_signup');

        # set the <title> 
        $this->template->title = APP_NAME . ": Sign Up";

        # Pass data to the view
        $this->template->content->error = $error;

        # render this view
        echo $this->template;

    }

    public function p_signup() { 

        ##### Validation checks #######
        
        # Email duplicate check # 
        $e = 'SELECT email
                FROM users
                WHERE email = "' . $_POST['email'] . '"';
        $email = DB::instance(DB_NAME)->select_field($e);

        # Blank validation error check #
        if ($_POST['first_name'] == "" || 
            $_POST['last_name'] == "" ||
            $_POST['email'] == "" ||
            $_POST['password'] == "" ||
            $_POST['password_check'] == "") {
            $val_error = 'blanks';
        
        # Dupe email validation error check #
        } elseif($email) {
            $val_error = 'emaildupe';
        
        # Invalid email format error check #
        } elseif(strpos($_POST['email'], '@', 1) === FALSE ||
            strpos($_POST['email'], '@') > strlen($_POST['email']) - 6) {
            $val_error = 'emailinvalid';
        
        # Password mismatch error check #
        } elseif($_POST['password'] != $_POST['password_check']) {
            $val_error = 'password';
        
        # Pass all validations, set variable to blank
        } else {
            $val_error = '';
        }

        # Use a switch statement to go through validation checks
        switch ($val_error) {
            case 'blanks':
                Router::redirect('/users/signup/blanks');
                break;         
            case 'emailinvalid':
                Router::redirect('/users/signup/emailinvalid');
                break;
            case 'emaildupe':
                Router::redirect('/users/signup/emaildupe');
                break;
            case 'password':
                Router::redirect('/users/signup/password');
                break;
            default:
                # Additional data to store with each user
                $_POST['created'] = Time::now();
                $_POST['modified'] = Time::now();

                # Randomly select an avatar to start
                $avatar = Array("cat.gif", "ghost.png", "monster.png", "witch.png");
                $i = rand(0,count($avatar)-1);
                $_POST['avatar'] = $avatar[$i];

                # Encrypt the password
                $_POST['password'] = sha1(PASSWORD_SALT. $_POST['password']);
               
                # Unset password_check since we don't need it 
                unset($_POST['password_check']);

                # Create an encrypted token via the email address and a random string
                $_POST['token'] = sha1(TOKEN_SALT . $_POST['email'] . Utils::generate_random_string());

                # Insert into table users
                DB::instance(DB_NAME)->insert_row('users', $_POST);

                Router::redirect('/users/login');
                break;
        }

    }

    public function login($error = NULL) {
        
        # link to content in view
        $this->template->content = View::instance('v_users_login');

        # set <title>
        $this->template->title = APP_NAME . ": Login";

        # Pass data to the view
        $this->template->content->error = $error;

        # render this view
        echo $this->template;

    }

    public function p_login() {

        # Sanitize the user entered data to prevent SQL Injection Attacks
        $_POST = DB::instance(DB_NAME)->sanitize($_POST);

        # Hash submitted password to compare with one already in table
        $_POST['password'] = sha1(PASSWORD_SALT . $_POST['password']);

        # Search the db for this email and password
        # Retrieve the token if available

        # Search for email specifically 
        $e = 'SELECT email
                FROM users
                WHERE email = "' . $_POST['email'] . '"';

        $email = DB::instance(DB_NAME)->select_field($e);

        $q = 'SELECT token 
            FROM users
            WHERE email = "' . $_POST['email'] . '"
            AND password = "' . $_POST['password'] . '"';

        $token = DB::instance(DB_NAME)->select_field($q);

        # If no email match, login failed because of email
        if(!$email) {
            Router::redirect("/users/login/emailerr");

        # Else if login fails without an email and password combo correct
        } else if(!$token) {
            Router::redirect("/users/login/error");

        # Or success! 
        } else {
            # Set cookie
            setcookie("token", $token, strtotime('+1 year'), '/');
            
            # After successful log in where to go
            Router::redirect("/");
        }
    }

    public function logout() {
        
        # Generate and save a new token for next login
        $new_token = sha1(TOKEN_SALT . $this->user->email.Utils::generate_random_string());

        # Create the data array we'll use with the update method
        $data = Array("token" => $new_token);

        # Do the update
        DB::instance(DB_NAME)->update("users", $data, "WHERE token = '" . $this->user->token . "'");

        # Delete their cookie by setting it to a date in the past
        setcookie("token", "", strtotime('-1 year'), '/');

        # Send them back to main index
        Router::redirect("/");

    }

    public function profile($user_id) {

        # If user is blank, not logged in. redirect to login
        if(!$this->user) {
            Router::redirect('/users/login');
        }

        # Otherwise continue

        # Set up View
        $this->template->content = View::instance('v_users_profile');
        $this->template->title = 'Profile of ' . $this->user->first_name;
        
        ### Query for $this_user to access specific profile ####
        $q = 'SELECT
            user_id,
            first_name,
            last_name,
            avatar, 
            avatar_history
            FROM users
            WHERE user_id = ' . $user_id;

        # Run it
        $all_users = DB::instance(DB_NAME)->select_rows($q);
        
        # Find this user (determined by $user_id parameter)
        foreach ($all_users as $all_user) {
            if($all_user['user_id'] == $user_id){
                $this_user = $all_user;
            }
        }

        ### End of $this_user query ###

        # Since we need to do some things to avatar_history, create a variable
        $avatar_history = $this_user['avatar_history'];

        # Explode it into an array
        $avatars = explode(",", $avatar_history);

        # Query to gather user's posts
        $d = 'SELECT 
            post_id,
            content,
            created,
            user_id FROM posts
            WHERE user_id = ' . $user_id . '
            ORDER BY created DESC';

        # Run it
        $posts = DB::instance(DB_NAME)->select_rows($d);

        # Query to get comments from posts
        $q = "SELECT
            comments.comment_id,
            comments.created,
            comments.modified,
            comments.post_id,
            comments.user_id,
            comments.content,
            users.first_name,
            users.last_name,
            users.avatar
            FROM comments
            JOIN users
                ON comments.user_id = users.user_id";

        # Run it
        $comments = DB::instance(DB_NAME)->select_rows($q);

        # Query connections 
        $q = "SELECT * 
            FROM users_users
            WHERE user_id = ".$this->user->user_id;

        # Run it
        $connections = DB::instance(DB_NAME)->select_array($q, 'user_id_followed');

        # Pass all data to the view
        $this->template->content->user_name = $user_name;
        $this->template->content->avatars = $avatars;
        $this->template->content->this_user = $this_user;
        $this->template->content->posts = $posts;
        $this->template->content->comments = $comments;
        $this->template->content->connections = $connections;
        
        # Display the view
        echo $this->template;

    }

    public function p_upload() {

        # Save old image path
        $avatar_old = $this->user->avatar;

        # Pull up old avatar history
        $q = 'SELECT avatar_history 
            FROM users
            WHERE user_id = ' . $this->user->user_id;

        $avatar_history = Array(DB::instance(DB_NAME)->select_field($q));

        # Add image path to the avatar history array
        array_push($avatar_history, $avatar_old);

        # Make the array one string delimited by comma
        $ahist = implode(",", $avatar_history);

        # Create the data array we'll use with the update method
        $adata = Array("avatar_history" => $ahist);

        # Do the update
        DB::instance(DB_NAME)->update("users", $adata, "WHERE token = '" . $this->user->token . "'"); 

        # Upload image to directory
        $profile = Upload::upload($_FILES, "/uploads/avatars/", 
            array("jpg", "jpeg", "gif", "png"), 
            "user" . $this->user->user_id . "_" . Time::now());

        # Redirect back to page if no file is loaded
        if($profile == "Invalid file type.") {
            Router::redirect("/users/profile");
        }

        # Create the data array we'll use with the update method
        $data = Array("avatar" => $profile);

        # Do the update
        DB::instance(DB_NAME)->update("users", $data, "WHERE token = '" . $this->user->token . "'");

        Router::redirect("/users/profile/" . $this->user->user_id);
    }

    public function deletepic($pic_index) {

        # First pull up avatar history for this user
        $avatar_history = $this->user->avatar_history;

        # Explode string to an array
        $avatar_history = explode(",", $avatar_history);

        # Remove pic location path from string
        unset($avatar_history[$pic_index]);

        # Make it a string again
        $avatar_history = implode(",", $avatar_history);

        # Create the data array we'll use with the update method
        $adata = Array("avatar_history" => $avatar_history);

        # Do the update
        DB::instance(DB_NAME)->update("users", $adata, "WHERE token = '" . $this->user->token . "'"); 

        # Return to profile
        Router::redirect("/users/profile/" . $this->user->user_id);
    }

} # end of the class