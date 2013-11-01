<article id="profile">
	<h1>This is the profile of <?=$this_user['first_name']?> <?=$this_user['last_name']?></h1>
	<img class="img_big" src="/uploads/avatars/<?=$this_user['avatar']?>">

	<?php if($this_user['user_id'] == $user->user_id):?>
		<nav id="profile_follow">
	        <!-- If there exists a connection with this user, show a unfollow link -->
	        <?php if(isset($connections[$this_user['user_id']])): ?>
	            <a class='smalllink' href='/posts/unfollow_profile/<?=$this_user['user_id']?>/<?=$this_user['user_id']?>'>Hide my posts</a>

	        <!-- Otherwise, show the follow link -->
	        <?php else: ?>
	            <a class="smalllink" href='/posts/follow_profile/<?=$this_user['user_id']?>/<?=$this_user['user_id']?>'>Show my posts</a>
	        <?php endif; ?>
	    </nav>
	<?php else: ?>
		<nav id="profile_follow">
	        <!-- If there exists a connection with this user, show a unfollow link -->
	        <?php if(isset($connections[$this_user['user_id']])): ?>
	            <a class='smalllink' href='/posts/unfollow_profile/<?=$this_user['user_id']?>/<?=$this_user['user_id']?>'>Unfollow</a>

	        <!-- Otherwise, show the follow link -->
	        <?php else: ?>
	            <a class="smalllink" href='/posts/follow_profile/<?=$this_user['user_id']?>/<?=$this_user['user_id']?>'>Follow</a>
	        <?php endif; ?>
	    </nav>
	<?php endif; ?>
</article>
<br><br>

<!-- You can only update your own profile! -->

<?php if($user->user_id == $this_user['user_id']):?>
	<form method='POST' enctype="multipart/form-data" action='/users/p_upload/'>	
		<h3>Update Profile Image</h3>
		<p>Please keep the image file size under <em>1MB</em> and <em>crop to square</em> for best looking results!</p>
		<input type='file' name='avatar'>
		<input type='submit'>
	</form>
	<br><br>
<?php endif; ?>

<section class="profile_history">
	<h1>Profile Image History</h1>
	<?php for($i = count($avatars) - 1; $i >= 0; $i--): ?>
		<?php if($avatars[$i] != ""): ?>
			<img class="img_big" src="<?=$avatars[$i]?>">
			<br>
			<?php if($this_user['user_id'] == $user->user_id): ?>
				<a class="smalllink" href="/users/deletepic/<?=$i?>">Delete Photo</a>
			<?php endif; ?>
			<br><br>
		<?php endif; ?>
	<?php endfor; ?>
</section>

<section class="post_history">
	<h1>Post History</h1>
	<?php foreach($posts as $post): ?>
		<div class="post_history_div">
		    <p><?=$post['content']?></p>

		    <time datetime="<?=Time::display($post['created'],'Y-m-d G:i')?>">
		        <?=Time::display($post['created'])?>
		    </time>
		    <br><br>

		    <?php if($post['user_id'] == $user->user_id): ?>
			    <a class="smalllink" href="/posts/deletepostonprofile/<?=$post['post_id']?>">Delete Post</a>
		    <?php endif; ?>
		    <br><br>
		    <div class="comments">
		    	<h3>Comments</h3>
		    	<?php foreach ($comments as $comment):
		    		if($post['post_id'] == $comment['post_id']): ?>
		    			<div class="comment_single">
			    			<img class="comment_img" src="/uploads/avatars/<?=$comment['avatar']?>">
			    			<div class="comment_single_content comm_prof">
				    			
				    			<p><?=$comment['content']?></p>
				    			<p><?=$comment['first_name']?> <?=$comment['last_name']?></p>
							    <time datetime="<?=Time::display($comment['created'],'Y-m-d G:i')?>">
							        <?=Time::display($comment['created'])?>
							    </time>
							    

							    <?php if($comment['user_id'] == $user->user_id): ?>
								    <a class="smalllink" href="/posts/deletecommentprofile/<?=$comment['comment_id']?>/<?=$this_user['user_id']?>">Delete Comment</a>
							    <?php endif; ?>
						    </div>
					    </div>
				    <?php endif; ?>
			    <?php endforeach; ?>
			    <form method='POST' action='/posts/p_commentprofile/<?=$post['post_id']?>/<?=$this_user['user_id']?>'>

				    <label for='content'>New Comment</label><br>
				    <textarea name='content' id='content'></textarea>

				    <br><br>
				    <input type='submit' value='New comment'>

				</form> 
		    </div>


	    </div>
	

	<?php endforeach; ?>
</section>



