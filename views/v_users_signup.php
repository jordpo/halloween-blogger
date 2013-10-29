<article class="general">
	<h1>Sign Up</h1>
</article>
<br><br>
<form method='POST' action='/users/p_signup'>
	
	First Name<br><input type='text' name='first_name'><br>
	Last Name <br><input type='text' name='last_name'><br>
	Email <br><input type='email' name='email'><br>
	Password <br><input type='password' name='password'><br>
	Confirm Password <br><input type='password' name='password_check'><br>

	<?php if($error == 'blanks'): ?>
		<div class='error'>
			Please fill out all fields. 
		</div>
		<br>
	<?php elseif($error == 'emailinvalid'): ?>
		<div class='error'>
			A valid email is required. 
		</div>
		<br>
	<?php elseif($error == 'emaildupe'): ?>
		<div class='error'>
			Email is already in use. Please Sign in or use a different email. 
		</div>
		<br>
	<?php elseif($error == 'password'): ?>
		<div class='error'>
			Passwords do not match.  
		</div>
		<br>
	<?php endif; ?>

	<input type='submit' value='Sign Up'>

</form>