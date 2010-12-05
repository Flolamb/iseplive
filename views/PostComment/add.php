<?php
$user_url = Config::URL_ROOT.Routes::getPage('student', array('username' => $username));
?>

<div id="post-comment-<?php echo $id; ?>" class="post-comment<?php
echo ' post-comment-attachment'.(isset($attachment_id) ? $attachment_id: '0');
?>">
	<a href="<?php echo $user_url; ?>" class="avatar"><img src="<?php echo $avatar_url; ?>" alt="" /></a>
	<a href="<?php echo Config::URL_ROOT.Routes::getPage('post_comment_delete', array('id' => $id)); ?>" class="post-comment-delete">x</a>
	<div class="post-comment-message">
		<a href="<?php echo $user_url; ?>" class="post-comment-username"><?php echo htmlspecialchars($firstname.' '.$lastname); ?></a>
		<?php echo Text::inHTML($message); ?>
		<div class="post-comment-info">
			<?php echo Date::easy(time()); ?>
		</div>
	</div>
</div>
