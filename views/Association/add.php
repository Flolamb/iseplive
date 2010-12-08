
<div class="associations-column center">
	<form action="?" method="post" enctype="multipart/form-data" id="association-profile">
		<h1><?php echo __('ASSOCIATION_ADD_TITLE'); ?></h1>
		
		<div class="profile-info no-avatar">
			<label for="association_edit_name"><?php echo __('ASSOCIATION_EDIT_FORM_NAME'); ?></label>
			<input type="text" name="name" id="association_edit_name" value="<?php if(isset($association['name'])) echo htmlspecialchars($association['name']); ?>" /><br />
			<?php if(isset($form_error) && $form_error == 'invalid_name'){ ?>
			<p class="form-error">
				<?php echo __('ASSOCIATION_EDIT_ERROR_INVALID_NAME'); ?>
			</p>
			<?php } ?>
			
			<label for="association_edit_creation_date"><?php echo __('ASSOCIATION_EDIT_FORM_CREATION_DATE'); ?></label>
			<input type="text" name="creation_date" id="association_edit_creation_date" value="<?php if(isset($association['creation_date'])) echo $association['creation_date']; ?>" /><br />
			<?php if(isset($form_error) && $form_error == 'invalid_creation_date'){ ?>
			<p class="form-error">
				<?php echo __('ASSOCIATION_EDIT_ERROR_INVALID_CREATION_DATE'); ?>
			</p>
			<?php } ?>
			
			<label for="association_edit_mail"><?php echo __('ASSOCIATION_EDIT_FORM_CONTACT'); ?></label>
			<input type="text" name="mail" id="association_edit_mail" value="<?php if(isset($association['mail'])) echo htmlspecialchars($association['mail']); ?>" /><br />
			<?php if(isset($form_error) && $form_error == 'invalid_mail'){ ?>
			<p class="form-error">
				<?php echo __('ASSOCIATION_EDIT_ERROR_INVALID_MAIL'); ?>
			</p>
			<?php } ?>
			
			<label for="association_edit_description"><?php echo __('ASSOCIATION_EDIT_FORM_DESCRIPTION'); ?></label><br />
			<textarea name="description" id="association_edit_description" cols="50" rows="5"><?php if(isset($association['description'])) echo htmlspecialchars($association['description']); ?></textarea>
			<br /><br />
			
			<label for="association_edit_avatar"><?php echo __('ASSOCIATION_EDIT_FORM_AVATAR'); ?></label>
			<input type="file" name="avatar" id="association_edit_avatar" /><br />
			<?php if(isset($form_error) && $form_error == 'avatar'){ ?>
			<p class="form-error">
				<?php echo __('ASSOCIATION_EDIT_ERROR_AVATAR', array('size' => File::humanReadableSize(Config::UPLOAD_MAX_SIZE_PHOTO))); ?>
			</p>
			<?php } ?>
			
			<br />
			<div id="association-edit-members">
				<strong><?php echo __('ASSOCIATION_MEMBERS'); ?></strong>
				<ul>
<?php
if(!isset($association['members']))
	$association['members'] = array();
foreach($association['members'] as $member){
?>
					<li>
						<div class="association-member-handle"></div>
						<a href="javascript:;" class="association-member-delete">x</a>
						<a href="<?php echo Config::URL_ROOT.Routes::getPage('student', array('username' => $member['username'])); ?>"><?php echo htmlspecialchars($member['firstname'].' '.$member['lastname']); ?></a>
						<input type="hidden" name="members_ids[]" value="<?php echo $member['user_id']; ?>" /><br />
						<label>
							<?php echo __('ASSOCIATION_EDIT_FORM_MEMBER_TITLE'); ?>
							<input type="text" name="member_title_<?php echo $member['user_id']; ?>" value="<?php echo htmlspecialchars($member['title']); ?>" />
						</label><br />
						<label>
							<input type="checkbox" name="member_admin_<?php echo $member['user_id']; ?>" value="1"<?php if($member['admin']=='1') echo ' checked="checked"'; ?> />
							<?php echo __('ASSOCIATION_EDIT_FORM_MEMBER_ADMIN'); ?>
						</label>
					</li>
<?php
}
?>
				</ul>
				
				<label for="association_edit_add_member"><?php echo __('ASSOCIATION_EDIT_FORM_ADD_MEMBER'); ?></label>
				<input type="text" name="" id="association_edit_add_member" value="" />
				<input type="hidden" name="" id="association_edit_add_member_url" value="<?php echo Config::URL_ROOT.Routes::getPage('autocompletion_student_name'); ?>" />
			</div>
			
			<br />
			<input type="submit" value="<?php echo __('ASSOCIATION_EDIT_FORM_SUBMIT'); ?>" />
		</div>
	</form>
</div>



<div id="association-edit-member-stock" class="hidden">
	<div class="association-member-handle"></div>
	<a href="javascript:;" class="association-member-delete">x</a>
	<a href="" class="association-member-name"></a>
	<input type="hidden" name="members_ids[]" value="" /><br />
	<label>
		<?php echo __('ASSOCIATION_EDIT_FORM_MEMBER_TITLE'); ?>
		<input type="text" name="member_title" value="" />
	</label><br />
	<label>
		<input type="checkbox" name="member_admin" value="1" />
		<?php echo __('ASSOCIATION_EDIT_FORM_MEMBER_ADMIN'); ?>
	</label>
</div>