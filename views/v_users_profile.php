<style type="text/css">
span {
	color:DarkOrange;
	text-decoration:underline;
	text-shadow: 1px 1px 1px #000;
}
</style>

<?php if(isset($user_name)): ?> <!-- limited logic is OK when it is determining view-->
	<h1>This is the profile for <span><?=$user_name?></span></h1>
<?php else: ?>
	<h1>No user has been specified</h1>
<?php endif; ?>