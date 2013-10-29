<?php if(!$user): ?>
	<article class="general">
		<h4>Welcome to..</h4>
		<h1>Morano Space</h1>
		<p>
		Welcome to <?=APP_NAME?>. A simple micro-blog site where you can create your own short posts or follow others!
		</p>
	</article>
<?php else: ?>

	<article class="general">
		<h2>Welcome! <?=$user->first_name?></h2>
		<p>Make sure to check out the links above to update your 
			<a href="/users/profile/<?=$user->user_id?>">Profile</a> 
			image or start following <a href="/posts/users">Users</a>!</p>
	</article>
	
<?php endif; ?>