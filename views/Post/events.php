
<h1><?php
if(isset($group))
	echo $group['name'].' - ';
if(isset($day_time))
	echo Date::dateMonth($day_time);
else
	echo Date::getMonthByNum($calendar_month).' '.$calendar_year;
?></h1>

<div id="posts-official" class="timeline">
	<h1><?php echo __('EVENTS_TITLE_OFFICIAL'); ?></h1>
<?php
foreach($posts as $post){
	if($post['official'] == '1')
		require dirname(__FILE__).'/../_includes/view_post.php';
}
?>
</div>

<div id="posts-nonofficial" class="timeline">
	<h1><?php echo __('EVENTS_TITLE_NONOFFICIAL'); ?></h1>
<?php
if($is_logged){
	foreach($posts as $post){
		if($post['official'] == '0')
			require dirname(__FILE__).'/../_includes/view_post.php';
	}
}else{
	require dirname(__FILE__).'/../User/signin.php';
}
?>
</div>


<div id="posts-sidebar">
	<div id="posts-sidebar-content">
		<div id="calendar">
			<?php
			if(isset($group))
				$calendar_group = $group['url_name'];
			require dirname(__FILE__).'/../_includes/calendar.php';
			?>
		</div>
	</div>
	
</div>

