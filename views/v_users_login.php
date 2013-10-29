<article class="general">
	<h2>Log In</h2>
</article>

<br><br>

<form method='post' action='/users/p_login'>

	Email
	<br>
	<input type='email' name='email'>
	<br>
	Password
	<br>
	<input type='password' name='password'>

	<?php if($error == 'error'): ?>
		<div class='error'>
			Login failed. Please double check your email and password. 
		</div>
		<br> <br>
	<?php elseif($error == 'emailerr'): ?>
		<div class='error'>
			Login failed. Please double check your email. 
		</div>
		<br> <br>	
	<?php endif; ?>

	<input type='submit' value='Log In'>

</form>