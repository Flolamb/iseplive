<h1><?php echo __('SEARCH_TITLE', array('query' => htmlspecialchars($query))); ?></h1>

<?php
if(count($posts) != 0){
?>

<div id="posts-search" class="timeline">
	<h1><?php echo __('SEARCH_POSTS'); ?></h1>
	<?php 
	foreach($posts as $post){
		require dirname(__FILE__).'/../_includes/view_post.php';
	}
	?>
</div>

<?php
}

if(count($groups) != 0){
?>

<div id="groups-search" class="timeline">
	<h1><?php echo __('SEARCH_GROUPS'); ?></h1>
	<?php
	foreach($groups as $group){
		$url = Config::URL_ROOT.Routes::getPage('group', array('group' => $group['url_name']));
	?>
	<div class="post">
		<a href="<?php echo $url; ?>" class="avatar"><img src="<?php echo $group['avatar_url']; ?>" alt="" /></a>
		<div class="post-message">
			<a href="<?php echo $url; ?>" class="post-username"><?php echo htmlspecialchars($group['name']); ?></a>
		</div>
	</div>
	
	<?php } ?>
</div>

<?php
}

if(count($students) != 0){
?>

<div id="students-search" class="timeline">
	<h1><?php echo __('SEARCH_STUDENTS'); ?></h1>
	<?php
	foreach($students as $student){
		$url = Config::URL_ROOT.Routes::getPage('student', array('username' => $student['username']));
	?>
	<div class="post">
		<a href="<?php echo $url; ?>" class="avatar"><img src="<?php echo $student['avatar_url']; ?>" alt="" /></a>
		<div class="post-message">
			<a href="<?php echo $url; ?>" class="post-username"><?php echo htmlspecialchars($student['firstname'].' '.$student['lastname']); ?></a>
		</div>
	</div>
	
	<?php } ?>
</div>

<?php
}
?>