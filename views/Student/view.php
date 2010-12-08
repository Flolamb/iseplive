
<div class="profile-block">
	<a href="<?php echo $avatar_big_url; ?>" rel="lightbox" class="avatar profile-avatar"><img src="<?php echo $avatar_url; ?>" alt="" /></a>

	<div class="profile-info">
		<h1><?php echo htmlspecialchars($firstname.' '.$lastname); ?></h1>
		
		<strong><?php echo __('PROFILE_PROMO'); ?></strong> <?php echo $promo; ?><br />
		<strong><?php echo __('PROFILE_STUDENT_NUMBER'); ?></strong> <?php echo $student_number; ?><br />
		<br />
		
		<strong><?php echo __('PROFILE_MAIL_ISEP'); ?></strong> <?php echo htmlspecialchars($username.'@isep.fr'); ?><br />
		
		<?php if(isset($mail) && $mail != ''){ ?>
		<strong><?php echo __('PROFILE_MAIL'); ?></strong> <?php echo htmlspecialchars($mail); ?><br />
		<?php } ?>
		
		<?php if(isset($msn) && $msn != ''){ ?>
		<strong><?php echo __('PROFILE_MSN'); ?></strong> <?php echo htmlspecialchars($msn); ?><br />
		<?php } ?>
		
		<?php if(isset($jabber) && $jabber != ''){ ?>
		<strong><?php echo __('PROFILE_JABBER'); ?></strong> <?php echo htmlspecialchars($jabber); ?><br />
		<?php } ?>
		
		<?php if(isset($address) && $address != ''){ ?>
		<strong><?php echo __('PROFILE_ADDRESS'); ?></strong> <?php echo
			htmlspecialchars($address).
			(isset($zipcode) || $city!=''
				?	($address!='' ? ',' : '').
					(isset($zipcode) ? ' '.htmlspecialchars($zipcode) : '').
					($city!='' ? ' '.htmlspecialchars($city) : '')
				: ''
			); ?><br />
		<?php } ?>
		
		<?php if(isset($cellphone) && $cellphone != ''){ ?>
		<strong><?php echo __('PROFILE_PHONE'); ?></strong> <?php echo htmlspecialchars($cellphone); ?><br />
		<?php } ?>
		
		<?php if(isset($phone) && $phone != ''){ ?>
		<strong><?php echo __('PROFILE_PHONE'); ?></strong> <?php echo htmlspecialchars($phone); ?><br />
		<?php } ?>
		
		<?php if(isset($birthday) && $birthday != '0000-00-00'){ ?>
		<strong><?php echo __('PROFILE_BIRTHDAY'); ?></strong> <?php echo date(__('PROFILE_BIRTHDAY_FORMAT'), strtotime($birthday)); ?><br />
		<?php } ?>
		
		<?php if($is_owner){ ?>
		<br />
		<a href="<?php echo Config::URL_ROOT.Routes::getPage('profile_edit'); ?>"><img src="<?php echo Config::URL_STATIC; ?>images/icons/edit.png" alt="" class="icon" /> <?php echo __('PROFILE_EDIT_PROFILE'); ?></a>
		<?php } ?>
	</div>
</div>