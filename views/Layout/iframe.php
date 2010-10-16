<!doctype html>
<html>
<head>
<title></title>
<meta charset="utf-8">
</head>
<body>

	<?php
	$this->__renderContent();
	?>
	
	
<?php foreach($jsFiles as $jsFile){ ?>
	<script type="text/javascript" src="<?php echo $jsFile; ?>"></script>
<?php } ?>
	<script type="text/javascript">
	//<![CDATA[
	<?php echo $jsCode; ?>
	//]]>
	</script>

</body>
</html>