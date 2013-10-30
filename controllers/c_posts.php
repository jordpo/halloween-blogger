<?php


class posts_controller extends base_controller {

	public function __construct() {
        parent::__construct();
    
       	# The user needs to be logged in
       	if(!$this->user) {
       		die("Want to see posts?? Log in! <a href='/users/login'>Login</a>");
       	}

    } 

	public function add() {

		# Set up view
		$this->template->content = View::instance('v_posts_add');
		$this->template->title = "New Post";

		# Render template
		echo $this->template;

	}

	public function p_add() {

		# Link this post with this user
		$_POST['user_id'] = $this->user->user_id;

		# Unix timestamp for created and modified
		$_POST['created'] = Time::now();
		$_POST['modified'] = Time::now();

		# Insert (don't need to sanitize data)

		DB::instance(DB_NAME)->insert('posts', $_POST);


		# Redirect back to posts index
		Router::redirect('/posts');

	}

	public function users() {

	    # Set up the View
	    $this->template->content = View::instance("v_posts_users");
	    $this->template->title   = "Users";

	    # Query posts to get post counts
	    $q = "SELECT user_id
	    	FROM posts";
	    
	    $posts = DB::instance(DB_NAME)->select_rows($q);


	    # Build the query to get all the users
	    $q = "SELECT *
	        FROM users";

	    # Execute the query to get all the users. 
	    # Store the result array in the variable $users
	    $pusers = DB::instance(DB_NAME)->select_rows($q);

	    # Build the query to figure out what connections does this user already have? 
	    # I.e. who are they following
	    $q = "SELECT * 
	        FROM users_users
	        WHERE user_id = ".$this->user->user_id;

	    # Execute this query with the select_array method
	    # select_array will return our results in an array and use the "users_id_followed" field as the index.
	    # This will come in handy when we get to the view
	    # Store our results (an array) in the variable $connections
	    $connections = DB::instance(DB_NAME)->select_array($q, 'user_id_followed');

	    # Pass data (users and connections) to the view
	    $this->template->content->pusers       = $pusers;
	    $this->template->content->connections = $connections;
	    $this->template->content->posts = $posts;

	    # Render the view
	    echo $this->template;

	}

	public function follow($user_id_followed) {

	    # Prepare the data array to be inserted
	    $data = Array(
	        "created" => Time::now(),
	        "user_id" => $this->user->user_id,
	        "user_id_followed" => $user_id_followed
	        );

	    # Do the insert
	    DB::instance(DB_NAME)->insert('users_users', $data);

	    # Send them back
	    Router::redirect("/posts/users");

	}

	public function unfollow($user_id_followed) {

	    # Delete this connection
	    $where_condition = 'WHERE user_id = '. $this->user->user_id .' AND user_id_followed = '.$user_id_followed;
	    DB::instance(DB_NAME)->delete('users_users', $where_condition);

	    # Send them back
	    Router::redirect("/posts/users");

	}

	public function follow_profile($user_id_followed, $user_id) {

	    # Prepare the data array to be inserted
	    $data = Array(
	        "created" => Time::now(),
	        "user_id" => $this->user->user_id,
	        "user_id_followed" => $user_id_followed
	        );

	    # Do the insert
	    DB::instance(DB_NAME)->insert('users_users', $data);

	    # Send them back
	    Router::redirect("/users/profile/" . $user_id);

	}

	public function unfollow_profile($user_id_followed, $user_id) {

	    # Delete this connection
	    $where_condition = 'WHERE user_id = '. $this->user->user_id .' AND user_id_followed = '.$user_id_followed;
	    DB::instance(DB_NAME)->delete('users_users', $where_condition);

	    # Send them back
	    Router::redirect("/users/profile/" . $user_id);

	}
	

	public function index() {

		# Set up the View
		$this->template->content = View::instance('v_posts_index');
		$this->template->title = "Posts";

		# Build the query for posts
		$q = 'SELECT 
		    posts.post_id,
		    posts.content,
		    posts.created,
		    posts.user_id AS post_user_id,
		    users_users.user_id AS follower_id,
		    users.first_name,
		    users.last_name,
		    users.avatar
			FROM posts
			JOIN users_users 
			    ON posts.user_id = users_users.user_id_followed
			JOIN users 
			    ON posts.user_id = users.user_id
			WHERE users_users.user_id = ' . $this->user->user_id . '
			ORDER BY posts.created DESC' ;

		# Run it
		$posts = DB::instance(DB_NAME)->select_rows($q);

		# Build the query for comments
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

		# Pass the data into view
		$this->template->content->posts = $posts;
		$this->template->content->comments = $comments;

		# Render the view
		echo $this->template;

	}

	public function deletepost($post_to_delete) {
		$where_condition = 'WHERE post_id = ' . $post_to_delete;

		DB::instance(DB_NAME)->delete('posts', $where_condition);

	    # Send them back
	    Router::redirect("/posts");
	}

	public function deletepostonprofile($post_to_delete) {
		$where_condition = 'WHERE post_id = ' . $post_to_delete;

		DB::instance(DB_NAME)->delete('posts', $where_condition);

	    # Send them back
	    Router::redirect("/users/profile/" . $this->user->user_id);
	}

	public function p_comment($post_id) {

		# Link this post with this user
		$_POST['user_id'] = $this->user->user_id;

		# Unix timestamp for created and modified
		$_POST['created'] = Time::now();
		$_POST['modified'] = Time::now();

		# Pass in post id
		$_POST['post_id'] = $post_id;

		# Insert (don't need to sanitize data)

		DB::instance(DB_NAME)->insert('comments', $_POST);


		# Redirect back to posts index
		Router::redirect('/posts');

	}

	public function deletecomment($comment_to_delete) {
		$where_condition = 'WHERE comment_id = ' . $comment_to_delete;

		DB::instance(DB_NAME)->delete('comments', $where_condition);

	    # Send them back
	    Router::redirect("/posts");
	}

	public function p_commentprofile($post_id, $user_id) {

		# Link this post with this user
		$_POST['user_id'] = $this->user->user_id;

		# Unix timestamp for created and modified
		$_POST['created'] = Time::now();
		$_POST['modified'] = Time::now();

		# Pass in post id
		$_POST['post_id'] = $post_id;

		# Insert (don't need to sanitize data)

		DB::instance(DB_NAME)->insert('comments', $_POST);


		# Redirect back to posts index
		Router::redirect('/users/profile/' . $user_id);

	}

	public function deletecommentprofile($comment_to_delete, $user_id) {
		$where_condition = 'WHERE comment_id = ' . $comment_to_delete;

		DB::instance(DB_NAME)->delete('comments', $where_condition);

	    # Send them back
	    Router::redirect("/users/profile/" . $user_id);
	}
}



?>