
<div id="associations-list">
<?php
foreach($associations as $association){
	$url = Config::URL_ROOT.Routes::getPage('association', array('association' => $association['url_name']));
?>

	<div class="association-profile">
		<a href="<?php echo $url; ?>" class="avatar profile-avatar"><img src="<?php echo $association['avatar_url']; ?>" alt="" /></a>

		<div class="profile-info">
			<h1><a href="<?php echo $url; ?>"><?php echo htmlspecialchars($association['name']); ?></a></h1>
			
			<?php echo nl2br(htmlspecialchars($association['description'])); ?><br />
			<br />
			<strong><?php echo __('ASSOCIATION_CREATION'); ?></strong> <?php echo Date::dateHour(strtotime($association['creation_date'])); ?><br />
			
			<?php if($association['mail'] != ''){ ?>
			<strong><?php echo __('ASSOCIATION_CONTACT'); ?></strong> <?php echo htmlspecialchars($association['mail']); ?><br />
			<?php } ?>
		</div>
	</div>
	
<?php
}
?>
</div>
