
<div class="profile-column <?php echo isset($posts) ? 'left' : 'center'; ?>">
	<a href="<?php echo $student['avatar_big_url']; ?>" rel="lightbox" class="avatar profile-avatar"><img src="<?php echo $student['avatar_url']; ?>" alt="" /></a>

	<div class="profile-info">
		<h1><?php echo htmlspecialchars($student['firstname'].' '.$student['lastname']); ?></h1>
		
		<?php if($is_admin){ ?>
		<a href="<?php echo Config::URL_ROOT.Routes::getPage('student_edit', array('username' => $student['username'])); ?>"><img src="<?php echo Config::URL_STATIC; ?>images/icons/edit.png" alt="" class="icon" /> <?php echo __('PROFILE_EDIT'); ?></a><br /><br />
		<?php }else if($is_owner){ ?>
		<a href="<?php echo Config::URL_ROOT.Routes::getPage('profile_edit'); ?>"><img src="<?php echo Config::URL_STATIC; ?>images/icons/edit.png" alt="" class="icon" /> <?php echo __('PROFILE_EDIT_PROFILE'); ?></a><br /><br />
		<?php } ?>
		
		
		<strong><?php echo __('PROFILE_PROMO'); ?></strong> <?php echo $student['promo']; if($student['cesure']=='1') echo ' '.__('PROFILE_CESURE') ?><br />
		<strong><?php echo __('PROFILE_STUDENT_NUMBER'); ?></strong> <?php echo $student['student_number']; ?><br />
		<br />
		
		<strong><?php echo __('PROFILE_MAIL_ISEP'); ?></strong> <?php echo htmlspecialchars($student['username'].'@isep.fr'); ?><br />
		
		<?php if(isset($student['mail']) && $student['mail'] != ''){ ?>
		<strong><?php echo __('PROFILE_MAIL'); ?></strong> <?php echo htmlspecialchars($student['mail']); ?><br />
		<?php } ?>
		
		<?php if(isset($student['msn']) && $student['msn'] != ''){ ?>
		<strong><?php echo __('PROFILE_MSN'); ?></strong> <?php echo htmlspecialchars($student['msn']); ?><br />
		<?php } ?>
		
		<?php if(isset($student['jabber']) && $student['jabber'] != ''){ ?>
		<strong><?php echo __('PROFILE_JABBER'); ?></strong> <?php echo htmlspecialchars($student['jabber']); ?><br />
		<?php } ?>
		
		<?php if(isset($student['address']) && $student['address'] != ''){ ?>
		<strong><?php echo __('PROFILE_ADDRESS'); ?></strong> <?php echo
			htmlspecialchars($student['address']).
			(isset($student['zipcode']) || $student['city']!=''
				?	($student['address']!='' ? ',' : '').
					(isset($student['zipcode']) ? ' '.htmlspecialchars($student['zipcode']) : '').
					($student['city']!='' ? ' '.htmlspecialchars($student['city']) : '')
				: ''
			); ?><br />
		<?php } ?>
		
		<?php if(isset($student['cellphone']) && $student['cellphone'] != ''){ ?>
		<strong><?php echo __('PROFILE_PHONE'); ?></strong> <?php echo htmlspecialchars($student['cellphone']); ?><br />
		<?php } ?>
		
		<?php if(isset($student['phone']) && $student['phone'] != ''){ ?>
		<strong><?php echo __('PROFILE_PHONE'); ?></strong> <?php echo htmlspecialchars($student['phone']); ?><br />
		<?php } ?>
		
		<?php if(isset($student['birthday']) && $student['birthday'] != '0000-00-00'){ ?>
		<strong><?php echo __('PROFILE_BIRTHDAY'); ?></strong> <?php echo date(__('PROFILE_BIRTHDAY_FORMAT'), strtotime($student['birthday'])); ?><br />
		<?php } ?>
		
		<?php if(count($groups) != 0){ ?>
		<br />
		<strong><?php echo __('PROFILE_GROUPS'); ?></strong>
		<ul>
<?php
foreach($groups as $group){
?>
			<li>
				<a href="<?php echo Config::URL_ROOT.Routes::getPage('group', array('group' => $group['url_name'])); ?>"><?php echo htmlspecialchars($group['name']); ?></a>
				<?php if($group['title'] !='') echo '('.htmlspecialchars($group['title']).')'; ?>
			</li>
<?php
}
?>
		</ul>
		<?php } ?>
		
	</div>
</div>


<?php
if(isset($posts)){
?>

<div id="posts-nonofficial" class="timeline user">
	<h1><?php echo __('PROFILE_POSTS'); ?></h1>
<?php 
foreach($posts as $post){
	require dirname(__FILE__).'/../_includes/view_post.php';
}
if(isset($current_category)){
?>
	<a href="<?php echo Config::URL_ROOT.Routes::getPage('user_posts_category_ajax_page', array('user_id' => $student['id'], 'category' => $current_category, 'page' => '{page}')); ?>" class="posts-more-link official"><?php echo __('POST_SHOW_MORE'); ?></a>
<?php
}else{
?>
	<a href="<?php echo Config::URL_ROOT.Routes::getPage('user_posts_ajax_page', array('user_id' => $student['id'], 'page' => '{page}')); ?>" class="posts-more-link official"><?php echo __('POST_SHOW_MORE'); ?></a>
<?php
}
?>
</div>


<div id="posts-sidebar">
	<div id="posts-sidebar-content">
		<h2><?php echo __('POST_CATEGORIES'); ?></h2>
		<ul>
			<li><a href="<?php echo Config::URL_ROOT.Routes::getPage('student', array('username' => $student['username'])); ?>"<?php if(!isset($current_category)) echo ' class="active"'; ?>><?php echo __('POST_CATEGORIES_ALL'); ?></a></li>
<?php
foreach($categories as $category){
?>
			<li><a href="<?php echo Config::URL_ROOT.Routes::getPage('user_posts_category', array('username' => $student['username'], 'category' => $category['url_name'])); ?>"<?php if($category['url_name'] == $current_category) echo ' class="active"'; ?>><?php echo $category['name']; ?></a></li>
<?php
}
?>
		</ul>
	</div>
	
</div>

<?php
}
?>