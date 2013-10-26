<?php
class users_controller extends base_controller {

    public function __construct() {
        parent::__construct();
        // echo "users_controller construct called<br><br>";
    } 

    public function index() {
        echo "This is the index page";
    }

    public function signup() {
        
        # first, set the content via a view file
        $this->template->content = View::instance('v_users_signup');

        # set the <title> 
        $this->template->title = APP_NAME . ": Sign Up";

        # CSS and JS Includes
        $client_files_head = Array("/css/style.css");
        $this->template->client_files_head = Utils::load_client_files($client_files_head);


        # render this view
        echo $this->template;
    }

    public function p_signup() { 

        # Additional data to store with each user
        $_POST['created'] = Time::now();
        $_POST['modified'] = Time::now();

        # Encrypt the password
        $_POST['password'] = sha1(PASSWORD_SALT. $_POST['password']);
       
        # Create an encrypted token via the email address and a random string
        $_POST['token'] = sha1(TOKEN_SALT . $_POST['email'] . Utils::generate_random_string());

        # Insert into table users
        DB::instance(DB_NAME)->insert_row('users', $_POST);

        Router::redirect('/users/login');

    }

    public function login() {
        
        # link to content in view
        $this->template->content = View::instance('v_users_login');

        # set <title>
        $this->template->title = APP_NAME . ": Login";

        # CSS and JS includes
        $client_files_head = Array("/css/style.css");
        $this->template->client_files_head = Utils::load_client_files($client_files_head);

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
        $q = 'SELECT token 
            FROM users
            WHERE email = "' . $_POST['email'] . '"
            AND password = "' . $_POST['password'] . '"';


        $token = DB::instance(DB_NAME)->select_field($q);

        # If no match - login failed
        if(!$token) {
            Router::redirect("/users/login/");
        
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

    public function profile() {

        # If user is blank, not logged in. redirect to login
        if(!$this->user) {
            Router::redirect('/users/login');
        }

        # Otherwise continue

        # Set up View
        $this->template->content = View::instance('v_users_profile');
        $this->template->title = 'Profile of ' . $this->user->first_name;

        # Load client files
        $client_files_head = Array('/css/style.css','/js/function.js');
        $this->template->client_files_head = Utils::load_client_files($client_files_head);

        $client_files_body = Array('/js/script.js');
        $this->template->client_files_body = Utils::load_client_files($client_files_body);
        
        # Pass data to the view
        $this->template->content->user_name = $user_name;
        
        # Display the view
        echo $this->template;

        //$view = View::instance('v_users_profile'); // static get of method instance to retrieve file

        //$view->user_name = $user_name; // pass a global variable to the new variable

        //echo $view; // echo it out so that it is displayed

        /*if($user_name == NULL) {
            echo "No user specified";
        }
        else {
            echo "This is the profile for ".$user_name;
        }*/
    }

} # end of the class