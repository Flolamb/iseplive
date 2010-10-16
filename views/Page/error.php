<h1><?php echo __('PAGE_ERROR_TITLE'); ?></h1>
<?php
if(isset($message))
	echo $message;
else
	echo __('PAGE_ERROR_MESSAGE'); ?>
