
<?php if(count($posts) == 0): ?>
	<article class='post'>
		<p>Make sure to follow someone under <a class="smalllink" href="/posts/users">Users</a> to see posts!</p>
	</article>
<?php endif; ?>


<?php foreach($posts as $post): ?>
	<article class='post clearfix'>

		<div class="post_pic">
			<img class='img_big' src="/uploads/avatars/<?=$post['avatar']?>">
		    <a class="smalllink post_a" href="/users/profile/<?=$post['post_user_id']?>"><?=$post['first_name']?> <?=$post['last_name']?></a> 
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
	    
		    <div class="comments">
		    	<h3>Comments</h3>
		    	<?php foreach ($comments as $comment):
		    		if($post['post_id'] == $comment['post_id']): ?>
		    			<div class="comment_single">
			    			<img class="comment_img" src="/uploads/avatars/<?=$comment['avatar']?>">
			    			<div class="comment_single_content">	
				    			<p><?=$comment['content']?></p>
				    			
				    			<?=$comment['first_name']?> <?=$comment['last_name']?>
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
			    <form method='POST' action='/posts/p_comment/<?=$post['post_id']?>'>

				    <label for='content'>New Comment</label><br>
				    <textarea name='content' id='content'></textarea>

				    <br><br>
				    <input type='submit' value='New comment'>

				</form> 
		    </div>

	    </div>
	</article>
	<br><br>
<?php endforeach; ?>
