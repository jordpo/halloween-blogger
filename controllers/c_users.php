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
        
        $this->template->content = View::instance('v_users_signup');

        echo $this->template;
    }

    public function p_signup() {

        echo "<pre>";
        print_r($_POST);
        echo "<\pre>"; 

        $_POST['created'] = Time::now();
        $_POST['password'] = sha1(PASSWORD_SALT. $_POST['password']);
        $_POST['token'] = sha1(TOKEN_SALT . $_POST['email'] . Utils::generate_random_string());

        DB::instance(DB_NAME)->insert_row('users', $_POST);

        Router::redirect('/users/login');

    }

    public function login() {
        
        $this->template->content = View::instance('v_login_users');

        echo $this->template;

    }

    public function p_login() {

        // encrypt it the same way as you did before 

        $_POST['password'] = sha1(PASSWORD_SALT . $_POST['password']);

        $q = 'SELECT token FROM users
            WHERE email = "' . $_POST['email'] . '"
            AND password = "' . $_POST['password'] . '"';


        $token = DB::instance(DB_NAME)->select_row($q);

        echo $token;
    }

    public function logout() {
        echo "This is the logout page";
    }

    public function profile($user_name = NULL) {

        # Set up View
        $this->template->content = View::instance('v_users_profile');
        $this->template->title = 'Profile';

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