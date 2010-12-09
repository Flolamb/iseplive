
<div id="groups-list">
<?php
foreach($groups as $group){
	$url = Config::URL_ROOT.Routes::getPage('group', array('group' => $group['url_name']));
?>

	<div class="group-profile">
		<a href="<?php echo $url; ?>" class="avatar profile-avatar"><img src="<?php echo $group['avatar_url']; ?>" alt="" /></a>

		<div class="profile-info">
			<h1><a href="<?php echo $url; ?>"><?php echo htmlspecialchars($group['name']); ?></a></h1>
			
			<?php echo Text::inHTML($group['description']); ?><br />
			<br />
			<strong><?php echo __('GROUP_CREATION'); ?></strong> <?php echo Date::dateMonth(strtotime($group['creation_date'])); ?><br />
			
			<?php if($group['mail'] != '' && $is_logged){ ?>
			<strong><?php echo __('GROUP_CONTACT'); ?></strong> <?php echo htmlspecialchars($group['mail']); ?><br />
			<?php } ?>
		</div>
	</div>
	
<?php
}
?>
</div>

<?php
if($is_admin){
?>
<p>
	<a href="<?php echo Config::URL_ROOT.Routes::getPage('group_add'); ?>"><img src="<?php echo Config::URL_STATIC; ?>images/icons/add.png" alt="" class="icon" /> <?php echo __('GROUP_ADD'); ?></a>
</p>
<?php
}
?>
