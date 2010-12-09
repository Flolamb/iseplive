
<div id="publish-error" class="hidden"></div>

<form id="publish-form" action="<?php echo Config::URL_ROOT.Routes::getPage('post_add'); ?>" method="post" enctype="multipart/form-data" target="publish_iframe" onsubmit="return Post.submitForm();">
	<div id="publish-left-side">
		<?php
		if($is_student){
		?>
		<span class="avatar"><img src="<?php echo $avatar_url; ?>" alt="<?php echo $firstname.' '.$lastname; ?>" /></span>
		<?php
		}else{
			echo __('PUBLISH_TITLE');
		}
		?>
	</div>

	<div id="publish-right-side">
	
		<textarea name="message" id="publish-message" class="publish-message-default"><?php echo __('PUBLISH_DEFAULT_MESSAGE'); ?></textarea>
		
		<input type="submit" id="publish-submit" value="<?php echo __('PUBLISH_SUBMIT'); ?>" />
		
		<div id="publish-attachments">
			<?php echo __('PUBLISH_ATTACHMENTS'); ?>
			<img src="<?php echo Config::URL_STATIC; ?>images/icons/attachment_photo.png" alt="" class="icon attachment-join" onclick="Post.attach('photo');" />
			<img src="<?php echo Config::URL_STATIC; ?>images/icons/attachment_video.png" alt="" class="icon attachment-join" onclick="Post.attach('video');" />
			<img src="<?php echo Config::URL_STATIC; ?>images/icons/attachment_audio.png" alt="" class="icon attachment-join" onclick="Post.attach('audio');" />
			<img src="<?php echo Config::URL_STATIC; ?>images/icons/attachment_file.png" alt="" class="icon attachment-join" onclick="Post.attach('file');" />
			<img src="<?php echo Config::URL_STATIC; ?>images/icons/event.png" alt="" class="icon attachment-join" onclick="Post.attachEvent();" />
			<img src="<?php echo Config::URL_STATIC; ?>images/icons/survey.png" alt="" class="icon attachment-join" onclick="Post.attachSurvey();" />
		</div>
		
		<div id="publish-categories">
			<?php echo __('PUBLISH_CATEGORY'); ?>
			<span id="publish-categories-list"></span>
			<select name="category" id="publish-categories-select">
				<?php foreach($categories as $category){ ?>
				<option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
				<?php } ?>
			</select>
		</div>
		
		<?php if(count($groups_auth) != 0){ ?>
		<div id="publish-group">
			<?php echo __('PUBLISH_GROUP'); ?>
			<select name="group" id="publish-group-select">
				<option value="0"><?php echo __('PUBLISH_GROUP_NONE'); ?></option>
				<?php foreach($groups_auth as $group_id => $group_data){ ?>
				<option value="<?php echo $group_id; ?>"<?php if($group_data['admin']) echo ' class="publish-group-admin"'; ?>><?php echo $group_data['name']; ?></option>
				<?php } ?>
			</select>
			<span id="publish-group-official">
				<input type="checkbox" name="official" id="publish-group-official-checkbox" value="1" />
				<label for="publish-group-official-checkbox"><?php echo __('PUBLISH_GROUP_OFFICIAL'); ?></label>
			</span>
		</div>
		<?php } ?>
		
		<?php if($is_student){ ?>
		<div id="publish-private">
			<input type="checkbox" name="private" id="publish-private-checkbox" value="1" />
			<label for="publish-private-checkbox"><?php echo __('PUBLISH_PRIVATE'); ?></label>
		</div>
		<?php } ?>
		
	</div>
</form>

<iframe name="publish_iframe" class="hidden"></iframe>

<div class="hidden">
	
	<fieldset id="publish-stock-attachment-photo" class="publish-attachment">
		<legend><img src="<?php echo Config::URL_STATIC; ?>images/icons/attachment_photo.png" alt="" class="icon" /> <?php echo __('PUBLISH_ATTACHMENT_PHOTO'); ?></legend>
		<a href="javascript:;" class="publish-attachment-delete">x</a>
		<?php echo __('PUBLISH_ATTACHMENT_SEND'); ?> <input type="file" name="attachment_photo[]" multiple /><br />
		<span class="publish-attachment-info"><?php echo __('PUBLISH_ATTACHMENT_PHOTO_INFO', array('size' => File::humanReadableSize(Config::UPLOAD_MAX_SIZE_PHOTO))); ?></span>
	</fieldset>
	
	<fieldset id="publish-stock-attachment-video" class="publish-attachment">
		<legend><img src="<?php echo Config::URL_STATIC; ?>images/icons/attachment_video.png" alt="" class="icon" /> <?php echo __('PUBLISH_ATTACHMENT_VIDEO'); ?></legend>
		<a href="javascript:;" class="publish-attachment-delete">x</a>
		<?php echo __('PUBLISH_ATTACHMENT_SEND'); ?> <input type="file" name="attachment_video[]" multiple /><br />
		<span class="publish-attachment-info"><?php echo __('PUBLISH_ATTACHMENT_VIDEO_INFO', array('size' => File::humanReadableSize(Config::UPLOAD_MAX_SIZE_VIDEO))); ?></span>
	</fieldset>
	
	<fieldset id="publish-stock-attachment-audio" class="publish-attachment">
		<legend><img src="<?php echo Config::URL_STATIC; ?>images/icons/attachment_audio.png" alt="" class="icon" /> <?php echo __('PUBLISH_ATTACHMENT_AUDIO'); ?></legend>
		<a href="javascript:;" class="publish-attachment-delete">x</a>
		<?php echo __('PUBLISH_ATTACHMENT_SEND'); ?> <input type="file" name="attachment_audio[]" multiple /><br />
		<span class="publish-attachment-info"><?php echo __('PUBLISH_ATTACHMENT_AUDIO_INFO', array('size' => File::humanReadableSize(Config::UPLOAD_MAX_SIZE_AUDIO))); ?></span>
	</fieldset>
	
	<fieldset id="publish-stock-attachment-file" class="publish-attachment">
		<legend><img src="<?php echo Config::URL_STATIC; ?>images/icons/attachment_file.png" alt="" class="icon" /> <?php echo __('PUBLISH_ATTACHMENT_FILE'); ?></legend>
		<a href="javascript:;" class="publish-attachment-delete">x</a>
		<?php echo __('PUBLISH_ATTACHMENT_SEND'); ?> <input type="file" name="attachment_file[]" multiple /><br />
		<span class="publish-attachment-info"><?php echo __('PUBLISH_ATTACHMENT_FILE_INFO', array('size' => File::humanReadableSize(Config::UPLOAD_MAX_SIZE_FILE))); ?></span>
	</fieldset>
	
	<fieldset id="publish-stock-attachment-event" class="publish-attachment">
		<legend><img src="<?php echo Config::URL_STATIC; ?>images/icons/event.png" alt="" class="icon" /> <?php echo __('PUBLISH_ATTACHMENT_EVENT'); ?></legend>
		<a href="javascript:;" class="publish-attachment-delete">x</a>
		<?php echo __('PUBLISH_EVENT_TITLE'); ?> <input type="text" name="event_title" value="" size="30" /><br />
		<?php echo __('PUBLISH_EVENT_START'); ?> <input type="text" name="event_start" value="" size="20" /><br />
		<?php echo __('PUBLISH_EVENT_END'); ?> <input type="text" name="event_end" value="" size="20" /><br />
	</fieldset>
	
	<fieldset id="publish-stock-attachment-survey" class="publish-attachment">
		<legend><img src="<?php echo Config::URL_STATIC; ?>images/icons/survey.png" alt="" class="icon" /> <?php echo __('PUBLISH_ATTACHMENT_SURVEY'); ?></legend>
		<a href="javascript:;" class="publish-attachment-delete">x</a>
		<?php echo __('PUBLISH_SURVEY_QUESTION'); ?> <input type="text" name="survey_question" value="" size="40" /><br />
		<?php echo __('PUBLISH_SURVEY_END'); ?> <input type="text" name="survey_end" value="" size="20" /><br />
		<?php echo __('PUBLISH_SURVEY_ANSWERS'); ?>
		<ul class="publish-survey-answers publish-survey-answers-unique">
			<li><input type="text" name="survey_answer[]" value="" size="30" /> <a href="javascript:;" onclick="Post.surveyDelAnswer(this);" class="publish-survey-anwser-delete hidden">x</a></li>
			<li class="publish-survey-add-answer"><a href="javascript:;" onclick="Post.surveyAddAnswer(this);"><img src="<?php echo Config::URL_STATIC; ?>images/icons/add.png" alt="" class="icon" /> <?php echo __('PUBLISH_SURVEY_ADD_ANSWER'); ?></a></li>
		</ul>
		<label><input type="checkbox" name="survey_multiple" value="1" class="publish-survey-mulitple" /> <?php echo __('PUBLISH_SURVEY_MULTIPLE'); ?></label>
	</fieldset>
	
</div>
