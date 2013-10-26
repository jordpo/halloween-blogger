<h4>Welcome to..</h4>
<h1>Morano Space</h1>

<p>
	Welcome to <?=APP_NAME?>. A simple micro-blog site where you can create your own short posts or follow others!
</p>

<?php if(!$user): ?>
	<nav id="nav_index">
		<a href="users/signup">Sign Up</a>
		<a href="users/login">Log In</a>
	</nav>
<?php else: ?>

	<h2>Welcome! <?=$user->first_name?></h2>

	<a href="/users/profile">Profile Page</a>
	
<?php endif; ?>