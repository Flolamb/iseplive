
<div class="profile-column center">
	<form action="?" method="post" enctype="multipart/form-data" id="form-profile">
		<h1><?php echo __('USER_EDIT_TITLE'); ?></h1>
		
		<a href="<?php echo $user['avatar_big_url']; ?>" rel="lightbox" class="avatar profile-avatar"><img src="<?php echo $user['avatar_url']; ?>" alt="" /></a>
		
		<div class="profile-info">
			<label for="user_edit_mail"><?php echo __('USER_EDIT_FORM_MAIL'); ?></label>
			<input type="text" name="mail" id="user_edit_mail" value="<?php echo htmlspecialchars($user['mail']); ?>" /><br />
			<?php if(isset($form_error) && $form_error == 'mail'){ ?>
			<p class="form-error">
				<?php echo __('USER_EDIT_ERROR_MAIL'); ?>
			</p>
			<?php } ?>
			
			<label for="user_edit_msn"><?php echo __('USER_EDIT_FORM_MSN'); ?></label>
			<input type="text" name="msn" id="user_edit_msn" value="<?php echo htmlspecialchars($user['msn']); ?>" /><br />
			<?php if(isset($form_error) && $form_error == 'msn'){ ?>
			<p class="form-error">
				<?php echo __('USER_EDIT_ERROR_MSN'); ?>
			</p>
			<?php } ?>
			
			<label for="user_edit_jabber"><?php echo __('USER_EDIT_FORM_JABBER'); ?></label>
			<input type="text" name="jabber" id="user_edit_jabber" value="<?php echo htmlspecialchars($user['jabber']); ?>" /><br />
			<?php if(isset($form_error) && $form_error == 'jabber'){ ?>
			<p class="form-error">
				<?php echo __('USER_EDIT_ERROR_JABBER'); ?>
			</p>
			<?php } ?>
			
			<label for="user_edit_address"><?php echo __('USER_EDIT_FORM_ADDRESS'); ?></label>
			<input type="text" name="address" id="user_edit_address" value="<?php echo htmlspecialchars($user['address']); ?>" /><br />
			
			<label for="user_edit_zipcode"><?php echo __('USER_EDIT_FORM_ZIPCODE'); ?></label>
			<input type="text" name="zipcode" id="user_edit_zipcode" value="<?php echo htmlspecialchars($user['zipcode']); ?>" /><br />
			<?php if(isset($form_error) && $form_error == 'zipcode'){ ?>
			<p class="form-error">
				<?php echo __('USER_EDIT_ERROR_ZIPCODE'); ?>
			</p>
			<?php } ?>
			
			<label for="user_edit_city"><?php echo __('USER_EDIT_FORM_CITY'); ?></label>
			<input type="text" name="city" id="user_edit_city" value="<?php echo htmlspecialchars($user['city']); ?>" /><br />
			
			<label for="user_edit_cellphone"><?php echo __('USER_EDIT_FORM_CELLPHONE'); ?></label>
			<input type="text" name="cellphone" id="user_edit_cellphone" value="<?php echo $user['cellphone']; ?>" /><br />
			<?php if(isset($form_error) && $form_error == 'cellphone'){ ?>
			<p class="form-error">
				<?php echo __('USER_EDIT_ERROR_CELLPHONE'); ?>
			</p>
			<?php } ?>
			
			<label for="user_edit_phone"><?php echo __('USER_EDIT_FORM_PHONE'); ?></label>
			<input type="text" name="phone" id="user_edit_phone" value="<?php echo $user['phone']; ?>" /><br />
			<?php if(isset($form_error) && $form_error == 'phone'){ ?>
			<p class="form-error">
				<?php echo __('USER_EDIT_ERROR_PHONE'); ?>
			</p>
			<?php } ?>
			
			<label for="user_edit_birthday"><?php echo __('USER_EDIT_FORM_BIRTHDAY'); ?></label>
			<input type="text" name="birthday" id="user_edit_birthday" value="<?php echo $user['birthday']; ?>" /><br />
			<?php if(isset($form_error) && $form_error == 'birthday'){ ?>
			<p class="form-error">
				<?php echo __('USER_EDIT_ERROR_BIRTHDAY'); ?>
			</p>
			<?php } ?>
			
			<br />
			<input type="submit" value="<?php echo __('USER_EDIT_FORM_SUBMIT'); ?>" />
		</div>
	</form>
</div>
