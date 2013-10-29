<article id="profile">
	<h1>This is the profile of <?=$this_user['first_name']?> <?=$this_user['last_name']?></h1>
	<img class="img_big" src="/uploads/avatars/<?=$this_user['avatar']?>">
</article>
<br><br>

<!-- You can only update your own profile! -->

<?php if($user->user_id == $this_user['user_id']):?>
	<form method='POST' enctype="multipart/form-data" action='/users/p_upload/'>	
		<h3>Update Profle Image</h3>
		<input type='file' name='avatar'>
		<input type='submit'>
	</form>
	<br><br>
<?php endif; ?>

<article class="profile_history">
	<h1>Profile Image History</h1>
	<?php for($i = count($avatars) - 1; $i >= 0; $i--): ?>
		<?php if($avatars[$i] != ""): ?>
			<img class="img_big" src="<?=$avatars[$i]?>">
			<br><br>
		<?php endif; ?>
	<?php endfor; ?>
</article>

<article class="post_history">
	<h1>Post History</h1>
	<?php foreach ($posts as $post): ?>
		<div>
		    <p><?=$post['content']?></p>

		    <time datetime="<?=Time::display($post['created'],'Y-m-d G:i')?>">
		        <?=Time::display($post['created'])?>
		    </time>
		    <br><br>

		    <?php if($post['user_id'] == $user->user_id): ?>
			    <a href="/posts/deletepostonprofile/<?=$post['post_id']?>">Delete Post</a>
		    <?php endif; ?>
		    <br><br>
	    </div>
	<?php endforeach; ?>
    
</article>