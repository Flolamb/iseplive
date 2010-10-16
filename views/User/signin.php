
<div id="signin-block">
	<h1><?php echo __('SIGNIN_TITLE'); ?></h1>

	<form action="<?php echo Config::URL_ROOT.Routes::getPage('signin', array('redirect' => isset($signin_redirect) ? $signin_redirect : $_SERVER['REQUEST_URI'])); ?>" method="post">
		<p id="signin-block-username">
			<label for="signin-username"><?php echo __('SIGNIN_USERNAME'); ?></label>
			<input type="text" name="username" id="signin-username" value="" />
		</p>
		<p id="signin-block-password">
			<label for="signin-password"><?php echo __('SIGNIN_PASSWORD'); ?></label>
			<input type="password" name="password" id="signin-password" value="" />
		</p>
		<p id="signin-block-submit">
			<input type="submit" value="<?php echo __('SIGNIN_SUBMIT'); ?>" />
		</p>
	</form>

	<?php
	if(User_Model::$auth_status == User_Model::AUTH_STATUS_BAD_USERNAME_OR_PASSWORD){
	?>
	<p class="form-error">
		<?php echo __('SIGNIN_BAD_USERNAME_OR_PASSWORD'); ?>
	</p>
	<?php
	}
	?>
</div>
