<!doctype html>
<html lang="en" class="no-js">
	<head>
		<title><?php if($this->specificController->title != '') echo $this->specificController->title.' - '; ?>ISEPLive</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
		<meta name="robots" content="index,follow"/>
		
<?php
foreach($cssFiles as $cssFile){
?>
		<link rel="stylesheet" type="text/css" href="<?php echo $cssFile; ?>" />
<?php
}
?>
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo Config::URL_STATIC; ?>favicon.ico" />
		
		<!--[if IE 6]>
		<style type="text/css">
		img {behavior: url("<?php echo Config::URL_STATIC; ?>css/pnghack.htc");}
		</style>
		<![endif]-->
		<!--[if IE]>
		<style type="text/css">
		* {zoom: 1;}
		</style>
		<script type="text/javascript">
		//<![CDATA[
		(function(){
			for(var e = 'abbr,article,aside,audio,bb,canvas,datagrid,datalist,details,dialog,eventsource,figure,footer,header,hgroup,mark,menu,meter,nav,output,progress,section,time,video'.split(','), i=0, iTotal = e.length; i < iTotal; i++)
				document.createElement(e[i]);
		})();
		//]]>
		</script>
		<![endif]-->
		<script type="text/javascript">
		//<![CDATA[
		document.getElementsByTagName("html")[0].className = "js";
		//]]>
		</script>
		
	</head>
	<body>
		
		<div id="container">
			<header>
				<a href="<?php echo Config::URL_ROOT; ?>" id="header-title">
					<img src="<?php echo Config::URL_STATIC; ?>images/header/logo_iseplive.png" alt="ISEPLive" id="logo-iseplive" />
					<img src="<?php echo Config::URL_STATIC; ?>images/header/logo_bde.png" alt="BDE Aerodynamic" id="logo-bde" />
				</a>
				
				<nav>
					<a href="<?php echo Config::URL_ROOT.Routes::getPage('home'); ?>"><?php echo __('NAV_PUBLICATIONS'); ?></a>
					<a href="<?php echo Config::URL_ROOT.Routes::getPage('groups'); ?>"><?php echo __('NAV_GROUPS'); ?></a>
					<?php if($is_logged){ ?>
					<a href="<?php echo Config::URL_ROOT.Routes::getPage('students'); ?>"><?php echo __('NAV_DIRECTORY'); ?></a>
					<?php } ?>
					<a href="<?php echo Config::URL_ROOT.Routes::getPage('links'); ?>"><?php echo __('NAV_LINKS'); ?></a>
					<?php if($is_logged){ ?>
						<?php if($is_student){ ?>
					<a href="<?php echo Config::URL_ROOT.Routes::getPage('student', array('username' => $username)); ?>"><?php echo __('NAV_PROFILE'); ?></a>
						<?php } ?>
					<a href="<?php echo Config::URL_ROOT.Routes::getPage('logout', array('redirect', '/')); ?>"><?php echo __('NAV_LOGOUT'); ?></a>
					<?php }else{ ?>
					<a href="<?php echo Config::URL_ROOT.Routes::getPage('signin'); ?>"><?php echo __('NAV_SIGNIN'); ?></a>
					<?php } ?>
			
					<form action="<?php echo Config::URL_ROOT.Routes::getPage('search'); ?>" method="get" id="search-box">
						<input type="text" name="q" value="<?php echo __('LAYOUT_SEARCH_FIELD'); ?>" id="search" class="search-default" />
						<input type="hidden" value="<?php echo Config::URL_ROOT.Routes::getPage('autocomplete'); ?>" id="search-ajax-url" />
					</form>
				</nav>
			</header>
			
			<div id="main">
				<?php
				$this->__renderContent();
				?>
			</div>
			
			<footer>
				<a href="<?php echo Config::URL_ROOT.Routes::getPage('group', array('group' => 'iseplive')); ?>">Association ISEPLive</a> + <a href="<?php echo Config::URL_ROOT.Routes::getPage('group', array('group' => 'bde-aerodynamic')); ?>">BDE Aerodynamic</a> ::
				Site développé par <a href="http://godefroy.me">Godefroy</a> et <a href="http://github.com/Godefroy/iseplive">libéré sur Github</a>
			</footer>
		</div>

<?php foreach($jsFiles as $jsFile){ ?>
		<script type="text/javascript" src="<?php echo $jsFile; ?>"></script>
<?php } ?>
		<script type="text/javascript">
		//<![CDATA[
		
		var Translations = {<?php
				$js_translations = array(
					'WEEK_FIRST_DAY',
					'DAY_SUNDAY',
					'DAY_MONDAY',
					'DAY_THUESDAY',
					'DAY_WEDNESDAY',
					'DAY_THURSDAY',
					'DAY_FRIDAY',
					'DAY_SATERDAY',
					'MONTH_JANUARY',
					'MONTH_FEBRUARY',
					'MONTH_MARCH',
					'MONTH_APRIL',
					'MONTH_MAY',
					'MONTH_JUNE',
					'MONTH_JULY',
					'MONTH_AUGUST',
					'MONTH_SEPTEMBER',
					'MONTH_OCTOBER',
					'MONTH_NOVEMBER',
					'MONTH_DECEMBER',
					'PUBLISH_EVENT_DATE_FORMAT',
					'PUBLISH_SURVEY_DATE_FORMAT',
					'POST_DELETE_CONFIRM',
					'POST_COMMENT_DELETE_CONFIRM',
					'GROUP_EDIT_FORM_CREATION_DATE_FORMAT_PARSE',
					'USER_EDIT_FORM_BIRTHDAY_FORMAT_PARSE'
				);
				foreach($js_translations as $i => $js_translation){
					if($i != 0)
						echo ',';
					echo '"'.$js_translation.'":"'.__($js_translation).'"';
				}
			?>};
		
		<?php echo $jsCode; ?>
		//]]>
		</script>
		
		<?php if(!Config::DEBUG){ ?>
		<script type="text/javascript">
		//<![CDATA[
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', '<?php echo Config::GA_TRACKER; ?>']);
		_gaq.push(['_trackPageview']);

		(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
		//]]>
		</script>
		<?php } ?>
		
<?php




// Debug mode
require APP_DIR.'views/'.$this->name.'/_debug.php';


?>
	</body>
</html>