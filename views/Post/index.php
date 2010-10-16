
<?php
if($is_student)
	require dirname(__FILE__).'/../_includes/publish.php';
?>


<div id="posts-official" class="timeline">
	<h1><?php echo __('POST_TITLE_OFFICIAL'); ?></h1>
<?php 
foreach($official_posts as $post){
	require dirname(__FILE__).'/../_includes/view_post.php';
}
if(isset($current_category)){
?>
	<a href="<?php echo Config::URL_ROOT.Routes::getPage('posts_category_ajax_page', array('category' => $current_category, 'page' => '{page}', 'official' => '1')); ?>" class="posts-more-link official"><?php echo __('POST_SHOW_MORE'); ?></a>
<?php
}else{
?>
	<a href="<?php echo Config::URL_ROOT.Routes::getPage('posts_ajax_page', array('page' => '{page}', 'official' => '1')); ?>" class="posts-more-link official"><?php echo __('POST_SHOW_MORE'); ?></a>
<?php
}
?>
</div>


<div id="posts-nonofficial" class="timeline">
<?php
if($is_logged){
?>
	<h1><?php echo __('POST_TITLE_NONOFFICIAL'); ?></h1>
<?php 
	foreach($posts as $post){
		require dirname(__FILE__).'/../_includes/view_post.php';
	}
	if(isset($current_category)){
?>
	<a href="<?php echo Config::URL_ROOT.Routes::getPage('posts_category_ajax_page', array('category' => $current_category, 'page' => '{page}', 'official' => '0')); ?>" class="posts-more-link"><?php echo __('POST_SHOW_MORE'); ?></a>
<?php
	}else{
?>
	<a href="<?php echo Config::URL_ROOT.Routes::getPage('posts_ajax_page', array('page' => '{page}', 'official' => '0')); ?>" class="posts-more-link"><?php echo __('POST_SHOW_MORE'); ?></a>
<?php
	}

}else{
	require dirname(__FILE__).'/../User/signin.php';
}
?>
</div>


<div id="posts-sidebar">
	<div id="posts-sidebar-content">
		<h2><?php echo __('POST_CATEGORIES'); ?></h2>
		<ul>
			<li><a href="<?php echo Config::URL_ROOT.Routes::getPage('posts'); ?>"<?php if(!isset($current_category)) echo ' class="active"'; ?>><?php echo __('POST_CATEGORIES_ALL'); ?></a></li>
<?php
foreach($categories as $category){
?>
			<li><a href="<?php echo Config::URL_ROOT.Routes::getPage('posts_category', array('category' => $category['url_name'])); ?>"<?php if($category['url_name'] == $current_category) echo ' class="active"'; ?>><?php echo $category['name']; ?></a></li>
<?php
}
?>
		</ul>
		<br /><br />
	
		<div id="calendar">
			<?php
			require dirname(__FILE__).'/../_includes/calendar.php';
			?>
		</div>
	</div>
	
</div>
