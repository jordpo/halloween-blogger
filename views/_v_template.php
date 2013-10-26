<!DOCTYPE html>
<html>
<head>
	<title><?php if(isset($title)) echo $title; ?></title>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	
					
	<!-- Controller Specific JS/CSS -->
	<?php if(isset($client_files_head)) echo $client_files_head; ?>
	
</head>

<body>	

	<nav id="top_nav">
		<?php if($user): ?>
			<li><a href="/users/profile">Profile</a></li>
			<li><a href="/posts">Posts</a></li>
			<li><a href="/posts/users">Users</a></li>
			<li><a href="/posts/add">Add a post</a></li>
			<li><a href="/users/logout">Logout</a></li>
		<?php endif; ?>
	</nav>
	<?php if(isset($content)) echo $content; ?>

	<?php if(isset($client_files_body)) echo $client_files_body; ?>
</body>
</html>