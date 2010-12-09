
<div class="profile-column left">
	<div class="group-profile">
		<a href="<?php echo $group['avatar_big_url']; ?>" rel="lightbox" class="avatar profile-avatar"><img src="<?php echo $group['avatar_url']; ?>" alt="" /></a>

		<div class="profile-info">
			<h1><?php echo htmlspecialchars($group['name']); ?></h1>
			
			<?php
			if($is_admin || (isset($groups_auth[(int) $group['id']]) && $groups_auth[(int) $group['id']]['admin'])){
			?>
			<a href="<?php echo Config::URL_ROOT.Routes::getPage('group_edit', array('group' => $group['url_name'])); ?>"><img src="<?php echo Config::URL_STATIC; ?>images/icons/edit.png" alt="" class="icon" /> <?php echo __('GROUP_EDIT'); ?></a>
			<a href="<?php echo Config::URL_ROOT.Routes::getPage('group_delete', array('group' => $group['url_name'])); ?>" onclick="if(!confirm(<?php echo htmlspecialchars(json_encode(__('GROUP_DELETE_CONFIRM'))); ?>)) return false;"><img src="<?php echo Config::URL_STATIC; ?>images/icons/delete.png" alt="" class="icon" /> <?php echo __('GROUP_DELETE'); ?></a>
			<br /><br />
			<?php
			}
			?>
			
			<?php echo Text::inHTML($group['description']); ?><br />
			<br />
			<strong><?php echo __('GROUP_CREATION'); ?></strong> <?php echo Date::dateMonth(strtotime($group['creation_date'])); ?><br />
			
			<?php if($group['mail'] != '' && $is_logged){ ?>
			<strong><?php echo __('GROUP_CONTACT'); ?></strong> <?php echo htmlspecialchars($group['mail']); ?><br />
			<?php } ?>
			
			<br />
			<strong><?php echo __('GROUP_MEMBERS'); ?></strong>
			<ul>
<?php
foreach($group['members'] as $member){
?>
				<li>
					<a href="<?php echo Config::URL_ROOT.Routes::getPage('student', array('username' => $member['username'])); ?>"><?php echo htmlspecialchars($member['firstname'].' '.$member['lastname']); ?></a>
					<?php if($member['title'] !='') echo '('.htmlspecialchars($member['title']).')'; ?>
				</li>
<?php
}
?>
			</ul>
		</div>
	</div>
</div>


<div id="posts-official" class="timeline group">
	<h1><?php echo __('GROUP_POSTS'); ?></h1>
<?php 
foreach($posts as $post){
	require dirname(__FILE__).'/../_includes/view_post.php';
}
if(isset($current_category)){
?>
	<a href="<?php echo Config::URL_ROOT.Routes::getPage('group_posts_category_ajax_page', array('group' => $group['url_name'], 'category' => $current_category, 'page' => '{page}')); ?>" class="posts-more-link official"><?php echo __('POST_SHOW_MORE'); ?></a>
<?php
}else{
?>
	<a href="<?php echo Config::URL_ROOT.Routes::getPage('group_posts_ajax_page', array('group' => $group['url_name'], 'page' => '{page}')); ?>" class="posts-more-link official"><?php echo __('POST_SHOW_MORE'); ?></a>
<?php
}
?>
</div>


<div id="posts-sidebar">
	<div id="posts-sidebar-content">
		<h2><?php echo __('POST_CATEGORIES'); ?></h2>
		<ul>
			<li><a href="<?php echo Config::URL_ROOT.Routes::getPage('group', array('group' => $group['url_name'])); ?>"<?php if(!isset($current_category)) echo ' class="active"'; ?>><?php echo __('POST_CATEGORIES_ALL'); ?></a></li>
<?php
foreach($categories as $category){
?>
			<li><a href="<?php echo Config::URL_ROOT.Routes::getPage('group_posts_category', array('group' => $group['url_name'], 'category' => $category['url_name'])); ?>"<?php if($category['url_name'] == $current_category) echo ' class="active"'; ?>><?php echo $category['name']; ?></a></li>
<?php
}
?>
		</ul>
		<br /><br />
	
		<div id="calendar">
			<?php
			$calendar_group = $group['url_name'];
			require dirname(__FILE__).'/../_includes/calendar.php';
			?>
		</div>
	</div>
	
</div>
