
<div id="post-<?php echo $post['id']; ?>" class="post">

<?php
$post_user_url = Config::URL_ROOT.Routes::getPage('student', array('username' => $post['username']));
$post_user_name = isset($post['firstname']) ? $post['firstname'].' '.$post['lastname'] : $post['username'];
if(isset($post['avatar_url'])){
?>
	<a href="<?php echo $post_user_url; ?>"><img src="<?php echo $post['avatar_url']; ?>" alt="" class="avatar" /></a>
<?php
}
?>

	<div class="post-message">
		<a href="<?php echo $post_user_url; ?>" class="post-username"><?php echo $post_user_name; ?></a>
		<?php echo Text::inHTML($post['message']); ?>

<?php

// Event
if(isset($post['event'])){
?>
<div class="event">
	<img src="<?php echo Config::URL_STATIC; ?>images/icons/event.png" alt="" class="icon" /> <strong><?php echo htmlspecialchars($post['event']['title']); ?></strong><br />
	<?php echo Date::event(strtotime($post['event']['date_start']), strtotime($post['event']['date_end'])); ?>
</div>
<?php
}



// Survey
if(isset($post['survey'])){
?>
<form action="<?php echo Config::URL_ROOT.Routes::getPage('survey_vote', array('id' => $post['survey']['id'])); ?>" class="survey" method="post">
	<img src="<?php echo Config::URL_STATIC; ?>images/icons/survey.png" alt="" class="icon" /> <strong><?php echo htmlspecialchars($post['survey']['question']); ?></strong><br />
	<ul>
<?php
	$showresults = (isset($username) && $username != $post['username']) || strtotime($post['survey']['date_end']) < time();
	$total_votes = 0;
	foreach($post['survey']['answers'] as &$answer){
		$total_votes += (int) $answer['nb_votes'];
		$answer['votes'] = $answer['votes'] == '' ? array() : json_decode($answer['votes'], true);
	}
	
	foreach($post['survey']['answers'] as &$answer){
		// Results
		$perc = $total_votes==0 ? 0 : ((int) $answer['nb_votes'])/$total_votes;
		$perc_s = round(100*$perc);
		/*
		* 	Graph of colors
		* 	|\  /\  /    <-- blue, then red
		* 	| \/  \/
		* 	| /\  /\
		* 	|/  \/  \    <-- green
		* 	------------
		*/
		if($perc < 0.5){
			$red = '00';
			$green = str_pad(dechex(255 * $perc*2), 2, '0', STR_PAD_LEFT);
			$blue = str_pad(dechex(255 * (1 - $perc*2)), 2, '0', STR_PAD_LEFT);
		}else{
			$red = str_pad(dechex(255 * ($perc - 0.5)*2), 2, '0', STR_PAD_LEFT);
			$green = str_pad(dechex(255 * (1 - ($perc - 0.5)*2)), 2, '0', STR_PAD_LEFT);
			$blue = '00';
		}
?>
		<li class="survey-answer-result">
			<?php echo htmlspecialchars($answer['answer']); ?><br />
			<div class="answer-bar">
				<div style="width: <?php echo $perc_s; ?>%; background-color: #<?php echo $red.$green.$blue; ?>;">
					&nbsp;<?php echo $perc_s; ?>%
				</div>
			</div>
			<?php echo __('POST_SURVEY_NB_VOTES', array('votes' => $answer['nb_votes'])); ?>
		</li>
<?php
		
		// Form to votes
		if($is_student){
?>
		<li class="survey-answer-vote">
			<label>
				<input type="<?php echo $post['survey']['multiple']=='1' ? 'checkbox' : 'radio'; ?>" name="answer<?php echo $post['survey']['multiple']=='1' ? '-'.$answer['id'] : ''; ?>" value="<?php echo $answer['id']; ?>"<?php if(in_array($username, $answer['votes'])) echo ' checked="checked"'; ?> />
				<?php echo htmlspecialchars($answer['answer']); ?>
			</label>
		</li>
<?php
		}
	}
?>
	</ul>

<?php
	if($is_student){
?>
	<div class="survey-choice-vote">
		<input type="submit" value="<?php echo __('POST_SURVEY_SUBMIT'); ?>" />
		- <a href="javascript:;"><?php echo __('POST_SURVEY_SHOW_RESULTS'); ?></a>
	</div>
	<div class="survey-choice-results">
		<a href="javascript:;"><?php echo __('POST_SURVEY_SHOW_VOTE'); ?></a>
	</div>
<?php
	}
?>
	<?php echo __('POST_SURVEY_ENDING_DATE').' '.Date::dateHour(strtotime($post['survey']['date_end'])); ?>
</form>
<?php
}



// Attachments
if(!isset($post['attachments']))
	$post['attachments'] = array();
$nb_photos = 0;

foreach($post['attachments'] as $attachment){
	switch($attachment['ext']){
		
		
		
		// Photo
		// see: http://flash-mp3-player.net/players/maxi/
		case 'jpg':
		case 'gif':
		case 'png':
			if($nb_photos==0){
?>
		<div class="photos">
<?php
			}
?>
		<a href="<?php echo $attachment['url']; ?>" rel="lightbox-post-<?php echo $post['id']; ?>"><img src="<?php echo $attachment['thumb']; ?>" alt="" /></a>
<?php
			$nb_photos++;
			if($nb_photos == Config::PHOTOS_PER_POST && Config::PHOTOS_PER_POST < $post['attachments_nb_photos']){
?>
		<a href="<?php echo Config::URL_ROOT.Routes::getPage('post', array('id' => $post['id'])); ?>" class="photos-more"><?php echo __('POST_LINK_PHOTOS', array('nb' => $post['attachments_nb_photos'])); ?></a>
<?php
			}
			if($nb_photos == min(Config::PHOTOS_PER_POST, $post['attachments_nb_photos'])){
?>
		</div>
<?php
			}
			break;
		
		
		
		
		// Video
		// see: http://flv-player.net/players/maxi/
		case 'flv':
?>
		<object data="<?php echo Config::URL_STATIC; ?>players/player_flv_maxi.swf" width="480" height="360" type="application/x-shockwave-flash" class="video">
			<param name="movie" value="<?php echo Config::URL_STATIC; ?>players/player_flv_maxi.swf" />
			<param name="allowfullscreen" value="true" />
			<param name="wmode" value="transparent" />
			<param name="base" value="." />
			<param name="flashvars" value="flv=<?php echo urlencode($attachment['url']); ?>&amp;startimage=<?php echo urlencode($attachment['thumb']); ?>&amp;margin=0&amp;showvolume=1&amp;showtime=1&amp;showtime=autohide&amp;showloading=always&amp;showfullscreen=1&amp;showmouse=always&amp;showiconplay=1&amp;iconplaycolor=7F82CA&amp;iconplaybgcolor=E4E5FF&amp;iconplaybgalpha=80&amp;playercolor=E4E5FF&amp;bgcolor1=E4E5FF&amp;bgcolor2=D4D6FF&amp;slidercolor1=#3F3D6A&amp;slidercolor2=242153&amp;sliderovercolor=15123C&amp;buttoncolor=242153&amp;buttonovercolor=15123C&amp;textcolor=242153" />
		</object>
<?php
			break;
		
		
		
		
		// Audio
		case 'mp3':
?>
		<object data="<?php echo Config::URL_STATIC; ?>players/player_mp3_maxi.swf" width="300" height="20" type="application/x-shockwave-flash" class="audio">
			<param name="movie" value="<?php echo Config::URL_STATIC; ?>players/player_mp3_maxi.swf" />
			<param name="wmode" value="transparent" />
			<param name="flashvars" value="mp3=<?php echo urlencode($attachment['url']); ?>&amp;width=300&amp;showstop=0&amp;showinfo=0&amp;showvolume=1&amp;showloading=autohide&amp;volumewidth=40&amp;bgcolor1=E4E5FF&amp;bgcolor2=D4D6FF&amp;slidercolor1=#3F3D6A&amp;slidercolor2=242153&amp;sliderovercolor=15123C&amp;buttoncolor=242153&amp;buttonovercolor=15123C&amp;textcolor=242153"/>
		</object>
<?php
			break;
		
		
		// Document
		default:
?>
		<div class="attachment">
			<a href="<?php echo $attachment['url']; ?>"><img src="<?php echo Mimetype::getIcon($attachment['ext']); ?>" alt="" class="icon" /> <?php echo htmlspecialchars($attachment['name']); ?></a>
		</div>
<?php
		
		
	}
}
?>

		<div class="post-info">
			<?php echo Date::easy((int) $post['time']); ?>
<?php
if($is_student){
?>
			&#183;
			<a href="javascript:;" onclick="Comment.write(<?php echo $post['id']; ?>);"><?php echo __('POST_COMMENT_LINK'); ?></a>
<?php
}
if($post['private'] == '1'){
?>
			<br /><?php echo __('POST_PRIVATE'); ?>
<?php
}
?>
		</div>
		
		
		<div class="post-comments">
<?php
if(!isset($post['comments']))
	$post['comments'] = array();
$nb_comments = count($post['comments']);
$n = 0;
$comment_hidden = false;
$comments_at_the_beginning = floor(Config::COMMENTS_PER_POST / 2);
$comments_at_the_end = Config::COMMENTS_PER_POST - $comments_at_the_beginning;
foreach($post['comments'] as $comment){
	$n++;
	if($nb_comments > Config::COMMENTS_PER_POST && !isset($one_post)){
		if($n == $comments_at_the_beginning+1){
			$comment_hidden = true;
?>
			<div id="post-<?php echo $post['id']; ?>-comment-show-all" class="post-comment">
				<a href="javascript:;" onclick="Comment.showAll(<?php echo $post['id']; ?>)"><?php echo __('POST_COMMENT_SHOW_ALL', array('nb' => $nb_comments)); ?></a>
			</div>
<?php
		}else if($n == $nb_comments - $comments_at_the_end+1){
			$comment_hidden = false;
		}
	}
?>
			<div id="post-comment-<?php echo $comment['id']; ?>" class="post-comment<?php if($comment_hidden) echo ' hidden'; ?>">
				<?php
$comment_user_url = Config::URL_ROOT.Routes::getPage('student', array('username' => $comment['username']));
?>
				<a href="<?php echo $comment_user_url; ?>"><img src="<?php echo $comment['avatar_url']; ?>" alt="" class="avatar" /></a>
				<div class="post-comment-message">
					<a href="<?php echo $comment_user_url; ?>" class="post-comment-username"><?php echo $comment['firstname'].' '.$comment['lastname']; ?></a>
					<?php echo Text::inHTML($comment['message']); ?>
					<div class="post-comment-info">
						<?php echo Date::easy((int) $comment['time']); ?>
					</div>
				</div>
			</div>
<?php
}
if($is_student){
?>
			<form action="<?php echo Config::URL_ROOT.Routes::getPage('post_comment', array('id' => $post['id'])); ?>" method="post" class="post-comment-write">
				<img src="<?php echo $avatar_url; ?>" alt="<?php echo $firstname.' '.$lastname; ?>" class="avatar hidden" />
				<div class="post-comment-write-message hidden">
					<textarea name="comment" rows="1" cols="50"></textarea>
					<input type="submit" value="<?php echo __('POST_COMMENT_SUBMIT'); ?>" />
				</div>
				<div class="post-comment-write-placeholder" onmouseup="Comment.write(<?php echo $post['id']; ?>);"><?php echo __('POST_COMMENT_PLACEHOLDER'); ?></div>
			</form>
<?php
}
?>
		</div>
	</div>

</div>