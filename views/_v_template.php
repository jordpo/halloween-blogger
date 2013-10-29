<!DOCTYPE html>
<html>
<head>
	<title><?php if(isset($title)) echo $title; ?></title>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	
					
	<link rel="stylesheet" type="text/css" href="/css/style.css">
	<link href='http://fonts.googleapis.com/css?family=Underdog' rel='stylesheet' type='text/css'>
	
	<!-- Controller Specific JS/CSS -->
	<?php if(isset($client_files_head)) echo $client_files_head; ?>
	
</head>

<body>	

	<nav id="top_nav">
		<?php if($user): ?>
			<a href="/users/profile/<?=$user->user_id?>"><?=$user->first_name?> <?=$user->last_name?>'s Profile</a> <span class="separator">|</span>
			<a href="/posts">Posts</a> <span class="separator">|</span>
			<a href="/posts/users">Users</a> <span class="separator">|</span>
			<a href="/posts/add">Add a post</a> <span class="separator">|</span>
			<a href="/users/logout">Logout</a>
		<?php else: ?>
			<a href="/users/signup">Sign up</a> <span class="separator">|</span>
			<a href="/users/login">Sign in</a>
		<?php endif; ?>
		<br><br>
	</nav>
	<?php if(isset($content)) echo $content; ?>

	<?php if(isset($client_files_body)) echo $client_files_body; ?>
</body>
</html>