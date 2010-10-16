<?php
/**
 * General configuration
 */

final class Config extends ConfigAbstract {
	
	// Absolute URL of the website
	const URL_ABSOLUTE	= 'http://test.iseplive.fr/';
	// Absolute URL of the storage dir
	const URL_STORAGE	= 'http://storage.iseplive.fr/';
	// Absolute path of the website on the domain
	const URL_ROOT		= '/';
	// Absolute path for static files
	const URL_STATIC	= '/static/';
	
	// Timezone
	const TIMEZONE	= 'Europe/Paris';

	// DB connection
	public static $DB	= array(
		'driver'	=> 'mysql',
		'dsn'		=> 'host=localhost;dbname=iseplive',
		'username'	=> 'iseplive',
		'password'	=> 'TAKsKZb4EhmfdtDG'
	);
	
	// LDAP
	public static $LDAP = array (
		'host'		=> 'hera.isep.fr',
		'port'		=> 389,
		'basedn'	=> 'ou=People,dc=isep.fr'
	);
	
	// Encryption secret key (for Encryption class)
	const ENCRYPTION_KEY	= 'OyFDrRd3db';
	
	// Md5 salt for more security
	const MD5_SALT	= 'g64erta54bi';

	// Directories
	// relative to "app" dir
	const DIR_APP_STATIC	= 'static/';		// Fichiers statics
	// relative to "data" dir
	const DIR_DATA_LOGS		= 'logs/';		// Logs²
	const DIR_DATA_STORAGE	= 'storage/';	// Storage
	const DIR_DATA_TMP		= 'tmp/';		// Temporary files
	
	// Name of the session
	const SESS_ID		= 'PHPSESSID';
	
	// Cache
	public static $CACHE	= array(
		'driver'	=> 'memcache',
		'prefix'	=> 'iseplive-'
	);
	
	// Solr
	public static $SOLR	= array(
		'host'	=> 'solr',
		'port'	=> 8983,
		'path'	=> '/solr/iseplive/'
	);
	
	// Contact name and mail
	const CONTACT_NAME	= 'ISEPLive';
	const CONTACT_MAIL	= 'contact@iseplive.fr';
	
	// SMTP server
	const SMTP_HOST		= 'smtp.iseplive.fr';
	
	
	// Languages
	public static $LOCALES = array('fr_FR');
	
	// Thumbs sizes
	public static $THUMBS_SIZES = array(100, 100);
	
	// Avatars' thumbs sizes
	public static $AVATARS_THUMBS_SIZES = array(70, 70);
	
	// Max uploaded files sizes
	const UPLOAD_MAX_SIZE_PHOTO = 2097152;
	const UPLOAD_MAX_SIZE_VIDEO = 209715200;
	const UPLOAD_MAX_SIZE_AUDIO = 20971520;
	const UPLOAD_MAX_SIZE_FILE = 10485760;
	
	// Max width for videos
	const VIDEO_MAX_WIDTH = 480;
	const VIDEO_SAMPLING_RATE = 44100;
	const VIDEO_AUDIO_BIT_RATE = 64;
	
	// Number of displayed posts
	const POST_DISPLAYED = 10;
	
	// Number of displayed photos per post in the timeline
	const PHOTOS_PER_POST = 3;
	
	// Galleries
	const GALLERY_COLS = 5;
	const GALLERY_ROWS = 8;
	
	// Debug mode
	const DEBUG			= false;
}


// PHPVideoToolkit constants
define('PHPVIDEOTOOLKIT_TEMP_DIRECTORY', '/tmp/');
define('PHPVIDEOTOOLKIT_FFMPEG_BINARY', '/usr/bin/ffmpeg');
define('PHPVIDEOTOOLKIT_FLVTOOLS_BINARY', '/usr/bin/flvtool2');
