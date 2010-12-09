
<div class="profile-column center">
	<form action="?" method="post" enctype="multipart/form-data" id="form-profile">
		<h1><?php echo __('STUDENT_EDIT_TITLE', array('username' => $student['username'])); ?></h1>
		
		<a href="<?php echo $student['avatar_big_url']; ?>" rel="lightbox" class="avatar profile-avatar"><img src="<?php echo $student['avatar_url']; ?>" alt="" /></a>
		
		<div class="profile-info">
			<label for="student_edit_firstname"><?php echo __('STUDENT_EDIT_FORM_FIRSTNAME'); ?></label>
			<input type="text" name="firstname" id="student_edit_firstname" value="<?php echo htmlspecialchars($student['firstname']); ?>" /><br />
			<?php if(isset($form_error) && $form_error == 'firstname'){ ?>
			<p class="form-error">
				<?php echo __('STUDENT_EDIT_ERROR_FIRSTNAME'); ?>
			</p>
			<?php } ?>
			
			<label for="student_edit_lastname"><?php echo __('STUDENT_EDIT_FORM_LASTNAME'); ?></label>
			<input type="text" name="lastname" id="student_edit_lastname" value="<?php echo htmlspecialchars($student['lastname']); ?>" /><br />
			<?php if(isset($form_error) && $form_error == 'lastname'){ ?>
			<p class="form-error">
				<?php echo __('STUDENT_EDIT_ERROR_LASTNAME'); ?>
			</p>
			<?php } ?>
			
			<label for="student_edit_student_number"><?php echo __('STUDENT_EDIT_FORM_STUDENT_NUMBER'); ?></label>
			<input type="text" name="student_number" id="student_edit_student_number" value="<?php echo htmlspecialchars($student['student_number']); ?>" /><br />
			<?php if(isset($form_error) && $form_error == 'student_number'){ ?>
			<p class="form-error">
				<?php echo __('STUDENT_EDIT_ERROR_STUDENT_NUMBER'); ?>
			</p>
			<?php } ?>
			
			<label for="student_edit_promo"><?php echo __('STUDENT_EDIT_FORM_PROMO'); ?></label>
			<input type="text" name="promo" id="student_edit_promo" value="<?php echo htmlspecialchars($student['promo']); ?>" />
			<input type="checkbox" name="cesure" id="student_edit_cesure" value="1"<?php if($student['cesure']) echo ' checked="checked"'; ?> />
			<label for="student_edit_cesure" id="student_edit_cesure_label"><?php echo __('STUDENT_EDIT_FORM_CESURE'); ?></label><br />
			<?php if(isset($form_error) && $form_error == 'promo'){ ?>
			<p class="form-error">
				<?php echo __('STUDENT_EDIT_ERROR_PROMO'); ?>
			</p>
			<?php } ?>
			
			<label for="user_edit_avatar"><?php echo __('USER_EDIT_FORM_AVATAR'); ?></label>
			<input type="file" name="avatar" id="user_edit_avatar" /><br />
			<?php if(isset($form_error) && $form_error == 'avatar'){ ?>
			<p class="form-error">
				<?php echo __('USER_EDIT_ERROR_AVATAR', array('size' => File::humanReadableSize(Config::UPLOAD_MAX_SIZE_PHOTO))); ?>
			</p>
			<?php } ?>
			
			<label for="user_edit_mail"><?php echo __('USER_EDIT_FORM_MAIL'); ?></label>
			<input type="text" name="mail" id="user_edit_mail" value="<?php echo htmlspecialchars($student['mail']); ?>" /><br />
			<?php if(isset($form_error) && $form_error == 'mail'){ ?>
			<p class="form-error">
				<?php echo __('USER_EDIT_ERROR_MAIL'); ?>
			</p>
			<?php } ?>
			
			<label for="user_edit_msn"><?php echo __('USER_EDIT_FORM_MSN'); ?></label>
			<input type="text" name="msn" id="user_edit_msn" value="<?php echo htmlspecialchars($student['msn']); ?>" /><br />
			<?php if(isset($form_error) && $form_error == 'msn'){ ?>
			<p class="form-error">
				<?php echo __('USER_EDIT_ERROR_MSN'); ?>
			</p>
			<?php } ?>
			
			<label for="user_edit_jabber"><?php echo __('USER_EDIT_FORM_JABBER'); ?></label>
			<input type="text" name="jabber" id="user_edit_jabber" value="<?php echo htmlspecialchars($student['jabber']); ?>" /><br />
			<?php if(isset($form_error) && $form_error == 'jabber'){ ?>
			<p class="form-error">
				<?php echo __('USER_EDIT_ERROR_JABBER'); ?>
			</p>
			<?php } ?>
			
			<label for="user_edit_address"><?php echo __('USER_EDIT_FORM_ADDRESS'); ?></label>
			<input type="text" name="address" id="user_edit_address" value="<?php echo htmlspecialchars($student['address']); ?>" /><br />
			
			<label for="user_edit_zipcode"><?php echo __('USER_EDIT_FORM_ZIPCODE'); ?></label>
			<input type="text" name="zipcode" id="user_edit_zipcode" value="<?php echo htmlspecialchars($student['zipcode']); ?>" /><br />
			<?php if(isset($form_error) && $form_error == 'zipcode'){ ?>
			<p class="form-error">
				<?php echo __('USER_EDIT_ERROR_ZIPCODE'); ?>
			</p>
			<?php } ?>
			
			<label for="user_edit_city"><?php echo __('USER_EDIT_FORM_CITY'); ?></label>
			<input type="text" name="city" id="user_edit_city" value="<?php echo htmlspecialchars($student['city']); ?>" /><br />
			
			<label for="user_edit_cellphone"><?php echo __('USER_EDIT_FORM_CELLPHONE'); ?></label>
			<input type="text" name="cellphone" id="user_edit_cellphone" value="<?php echo $student['cellphone']; ?>" /><br />
			<?php if(isset($form_error) && $form_error == 'cellphone'){ ?>
			<p class="form-error">
				<?php echo __('USER_EDIT_ERROR_CELLPHONE'); ?>
			</p>
			<?php } ?>
			
			<label for="user_edit_phone"><?php echo __('USER_EDIT_FORM_PHONE'); ?></label>
			<input type="text" name="phone" id="user_edit_phone" value="<?php echo $student['phone']; ?>" /><br />
			<?php if(isset($form_error) && $form_error == 'phone'){ ?>
			<p class="form-error">
				<?php echo __('USER_EDIT_ERROR_PHONE'); ?>
			</p>
			<?php } ?>
			
			<label for="user_edit_birthday"><?php echo __('USER_EDIT_FORM_BIRTHDAY'); ?></label>
			<input type="text" name="birthday" id="user_edit_birthday" value="<?php echo $student['birthday']; ?>" /><br />
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
