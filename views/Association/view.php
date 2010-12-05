
<div class="associations-column left">
	<div class="association-profile">
		<a href="<?php echo $association['avatar_big_url']; ?>" rel="lightbox" class="avatar profile-avatar"><img src="<?php echo $association['avatar_url']; ?>" alt="" /></a>

		<div class="profile-info">
			<h1><?php echo htmlspecialchars($association['name']); ?></h1>
			
			<?php echo nl2br(htmlspecialchars($association['description'])); ?><br />
			<br />
			<strong><?php echo __('ASSOCIATION_CREATION'); ?></strong> <?php echo Date::dateHour(strtotime($association['creation_date'])); ?><br />
			
			<?php if($association['mail'] != ''){ ?>
			<strong><?php echo __('ASSOCIATION_CONTACT'); ?></strong> <?php echo htmlspecialchars($association['mail']); ?><br />
			<?php } ?>
			
			<br />
			<strong><?php echo __('ASSOCIATION_MEMBERS'); ?></strong>
			<ul>
<?php
foreach($association['members'] as $member){
?>
				<li>
					<a href="<?php echo Config::URL_ROOT.Routes::getPage('student', array('username' => $member['username'])); ?>"><?php echo $member['firstname'].' '.$member['lastname']; ?></a>
					(<?php echo $member['title']; ?>)
				</li>
<?php
}
?>
			</ul>
		</div>
	</div>
</div>


<div id="posts-official" class="timeline association">
	<h1><?php echo __('ASSOCIATION_POSTS'); ?></h1>
<?php 
foreach($posts as $post){
	require dirname(__FILE__).'/../_includes/view_post.php';
}
if(isset($current_category)){
?>
	<a href="<?php echo Config::URL_ROOT.Routes::getPage('association_posts_category_ajax_page', array('association' => $association['url_name'], 'category' => $current_category, 'page' => '{page}')); ?>" class="posts-more-link official"><?php echo __('POST_SHOW_MORE'); ?></a>
<?php
}else{
?>
	<a href="<?php echo Config::URL_ROOT.Routes::getPage('association_posts_ajax_page', array('association' => $association['url_name'], 'page' => '{page}')); ?>" class="posts-more-link official"><?php echo __('POST_SHOW_MORE'); ?></a>
<?php
}
?>
</div>


<div id="posts-sidebar">
	<div id="posts-sidebar-content">
		<h2><?php echo __('POST_CATEGORIES'); ?></h2>
		<ul>
			<li><a href="<?php echo Config::URL_ROOT.Routes::getPage('association', array('association' => $association['url_name'])); ?>"<?php if(!isset($current_category)) echo ' class="active"'; ?>><?php echo __('POST_CATEGORIES_ALL'); ?></a></li>
<?php
foreach($categories as $category){
?>
			<li><a href="<?php echo Config::URL_ROOT.Routes::getPage('association_posts_category', array('association' => $association['url_name'], 'category' => $category['url_name'])); ?>"<?php if($category['url_name'] == $current_category) echo ' class="active"'; ?>><?php echo $category['name']; ?></a></li>
<?php
}
?>
		</ul>
		<br /><br />
	
		<div id="calendar">
			<?php
			$calendar_association = $association['url_name'];
			require dirname(__FILE__).'/../_includes/calendar.php';
			?>
		</div>
	</div>
	
</div>
