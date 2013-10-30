
<?php if(count($posts) == 0): ?>
	<article class='post'>
		<p>Make sure to follow someone under <a class="smalllink" href="/posts/users">Users</a> to see posts!</p>
	</article>
<?php endif; ?>


<?php foreach($posts as $post): ?>
	<article class='post clearfix'>

		<div class="post_pic">
			<img class='img_big' src="/uploads/avatars/<?=$post['avatar']?>">
		    <h4><?=$post['first_name']?> <?=$post['last_name']?> posted:</h4> 
		</div>

	    <div class='post_div'>
		    <p><?=$post['content']?></p>

		    <time datetime="<?=Time::display($post['created'],'Y-m-d G:i')?>">
		        <?=Time::display($post['created'])?>
		    </time>
		    <br><br>

		    <?php if($post['post_user_id'] == $user->user_id): ?>
			    <a class="smalllink" href="/posts/deletepost/<?=$post['post_id']?>">Delete Post</a>
		    <?php endif; ?>
		    <br><br>
	    </div>
	    

	</article>
	<br><br>
<?php endforeach; ?>
