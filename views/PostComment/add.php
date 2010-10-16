<?php
$user_url = Config::URL_ROOT.Routes::getPage('student', array('username' => $username));
?>

<div id="post-comment-<?php echo $id; ?>" class="post-comment">
	<a href="<?php echo $user_url; ?>"><img src="<?php echo $avatar_url; ?>" alt="" class="avatar" /></a>
	<div class="post-comment-message">
		<a href="<?php echo $user_url; ?>" class="post-comment-username"><?php echo $firstname.' '.$lastname; ?></a>
		<?php echo Text::inHTML($message); ?>
		<div class="post-comment-info">
			<?php echo Date::easy(time()); ?>
		</div>
	</div>
</div>
