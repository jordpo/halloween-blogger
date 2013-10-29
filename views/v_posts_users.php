<article class="general">
    <h1>Make sure to follow someone!</h1>
    <p>What do you want to see in your <a href="/posts">Posts</a> feed?</p>
</article>
<br><br>
<?php foreach($pusers as $puser): ?>
    <?php if($user->user_id == $puser['user_id']): ?>
        <article class="users_top users">
            
            <div>
                <img src="/uploads/avatars/<?=$puser['avatar']?>">
                <br>
                <p>
                    <?=$puser['first_name']?> <?=$puser['last_name']?>
                </p>

            </div>

            <nav>
                <!-- If there exists a connection with this user, show a unfollow link -->
                <?php if(isset($connections[$puser['user_id']])): ?>
                    <a href='/posts/unfollow/<?=$puser['user_id']?>'>Hide posts from you</a>

                <!-- Otherwise, show the follow link -->
                <?php else: ?>
                    <a href='/posts/follow/<?=$puser['user_id']?>'>See posts from you</a>
                <?php endif; ?>
            </nav>
        </article>

        <br><br>
    <?php endif;?>

    

<?php endforeach; ?>

<?php foreach($pusers as $puser): ?>
    <?php if($user->user_id != $puser['user_id']): ?>
        <article class="users">
            
            <div>
                <img src="/uploads/avatars/<?=$puser['avatar']?>">
                <br>
                <p>
                    <?=$puser['first_name']?> <?=$puser['last_name']?>
                </p>    
            </div>

            <nav>
                <a href="/users/profile/<?=$puser['user_id']?>">Profile</a>
                <!-- If there exists a connection with this user, show a unfollow link -->
                <?php if(isset($connections[$puser['user_id']])): ?>
                    <a href='/posts/unfollow/<?=$puser['user_id']?>'>Unfollow</a>

                <!-- Otherwise, show the follow link -->
                <?php else: ?>
                    <a href='/posts/follow/<?=$puser['user_id']?>'>Follow</a>
                <?php endif; ?>
            </nav>
        </article>
        <br><br>
    <?php endif;?>
<?php endforeach; ?>