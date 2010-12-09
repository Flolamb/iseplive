
<div class="profile-column center">
	<form action="?" method="post" enctype="multipart/form-data" id="group-profile">
		<h1><?php echo __('GROUP_ADD_TITLE'); ?></h1>
		
		<div class="profile-info no-avatar">
			<label for="group_edit_name"><?php echo __('GROUP_EDIT_FORM_NAME'); ?></label>
			<input type="text" name="name" id="group_edit_name" value="<?php if(isset($group['name'])) echo htmlspecialchars($group['name']); ?>" /><br />
			<?php if(isset($form_error) && $form_error == 'invalid_name'){ ?>
			<p class="form-error">
				<?php echo __('GROUP_EDIT_ERROR_INVALID_NAME'); ?>
			</p>
			<?php } ?>
			
			<label for="group_edit_creation_date"><?php echo __('GROUP_EDIT_FORM_CREATION_DATE'); ?></label>
			<input type="text" name="creation_date" id="group_edit_creation_date" value="<?php if(isset($group['creation_date'])) echo $group['creation_date']; ?>" /><br />
			<?php if(isset($form_error) && $form_error == 'invalid_creation_date'){ ?>
			<p class="form-error">
				<?php echo __('GROUP_EDIT_ERROR_INVALID_CREATION_DATE'); ?>
			</p>
			<?php } ?>
			
			<label for="group_edit_mail"><?php echo __('GROUP_EDIT_FORM_CONTACT'); ?></label>
			<input type="text" name="mail" id="group_edit_mail" value="<?php if(isset($group['mail'])) echo htmlspecialchars($group['mail']); ?>" /><br />
			<?php if(isset($form_error) && $form_error == 'invalid_mail'){ ?>
			<p class="form-error">
				<?php echo __('GROUP_EDIT_ERROR_INVALID_MAIL'); ?>
			</p>
			<?php } ?>
			
			<label for="group_edit_description"><?php echo __('GROUP_EDIT_FORM_DESCRIPTION'); ?></label><br />
			<textarea name="description" id="group_edit_description" cols="50" rows="5"><?php if(isset($group['description'])) echo htmlspecialchars($group['description']); ?></textarea>
			<br /><br />
			
			<label for="group_edit_avatar"><?php echo __('GROUP_EDIT_FORM_AVATAR'); ?></label>
			<input type="file" name="avatar" id="group_edit_avatar" /><br />
			<?php if(isset($form_error) && $form_error == 'avatar'){ ?>
			<p class="form-error">
				<?php echo __('GROUP_EDIT_ERROR_AVATAR', array('size' => File::humanReadableSize(Config::UPLOAD_MAX_SIZE_PHOTO))); ?>
			</p>
			<?php } ?>
			
			<br />
			<div id="group-edit-members">
				<strong><?php echo __('GROUP_MEMBERS'); ?></strong>
				<ul>
<?php
if(!isset($group['members']))
	$group['members'] = array();
foreach($group['members'] as $member){
?>
					<li>
						<div class="group-member-handle"></div>
						<a href="javascript:;" class="group-member-delete">x</a>
						<a href="<?php echo Config::URL_ROOT.Routes::getPage('student', array('username' => $member['username'])); ?>"><?php echo htmlspecialchars($member['firstname'].' '.$member['lastname']); ?></a>
						<input type="hidden" name="members_ids[]" value="<?php echo $member['user_id']; ?>" /><br />
						<label>
							<?php echo __('GROUP_EDIT_FORM_MEMBER_TITLE'); ?>
							<input type="text" name="member_title_<?php echo $member['user_id']; ?>" value="<?php echo htmlspecialchars($member['title']); ?>" />
						</label><br />
						<label>
							<input type="checkbox" name="member_admin_<?php echo $member['user_id']; ?>" value="1"<?php if($member['admin']=='1') echo ' checked="checked"'; ?> />
							<?php echo __('GROUP_EDIT_FORM_MEMBER_ADMIN'); ?>
						</label>
					</li>
<?php
}
?>
				</ul>
				
				<label for="group_edit_add_member"><?php echo __('GROUP_EDIT_FORM_ADD_MEMBER'); ?></label>
				<input type="text" name="" id="group_edit_add_member" value="" />
				<input type="hidden" name="" id="group_edit_add_member_url" value="<?php echo Config::URL_ROOT.Routes::getPage('autocompletion_student_name'); ?>" />
			</div>
			
			<br />
			<input type="submit" value="<?php echo __('GROUP_EDIT_FORM_SUBMIT'); ?>" />
		</div>
	</form>
</div>



<div id="group-edit-member-stock" class="hidden">
	<div class="group-member-handle"></div>
	<a href="javascript:;" class="group-member-delete">x</a>
	<a href="" class="group-member-name"></a>
	<input type="hidden" name="members_ids[]" value="" /><br />
	<label>
		<?php echo __('GROUP_EDIT_FORM_MEMBER_TITLE'); ?>
		<input type="text" name="member_title" value="" />
	</label><br />
	<label>
		<input type="checkbox" name="member_admin" value="1" />
		<?php echo __('GROUP_EDIT_FORM_MEMBER_ADMIN'); ?>
	</label>
</div>